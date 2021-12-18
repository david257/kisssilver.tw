<?php
namespace app\models;

use think\facade\Db;

class ProductImages
{
    static $tablename = "product_images";

    static function getMainImagesByProdid($prodids=[])
    {
        $list = Db::name(static::$tablename)->where("productid", "IN", $prodids)->where("is_main", 1)->order("sortorder ASC")->select();
        $images = [];
        if(!empty($list)) {
            foreach ($list as $v) {
                $images[$v["productid"]] = $v;
            }
        }
        return $images;
    }
}