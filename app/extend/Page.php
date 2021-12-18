<?php
namespace  app\extend;

use think\facade\View;

class Page
{

   static function make($nowPage=1, $totalPages=1, $params=[])
   {
       $data["nowPage"] = $nowPage;
       $data["totalPages"] = $totalPages;
       $data["params"] = $params;
       $data["url"] = request()->controller()."/".request()->action();
       View::assign($data);
       return View::fetch("public/pages");
   }
}
