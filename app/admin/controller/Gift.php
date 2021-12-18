<?php
namespace app\admin\controller;

use think\Exception;
use think\facade\Db;
use think\facade\View;
use app\models\Gift as GiftModel;

class Gift extends Base
{
    public function index()
    {
        $where = [];
        $query = Db::name(GiftModel::$tablename)->where($where)->order("create_at DESC, stock DESC")->paginate(20, false);
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
        $giftid = input("giftid");
        $gift = Db::name(GiftModel::$tablename)->where("giftid", $giftid)->find();
        $data["gift"] = $gift;
        return View::fetch("form", $data);
    }

    public function save()
    {
        try {

            $giftid = (int) input("giftid", 0);
            $prodname = input("prodname", '');
            $stock = (int) input("stock", 0);
            $thumb_image = input("thumb_image", "");
            $state = (int) input("state", 0);

            if(empty($prodname)) {
                throw new Exception("贈品名稱不能為空");
            }

            if(mb_strlen($prodname)>255) {
                throw new Exception("贈品名稱不能超過255字");
            }

            if(!in_array($state, [0, 1])) {
                throw new Exception("啟用狀態無效");
            }


            $data = [
                "prodname" => $prodname,
                "stock" => $stock,
                "state" => $state,
            ];

            if(!empty($thumb_image)) {
                $data["thumb_image"] = $thumb_image;
            }

            if($giftid) {
                if(false === Db::name(GiftModel::$tablename)->where("giftid", $giftid)->update($data)) {
                    throw new Exception("儲存失敗");
                }
            } else {
                $data["create_at"] = time();
                if(false === Db::name(GiftModel::$tablename)->insert($data)) {
                    throw new Exception("儲存失敗");
                }
            }

            toJSON([
                "code" => 0,
                "msg" => "儲存成功",
                "url" => admin_link("Gift/index")
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
            $giftid = (int) input("giftid", 0);
            if(empty($giftid)) {
                throw new Exception("記錄無效");
            }

            if(false === Db::name(GiftModel::$tablename)->where("giftid", $giftid)->delete()) {
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
