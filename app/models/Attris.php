<?php
namespace app\models;

use think\facade\Db;

class Attris
{
    static $tablename = "product_attris";
    static $attrTypes = [
        "text" => "顯示文本",
        "image" => "顯示圖像",
        "color" => "顯示顏色"
    ];

    static function getAttrTypeById($attrid) {
        $attr = Db::name(static::$tablename)->where("attrid", $attrid)->find();
        if(!empty($attr)) {
            return $attr["attrtype"];
        }
        return;
    }
}
