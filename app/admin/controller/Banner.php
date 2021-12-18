<?php
namespace app\admin\controller;

use think\Exception;
use think\facade\Db;
use think\facade\View;
use app\models\Banner as BannerModel;

class Banner extends Base
{
    public function index()
    {
        $data["list"] = Db::name(BannerModel::$tablename)->order("page ASC, location ASC, sortorder ASC")->select();
        $data["locations"] = BannerModel::$locations;
        return View::fetch('', $data);
    }

    public function add()
    {
        $data["pages"] = BannerModel::$pages;
        $data["locations"] = BannerModel::$locations;
        return View::fetch("form", $data);
    }

    public function edit()
    {
        $bannerid = input("bannerid");
        $banner = Db::name(BannerModel::$tablename)->where("bannerid", $bannerid)->find();
        $data["banner"] = $banner;
        $data["pages"] = BannerModel::$pages;
        $data["locations"] = BannerModel::$locations;
        return View::fetch("form", $data);
    }

    public function save()
    {
        try {

            $bannerid = (int) input("bannerid", 0);
            $title = input("title", '');
            $content = input('content', '');
            $imagefile = input('imagefile', '');
            $min_imagefile = input("min_imagefile", "");
            $sortorder = (int) input("sortorder", 0);
            $page = input("page");
            $location = input("location");
            $url = input("url");
            $state = (int) input("state", 0);

            if(empty($title)) {
                throw new Exception("廣告圖標題不能為空");
            }

            if(mb_strlen($title)>255) {
                throw new Exception("廣告圖標題不能超過255字元");
            }

            if(mb_strlen($content)>1000) {
                throw new Exception("廣告圖內容不能超過1000字元");
            }

            $pages = BannerModel::$pages;
            $locations = BannerModel::$locations;

            if(empty($page) || !isset($pages[$page])) {
                throw new Exception("顯示頁面無效");
            }

            if(empty($location) || !isset($locations[$location])) {
                throw new Exception("頁面位置無效");
            }

            if(empty($imagefile)) {
                throw new Exception("請上傳廣告圖大圖/影片");
            }

            if(empty($min_imagefile)) {
                throw new Exception("請上傳廣告圖小圖/影片");
            }

            if(mb_strlen($url)>500) {
                throw new Exception("連結地址不能超過500字元");
            }

            if($sortorder<0) {
                throw new Exception("排序數字無效,請填寫整數");
            }

            if(!in_array($state, [0, 1])) {
                throw new Exception("啟用狀態無效");
            }

            $data = [
                "title" => $title,
                "sortorder" => $sortorder,
                "state" => $state,
                "content" => $content,
                "page" => $page,
                "location" => $location,
                "url" => $url,
                "min_imagefile" => $min_imagefile,
                "imagefile" => $imagefile,
                "update_at" => time(),
            ];

            if($bannerid) {
                if(false === Db::name(BannerModel::$tablename)->where("bannerid", $bannerid)->update($data)) {
                    throw new Exception("儲存失敗");
                }
            } else {
                $data["create_at"] = time();
                if(false === Db::name(BannerModel::$tablename)->insert($data)) {
                    throw new Exception("儲存失敗");
                }
            }

            toJSON([
                "code" => 0,
                "msg" => "儲存成功",
                "url" => admin_link("Banner/index")
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
            $bannerid = (int) input("bannerid", 0);
            if(empty($bannerid)) {
                throw new Exception("記錄無效");
            }

            if(false === Db::name(BannerModel::$tablename)->where("bannerid", $bannerid)->delete()) {
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
