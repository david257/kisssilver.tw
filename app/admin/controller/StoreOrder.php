<?php
namespace app\admin\controller;
use app\models\AttrValue;
use app\models\CouponCodeOrders;
use app\models\CouponCodes;
use app\models\CreditLog;
use app\models\Customer;
use app\models\CustomerAddress;
use app\models\Gift;
use app\models\Order;
use app\models\OrderGift;
use app\models\ProductImages;
use app\models\Products;
use app\models\Promotion;
use app\models\StorePaidOrder;
use app\models\VariationCombinations;
use think\facade\Db;
use think\facade\Request;
use think\facade\Session;
use think\facade\View;
use think\Exception;
use app\models\Coupon;
use app\models\StorePaidOrder as SPO;

class StoreOrder extends Base
{
    public function index()
    {
           $page_size = 30;
           $keyword = input("keyword");
           $start_date = input("start_date");
           $end_date = input("end_date");
           $userid = input("userid", 0);

           $map = [];

           if(empty($userid)) {
               $spouserid = $this->getUserId();
               $tpl = "index";
           } else {
               $spouserid = $userid;
               $tpl = "report_index";
           }

           $map[] = ["spo.userid", "=", $spouserid];
           if(!empty($keyword)) {
               $map[] = ["coupon_code|vipcode|custconemail|ct.fullname", "LIKE", "%".$keyword."%"];
           }

           if(!empty($start_date)) {
               $map[] = ["spo.create_at",">=", strtotime($start_date)];
           }

           if(!empty($end_date)) {
               $map[] = ["spo.create_at", "<=", strtotime($end_date." 23:59:59")];
           }

           $params["list_rows"] = $page_size;
           $params["query"] = [
                "keyword" => $keyword,
                "start_date" => $start_date,
                "end_date" => $end_date,
               "userid" => $userid
            ];

           $list = Db::table(SPO::$tablename)->alias("spo")
               ->join("customers ct", "ct.customerid=spo.customerid")
               ->join("users u", "u.userid=spo.userid")
               ->where($map)
               ->field("spo.*, ct.vipcode, ct.fullname, u.fullname as user_fullname")
               ->order("spoid DESC")
               ->paginate($params, false);

           $data["list"] = $list->all();
           $data["pages"] = $list->render();

           $data["keyword"] = $keyword;
           $data["start_date"] = $start_date;
           $data["end_date"] = $end_date;
           $data["userid"] = $userid;
           View::assign($data);
           return View::fetch($tpl);
    }

    /**
     * find the customer
     */
    public function customer()
    {
        $keyword = input("keyword");
        $map = [];
        if(!empty($keyword)) {
            $map[] = ["vipcode|custconemail|fullname|mobile", "LIKE", "%".$keyword."%"];
        } else {
            $map["vipcode"] = 1;
        }
        $data["list"] = Db::name(Customer::$tablename)->where($map)->select();
        $data["keyword"] = $keyword;
        View::assign($data);
        return View::fetch();
    }

