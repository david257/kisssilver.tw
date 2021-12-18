<?php

namespace app\admin\controller;

use app\models\Products;
use think\facade\Db;
use think\facade\View;

class Setting extends Base {

    public function _initialize() {
        parent::_initialize();
    }

    public function index()
    {
        $setting = Db::name("settings")->where("sid", 1)->find();
        $data = [];
        if(!empty($setting) && !empty($setting["content"])) {
            $data = json_decode($setting["content"], true);
        }
        View::assign($data);
        return View::fetch();
    }
    
    public function save()
    {
        
        $data["content"] = toJSON($_POST, true);
        $data["update_time"] = time();
        if(Db::name("settings")->where("sid", 1)->update($data)) {
            self::updateAllFee(); //更新運費
            toJSON(["code" => 0, "msg" => "設置成功"]);
        } else {
            toJSON(["code" => 1, "msg" => "設置失敗"]);
        }
    }

    private function updateAllFee()
    {
        $config = get_setting();
        $setting = $config["setting"];
        $fee = isset($setting["shipping"]["fee"])?$setting["shipping"]["fee"]:0;
        $fee = (int) trim($fee);
        Db::name(Products::$tablename)->where("shipping_fee_type", "fixed")->update(["fixed_shipping_fee" => $fee]);
    }

}

?>
