<?php
namespace app\controller;

use app\BaseController;
use think\facade\Session;

class AuthBase extends BaseController
{
    private $customerId;
    public function initialize()
    {
        parent::initialize();
        $this->customerId = Session::get("customerId");
        if(empty($this->customerId)) {
            self::gotoLogin();
        }
    }

    public function gotoLogin()
    {
        if($this->request->isAjax()) {
            toJSON([
                "code" => 1,
                "msg" => "请登录会员",
                "url" => front_link("Login/index")
            ]);
        }
        header("location:".front_link("Login/index"));exit;
    }

    protected function getCustomerInfo()
    {
        return getLoginCustomer();
    }

    protected function getCustomerId()
    {
        return $this->customerId;
    }

}
