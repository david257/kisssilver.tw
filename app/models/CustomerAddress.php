<?php
namespace app\models;

use think\facade\Db;

class CustomerAddress
{
    static $tablename = "customer_address";

    static function getall($customerId=0)
    {
        $list = Db::name(static::$tablename)->where("customerid", $customerId)->select();
        $country_ids = [];
        $countryName = [];
        if(!empty($list)) {
            foreach($list as $v) {
                $country_ids[] = $v["provid"];
                $country_ids[] = $v["cityid"];
                $country_ids[] = $v["areaid"];
            }
            $country_ids = array_unique($country_ids);
            $countrylist = Db::name(Countries::$tablename)->where("id", "IN", $country_ids)->select();
            if(!empty($countrylist)) {
                foreach($countrylist as $v) {
                    $countryName[$v["id"]] = $v["name"];
                }
            }
        }

        return [
            "list" => $list,
            "countryName" => $countryName
        ];
    }
}