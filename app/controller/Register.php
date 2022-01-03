<?php
namespace app\controller;

use app\BaseController;
use app\extend\CouponCode;
use app\models\Coupon;
use app\models\CouponAutoRule;
use app\models\Customer;
use think\facade\Db;
use think\Exception;
use think\facade\Session;
use think\facade\View;

class Register extends BaseController
{

    public function index()
    {
        if(request()->isAjax()) {
            try {
                $fullname = input("fullname");
                $custconemail = input("custconemail");
                $custpassword = input("custpassword");
                $custpassword2 = input("custpassword2");
                $code = input("code");

                if(empty($code)) {
                    throw new Exception("請輸入驗證碼");
                }

                if(Session::get("reg_code") != $code) {
                    throw new Exception("驗證碼錯誤");
                }

                if(empty($fullname)) {
                    throw new Exception("請輸入姓名");
                }

                if(mb_strlen($fullname)>100) {
                    throw new Exception("姓名不能超過100字元");
                }

                if(empty($custconemail)) {
                    throw new Exception("請輸入帳號（Email）");
                }

                if(mb_strlen($custconemail)>255) {
                    throw new Exception("帳號不能超過255字元");
                }

                if(!filter_var($custconemail, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("帳號必須是Email");
                }

                if(empty($custpassword)) {
                    throw new Exception("請輸入密碼");
                }

                if($custpassword != $custpassword2) {
                    throw new Exception("兩次密碼不一致");
                }

                if(Db::name(Customer::$tablename)->where("custconemail", $custconemail)->count()) {
                    throw new Exception("郵箱已存在");
                }

                $vipcode = getMaxVipCode();

                $active_code = md5(uniqid());
                $data = [
                    "fullname" => $fullname,
                    "custconemail" => $custconemail,
                    "custpassword" => md5($custpassword),
                    "state" => 0,
                    "vipcode" => $vipcode,
                    "active_code" => $active_code,
                    "create_at" => time(),
                    "regip" => request()->ip()
                ];

                if(false == Customer::add($data)) {
                    throw new Exception("註冊會員失敗");
                }

                send_active_email($fullname, $custconemail, $custpassword, $active_code);

                toJSON([
                    "code" => 0,
                    "msg" => "註冊提交成功,請前往郵箱完成開通",
                    "url" => front_link("Cart/index")
                ]);
            } catch(Exception $ex) {
                toJSON([
                    "code" => 1,
                    "msg" => $ex->getMessage()
                ]);
            }
        }

        $wait_secs = (int) Session::get("wait_secs");
        $data["wait_secs"] = max(10-(time()-$wait_secs), 0);
        View::assign($data);
        return View::fetch("index");
    }

    public function active()
    {
        try {
            $code = input("code");
            if(empty($code)) {
                throw new Exception("開通失敗");
            }

            $customer = Db::name(Customer::$tablename)->where("active_code", $code)->find();
            if(empty($customer)) {
                throw new Exception("無效帳號");
            }

            if(false === Db::name(Customer::$tablename)->where("active_code", $code)->update(["state" => 1, "active_code" => ''])) {
                throw new Exception("開通失敗");
            }

            //贈送新人券
            $autoCouponRule = Db::name(CouponAutoRule::$tablename)->where("auto_type", "register")->where("state", 1)->find();
            if(!empty($autoCouponRule)) {
                $coupon = Db::name(Coupon::$tablename)->where("customerid", $customer["customerid"])->where("coupon_type", "reg")->count();
                if(empty($coupon)) {
                    $code_dao = new CouponCode();
                    $data = [
                        "title" => $autoCouponRule["title"],
                        "customerid" => $customer["customerid"],
                        "coupon_type" => "reg",
                        "code" => $code_dao->encodeID(rand(1000000, 9999999)),
                        "min_amount" => $autoCouponRule["min_amount"],
                        "amount" => $autoCouponRule["amount"],
                        "start_time" => time(),
                        "end_time" => strtotime("+".$autoCouponRule['expired_days']." day"),
                        "create_time" => time(),
                    ];
                    Db::name("coupons")->insert($data);
                }
            }

            send_register_email($customer);

            $data["msg"] = "帳號已開通成功，您的會員帳號已可以使用！";
        } catch (Exception $ex) {
            $data["msg"] = $ex->getMessage();
        }

        View::assign($data);
        return View::fetch();
    }

    //發送驗證碼
    public function send_sms_code()
    {
        $code = rand(1000, 9999);
        Session::set("reg_code", $code);
        $mobile = input("mobile");
        $content = "您本次註冊驗證碼為: ".$code;
        try {
            if(empty($mobile)) {
                throw new Exception("手機號碼不能為空");
            }

            /*
            $ok = sendSms($mobile, $content);
            if($ok<0) {
                throw new Exception("驗證碼獲取失敗");
            }*/

            Session::set("wait_secs", time());

            return json_encode([
                "code" => 0,
                "msg" => "驗證碼獲取成功"
            ], JSON_UNESCAPED_UNICODE);
        } catch (Exception $ex) {
            return json_encode([
                "code" => 1,
                "msg" => $ex->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }

}
