<?php

namespace app\admin\controller;

use app\extend\Page;
use app\models\CouponCodeOrders;
use app\models\Order;
use app\models\OrderGift;
use app\models\OrderMessage;
use app\models\OrderProduct;
use app\models\OrderReturn;
use app\models\OrderReturnProduct;
use app\models\Products as ProdModel;
use app\models\StockPushLogs;
use app\models\User;
use ecpay\ECPayInvoice;
use think\facade\Db;
use think\facade\Request;
use think\facade\Session;
use think\Exception;
use think\facade\View;

class Orders extends Base {

    private $page_size = 50;
    public function _initialize() {
        parent::_initialize();
        $this->request = Request::instance();
    }

    public function index() {
        $keyword = $this->request->param('keyword');
        $dialog = input("dialog", 0);
        $customerid = $this->request->param('customerid', 0);
        $start_date = $this->request->param('start_date');
        $end_date = $this->request->param('end_date');
        $min_price = $this->request->param('min_price');
        $max_price = $this->request->param('max_price');
        $order_status = $this->request->param('order_status', 99);
        $maps = [];
        $map = "";

        if(mb_stripos($keyword, "V") !== false) {
           $oids = explode("V", $keyword);
           if(strlen($oids[0])==16 && is_numeric($oids[0])) {
               $keyword = trim($oids[0]);
           }
        }

        if (!empty($keyword)) {
            $maps[] = "(o.oid='" . $keyword . "' OR o.billing_name LIKE '%".$keyword."%' OR billing_mobile='".$keyword."' OR co.code='".$keyword."')";
        }

        if($dialog) {
            $maps[] = "o.order_status=100";
        }

        if(!empty($customerid)) {
            $maps[] = "o.customerid=".$customerid;
        }

        if (!empty($min_price)) {
            $maps[] = "o.total_amount>=" . $min_price;
        }

        if (!empty($max_price)) {
            $maps[] = " o.total_amount<=" . $max_price;
        }

        if (!empty($start_date)) {
            $maps[] = "o.create_date>=" . strtotime($start_date . " 00:00:00");
        }

        if (!empty($end_date)) {
            $maps[] = " o.create_date<=" . strtotime($end_date . " 23:59:59");
        }

        if (!in_array($order_status, [99,'all'])) {
            $maps[] = "o.order_status=" . $order_status;
        }

        if ($order_status == 200) {
            $maps[] = "o.pay_status=1";
        }

        if (!empty($maps)) {
            $map = implode(" AND ", $maps);
        }
        $params["list_rows"] = $this->page_size;
        $params["query"] = [
            "keyword" => $keyword,
            "start_date" => $start_date,
            "end_date" => $end_date,
            "min_price" => $min_price,
            "max_price" => $max_price,
            "order_status" => $order_status
        ];
        $list = Db::table("orders")->alias("o")
                ->join(\app\models\Customer::$tablename." c", "c.customerid=o.customerid")
                ->leftJoin(CouponCodeOrders::$tablename." co", "co.oid=o.oid")
                ->where($map)
                ->field("o.*, c.fullname")
                ->order("o.create_date DESC")
                ->paginate($params);
        $msgCount = [];
        if(!empty($list)) {
            $oids = [];
            foreach($list as $v) {
                $oids[] = $v["oid"];
            }
            $message_list = Db::name(OrderMessage::$tablename)->where("oid", "IN", $oids)->field("oid, COUNT(id) AS t")->group("oid")->select();
            if(!empty($message_list)) {
                foreach($message_list as $v) {
                    $msgCount[$v["oid"]] = $v["t"];
                }
            }
        }

        //提取庫存同步記錄
        $stockPushLogs = [];
        $stocklogs = Db::name(StockPushLogs::$tablename)->where("oid", "IN", $oids)->select();
        if(!empty($stocklogs)) {
            foreach($stocklogs as $log) {
                $stockPushLogs[$log["oid"]] = $log;
            }
        }

        $data['list'] = $list->all();
        $data["msgCount"] = $msgCount;
        $data["stockPushLogs"] = $stockPushLogs;
        $data['keyword'] = $keyword;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['min_price'] = $min_price;
        $data['max_price'] = $max_price;
        $data['order_status'] = $order_status;
        $data["pages"] = $list->render();
        $data["pushStateMsg"] = [
            "Success" => "成功",
            "Error" => "失敗",
            "Fail" => "失敗"
        ];
        $data["pushStates"] = [
            "send" => "發送訂單",
            "canncel" => "取消訂單",
        ];
        View::assign($data);

        if(!empty($dialog)) {
            $tpl = "customer_order_index";
        } else {
            $tpl = "index";
        }
        return View::fetch($tpl);
    }

