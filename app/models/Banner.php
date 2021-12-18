<?php
namespace app\models;

use think\facade\Db;

class Banner
{
    static $tablename = "banners";
    static $pages = [
        "home" => "首頁"
    ];
    static $locations = [
        "header" => "頭部輪播廣告",
        "header_fou" => "四列區",
        "header_thr" => "三列區",
        "header_two" => "二列區",
        "header_one" => "一列區",
        "middle_one" => "中間一",
        "middle_two" => "中間二"
    ];
}