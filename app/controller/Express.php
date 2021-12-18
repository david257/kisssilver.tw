<?php

namespace app\controller;

use app\BaseController;
use think\facade\View;

class Express extends BaseController {
    
    //請求電子地圖，取回超商店的ID
    public function map()
    {
        $LogisticsSubType = input("LogisticsSubType");
        (new \ecpay\ECPayExpress())->map($LogisticsSubType);
    }
    
    //門市選擇返回地址
    public function express_map()
    {
        $data = $_POST;
        View::assign($data);
        return View::fetch();
    }

}