    public function export() {

        $keyword = $this->request->param('keyword');
        $min_price = $this->request->param('min_price');
        $max_price = $this->request->param('max_price');
        $start_date = $this->request->param('start_date');
        $end_date = $this->request->param('end_date');
        $order_status = $this->request->param('order_status', 1000000);
        $maps = [];
        $map = "";

        if (!empty($keyword)) {
            $maps[] = "(o.oid=" . $keyword . " OR o.billing_mobile='" . $keyword . "' OR o.billing_name LIKE '%" . $keyword . "%')";
        }

        if (empty($start_date)) {
            exit("請選擇開始日期");
        }

        if (empty($end_date)) {
            exit("請選擇結束日期");
        }

        if (!empty($min_price)) {
            $maps[] = "o.total_amount>=" . $min_price;
        }

        if (!empty($max_price)) {
            $maps[] = " o.total_amount<=" . $max_price;
        }

        $maps[] = "o.create_date>=" . strtotime($start_date . " 00:00:00");
        $maps[] = " o.create_date<=" . strtotime($end_date . " 23:59:59");

        if ($order_status != 99) {
            $maps[] = "o.order_status=" . $order_status;
        }

        if (!empty($maps)) {
            $map = implode(" AND ", $maps);
        }

        $list = Db::table("orders")->alias("o")
                ->field("o.*")
                ->where($map)
                ->select();

        $export_list = [];
        $export_list[] = [
            "訂單號",
            "訂單狀態",

            "收貨人姓名",
            "收貨人手機",
            "收貨人市話",
            "收貨人地址",
            
            "運費",
            "優惠券號",
            "優惠金額",
            "促銷活動名稱",
            "促銷已減金額",
            "付款方式",
            "收貨時間",
            "發票類型",
            "發票抬頭",
            "發票統一編號",
            "發票郵寄方式",
            "發票郵寄地址",
            
            "訂單金額",
            "配送方式",
            "是否付款",
            "付款流水號",
            "付款日期",
            "備註",
            "更新日期",
            "創建日期"
        ];
        if (!empty($list)) {
            $order_statuses = get_order_states();
            $pay_types = get_pay_types();
            $i = 1;
            foreach ($list as $row) {
                $rule_names = [];
                if(!empty($row["promotion_rules"])) {
                    $rules = json_decode($row["promotion_rules"], true);
                    if(!empty($rules)) {
                        foreach($rules as $rule) {
                            $rule_names[] = $rule["title"].":".$rule["amount"];
                        }
                    }
                }
                $export_list[] = [
                    $row["oid"],
                    isset($order_statuses[$row['order_status']]) ? strip_tags($order_statuses[$row['order_status']]) : "",

                    $row['shipping_name'],
                    $row['shipping_mobile'],
                    $row['shipping_tel1']."-".$row['shipping_tel2'],
                    ($row["LogisticsType"]=="HOME")?$row['shipping_city']."-".$row['shipping_area']."-".$row['shipping_address']:$row["CVSAddress"]."(".$row["CVSStoreName"].")",
                    
                    $row['shipping_fee'],
                    $row['coupon_code'],
                    $row['coupon_amount'],
                    !empty($rule_names)?implode("/", $rule_names):"",
                    $row['promotion_amount'],
                    $pay_types[$row['pay_type']]["title"],
                    $row['revice_time'],
                    $row['invoice_type'],
                    $row['invoice_header'],
                    $row['invoice_no'],
                    $row['invoice_shipping_type'],
                    $row['invoice_shipping_address'],
                    
                    $row["total_amount"],
                    $row['shipping_type_name'],
                    $row['pay_status'] ? "已付款" : "",
                    $row["TradeNo"],
                    $row["TradeDate"],
                    $row['remark'],
                    empty($row['update_date']) ? '' : date('Y-m-d H:i:s', $row['update_date']),
                    date('Y-m-d H:i:s', $row['create_date']),
                ];
                $i++;
            }
        }

        $excel = new \Excel_XML('UTF-8', FALSE, '訂單列表');
        $filename = "訂單列表" . $start_date . "至" . $end_date;
        $excel->addArray($export_list);
        $excel->generateXML($filename);
    }

