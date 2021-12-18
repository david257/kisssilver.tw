<?php
namespace app\admin\controller;

use app\models\Attris;
use think\Exception;
use think\facade\Db;
use think\facade\View;
use app\models\VariationOptions as VO;

class VariationOptions extends Base
{
    public function index()
    {
        $where = [];
        $keywords = input("keywords",'');
        $params["list_rows"] = 20;
        if(!empty($keywords)) {
            $where[] = ["vname", "LIKE", "%".$keywords."%"];
            $params["query"] = [
                "keywords" => $keywords
            ];
        }
        $query = Db::name(VO::$tablename)->where($where)->paginate($params);
        $data["list"] = $query->all();
        $data["pages"] = $query->render();
        $data["keywords"] = $keywords;
        return View::fetch('', $data);
    }

    public function add()
    {
        return View::fetch("form");
    }

    public function edit()
    {
        $void = input("void");
        $vo = Db::name(VO::$tablename)->where("void", $void)->find();
        $data["vo"] = $vo;
        return View::fetch("form", $data);
    }

    public function save()
    {
        try {

            $void = (int) input("void", 0);
            $vname = input("vname", '');

            if(empty($vname)) {
                throw new Exception("商品編號不能為空");
            }

            if(mb_strlen($vname)>255) {
                throw new Exception("商品編號不能超過255字元");
            }

            $data = [
                "vname" => $vname,
            ];

            if($void) {
                if(false === Db::name(VO::$tablename)->where("void", $void)->update($data)) {
                    throw new Exception("儲存失敗");
                }
            } else {

                if(Db::name(VO::$tablename)->where("vname", $vname)->count()) {
                    throw new Exception("商品編號不能重複");
                }

                if(false === Db::name(VO::$tablename)->insert($data)) {
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
            $void = (int) input("void", 0);
            if(empty($void)) {
                throw new Exception("記錄無效");
            }

            if(Db::name(Attris::$tablename)->where("void", $void)->count()) {
                throw new Exception("商品規格已關聯選項,無法直接刪除");
            }

            if(false === Db::name(VO::$tablename)->where("void", $void)->delete()) {
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

}
