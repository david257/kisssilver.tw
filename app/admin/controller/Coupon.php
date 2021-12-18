<?php
namespace app\admin\controller;
use think\facade\Db;
use think\facade\View;
use think\Exception;
use app\extend\CouponCode;

class Coupon extends Base
{
    public function index()
    {
       $page_size = 30;
       $keyword = input("keyword");
       $has_used = input("has_used", 'All');
       $used_date = input("used_date");
       $map = [];
       if(!empty($keyword)) {
           $map[] = "(code='".$keyword."' OR vipcode='".$keyword."' OR custconemail='".$keyword."' OR fullname LIKE '%".$keyword."%')";
       }
       
       if(is_numeric($has_used)) {
           $map[] = "has_used=".$has_used;
       }
       
       if(!empty($used_date)) {
           $map[] = "used_date between ".strtotime($used_date)." AND ".strtotime($used_date." 23:59:59");
       }

        $params["list_rows"] = $page_size;
        $params["query"] = [
            "keyword" => $keyword,
            "has_used" => $has_used,
            "used_date" => $used_date
        ];
       
       $list = Db::table("coupons")->alias("c")
           ->join("customers ct", "ct.customerid=c.customerid", "LEFT")
           ->where(implode(' AND ', $map))
           ->field("c.*, ct.vipcode, ct.fullname")
           ->order("cpid DESC")
           ->paginate($params, false);
       $data["list"] = $list->all();
       $data["pages"] = $list->render();

       $data["keyword"] = $keyword;
       $data["has_used"] = $has_used;
       $data["used_date"] = $used_date;
       View::assign($data);
       return View::fetch();
    }
    
    //設置優惠券自動發放規則
    public function auto_send_rule()
    {
        $list = Db::name("coupons_auto")->select();
        $rules = [];
        if(!empty($list)) {
            foreach($list as $v) {
                $rules[$v["auto_type"]] = $v;
            }
        }
        View::assign("rules", $rules);
        return View::fetch();
    }
    
    public function save_auto_rule()
    {
        try {
            $auto_type = input("auto_type");
            $title = input("title");
            $order_amount = input("order_amount", 0);
            $min_amount = (int)input("min_amount");
            $amount = (int)input("amount");
            $expired_days = (int)input("expired_days");
            $state = (int)input("state");

            $data = [
                "auto_type" => $auto_type,
                "title" => $title,
                "order_amount" => $order_amount,
                "min_amount" => $min_amount,
                "amount" => $amount,
                "expired_days" => $expired_days,
                "state" => $state
            ];

            if (Db::name("coupons_auto")->where("auto_type", $auto_type)->count()) {
                $data["update_time"] = time();
                if (false === Db::name("coupons_auto")->where("auto_type", $auto_type)->update($data)) {
                    throw new Exception("操作失敗");
                }
            } else {
                $data["create_time"] = time();
                if (false === Db::name("coupons_auto")->insert($data)) {
                    throw new Exception("操作失敗");
                }
            }

            toJSON([
                "code" => 0,
                "msg" => "操作成功"
            ]);
        } catch (Exception $ex) {
            toJSON([
                "code" => 1,
                "msg" => $ex->getMessage()
            ]);
        }
    }

    /**
     * send coupons
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function add()
    {
        $data["customers"] = Db::name("customers")->where("state", 1)->field("customerid, vipcode, fullname")->select();
        View::assign($data);
        return View::fetch("form");
    }

    /**
     * import offline coupons
     * @return string
     */
    public function import()
    {
        if(request()->isPost()) {
            try {
                $title = input("title");
                $coupon_type = input("coupon_type");
                $start_date = input("start_date");
                $end_date = input("end_date");
                $min_amount = (int) input("min_amount");
                $amount = (int) input("amount");
                $coupons = input("coupons");

                if(empty($title)) {
                    throw new Exception("優惠券標題未填寫");
                }

                if(empty($coupon_type)) {
                    throw new Exception("優惠券類型未設定");
                }

                if(empty($start_date) || empty($end_date) || date("Ymd",strtotime($end_date))<date("Ymd") || strtotime($end_date)<strtotime($start_date)) {
                    throw new Exception("有效日期段不正確");
                }

                if($min_amount<=0) {
                    throw new Exception("請填寫滿額金額");
                }

                if($amount<=0) {
                    throw new Exception("請填寫折扣金額");
                }

                if($min_amount<=$amount) {
                    throw new Exception("訂購金額必須大於折價金額");
                }

                if(empty($coupons)) {
                    throw new Exception("券號不能為空");
                }

                $coupons = explode("\n", $coupons);
                if(empty($coupons)) {
                    throw new Exception("券號不能為空");
                }

                $total = count($coupons);
                $suc = 0;
                Db::startTrans();

                foreach($coupons as $k => $coupon) {
                    $code = trim($coupon);
                    if(empty($code)) continue;

                    if(Db::name("coupons")->where("code", $code)->count()) {
                        continue;
                    }

                    $data = [
                        "title" => $title,
                        "coupon_type" => $coupon_type,
                        "code" => $code,
                        "min_amount" => $min_amount,
                        "amount" => $amount,
                        "start_time" => strtotime($start_date),
                        "end_time" => strtotime($end_date . " 23:59:59"),
                        "create_time" => time(),
                    ];
                    if(false === Db::name("coupons")->insert($data)) {
                        throw new Exception("優惠券匯入失敗");
                    }
                    $suc++;
                }

                Db::commit();
                toJSON([
                    "code" => 0,
                    "msg" => "匯入完成, 總行數(".$total."), 匯入成功(".$suc.")",
                    "url" => admin_link("Coupon/index")
                ]);
            } catch(Exception $ex) {
                Db::rollback();
                toJSON([
                    "code" => 1,
                    "msg" => $ex->getMessage()
                ]);
            }
        }
        return View::fetch("import_form");
    }