    //訂購商品列表
    public function items() {
        $oid = Request::instance()->param("oid");
        $items = Db::name("order_products")->where("oid", $oid)->select();
        $prodids = [];
        foreach($items as $item) {
            $prodids[] = $item["prodid"];
        }
        
        //get product to supplier
        $products = Db::name("products")->where("prodid", "IN", $prodids)->field("prodid, spids")->select();
        $suplier_data = [];
        foreach($products as $row) {
            $spids = explode(",", $row["spids"]);
            if(!empty($spids)) {
                $supplier_items = Db::name("supplier_items")->where("spid", "IN", $spids)->select();
                foreach($supplier_items as $sitem) {
                    $suplier_data[$row["prodid"]][$sitem["spid"]][] = $sitem;
                }
            }
        }
        
        $out_items_list = Db::name("supplier_item_orders")->where("oid", $oid)->select();
        $out_items = [];
        $itemid = 0;
        $spid = 0;
        if(!empty($out_items_list)) {
            foreach($out_items_list as $v) {
                $out_items[$v["opid"].'_'.$v["itemid"]] = [
                    "out_stock" => $v["qty"],
                    "out_date" => date("Y-m-d", $v["sold_time"])
                ];
                $itemid = $v["itemid"];
            }
            $xitem = Db::name("supplier_items")->where("itemid", $itemid)->find();
            $spid = $xitem["spid"];
        }
        
        $data["out_spid"] = $spid;
        $data["out_items"] = $out_items;
        $data["supplier"] = $suplier_data;
        $data["oid"] = $oid;
        $data["items"] = $items;
        View::assign($data);
        return View::fetch();
    }
    
    //delete
    public function delete() {
        try {
            $oid = Request::instance()->param("oid");
            Db::startTrans();
            if(!Db::name("orders")->where("oid", $oid)->delete()) {
                throw new Exception("訂單刪除失敗");
            }
            
            if(!Db::name("order_products")->where("oid", $oid)->delete()) {
                throw new Exception("訂單商品刪除失敗");
            }
            
            Db::commit();
            toJSON(["code" => 0, "msg" => "訂單刪除成功"]);
        } catch(\Exception $e) {
            Db::rollback();
            toJSON(["code" => 1, "msg" => $e->getMessage()]);
        }
    }

    //編輯
    public function edit() {
        $oid = $this->request->param('oid');
        $data['order'] = Db::name('orders')->where("oid", $oid)->find();
        $data["items"] = Db::name("order_products")->where("oid", $oid)->select();
        $data["gift"] = Db::name(OrderGift::$tablename)->where("oid", $oid)->find();
        $data["donate_company"] = get_invoice_to();
        View::assign($data);
        return View::fetch("form");
    }
    
    //編輯
    public function printer() {
        $config = get_setting();
        $data["senderName"] = $config["setting"]["express"]["name"];
        $data["senderPhone"] = $config["setting"]["express"]["tel"];
        $data["senderMobile"] = $config["setting"]["express"]["mobile"];
        $data["senderZipCode"] = $config["setting"]["express"]["zipcode"];
        $data["senderAddress"] = $config["setting"]["express"]["address"];

        $oid = $this->request->param('oid');
        $data['order'] = Db::name('orders')->where("oid", $oid)->find();
        $data["items"] = Db::name("order_products")->where("oid", $oid)->select();
        $data["gift"] = Db::name(OrderGift::$tablename)->where("oid", $oid)->find();
        $setting = get_setting();
        $data["se_address"] = $setting['setting']["se"]['address']??'';
        View::assign($data);
        return View::fetch("printer");
    }