    public function checkout()
    {
        try {

            $customerid = (int) input("customerid", 0);

            if(request()->isPost()) {
                Db::startTrans();
                try {
                    if(empty($customerid)) {
                        throw new Exception("會員未選擇");
                    }

                    $customer = Db::name(Customer::$tablename)->where("customerid", $customerid)->find();
                    if(empty($customer)) {
                        throw new Exception("未找到會員");
                    }

                    $total_amount = (int) input("total_amount", 0);
                    if(!is_numeric($total_amount) || $total_amount<=0 || $total_amount>100000000) {
                        throw new Exception("消費金額輸入錯誤");
                    }

                    $coupon_code = input("coupon_code");
                    $credits = (int) input("credits", 0);
                    $coupon_amount = 0;
                    $coupon_title = "";
                    if(!empty($coupon_code)) {
                          $coupon = Db::name(Coupon::$tablename)->where("code", $coupon_code)->find();
                          if(empty($coupon)) {
                              throw new Exception("優惠券不存在");
                          }

                          if($coupon["coupon_type"] != "offline") {
                            throw new Exception("該優惠券不是門店線下發放");
                          }

                          if(!$coupon["state"]) {
                              throw new Exception("優惠券無效");
                          }

                          if($coupon["has_used"]) {
                            throw new Exception("優惠券已使用");
                          }

                          if($coupon["start_time"]>time() && $coupon["end_time"]<time()) {
                              throw new Exception("優惠券不在有效期範圍");
                          }

                          if($coupon["min_amount"]>$total_amount) {
                              throw new Exception("此優惠券最低消費額為:".$coupon["min_amount"]);
                          }

                          $coupon_title = $coupon["title"];
                          $coupon_amount = $coupon["amount"];
                    }
                    $ordno = date("YmdHis").rand(1000,9999);

                    $paid_amount = bcsub($total_amount, $coupon_amount);
                    $creditMoney = 0;
                    if($credits>0) {

					   $customer = Db::name(Customer::$tablename)->where("customerid", $customerid)->find();
						if($customer['credits']<$credits) {
							throw new Exception("您的紅利點數不足");
						}

                       $creditMoney = CreditLog::creditsToMoney($credits);
                       if($creditMoney<=0) {
                           throw new Exception("紅利點數1點起用");
                       }

                       Customer::changeCredits($customerid, -$credits, $ordno, "門店消費抵扣");
                    }

					if($paid_amount>0 && $creditMoney>$paid_amount) {
						throw new Exception("您輸入抵扣的紅利點數太多了");
					}

                    $paid_amount -= $creditMoney;


                    $getcredits = 0;
                    if ($paid_amount > 0) { //calc credits
                        $getcredits = CreditLog::orderToCredit($paid_amount);

                        Customer::changeCredits($customerid, $getcredits, $ordno, "門市消費贈送"); //if exception, auto throw
                    }

                    $saveOrderData = [
                        "ordno" => $ordno,
                        "customerid" => $customerid,
                        "total_amount" => $total_amount,
                        "coupon_code" => $coupon_code,
                        "coupon_title" => $coupon_title,
                        "coupon_amount" => $coupon_amount,
                        "paid_amount" => $paid_amount,
                        "credits" => $getcredits,
                        "userid" => $this->getUserId(),
                        "create_at" => time()
                    ];

                    $oid = Db::name(StorePaidOrder::$tablename)->insertGetId($saveOrderData);
                    if(empty($oid)) {
                        throw new Exception("結帳失敗");
                    }

                    //coupon data
                    $couponUpdateData = [
                        "customerid" => $customerid,
                        "has_used" => 1,
                        "used_date" => time(),
                        "oid" => $oid,
                        "update_time" => time()
                    ];

                    if(false === Db::name(Coupon::$tablename)->where("code", $coupon_code)->update($couponUpdateData)) {
                        throw new Exception("結帳失敗");
                    }

                    $snid = $this->getUserId();
                    //生成到訂單表，統一推送到ERP
                    $oid = date("ymdHis").rand(1000,9999); //make order oid
                    //插入訂單
                    $order_data = [
                        "oid" => $oid,
                        "payoid" => $oid."V1",
                        "customerid" => $customerid,
                        "sub_total_amount" => $total_amount,
                        "shipping_fee" => 0,
                        "coupon_amount" => $coupon_amount,
                        "coupon_code" => $coupon_code,
                        "promotion_rules" => json_encode([], JSON_UNESCAPED_UNICODE),
                        "promotion_amount" => 0,
                        "credits" => $credits,
                        "credit_money" => $creditMoney,
                        "total_item_amount" => $total_amount,
                        "order_credits" => $getcredits,
                        "total_amount" => $paid_amount,

                        "billing_name" => $customer["fullname"],
                        "billing_mobile" => $customer["mobile"],
                        "billing_tel1" => $customer["tel"],
                        "billing_email" => $customer["custconemail"],
                        "snid" => $snid,
                        "update_date" => time(),
                        "create_date" => time(),
                    ];

                    $order_data["pay_status"] = 1;
                    $order_data["order_status"] = 100;

                    $oid = Db::name(Order::$tablename)->insertGetId($order_data);
                    if(!$oid) {
                        throw new Exception("結帳失敗", 1003);
                    }

                    //入庫產品
                    $qtys = [];
                    $prodSold = [];

                    $qtys = input("post.qty/a");
                    if(empty($qtys)) {
                        throw new Exception("商品未選擇");
                    }
                    $order_products = [];
                    foreach($qtys as $prodid => $qty) {
                        $optionStr = "";
                        $prod = Db::name(Products::$tablename)->where("prodid", $prodid)->find();
                        $prodcode = $prod["prodcode"];
                        $image_src = "";
                        $optionnames = [];
                        if(empty($prod["void"])) {
                            $price = $prod["prod_price"];
                            $image = Db::name(ProductImages::$tablename)->where("productid", $prodid)->where("is_main", 1)->find();
                            $image_src = empty($image)?"":$image["image_thumb"];
                        } else {
                            $valueids = input("post.valueids/a");
                            $vcoptionids = [];
                            foreach($valueids[$prodid] as $attrid => $valueid) {
                                $attr = Db::name("product_attris")->where("attrid", $attrid)->find();
                                $attrval = Db::name("product_attr_values")->where("valueid", $valueid)->find();
                                $optionnames[] = [
                                    "attrname" => $attr['name'],
                                    "valuename" => $attrval['name']
                                ];
                                $vcoptionids[] = $valueid;

                                if(!empty($attrval["imagefile"])) {
                                    $image_src = $attrval["imagefile"];
                                }
                            }

                            //check price, stock
                            sort($vcoptionids);
                            $xprod = Db::name(VariationCombinations::$tablename)->where("vcproductid", $prodid)->where("vcoptionids", implode(",",$vcoptionids))->find();
                            if(!empty($xprod['vcsku'])) {
                                $prodcode = $xprod['vcsku'];
                            } else {
                                throw new Exception("編號：".$prod["prodcode"]." 商品規格設置錯誤，請聯繫管理員重新編輯此商品再結帳");
                            }

                            $price = $xprod["vcprice"];
                        }

                        $order_products[] = [
                            "oid" => $oid,
                            "prodid" => $prodid,
                            "sku" => $prodcode,
                            "prodimage" => $image_src,
                            "prodname" => $prod["prodname"],
                            "options" => json_encode($optionnames),
                            "qty" => $qty,
                            "prod_price" => $price,
                            "total_amount" => bcmul($price, $qty),
                            "snid" => $snid,
                            "create_date" => time(),
                        ];

                        if(!isset($prodSold[$prodid])) {
                            $prodSold[$prodid] = 0;
                        }

                        $prodSold[$prodid] += $qty;
                    }

                    if(!Db::name("order_products")->insertAll($order_products)) {
                        throw new Exception("產品入庫失敗", 1006);
                    }

                    //購買數量記錄到產品表
                    if(!empty($prodSold)) {
                        foreach($prodSold as $prodid => $_qty) {
                            Db::name(Products::$tablename)->where("prodid", $prodid)->inc("sold_qty", $_qty)->update();
                        }
                    }

                    Db::commit();
                    toJSON([
                        "code" => 0,
                        "msg" => "結帳成功, 獲得".$credits."紅利點數",
                        "url" => admin_link("StoreOrder/index")
                    ]);
                } catch (Exception $ex) {
                    Db::rollback();
                    toJSON([
                        "code" => 1,
                        "msg" => $ex->getLine().":".$ex->getMessage()
                    ]);
                }
            }

            if(empty($customerid)) {
                throw new Exception("會員未選擇");
            }

            $data['customer'] = Db::name(Customer::$tablename)->where("customerid", $customerid)->find();
            if(empty($data['customer'])) {
                throw new Exception("未找到會員");
            }

            $data["coupons"] = Db::name(Coupon::$tablename)
                ->where("customerid", $customerid)
                ->where("state", 1)
                ->where("has_used", 0)
                ->where("start_time", "<=", time())
                ->where("end_time", ">=", time())
                ->order("amount DESC")
                ->select();
            View::assign($data);
            return View::fetch();
        } catch (Exception $ex) {
            return redirect()->restore();
        }

    }

