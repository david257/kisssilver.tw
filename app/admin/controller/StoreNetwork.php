<?php
namespace app\admin\controller;

use think\Exception;
use think\facade\Db;
use think\facade\View;
use app\models\StoreNetwork as SWModel;

class StoreNetwork extends Base
{
    public function index()
    {
        $query = Db::name(SWModel::$tablename)->order("sortorder ASC")->paginate(20, false);
        $data["list"] = $query->all();
        $data["pages"] = $query->render();
        return View::fetch('', $data);
    }

    public function add()
    {
        return View::fetch("form");
    }

    public function edit()
    {
        $id = input("id");
        $store = Db::name(SWModel::$tablename)->where("id", $id)->find();
        $data["store"] = $store;
        return View::fetch("form", $data);
    }

    public function save()
    {
        try {

            $id = (int) input("id", 0);
            $title = input("title", '');
            $bcode = input("bcode", '');
            $tel = input("tel", "");
            $address = input("address", "");
            $map_code = input("map_code", '');
            $content = input("content", '');
            $sortorder = (int) input("sortorder", 0);

            if(empty($title)) {
                throw new Exception("據點名稱不能為空");
            }

            if(mb_strlen($title)>255) {
                throw new Exception("據點不能超過255字");
            }

            if(mb_strlen($tel)>20) {
                throw new Exception("聯絡電話不能超過20字");
            }

            if($sortorder<0) {
                throw new Exception("排序數字無效,請填寫整數");
            }

            if(mb_strlen($address)>255) {
                throw new Exception("SEO標題不能超過255字元");
            }

            if(mb_strlen($tel)>25) {
                throw new Exception("SEO關鍵字不能超過255字元");
            }

            $data = [
                "title" => $title,
                "bcode" => $bcode,
                "tel" => $tel,
                "address" => $address,
                "map_code" => $map_code,
                "content" => $content,
                "sortorder" => $sortorder,
            ];

            if($id) {
                if(false === Db::name(SWModel::$tablename)->where("id", $id)->update($data)) {
                    throw new Exception("儲存失敗");
                }
            } else {
                if(false === Db::name(SWModel::$tablename)->insert($data)) {
                    throw new Exception("儲存失敗");
                }
            }

            toJSON([
                "code" => 0,
                "msg" => "儲存成功",
                "url" => admin_link("StoreNetwork/index")
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
            $id = (int) input("id", 0);
            if(empty($id)) {
                throw new Exception("記錄無效");
            }

            if(false === Db::name(SWModel::$tablename)->where("id", $id)->delete()) {
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
