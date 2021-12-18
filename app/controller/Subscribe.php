<?php
namespace app\controller;

use app\BaseController;
use think\facade\Db;
use think\Exception;
use app\models\Subscribe as SModel;

class Subscribe extends BaseController
{
    public function send()
    {
        try {
            
            $email = input("email");
            
            if(mb_strlen($email)>255) {
                throw new Exception("Email長度最多255字元");
            }
            
            if(empty($email)) {
                throw new Exception("請輸入Email");
            }
            
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Email地址無效");
            }
            
            if(Db::name(SModel::$tablename)->where("email", $email)->count()) {
                throw new Exception("您已經訂閱過了");
            }
            
            if(false === Db::name(SModel::$tablename)->insert(["email" => $email, "create_at" => time()])) {
                throw new Exception("訂閱失敗,請重試");
            }
            
            toJSON([
                "code" => 0,
                "msg" => "訂閱成功"
            ]);
        } catch (Exception $ex) {
            toJSON([
                "code" => 1,
                "msg" => $ex->getMessage()
            ]);
        }
    }

}
