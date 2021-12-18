<?php
namespace app\admin\controller;

use think\facade\Session;
use think\Exception;
use think\facade\Db;
use think\facade\View;
use app\extend\PHPTree;
use app\extend\Page;

class User extends Base
{
    public function index()
    {
       $keyword = input("keyword");
       $roleid = input("roleid");
       $map = 1;
       $map .= " AND username !='admin'";
       if(!empty($roleid)) {
           $map .= " AND roleid=".$roleid;
       }
       
       if(!empty($keyword)) {
           $map .= " AND (username='".$keyword."' OR fullname LIKE '".$keyword."%')";
       }

       $params["list_rows"] = 20;
       $params["query"] = [
           "keyword" => $keyword,
           "roleid" => $roleid
       ];
       $list = Db::name("users")->where($map)->paginate($params);
       $data["list"] = $list->all();
       $data["pages"] = $list->render();
       
       $data["roles"] = static::get_roles();
       $data["keyword"] = $keyword;
       $data["roleid"] = $roleid;
       $data["stores"] = static::getStores();
       View::assign($data);
       return View::fetch();
    }
    
    public function add()
    {
        $data["roles"] = static::get_enable_roles();
        $data["stores"] = static::getStores();
        View::assign($data);
        
        return View::fetch("form");
    }
    
    public function edit()
    {
        $data["roles"] = static::get_enable_roles();
       
        $userid = input("userid");
        $data["user"] = Db::name("users")->where("userid", $userid)->find();
        $data["stores"] = static::getStores();
        View::assign($data);
        return View::fetch("form");
    }
    
    public function save()
    {
        $userid = (int) input("userid");
        $roleid = (int) input("roleid");
        $snid = (int) input("snid");
        $username = input("username");
        $fullname = input("fullname");
        $userpass = input("userpass");
        $status = (int) input("status");
        
        $data = [
            "roleid" => $roleid,
            "snid" => $snid,
            "username" => $username,
            "fullname" => $fullname,
            "state" => $status,
            "update_at" => time(),
        ];

        try {
            if($userid) {
                if(!empty($userpass)) {
                    $data["passwd"] = md5($userpass);
                }
                if(false === Db::name("users")->where("userid", $userid)->update($data)) {
                    throw new Exception("更新失敗");
                }
            } else {
                $data["create_at"] = time();
                if(empty($userpass)) {
                    throw new Exception("請輸入密碼");
                }

                $data["passwd"] = md5($userpass);
                if(false === Db::name("users")->insert($data)) {
                    throw new Exception("儲存失敗");
                }
            }

            toJSON([
                "code" => 0,
                "msg" => "儲存成功",
                "url" => admin_link("User/index")
            ]);
        } catch (Exception $ex) {
            toJSON([
                "code" => 1,
                "msg" => $ex->getMessage()
            ]);
        }

    }
    
    public function delete()
    {
        try {
            $userid = (int)input("userid");
            $row = Db::name("users")->where("userid", $userid)->find();
            if ($row["username"] == "admin") {
                throw new Exception("管理員無法刪除");
            }
            if (false === Db::name("admin_users")->where("userid", $userid)->delete()) {
                throw new Exception("刪除失敗");
            }

            toJSON([
                "code" => 0,
                "msg" => "刪除成功"
            ]);
        } catch(Exception $ex) {
            toJSON([
                "code" => 1,
                "msg" => $ex->getMessage()
            ]);
        }
    }    
    
    //更改密碼
    public function change_pass()
    {
        return View::fetch();
    }
    
    public function do_change_pass()
    {
        try {
            $old_pass = input("old_pass");
            $new_pass = input("new_pass");
            $renew_pass = input("renew_pass");
            $user = Session::get("user");
            $user = Db::name("users")->where("userid", $user["userid"])->find();
            if ($user["passwd"] != md5($old_pass)) {
                throw new Exception("舊密碼錯誤");
            }

            if ($new_pass != $renew_pass) {
                throw new Exception("兩次密碼不一致");
            }

            $data = [
                "passwd" => md5($new_pass),
                "update_at" => time(),
            ];

            if (false === Db::name("users")->where("userid", $user["userid"])->update($data)) {
                throw new Exception("密碼更改失敗");
            }

            toJSON([
                "code" => 0,
                "msg" => "密碼更改成功"
            ]);
        } catch (Exception $ex) {
            toJSON([
                "code" => 1,
                "msg" => $ex->getMessage()
            ]);
        }
    }

    private static function get_enable_roles()
    {
        return Db::name("roles")->where("status", 1)->select(); 
    }
    
    private static function get_roles()
    {
        $list = Db::name("roles")->select(); 
        $roles = [];
        foreach($list as $v) {
            $roles[$v["roleid"]] = $v["title"];
        }
        return $roles;
    }

    private static function getStores()
    {
        $list = Db::name("store_network")->select();
        $stores = [];
        foreach($list as $v) {
            $stores[$v['id']] = $v['title'];
        }
        return $stores;
    }
    
}
