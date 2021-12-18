<?php
namespace app\controller;
use app\extend\Page;
use app\models\CreditLog;
use app\models\OrderGift;
use app\models\OrderMessage;
use app\models\OrderProduct;
use app\models\OrderReturn;
use app\models\OrderReturnProduct;
use app\models\User;
use think\facade\Request;
use think\Exception;
use think\facade\Db;
use think\facade\View;

class Order extends AuthBase
{

    //訂單列表
    public function index()
    {
        $order_status = Request::instance()->param("order_status", "99");
        $start_date = Request::instance()->param("start_date");
        $end_date = Request::instance()->param("end_date");

        $map = "customerid=".$this->getCustomerId();
        if($order_status != 99) {
            $map .= " AND order_status=".$order_status;
        }
        
        if(!empty($start_date)) {
            $map .= " AND create_date>=".strtotime($start_date);
        }
        
        if(!empty($end_date)) {
            $map .= " AND create_date<=".strtotime($end_date." 23:59:59");
        }
        
        $params = [
            "list_rows" => 20,
            "query" => [
                "order_status" => $order_status,
                "start_date" => $start_date,
                "end_date" => $end_date,
            ]
        ];

        $query = Db::name("orders")->where($map)->order("create_date DESC")->paginate($params);

        $list = $query->all();
        $oids = [];
        $order_products = [];
        if(!empty($list)) {
            foreach($list as $v) {
                $oids[] = $v["oid"];
            }
            
           $order_product_list = Db::table("order_products")->alias("op")
                   ->join("products p", "p.prodid=op.prodid")
                   ->where("oid", "in", $oids)
                   ->field("op.*")
                   ->select();
           if(!empty($order_product_list)) {
               foreach($order_product_list as $prod) {
                   $order_products[$prod["oid"]][] = $prod;
               }
           }
        }
        
        $data["orders"] = $list;
        $data["order_products"] = $order_products;
        $data["pages"] = Page::make($query->currentPage(), $query->lastPage(), $params["query"]);
        $data["order_status"] = $order_status;
        $data["start_date"] = $start_date;
        $data["end_date"] = $end_date;

        View::assign($data);
        return View::fetch();
    }

    public function detail()
    {
        try {
            $oid = input("oid");
            $data["order"] = Db::name(\app\models\Order::$tablename)->where("oid", $oid)->where("customerid", $this->getCustomerId())->find();
            $data["customer"] = Db::name(\app\models\Customer::$tablename)->where("customerid", $data["order"]["customerid"])->find();
            if (empty($data["order"])) {
                throw new Exception("");
            }

            $data["order_products"] = Db::name(OrderProduct::$tablename)->where("oid", $oid)->select();
            $data["expressStatus"] = "N/A";
            if(!empty($data["order"]["AllPayLogisticsID"])) {
                $express = Db::name("express_status")->where("AllPayLogisticsID", $data["order"]["AllPayLogisticsID"])->order("TradeDate DESC")->find();
                if(!empty($express)) {
                    $data["expressStatus"] = $express["LogisticsStatus"];
                }
            }

            $data["messages"] = Db::table(OrderMessage::$tablename)->alias("om")
                ->join(User::$tablename." u", "u.userid=om.userid", "LEFT")
                ->field("om.*, u.fullname")
                ->where("oid", $oid)->order("om.creat_at DESC")->select();

            //get gift

            $data["gift"] = Db::name(OrderGift::$tablename)->where("oid", $oid)->find();

            $data["donate_company"] = get_invoice_to();
            $setting = get_setting();
            $data["se_address"] = $setting['setting']["se"]['address']??'';
            View::assign($data);
            return View::fetch();
        } catch (Exception $ex) {
            abort(404);
        }
    }

    public function cancel_order()
    {
        try {
            $oid = input("oid", 0);
            if(empty($oid)) {
                throw new Exception("請選擇需要取消的訂單");
            }

            $order = Db::name(\app\models\Order::$tablename)->where("customerid", $this->getCustomerId())->where("oid", $oid)->find();
            if(empty($order)) {
                throw new Exception("訂單不存在");
            }

            if(!in_array($order["order_status"], [0,1, 2])) {
                throw new Exception("只有【訂單成立/已付款/等待取件】狀態的訂單才可以取消");
            }

            if($order["pay_status"] != 1) {
                throw new Exception("只有已付款的訂單才能取消");
            }

            if(false === Db::name(\app\models\Order::$tablename)->where("customerid", $this->getCustomerId())->where("oid", $oid)->update(["order_status" => -50, "update_date" => time()])) {
                throw new Exception("訂單取消失敗");
            }


            toJSON([
                "code" => 0,
                "msg" => "訂單取消成功"
            ]);
        } catch (Exception $ex) {
            toJSON([
                "code" => 1,
                "msg" => $ex->getMessage()
            ]);
        }
    }

