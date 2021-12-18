<?php
namespace app\controller;

use app\BaseController;
use app\extend\CouponCode;
use app\models\Coupon;
use app\models\CouponAutoRule;
use app\models\Customer;
use think\Exception;
use think\facade\Db;
use think\facade\Session;
use think\facade\View;

class Login extends BaseController
{

    public function index()
    {
        if(request()->isAjax()) {
            try {
                $custconemail = input("custconemail");
                $custpassword = input("custpassword");

                if(empty($custconemail)) {
                    throw new Exception("請輸入帳號（Email）");
                }

                if(!filter_var($custconemail, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("帳號必須是Email");
                }

                if(empty($custpassword)) {
                    throw new Exception("請輸入密碼");
                }


                $where = [
                    "custconemail" => $custconemail,
                    "custpassword" => md5($custpassword),
                ];

                $customer = Db::name(Customer::$tablename)->where($where)->find();
                if(empty($customer)) {
                    throw new Exception("帳號不存在");
                }

                if(!$customer["state"]) {
                    throw new Exception("帳號未開通");
                }

                Session::set("customerId", $customer["customerid"]);
                return json_encode([
                    "code" => 0,
                    "msg" => "登入成功",
                    "url" => front_link("Cart/index")
                ], JSON_UNESCAPED_UNICODE);
            } catch(Exception $ex) {
                toJSON([
                    "code" => 1,
                    "msg" => $ex->getMessage()
                ]);
            }
        }

        $setting = get_setting();
        $data["setting"] = $setting["setting"];
        View::assign($data);
        return View::fetch("index");
    }

	public function logout()
	{
		Session::delete("customerId");
		return redirect(front_link("Login/index"));
	}

    /**
     * facebook login
     */
    public function fb_login()
    {
        $loginInfo = $_POST;
        try {
            if(empty($loginInfo) || !isset($loginInfo["id"]) || !is_numeric($loginInfo["id"])) {
                throw new Exception("授權登入失敗");
            }

            $customer = Db::name(Customer::$tablename)->where("usertype", "facebook")->where("openid", $loginInfo["id"])->find();
            if(!empty($customer)) {
                if(isset($loginInfo["email"]) && empty($customer["custconemail"])) {
                    Db::name(Customer::$tablename)->where("customerid", $customer["customerid"])->update(["custconemail" => $loginInfo["email"]]);
                }
                Session::set("customerId", $customer["customerid"]);
            } else {

                $vipcode = getMaxVipCode();

                $data = [
                    "custconemail" => isset($loginInfo["email"])?$loginInfo["email"]:'',
                    "fullname" => $loginInfo["name"],
                    "usertype" => "facebook",
                    "openid" => $loginInfo["id"],
                    "vipcode" => $vipcode,
                    "state" => 1,
                    "create_at" => time()
                ];
                $customerid = Db::name(Customer::$tablename)->insertGetId($data);
                if(false === $customerid) {
                    throw new Exception("臉書授權登入失敗");
                }

                $autoCouponRule = Db::name(CouponAutoRule::$tablename)->where("auto_type", "register")->where("state", 1)->find();
                if(!empty($autoCouponRule)) {
                    $coupon = Db::name(Coupon::$tablename)->where("customerid", $customerid)->where("coupon_type", "reg")->count();
                    if(empty($coupon)) {
                        $code_dao = new CouponCode();
                        $data = [
                            "title" => $autoCouponRule["title"],
                            "customerid" => $customerid,
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

                Session::set("customerId", $customerid);
            }

            return json([
                "code" => 0,
                "url" => front_link("Cart/index"),
                "msg" => "登入成功"
            ]);
        } catch (Exception $ex) {
            return json([
                "code" => 1,
                "msg" => $ex->getMessage()
            ]);
        }

    }

    public function gg_login()
    {
        $id = input("id", 0);
        $name = input("name");
        $email = input("email");
        try {
            if(empty($id) || !is_numeric($id)) {
                throw new Exception("授權登入失敗");
            }

            $customer = Db::name(Customer::$tablename)->where("usertype", "google")->where("openid", $id)->find();
            if(!empty($customer)) {
                if(!empty($email) && empty($customer["custconemail"])) {
                    Db::name(Customer::$tablename)->where("customerid", $customer["customerid"])->update(["custconemail" => $email]);
                }
                Session::set("customerId", $customer["customerid"]);
            } else {

                $vipcode = getMaxVipCode();

                $data = [
                    "custconemail" => $email,
                    "fullname" => $name,
                    "usertype" => "google",
                    "openid" => $id,
                    "vipcode" => $vipcode,
                    "state" => 1,
                    "create_at" => time()
                ];
                $customerid = Db::name(Customer::$tablename)->insertGetId($data);
                if(!$customerid) {
                    throw new Exception("GOOGLE授權登入失敗");
                }

                $autoCouponRule = Db::name(CouponAutoRule::$tablename)->where("auto_type", "register")->where("state", 1)->find();
                if(!empty($autoCouponRule)) {
                    $coupon = Db::name(Coupon::$tablename)->where("customerid", $customerid)->where("coupon_type", "reg")->count();
                    if(empty($coupon)) {
                        $code_dao = new CouponCode();
                        $data = [
                            "title" => $autoCouponRule["title"],
                            "customerid" => $customerid,
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

                Session::set("customerId", $customerid);
            }

            return json([
                "code" => 0,
                "url" => front_link("Cart/index"),
                "msg" => "登入成功"
            ]);
        } catch (Exception $ex) {
            return json([
                "code" => 1,
                "msg" => $ex->getMessage()
            ]);
        }
    }

    /**
     * forgot passwd
     */
    public function forgot_pass()
    {
        if(request()->isPost()) {
            try {
                $email = input("email");
                if(empty($email)) {
                    throw new Exception("請輸入註冊的郵箱");
                }

                $customer = Db::name(Customer::$tablename)->where("custconemail", $email)->find();
                if(empty($customer)) {
                    throw new Exception($email."未註冊");
                }

                $code = md5(uniqid());
                if(false === Db::name(Customer::$tablename)->where("custconemail", $email)->update(["active_code" => $code])) {
                    throw new Exception("重置密碼送出失敗");
                }

                send_passwd_comfirm($customer["fullname"], $customer["custconemail"], $code);
                toJSON([
                    "code" => 0,
                    "msg" => "重置密碼郵件送出成功"
                ]);
            } catch (Exception $ex) {
                toJSON([
                    "code" => 1,
                    "msg" => $ex->getMessage()
                ]);
            }
        }
        return View::fetch();
    }

    public function resetpasswd()
    {
        try {
            $code = input("code");
            if(empty($code)) {
                throw new Exception("密碼重置失敗");
            }

            $customer = Db::name(Customer::$tablename)->where("active_code", $code)->find();
            if(empty($customer)) {
                throw new Exception("無效帳號");
            }

            $passwd = rand(100000, 999999);
            if(false === Db::name(Customer::$tablename)->where("active_code", $code)->update(["custpassword" => md5($passwd), "active_code" => ''])) {
                throw new Exception("密碼重置失敗");
            }

            send_pass_email($customer["fullname"], $customer["custconemail"], $passwd);

            $data["msg"] = "密碼重置成功, 請查收郵件";
        } catch (Exception $ex) {
            $data["msg"] = $ex->getMessage();
        }

        View::assign($data);
        return View::fetch();
    }

}
