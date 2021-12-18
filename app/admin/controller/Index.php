<?php
namespace app\admin\controller;

use think\facade\View;

class Index extends Base
{
    public function index()
    {
        return View::fetch();
    }

}