    public function returns()
    {
        $query = Db::name(OrderReturn::$tablename)->where("customerid", $this->getCustomerId())->order("create_date DESC")->paginate(20);
        $data["list"] = $query->all();
        $data["pages"] = Page::make($query->currentPage(), $query->lastPage());
        View::assign($data);
        return View::fetch();
    }
    
    public function return_order()
    {
        $oid = Request::instance()->param("oid");

        $map = "customerid=".$this->getCustomerId()." AND oid=".$oid;
       
        $order = Db::name("orders")->where($map)->find();

        if(empty($order)) {
            abort(404);
        }

        //count returned
        $returnlist = Db::name(OrderReturnProduct::$tablename)->where("oid", $oid)->field("opid, count(orpid) AS t")->group("opid")->select();
        $returedItems = [];
        if(!empty($returnlist)) {
            foreach($returnlist as $v) {
                $returedItems[$v["opid"]] = $v["t"];
            }
        }

        $order_product_list = Db::name("order_products")
                ->where("oid", $oid)
                ->select();
        $totalItems = 0;
        $order_products = [];

        if(!empty($order_product_list)) {
            foreach($order_product_list as $prod) {

                if(isset($returedItems[$prod["opid"]])) {
                    $prod["qty"] -= (int) $returedItems[$prod["opid"]];
                }

                $totalItems += $prod["qty"];
                $order_products[] = $prod;
            }
        }

        $data["totalItems"] = $totalItems;
        $data["order"] = $order;
        $data["order_products"] = $order_products;
        
        if($order["order_status"]>=0) {
            $template = "return_order";
        } else {
            $data["return_info"] = Db::name("order_returns")->where("oid", $oid)->where("user_id", $this->user_id)->find();
            $template = "returned_order";
        }
        View::assign($data);
        return View::fetch($template);
    }