    public function calc()
    {
        try {
            $coupon_code = input("coupon_code");
            $credits = (int)input("credits");
            $total_amount = (int)input("total_amount");
			$customerid = (int) input("customerid", 0);
            if (!empty($coupon_code)) {
                $coupon = Db::name(Coupon::$tablename)->where("code", $coupon_code)->find();
                if(empty($coupon)) {
                    throw new Exception("優惠券無效");
                }

                if($coupon["coupon_type"] != "offline") {
                    throw new Exception("該優惠券不是門店線下發放");
                }

                if(!$coupon["state"]) {
                    throw new Exception("優惠券無效");
                }

                if($coupon["has_used"]) {
                    throw new Exception("優惠券已使用");
                }

                if($coupon["start_time"]>time() || $coupon["end_time"]<time()) {
                    throw new Exception("優惠券有效期不正確");
                }

                if($coupon["min_amount"]>$total_amount) {
                    throw new Exception("優惠券最低消費金額未達到");
                }

                $total_amount -= $coupon["amount"];
            }

			//if($credits<0) {
			//		throw new Exception("紅利點數必須100點起");
			//}

            if($credits>0) {

				//check if input credits more than left
				$customer = Db::name(Customer::$tablename)->where("customerid", $customerid)->find();
				if($customer['credits']<$credits) {
					throw new Exception("您的紅利點數不足");
				}



                $money = CreditLog::creditsToMoney($credits);
                if($money<1) {
                    throw new Exception("紅利點數必須1點起");
                }
				
				if($total_amount>0 && $money>$total_amount) {
					throw new Exception("您輸入抵扣的紅利點數太多了");
				}

                $total_amount -= $money;
            }

            $return_credits = CreditLog::orderToCredit($total_amount);
            return json([
                "code" => 0,
                "total_amount" => $total_amount,
                "return_credits" => $return_credits,
            ]);
        } catch(Exception $ex) {
            return json([
                "code" => 1,
                "total_amount" => $total_amount,
                "return_credits" => 0,
                "msg" => $ex->getMessage()
            ]);
        }
    }

