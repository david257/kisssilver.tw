<?php
namespace app\admin\controller;

use app\models\Attris;
use app\models\AttrValue;
use app\models\Products;
use app\models\SearchAttries;
use app\models\VariationOptions;
use think\Exception;
use think\facade\Db;
use think\facade\View;

class AttributeValue extends Base
{
    public function index()
    {
        $data["list"] = $this->getAttrValueList();
        return View::fetch('', $data);
    }

    public function add()
    {
        $data["attrid"] = input("attrid", 0);
        $data["attrtype"] = Attris::getAttrTypeById($data["attrid"]);
        return View::fetch("form", $data);
    }

    public function edit()
    {
        $valueid = input("valueid");
        $value = Db::name(AttrValue::$tablename)->where("valueid", $valueid)->find();
        $data["attrid"] = $value["attrid"];
        $data["value"] = $value;
        $data["attrtype"] = Attris::getAttrTypeById($data["attrid"]);
        return View::fetch("form", $data);
    }



    public function save()
    {
        try {

            $valueid = (int) input("valueid", 0);
            $attrid = (int) input("attrid", 0);
            $name = input("name", '');
            $sortorder = (int) input("sortorder", 0);
            $bgcolor = input("bgcolor",'');
            $imagefile = input("imagefile",'');
            $state = (int) input("state", 0);

            if(empty($name)) {
                throw new Exception("商品規格名稱不能為空");
            }

            if(mb_strlen($name)>255) {
                throw new Exception("商品規格名稱不能超過255字元");
            }

            if($sortorder<0) {
                throw new Exception("排序數字無效,請填寫整數");
            }

            if(empty($attrid) || !is_numeric($attrid)) {
                throw new Exception("所屬規格無效");
            }

            if(!empty($bgcolor) && mb_strlen($bgcolor) != 7) {
                throw new Exception("顏色代碼錯誤");
            }

            $data = [
                "attrid" => $attrid,
                "name" => $name,
                "sortorder" => $sortorder,
                "bgcolor" => $bgcolor,
                "imagefile" => $imagefile,
                "state" => $state,
            ];

            if($valueid) {
                if(false === Db::name(AttrValue::$tablename)->where("valueid", $valueid)->update($data)) {
                    throw new Exception("儲存失敗");
                }
            } else {
                if(false === Db::name(AttrValue::$tablename)->insert($data)) {
                    throw new Exception("儲存失敗");
                }
            }

            toJSON([
                "code" => 0,
                "msg" => "儲存成功",
            ]);
        } catch (Exception $ex) {
            toJSON([
                "code" => 1,
                "msg" => $ex->getMessage()
            ]);
        }
    }

    public function delete()
    {
        try {
            $valueid = (int) input("valueid", 0);
            if(empty($valueid)) {
                throw new Exception("記錄無效");
            }

            $value = Db::name(AttrValue::$tablename)->where("valueid", $valueid)->find();
            if(empty($value)) {
                throw new Exception("規格選項不存在");
            }

            $attrid = $value["attrid"];
            $attr = Db::name(Attris::$tablename)->where("attrid", $attrid)->find();
            if($attr["void"]>0) {
                if(Db::name(Products::$tablename)->where("void", $attr["void"])->count()) {
                    throw new Exception("已關聯商品,不能刪除");
                }
            } else {
                if(Db::name(SearchAttries::$tablename)->where("valueid", $valueid)->count()) {
                    throw new Exception("已關聯商品,不能刪除");
                }
            }

            if(false === Db::name(AttrValue::$tablename)->where("valueid", $valueid)->delete()) {
                throw new Exception("刪除失敗");
            }
            toJSON([
                "code" => 0,
                "msg" => "刪除成功"
            ]);
        } catch (Exception $ex) {
            toJSON([
                "code" => 1,
                "msg" => $ex->getMessage()
            ]);
        }
    }


    /*
     * get list
     */
    private function getAttrValueList()
    {
        return Db::name(AttrValue::$tablename)->order("state DESC, sortorder ASC")->select();
    }

}