    /**
     * reutrn credits calc
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getReturnCredits()
    {
        $oid = Request::instance()->param("oid");
        $products = request()->post("products/a");
        $order = Db::name("orders")->where("oid", $oid)->where("customerid", $this->getCustomerId())->find();
        $totalPaidAmount = $order["credit_money"]+$order["total_item_amount"];
        $totalItemAmount = $order["sub_total_amount"];
        $returnRate = $totalPaidAmount/$totalItemAmount;
        $totalReturnAmount = 0;
        if(!empty($products)) {
            foreach ($products as $k => $prod) {
                $orderProduct = Db::name(OrderProduct::$tablename)->where("opid", $prod["opid"])->find();
                $returnedAmount = (int)($returnRate * ($orderProduct["prod_price"] * $prod["qty"]));
                $totalReturnAmount += $returnedAmount;
            }
        }
        $credits = (int) CreditLog::moneyToCredits($totalReturnAmount);
        return $credits;
    }

    /**
     * real to submit return items
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function do_return_order()
    {
        try {
            $oid = Request::instance()->param("oid");
            $return_type = Request::instance()->param("return_type");
            $remark = Request::instance()->param("remark");
            $products = request()->post("products/a");
            
            $order = Db::name("orders")->where("oid", $oid)->where("customerid", $this->getCustomerId())->find();
            if(empty($order)) {
                throw new Exception("訂單無效");
            }

            if(empty($return_type)) {
                throw new Exception("退換貨類型未選擇");
            }

            if(mb_strlen($return_type)>255) {
                throw new Exception("退貨類型不能超過255字");
            }

            if(empty($remark)) {
                throw new Exception("退換貨原因未填寫");
            }

            if(mb_strlen($remark)>1000) {
                throw new Exception("退貨原因不能超過1000字");
            }

            if(empty($products)) {
                throw new Exception("未選擇退貨商品數量");
            }

            $totalPaidAmount = $order["credit_money"]+$order["total_item_amount"];

            $totalItemAmount = $order["sub_total_amount"];

            $returnRate = $totalPaidAmount/$totalItemAmount;


            $total_items = 0;
            $returnProducts = [];
            $totalReturnAmount = 0;
            $returnno = date("YmdHis").rand(1000,9999);

            foreach($products as $k => $prod) {
                if(!is_numeric($prod["qty"]) || $prod["qty"]<=0 ) {
                    throw new Exception("请选择退货产品");
                }

                $orderProduct = Db::name(OrderProduct::$tablename)->where("opid", $prod["opid"])->find();
                $hasReturnedQty = Db::name(OrderReturnProduct::$tablename)->where("opid", $prod["opid"])->sum("qty");
                if($orderProduct["qty"]-$hasReturnedQty-$prod['qty']<0) {
                    throw new Exception("你选择的商品已经全退完了, 请勿重复申请");
                }

                $total_items += $prod["qty"];

                $returnedAmount = (int) ($returnRate*($orderProduct["prod_price"]*$prod["qty"]));
                $totalReturnAmount += $returnedAmount;
                //calc return money, via promotion
                $returnProducts[] = [
                    "oid" => $oid,
                    "opid" => $prod["opid"],
                    "customerid" => $this->getCustomerId(),
                    "returnno" => $returnno,
                    "qty" => $prod["qty"],
                    "return_amount" => $returnedAmount,
                ];
            }


            $data = [
                "oid" => $oid,
                "returnno" => $returnno,
                "customerid" => $this->getCustomerId(),
                "total_amount" => $totalReturnAmount,
                "total_items" => $total_items,
                "return_type" => $return_type,
                "remark" => $remark,
                "create_date" => time()
            ];

            Db::startTrans();
            if(!Db::name("order_returns")->insert($data)) {
                throw new Exception("退換貨申請送出失敗");
            }
            
            if(!Db::name(OrderReturnProduct::$tablename)->insertAll($returnProducts)) {
                throw new Exception("退換物品储存失败");
            }
            
            Db::commit();
            toJSON(["code" => 0, "msg" => "退換貨申請送出成功", "url" => front_link("Order/returns")]);
        } catch (Exception $e) {
            Db::rollback();
            toJSON(["code" => 1, "msg" => $e->getMessage()]);
        }
    }

    public function return_detail()
    {
        $returnno = Request::instance()->param("returnno");

        $map = "customerid=".$this->getCustomerId()." AND returnno=".$returnno;

        $order = Db::name(OrderReturn::$tablename)->where($map)->find();

        if(empty($order)) {
            abort(404);
        }

        $data["products"] = Db::table(OrderReturnProduct::$tablename)->alias("rp")
            ->join(OrderProduct::$tablename." op", "op.opid=rp.opid")
            ->where("rp.customerid", $this->getCustomerId())
            ->where("returnno", $returnno)
            ->field("op.prodid, op.prodname, op.prodimage,op.sku, op.prod_price, op.options, rp.qty, rp.return_amount")
            ->select();

        $data["order"] = $order;
        View::assign($data);
        return View::fetch();
    }

    /**
     * send order question
     */
    public function sendMsg()
    {
        try {

            $oid = input("oid", 0);
            $question = input("question");
            if(empty($oid)) {
                throw new Exception("訂單記錄無效");
            }

            if(empty($question)) {
                throw new Exception("請填寫咨詢的內容");
            }

            if(mb_strlen($question)>1000) {
                throw new Exception("咨詢內容不能超過1000字");
            }

            $data = [
                "oid" => $oid,
                "question" => $question,
                "creat_at" => time()
            ];

            $files = request()->file("images");
            $_fiels = [];
            if(!empty($files)) {
                foreach ($files as $file) {
                    $_fiels[] = \think\facade\Filesystem::disk("public")->putFile('images/order_msg', $file);
                }
            }
            if(!empty($_fiels)) {
                $data["images"] = implode(",", $_fiels);
            }

            if(false === Db::name(OrderMessage::$tablename)->insert($data)) {
                throw new Exception("送出失敗");
            }

            toJSON([
                "code" => 0,
                "msg" => "送出成功"
            ]);
        } catch (Exception $ex) {
            toJSON([
                "code" => 1,
                "msg" => $ex->getMessage()
            ]);
        }
    }

}