    public function report()
    {
        $keyword = input("keyword");
        $start_date = input("start_date");
        $end_date = input("end_date");

        $where = [];
        if(!empty($keyword)) {
            $where[] = ["u.fullname|u.username", "LIKE", "%".$keyword."%"];
        }

        if(!empty($start_date)) {
            $where[] = ["create_at", ">=", strtotime($start_date)];
        }

        if(!empty($end_date)) {
            $where[] = ["create_at", "<=", strtotime($end_date." 23:59:59")];
        }

        $list = Db::table(\app\models\User::$tablename)->alias("u")
            ->join(StorePaidOrder::$tablename." spo", "spo.userid=u.userid")
            ->where($where)
            ->field("u.fullname, u.userid, count(spo.spoid) as total, sum(total_amount) as totalAmount, sum(coupon_amount) as totalCouponAmount, sum(credits) as totalCredits, sum(paid_amount) as totalPaidAmount")
            ->group("spo.userid")
            ->select();

        $data["list"] = $list;
        $data["keyword"] = $keyword;
        $data["start_date"] = $start_date;
        $data["end_date"] = $end_date;

        View::assign($data);
        return View::fetch();
    }

    public function get_product()
    {
        try {
            $prodcode = input("prodcode", "");
            $prodcodes = explode("\n", $prodcode);
            if(empty($prodcodes)) {
                throw new Exception("未搜尋到商品");
            }

            $codes = [];
            foreach($prodcodes as $k => $pcode) {
                $code = trim($pcode);
                if(!empty($code)) {
                    $codes[] = $code;
                }
            }
            if(empty($codes)) {
                throw new Exception("未搜尋到商品");
            }

            $products = Db::name("products")->field("prodid,void,prodcode, prodname")->where("prodcode", "in", $codes)->where("state", 1)->select();
            if(empty($products)) {
                throw new Exception("未搜尋到商品");
            }

            $options = [];
            foreach($products as $product) {
                if (!empty($product["void"])) {
                    $list = Db::name("product_attris")->where("void", $product["void"])->where("state", 1)->order("sortorder", "asc")->select();
                    foreach ($list as $op) {
                        $values = Db::name("product_attr_values")->where("attrid", $op["attrid"])->where("state", 1)->order("sortorder", "asc")->select();
                        $options[$product["prodid"]][$op["attrid"]]["name"] = $op["name"];
                        foreach ($values as $v) {
                            $options[$product["prodid"]][$op["attrid"]]["values"][] = $v;
                        }
                    }
                }

            }
            $data["products"] = $products;
            $data["options"] = $options;
            View::assign($data);
            return View::fetch();
        } catch (Exception $ex) {
            exit($ex->getMessage());
        }

    }

}