    //儲存
    public function save() {
        try {
            $oid = $this->request->param('oid');
            $shipping_type_name = $this->request->param('shipping_type_name');
            $order_status = $this->request->param('order_status');
            $admin_remark = $this->request->param('admin_remark');
            if(in_array($order_status, [1, 100])) {
                $pay_status = 1;
            } elseif(empty($order_status)) {
                $pay_status = 0;
            } else {
                $pay_status = "N";
            }
            $data = array(
                "shipping_type_name" => $shipping_type_name,
                "order_status" => $order_status,
                "admin_remark" => $admin_remark,
                "update_date" => time(),
            );
            
            if(is_numeric($pay_status)) {
                $data["pay_status"] = $pay_status;
            }
            
            if($order_status==5) { //has shipped
                $data["shipped_date"] = time();
            }

            Db::startTrans();

            $order = Db::name("orders")->where("oid", $oid)->find();

            if (!Db::name("orders")->where("oid", $oid)->update($data)) {
                throw new Exception("訂單更新失敗");
            }

            if ($order_status == 100) {
                if ($order['order_credits'] > 0) {
                    \app\models\Customer::changeCredits($order["customerid"], $order['order_credits'], $order['oid'], "線上購物贈送");
                }
            }

            
            //執行郵件發送
            send_order_email($oid);
            Db::commit();
            toJSON(["code" => 0, "msg" => "更新成功"]);
        } catch (\Exception $e) {
            Db::rollback();
            toJSON(["code" => 1, "msg" => $e->getMessage()]);
        }
    }

    public function pay_info() {
        $oid = Request::instance()->param("oid");
        if (empty($oid)) {
            toJSON(["code" => 1, "msg" => "訂單號不存在"]);
        }
        $row = Db::name("user_pay")->where("oid", $oid)->where("pay_status", 1)->find();
        if (!empty($row)) {
            $html = "<table class='list'>";
            $html .= "<tr><td>付款金額:</td><td><i class='red'>" . $row["real_money"] . "</i></td></tr>";
            $html .= "<tr><td>付款流水號:</td><td><i class='blue'>" . $row["pay_pic"] . "</i></td></tr>";
            $html .= "<tr><td>備註:</td><td><i class='green'>" . $row["pay_note"] . "</i></td></tr>";
            $html .= "</table>";
            exit($html);
        }

        echo "未找到付款訊息";
    }
    
    //創建物流訂單
    public function create_express_order()
    {
        try {
            $oid = Request::instance()->param("oid");
            $express = new \ecpay\ECPayExpress();
            $order = Db::name("orders")->where("oid", $oid)->find();
            if($order["LogisticsType"] == "CVS") {
                $ret = $express->BGCvsCreateShippingOrder($order);
            } elseif($order["LogisticsType"] == "HOME") {
                $ret = $express->BGHomeCreateShippingOrder($order);
            }
            //throw new Exception(json_encode($ret, JSON_UNESCAPED_UNICODE));
            if($ret["err_code"]>0) {
                throw new Exception($ret["msg"]);
            }
            
            if(empty($ret["ResCode"])) {
                throw new Exception($ret["ErrorMessage"]);
            }

            //儲存
            $data = [
                "order_status" => 2, //等待取貨
                "AllPayLogisticsID" => isset($ret["AllPayLogisticsID"])?$ret["AllPayLogisticsID"]:0,
                "ECheckMacValue" => isset($ret["CheckMacValue"])??'',
                "BookingNote" => $ret["BookingNote"]??'',
                "CVSPaymentNo" => $ret["CVSPaymentNo"]??'',
                "CVSValidationNo" => $ret["CVSValidationNo"]??''
            ];

            if(!Db::name("orders")->where("oid", $ret["MerchantTradeNo"])->update($data)) {
                throw new Exception("創建成功, 系統更新狀態失敗");
            }
        
            toJSON(["code" => 0, "msg" => $ret["RtnMsg"]]);
        } catch(\Exception $e) {
            toJSON(["code" => 1, "msg" => $e->getMessage()]);
        }
    }

    /**
     * returned items
     * @return string
     * @throws \think\db\exception\DbException
     */
    public function returns()
    {
        $keyword = input("keyword");
        $state = input("state");
        $map = [];
        if(!empty($state)) {
            $map[] = ["or.state", "=", $state];
        }

        if(!empty($keyword)) {
            $map[] = ["or.oid", "LIKE", "%".$keyword."%"];
        }
        $params["list_rows"] = 20;
        $params["query"] = [
            "keyword" => $keyword,
            "state" => $state
        ];
        $query = Db::table(OrderReturn::$tablename)->alias("or")
            ->join(Order::$tablename." o", "o.oid=or.oid")
            ->where($map)
            ->field("or.*, o.AllPayLogisticsID")
            ->order("or.create_date DESC")
            ->paginate($params);
        $data["list"] = $query->all();
        $data["pages"] = $query->render();
        $data["keyword"] = $keyword;
        $data["state"] = $state;
        View::assign($data);
        return View::fetch();
    }