    /**
     * send coupon by admin
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function save()
    {
        
        try {
            $send_type = input("send_type");
            $title = input("title");
            $coupon_type = input("coupon_type");
            $start_date = input("start_date");
            $end_date = input("end_date");
            $min_amount = (int) input("min_amount");
            $amount = (int) input("amount");
            if(empty($send_type)) {
                throw new Exception("請選擇優惠券發放人群");
            }
            
            if(empty($title)) {
                throw new Exception("優惠券標題未填寫");
            }

            if(empty($coupon_type)) {
                throw new Exception("優惠券類型未設定");
            }
            
            if(empty($start_date) || empty($end_date) || date("Ymd",strtotime($end_date))<date("Ymd") || strtotime($end_date)<strtotime($start_date)) {
                throw new Exception("有效日期段不正確");
            }
            
            if($min_amount<=0) {
                throw new Exception("請填寫滿額金額");
            }
            
            if($amount<=0) {
                throw new Exception("請填寫折扣金額");
            }

            if($min_amount<=$amount) {
                throw new Exception("訂購金額必須大於折價金額");
            }
            
            switch($send_type) {
                case "by_user_ids":
                {
                    $customer_ids = isset($_POST["customer_ids"])?$_POST["customer_ids"]:"";
                    if(empty($customer_ids)) {
                        throw new Exception("用戶不能為空");
                    }
                    
                    $total_users = Db::name("customers")->where("customerid", "IN", $customer_ids)->where("state", 1)->count();
                    if($total_users != count($customer_ids)) {
                        throw new Exception("未在資料庫中找到與您輸入的用戶數, 請刪除空行");
                    }
                    Db::startTrans();
                    $i = 0;
                    foreach($customer_ids as $k => $customerid) {
                        $code_dao = new CouponCode();
                        $customerid = trim($customerid);
                        if(!empty($customerid)) {
                            $data = [
                                "customerid" => $customerid,
                                "title" => $title,
                                "coupon_type" => $coupon_type,
                                "code" => $code_dao->encodeID(rand(1000000, 9999999)),
                                "min_amount" => $min_amount,
                                "amount" => $amount,
                                "start_time" => strtotime($start_date),
                                "end_time" => strtotime($end_date." 23:59:59"),
                                "create_time" => time(),
                            ];
                            if(false === Db::name("coupons")->insert($data)) {
                                throw new Exception("優惠券發放失敗");
                            }
                            
                            //send_coupon_email($user_id, $title, $amount, $min_amount);
                            $i++;
                        }
                    }
                    
                    Db::commit();
                    toJSON(["msg" => $i."張優惠券發放成功", "code" => 0, "url" => admin_link("Coupon/index")]);
                    break;
                }
                case "send_to_all":
                {
                    $customers = Db::name("customers")->where("state", 1)->select();

                    Db::startTrans();
                    $i = 0;
                    if(!empty($customers)) {
                        foreach($customers as $customer) {
                            $code_dao = new CouponCode();
                            $data = [
                                "customerid" => $customer["customerid"],
                                "title" => $title,
                                "coupon_type" => $coupon_type,
                                "code" => $code_dao->encodeID(rand(1000000, 9999999)),
                                "min_amount" => $min_amount,
                                "amount" => $amount,
                                "start_time" => strtotime($start_date),
                                "end_time" => strtotime($end_date." 23:59:59"),
                                "create_time" => time(),
                            ];
                            if(false === Db::name("coupons")->insert($data)) {
                                throw new Exception("優惠券發放失敗");
                            }
                            
                            //send_coupon_email($user["user_id"], $title, $amount, $min_amount);
                            $i++;
                        }
                    }

                    Db::commit();
                    toJSON(["msg" => $i."張優惠券發放成功", "code" => 0, "url" => admin_link("Coupon/index")]);
                    break;
                }
                default:
                {
                    throw new Exception("請選擇發放人群");
                }
            }
        } catch(Exception $e) {
            Db::rollback();
            toJSON(["msg" => $e->getMessage(), "code" => 1]);
        }
    }
    
    public function delete()
    {
        $cpid = (int) input("cpid");
        if(Db::name("coupons")->where("cpid", $cpid)->where("has_used", 0)->delete()) {
            toJSON(["msg" => "刪除成功", "code" => 0]);
        } else {
            toJSON(["msg" => "刪除失敗", "code" => 1]);
        }
    }

}
