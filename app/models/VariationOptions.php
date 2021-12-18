<?php
namespace app\models;

use think\facade\Db;

class VariationOptions
{
    static $tablename = "product_variation_options";

    static function getAttris($void)
    {
        $list = Db::name(Attris::$tablename)->where("void", $void)->order("sortorder ASC")->select();
        $attris = [];
        $attrTypes = [];
        foreach($list as $v) {

            $vlist = Db::name(AttrValue::$tablename)->where("attrid", $v['attrid'])->order("sortorder ASC")->select();
            $attrTypes[$v['name']] = $v;
            if(!empty($vlist)) {
                foreach($vlist as $vv) {
                    $attris[$v['name']][] = [
                        $vv['valueid'] => [
                            "title" => $vv['name'],
                            "image" => !empty($vv['imagefile'])?showfile($vv['imagefile']):'',
                            "color" => empty($vv["bgcolor"])?'':$vv["bgcolor"]
                        ]
                    ];
                }
            }
        }

        return [
            "attrtypes" => $attrTypes,
            "attrvalues" => $attris
        ];
    }
}