    public function changeReturnState()
    {
        try {
            $returnid = input("returnid");
            $state = input("state");
            if(false === Db::name(OrderReturn::$tablename)->where("returnid", $returnid)->update(["state" => $state, "update_date" => time()])) {
                throw new Exception("狀態變更失敗");
            }

            toJSON([
                "code" => 0,
                "msg" => "狀態變更成功"
            ]);
        } catch (Exception $ex) {
            toJSON([
                "code" => 1,
                "msg" => $ex->getMessage()
            ]);
        }
    }

    /**
     * returned items detail
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function return_detail()
    {
        $returnno = Request::instance()->param("returnno");

        $map = "returnno=".$returnno;

        $order = Db::name(OrderReturn::$tablename)->where($map)->find();

        if(empty($order)) {
            abort(404);
        }

        $data["products"] = Db::table(OrderReturnProduct::$tablename)->alias("rp")
            ->join(OrderProduct::$tablename." op", "op.opid=rp.opid")
            ->where("returnno", $returnno)
            ->field("op.prodid, op.prodname, op.prodimage,op.sku, op.prod_price, op.options, rp.qty, rp.return_amount")
            ->select();

        $data["order"] = $order;
        View::assign($data);
        return View::fetch();
    }
    
    //產生逆物流單
    public function create_return_express_order()
    {
        try {
            $returnid = Request::instance()->param("returnid");

            $returnItem = Db::name(OrderReturn::$tablename)->where("returnid", $returnid)->find();

            $express = new \ecpay\ECPayExpress();
            $order_return = Db::name("orders")->where("oid", $returnItem["oid"])->find();

            $order_return["total_amount"] = $returnItem["total_amount"];

            $ret = [];
            if($order_return["LogisticsType"] == "CVS") {
                if($order_return["LogisticsSubType"] == "FAMI") {
                    $ret = $express->CreateFamilyB2CReturnOrder($order_return);
                } elseif($order_return["LogisticsSubType"] == "UNIMART") {
                    $ret = $express->CreateUnimartB2CReturnOrder($order_return);
                } elseif($order_return["LogisticsSubType"] == "HILIFE") {
                    $ret = $express->CreateHiLifeB2CReturnOrder($order_return);
                }

                if($ret["err_code"]>0) {
                    throw new Exception($ret["msg"]);
                }

                if(!isset($ret["RtnMerchantTradeNo"])) {
                    throw new Exception("產生逆物流單失敗");
                }
                $data = [
                    "RtnMerchantTradeNo" => $ret["RtnMerchantTradeNo"],
                    "RtnOrderNo" => $ret["RtnOrderNo"],
                    "RtnCode" => 1,
                    "RtnMsg" => "OK"
                ];
                
            } elseif($order_return["LogisticsType"] == "HOME") {
                $ret = $express->CreateHomeReturnOrder($order_return);

                if($ret["err_code"]>0) {
                    throw new Exception($ret["msg"]);
                }

                if($ret["RtnCode"] !=1) {
                    throw new Exception($ret["RtnMsg"]);
                }

                $data = [
                    "RtnCode" => $ret["RtnCode"],
                    "RtnMsg" => $ret["RtnMsg"]
                ];

            }

            $data["state"] = "processing";
            $data["update_date"] = time();
            if(!Db::name(OrderReturn::$tablename)->where("returnid", $returnid)->update($data)) {
                throw new Exception("創建成功, 系統更新狀態失敗");
            }

            toJSON(["code" => 0, "msg" => "操作成功"]);
        } catch(Exception $ex) {
            toJSON(["code" => 1, "msg" => $ex->getMessage()]);
        }
    }

    public function recevied()
    {
        try {
            $returnid = (int) input("returnid");
            $order = Db::name(OrderReturn::$tablename)->where("returnid", $returnid)->find();
            if(!Db::name(OrderReturn::$tablename)->where("returnid", $returnid)->update(["state" => "complete", 'update_date' => time()])) {
                throw new Exception("操作失敗");
            }

            toJSON(["code" => 0, "msg" => "操作成功"]);
        } catch(Exception $ex) {
            toJSON(["code" => 1, "msg" => $ex->getMessage()]);
        }
    }
    
    //列印託運單
    public function PrintTradeDoc()
    {
        $oid = Request::instance()->param("oid");
        $order = Db::name("orders")->where("oid", $oid)->find();
        $express = new \ecpay\ECPayExpress();
        $express->PrintTradeDoc($order);
    }
    
    //物流查詢
    public function QueryLogisticsInfo()
    {
        $AllPayLogisticsID = Request::instance()->param("AllPayLogisticsID");
        $express = new \ecpay\ECPayExpress();
        $express->QueryLogisticsInfo($AllPayLogisticsID); //先入庫
        
        $data["list"] = Db::name("express_status")->where("AllPayLogisticsID", $AllPayLogisticsID)->order("TradeDate DESC")->select();
        View::assign($data);
        return View::fetch("express_status");
    }

    public function messages()
    {
        $oid = input("oid");
        $data["messages"] = Db::table(OrderMessage::$tablename)->alias("om")
            ->join(User::$tablename." u", "u.userid=om.userid", "LEFT")
            ->field("om.*, u.fullname")
            ->where("oid", $oid)->order("om.creat_at DESC")->select();
        View::assign($data);
        return View::fetch();
    }

    public function reply()
    {
        $id = input("id");
        $msg = Db::name(OrderMessage::$tablename)->where("id", $id)->find();
        if(request()->isPost()) {
            try {
                $answer = input("answer");
                if(empty($answer)) {
                    throw new Exception("請輸入回覆內容");
                }

                if(mb_strlen($answer)>1000) {
                    throw new Exception("回覆內容不能超過1000字");
                }

                if(false === Db::name(OrderMessage::$tablename)->where("id", $id)->update(["userid" => $this->getUserId(), 'answer' =>$answer, 'answer_date' => time()])) {
                    throw new Exception("回覆失敗");
                }

                $order = Db::name(Order::$tablename)->where("oid", $msg["oid"])->find();
                $customer = Db::name(\app\models\Customer::$tablename)->where("customerid", $order["customerid"])->find();
                $order_action = order_state_actions();
                if(isset($order_action[$order["order_status"]])) {
                    $source = [
                        "{domain}",
                        "{fullname}",
                        "{question}",
                        "{answer}"
                    ];

                    $replace = [
                        request()->domain(),
                        $customer["fullname"],
                        $msg["question"],
                        $answer,
                    ];

                    $html_template = BASE_ROOT."/".$order_action["reply"]["email_html"];
                    $html = file_get_contents($html_template);
                    $body = str_replace($source, $replace, $html);
                    $revs = [
                        $customer["custconemail"],
                    ];
                    send_email($order_action["reply"]["title"], $body, "", $revs);
                }

                toJSON([
                    "code" => 0,
                    "msg" => "回覆成功",
                    "url" => admin_link("messages", ['oid' => $msg['oid']])
                ]);
            } catch (Exception $ex) {
                toJSON([
                    "code" => 1,
                    "msg" => $ex->getMessage()
                ]);
            }
        }
        $data['msg'] = $msg;
        View::assign($data);
        return View::fetch("reply");
    }

    public function report()
    {
        $start_date = input("start_date");
        $end_date = input("end_date");

        $where = [];
        $where[] = ["o.order_status", "=", 100];

        if(!empty($start_date)) {
            $where[] = ["o.create_date", ">=", strtotime($start_date)];
        }
        if(!empty($end_date)) {
            $where[] = ["o.create_date", "<=", strtotime($end_date." 23:59:59")];
        }

        //统计总额
        $data["total"] = Db::table(Order::$tablename)->alias("o")
            ->join(OrderProduct::$tablename." op", "op.oid=o.oid")
            ->join(ProdModel::$tablename." p", "p.prodid=op.prodid")
            ->where($where)
            ->field("count(distinct o.oid) as totalOrderNums, sum(o.total_amount) as totalAmount, sum(op.qty) as totalQty, count(distinct o.customerid) as totalCustomers")
            ->find();

        $params["list_rows"] = 1000;
        $params["query"] = [
            "start_date" => $start_date,
            "end_date" => $end_date,
        ];
        $query = Db::table(Order::$tablename)->alias("o")
            ->join(OrderProduct::$tablename." op", "op.oid=o.oid")
            ->join(ProdModel::$tablename." p", "p.prodid=op.prodid")
            ->where($where)
            ->field("p.prodid, p.prodname, p.prodcode, count(distinct o.oid) as totalOrderNums, sum(o.total_amount) as totalAmount, sum(op.qty) as totalQty, count(distinct o.customerid) as totalCustomers")
            ->order("totalAmount DESC")
            ->group("op.prodid")
            ->paginate($params);

        $data["list"] = $query->all();
        $data["pages"] = $query->render();
        $data["start_date"] = $start_date;
        $data["end_date"] = $end_date;
        View::assign($data);
        return View::fetch("report");
    }

    //open invoice
    public function makeInvoice()
    {
        try {
            $oid = input("oid");
            $order = Db::name(Order::$tablename)->where("oid", $oid)->find();
            if(empty($order)) {
                throw new Exception("訂單無效");
            }

            if(!empty($order["InvoiceNumber"])) {
                throw new Exception("該筆訂單已開立發票,請不要重複操作");
            }

            $ecpayInvoice = new ECPayInvoice();
            $ret = $ecpayInvoice->makeInvoice($oid);

            if(!isset($ret["RtnCode"])) {
                throw new Exception("開立發票失敗");
            }

            if($ret["RtnCode"] != 1) {
                throw new Exception($ret["RtnMsg"]);
            }

            $update = [
                "InvoiceNumber" => $ret["InvoiceNumber"],
                "RandomNumber" => $ret["RandomNumber"],
                "InvoiceDate" => $ret["InvoiceDate"]
            ];

            if(false === Db::name(Order::$tablename)->where("oid", $oid)->update($update)) {
                throw new Exception("開票成功,儲存發票號碼失敗");
            }

            toJSON(["code" => 0, "msg" => $ret["RtnMsg"]]);
        } catch (Exception $ex) {
            toJSON(["code" => 1, "msg" => $ex->getMessage()]);
        }
    }

    //發送發票開立通知
    public function notifyInvoice()
    {
        $oid = input("oid", 0);
        $order = Db::name(Order::$tablename)->where("oid", $oid)->find();
        try {
            if(empty($order)) {
                throw new Exception("訂單無效");
            }

            if(empty($order["InvoiceNumber"])) {
                throw new Exception("該筆訂單未開立發票,請先開立發票再發送通知");
            }

            $notify = new ECPayInvoice();
            if(empty($order["invoice_email"])) {
                $mail = $order["billing_email"];
            } else {
                $mail = $order["invoice_email"];
            }
            $ret = $notify->notifyInvovice($order["InvoiceNumber"], $mail);
            if(!isset($ret["RtnCode"])) {
                throw new Exception("發票通知發送失敗");
            }

            if($ret["RtnCode"] != 1) {
                throw new Exception($ret["RtnMsg"]);
            }

            if(false === Db::name(Order::$tablename)->where("oid", $oid)->inc("invoice_notify_times", 1)->update()) {
                throw new Exception("發票通知發送成功, 記錄發送次數失敗");
            }

            toJSON(["code" => 0, "msg" => $ret["RtnMsg"]]);
        } catch (Exception $ex) {
            toJSON(["code" => 1, "msg" => $ex->getMessage()]);
        }
    }

    public function rePushStock()
    {
        try {
            $oid = input("oid");
            $log = Db::name(StockPushLogs::$tablename)->where("oid", $oid)->find();
            if(empty($log)) {
                throw new Exception("無庫存同步記錄");
            }

            if($log["ret_code"]=="Success") {
                throw new Exception("庫存同步成功不可重新同步");
            }

            if(false === Db::name(StockPushLogs::$tablename)->where("oid", $oid)->update(["state" => 1])) {
                throw new Exception("重置同步狀態失敗");
            }

            toJSON(["code" => 0, "msg" => "重置訂單同步狀態成功"]);
        } catch (Exception $ex) {
            toJSON(["code" => 1, "msg" => $ex->getMessage()]);
        }
    }

}
