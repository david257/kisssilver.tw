<?php

namespace app\admin\controller;

use think\facade\Db;
use think\Exception;
use think\facade\View;

class Email extends Base {

    public function index() {
        return View::fetch();
    }

    public function test_ping() {
        try {
            $config = get_setting();
            $setting = $config["setting"];
            $mails = [];

            if(!isset($setting["smtp"]["username"]) || empty($setting["smtp"]["username"])) {
                throw new Exception("請先設置發信郵箱, 點擊儲存成功再點測試連通性");
            }

            $mails[] = $setting["smtp"]["username"];
            $ret = send_email("郵件伺服器連通性測試", "郵件伺服器連通性測試", "", $mails, "");
            if ($ret["err_code"]>0) {
                throw new Exception($ret["msg"]);
            }

            toJSON(["code" => 0, "msg" => "配置成功"]);
        } catch (Exception $e) {
            toJSON(["code" => 1, "msg" => $e->getMessage()]);
        }
    }


    public function edit() {
        $tpid = input("tpid");
        $email_temps = order_state_actions();
        $tp = $email_temps[$tpid];
        
        $data["title"] = $tp["title"];
        $filename = BASE_ROOT.$tp["email_html"];
        $data["content"] = file_get_contents($filename);
        $data["tpid"] = $tpid;
        View::assign($data);
        return View::fetch("form");
    }

    public function save() {
        try {
            $tpid = input("tpid");
            $subject = input("subject");
            $body = input("body");

            $data = [
                "sku" => $tpid,
                "subject" => $subject,
                "update_time" => time(),
            ];

            if(Db::name("email_templates")->where("sku", $tpid)->count()) {
                if (!Db::name("email_templates")->where("sku", $tpid)->update($data)) {
                    throw new Exception("更新失敗");
                }
            } else {
                if (!Db::name("email_templates")->insert($data)) {
                    throw new Exception("更新失敗");
                }
            }
            $email_temps = order_state_actions();
            $tp = $email_temps[$tpid];
            $filename = BASE_ROOT.$tp["email_html"];
            if(!file_put_contents($filename, $body)) {
                throw new Exception("更新失敗");
            }
            
            toJSON(["msg" => "更新成功", "code" => 0]);
        } catch(Exception $e) {
            toJSON(["msg" => $e->getMessage(), "code" => 1]);
        }
    }

}
