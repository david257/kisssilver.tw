<?php
namespace app\admin\controller;

use app\BaseController;
use app\models\User;
use think\facade\Db;
use think\Exception;
use think\facade\Session;
use think\facade\View;

class Login extends BaseController
{
    public function index()
    {
        if($this->request->isAjax()) {
            try {
                $username = input("username");
                $passwd = input("passwd");
                if(empty($username)) {
                    throw new Exception("用戶名為空");
                }

                if(empty($passwd)) {
                    throw new Exception("密碼為空");
                }

                $user = Db::name(User::$tablename)->where("username", $username)->find();
                if(empty($user)) {
                    throw new Exception("帳號無效");
                }

                if(!$user["state"]) {
                    throw new Exception("帳號已被禁用");
                }

                if($user["username"] != "admin" && !$user["roleid"]) {
                    throw new Exception("帳號未授權");
                }

                if(md5($passwd) != $user["passwd"]) {
                    throw new Exception("密碼錯誤");
                }

                unset($user["passwd"]);
                session("user", $user);
                return toJSON([
                    "code" => 0,
                    "url" => admin_link("Index/index"),
                    "msg" => "登入成功"
                ], true);
            } catch (Exception $ex) {
                return toJSON([
                    "code" => 1,
                    "msg" => $ex->getMessage()
                ], true);
            }

        }
        return View::fetch();
    }

    public function logout()
    {
        Session::delete("user");
        return json([
            "code" => 0,
            "msg" => "登出成功",
            "url" => admin_link("Login/logout")
        ]);
    }

}
