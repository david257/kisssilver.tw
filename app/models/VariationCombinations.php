<?php
namespace app\models;

use think\facade\Db;

class VariationCombinations
{
    static $tablename = "product_variation_combinations";

    static function getAttris($prodid)
    {
        $list = Db::name(static::$tablename)->where("vcproductid", $prodid)->select();
        $combins = [];
        if(!empty($list)) {
            foreach($list as $v) {
                $combins[] = [
                    "combinationid" => $v["combinationid"],
                    "attrs" => str_replace(",", "|", $v["vcoptionids"]),
                    "stock" => $v["vcstock"],
                    "vcsku" => $v["vcsku"],
                    "vcprice" => $v["vcprice"],
                    "vcenabled" => $v["vcenabled"]
                ];
            }
        }
        return $combins;
    }
}