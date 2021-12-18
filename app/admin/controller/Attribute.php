<?php
namespace app\admin\controller;

use app\models\Attris;
use app\models\AttrValue;
use app\models\SearchAttries;
use think\Exception;
use think\facade\Db;
use think\facade\View;

class Attribute extends Base
{
    public function index()
    {
        $void = (int) input("void", 0);
        $data["list"] = $this->getAttrList($void);
        $data["attrvalues"] = $this->getAttrValues($void);

        if(!empty($void)) {
            $tpl = "vo_index";
        } else {
            $tpl = "index";
        }
        $data["void"] = $void;
        return View::fetch($tpl, $data);
    }

    public function add()
    {
        $void = (int) input("void",0);
        $attrtypes = Attris::$attrTypes;
        if(!$void) {
            unset($attrtypes["image"]);
        }
        $data["attrtypes"] = $attrtypes;
        $data["void"] = $void;
        return View::fetch("form", $data);
    }

    public function edit()
    {
        $attrid = input("attrid");
        $attr = Db::name(Attris::$tablename)->where("attrid", $attrid)->find();
        $data["attr"] = $attr;
        $attrtypes = Attris::$attrTypes;
        if(!$attr["void"]) {
            unset($attrtypes["image"]);
        }
        $data["attrtypes"] = $attrtypes;
        $data["void"] = (int) $attr["void"];
        return View::fetch("form", $data);
    }

    public function save()
    {
        try {

            $attrid = (int) input("attrid", 0);
            $void = (int) input("void", 0);
            $name = input("name", '');
            $sortorder = (int) input("sortorder", 0);
            $attrtype = input("attrtype", "text");
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


            $data = [
                "name" => $name,
                "void" => $void,
                "sortorder" => $sortorder,
                "attrtype" => $attrtype,
                "state" => $state,
            ];

            $exist = Db::name(Attris::$tablename)->where("name", $name)->where("void")->find();

            if($attrid) {
                if(!empty($exist) && $exist["attrid"] != $attrid) {
                    throw new Exception("規格名稱重複");
                }
                if(false === Db::name(Attris::$tablename)->where("attrid", $attrid)->update($data)) {
                    throw new Exception("儲存失敗");
                }
            } else {
                if(!empty($exist)) {
                    throw new Exception("規格名稱重複");
                }
                if(false === Db::name(Attris::$tablename)->insert($data)) {
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
            $attrid = (int) input("attrid", 0);
            if(empty($attrid)) {
                throw new Exception("記錄無效");
            }

			Db::startTrans();

			$attr = Db::name(Attris::$tablename)->where("attrid", $attrid)->find();
			if(empty($attr)) {
				throw new Exception("屬性/規格無效");
			}


			if($attr['void']>0) {
				if(Db::name(AttrValue::$tablename)->where("attrid", $attrid)->count()) {
					throw new Exception("商品規格已關聯選項,無法直接刪除");
				}
			} else {
				//刪除選項
				if(false === Db::name(AttrValue::$tablename)->where("attrid", $attrid)->delete()) {
					throw new Exception("刪除屬性選項失敗");
				}

				//刪除已關聯的產品記錄
				if(false === Db::name(SearchAttries::$tablename)->where("attrid", $attrid)->delete()) {
					throw new Exception("刪除產品關聯數據失敗");
				}
			}

            if(false === Db::name(Attris::$tablename)->where("attrid", $attrid)->delete()) {
                throw new Exception("刪除失敗");
            }

			Db::commit();
            toJSON([
                "code" => 0,
                "msg" => "刪除成功"
            ]);
        } catch (Exception $ex) {
			Db::rollback();
            toJSON([
                "code" => 1,
                "msg" => $ex->getMessage()
            ]);
        }
    }


    /*
     * get list
     */
    private function getAttrList($void=0)
    {
        $where = [
            "void" => $void
        ];

        return Db::name(Attris::$tablename)->where($where)->order("state DESC, sortorder ASC")->select();
    }

    private function getAttrValues($void=0)
    {
        $attrlist = $this->getAttrList($void);
        $attrIds = [];
        if(!empty($attrlist)) {
            foreach($attrlist as $v) {
                $attrIds[] = $v["attrid"];
            }
        }

        $list = Db::name(AttrValue::$tablename)->where("attrid", "IN", $attrIds)->order("state DESC, sortorder ASC")->select();
        $attrvalues = [];
        if(!empty($list)) {
            foreach($list as $v) {
                $attrvalues[$v["attrid"]][] = $v;
            }
        }
        return $attrvalues;
    }

}
