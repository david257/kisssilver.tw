<?php
namespace app\controller;

use app\BaseController;
use app\extend\CouponCode;
use app\models\Coupon;
use app\models\CouponAutoRule;
use app\models\Customer;
use think\Exception;
use think\facade\Db;
use think\facade\Log;
use think\facade\Session;
use think\response\Redirect;

class Oauth extends BaseController
{
    //登錄地址
    public function login($type = null)
    {
        try {
            if ($type == null) {
                throw new Exception('連接登錄類型未知');
            }
            // 獲取對象實例
            $sns = \liliuwei\social\Oauth::getInstance($type);
            Log::write($sns->getRequestCodeURL());
            //跳轉到授權頁面
            return redirect($sns->getRequestCodeURL());
        } catch(Exception $ex) {
            echo $ex->getMessage();
        }
    }

    //授權回調地址
    public function callback($type = null, $code = null)
    {
        try {
            if ($type == null || $code == null) {
                throw new Exception('參數錯誤');
            }
            $sns = \liliuwei\social\Oauth::getInstance($type);
            // 獲取TOKEN
            $token = $sns->getAccessToken($code);
            //獲取當前第三方登錄用戶信息
            if (is_array($token)) {
                $user_info = \liliuwei\social\GetInfo::getInstance($type, $token);
                Log::write(json_encode($user_info, JSON_UNESCAPED_UNICODE));
                if(!isset($user_info["openid"]) || empty($user_info["openid"])) {
                    throw new Exception("授權登入失敗");
                }

                switch ($type) {
                    case 'facebook':
                    {
                        return $this->fb_login($user_info);
                        break;
                    }
                    case 'google':
                    {
                        return $this->gg_login($user_info);
                        break;
                    }
                    default:
                    {
                        throw new Exception("未知授權");
                    }
                }
            } else {
                throw new Exception("獲取第三方用戶的基本信息失敗");
            }
        } catch(Exception $ex) {
            echo $ex->getMessage().$ex->getLine();
        }
    }

    /**
     * facebook login
     */
    public function fb_login($loginInfo=[])
    {
        try {
            if(empty($loginInfo) || !isset($loginInfo["openid"]) || !is_numeric($loginInfo["openid"])) {
                throw new Exception("授權登入失敗");
            }

            $customer = Db::name(Customer::$tablename)->where("usertype", "facebook")->where("openid", $loginInfo["openid"])->find();
            if(!empty($customer)) {
                if(isset($loginInfo["email"]) && empty($customer["custconemail"])) {
                    Db::name(Customer::$tablename)->where("customerid", $customer["customerid"])->update(["custconemail" => $loginInfo["email"]]);
                }
                Session::set("customerId", $customer["customerid"]);
            } else {

                $vipcode = getMaxVipCode();
                //$vipcode = "VIP".str_pad($totals+1, 8, 0, STR_PAD_LEFT);

                $data = [
                    "custconemail" => isset($loginInfo["email"])?$loginInfo["email"]:'',
                    "fullname" => $loginInfo["name"],
                    "usertype" => "facebook",
                    "openid" => $loginInfo["openid"],
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

            return redirect(front_link("Customer/profile"));
        } catch (Exception $ex) {
            //echo $ex->getMessage().$ex->getLine();
            return redirect(front_link("Login/index"));
        }

    }

    public function gg_login($user_info=[])
    {
        try {
            if(empty($user_info)) {
                throw new Exception("授權失敗");
            }

            $id = $user_info["openid"];
            $email = isset($user_info["email"])?$user_info["email"]:"";
            $name = $user_info["name"];

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
                //$vipcode = "VIP".str_pad($totals+1, 8, 0, STR_PAD_LEFT);

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

            return redirect(front_link("Customer/profile"));
        } catch (Exception $ex) {
            //echo $ex->getMessage().$ex->getLine();
            Log::write($ex->getMessage().$ex->getLine());
            return redirect(front_link("Login/index"));
        }
    }
}
