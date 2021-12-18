<?php
namespace app\admin\controller;

use app\models\CategoryProductAss;
use think\Exception;
use think\facade\Db;
use think\facade\View;
use app\models\Category as CateModel;

class Category extends Base
{
    public function index()
    {
        $list = CateModel::getList();
        $data["trlist"] = \PHPTree::makeTreeForHtml($list);
        return View::fetch('', $data);
    }

    public function add()
    {
        $data["selectOptions"] = CateModel::getSelectOptions(0);
        return View::fetch("form", $data);
    }

    public function edit()
    {
        $catid = input("id");
        $cate = Db::name(CateModel::$tablename)->where("catid", $catid)->find();
        $parentid = isset($cate["parentid"])?$cate["parentid"]:0;
        $data["selectOptions"] = CateModel::getSelectOptions($parentid);
        $data["cate"] = $cate;
        return View::fetch("form", $data);
    }

    public function save()
    {
        try {

            $catid = (int) input("catid", 0);
            $parentid = (int) input("parentid", 0);
            $catname = input("catname", '');
            $en_catname = input("en_catname", '');
            $cat_banner = input('cat_banner', '');
            $cat_banner_xs = input('cat_banner_xs', '');
            $icon = input("icon", "");
            $sortorder = (int) input("sortorder", 0);
            $state = (int) input("state", 0);
            $seo_title = input("seo_title", '');
            $seo_keywords = input("seo_keywords", '');
            $seo_desc = input("seo_desc", '');

            if(!is_int($parentid)) {
                throw new Exception("所屬分類無效");
            }

            if(empty($catname)) {
                throw new Exception("分類名稱不能為空");
            }

            if(mb_strlen($catname)>100) {
                throw new Exception("分類名稱不能超過100字元");
            }

            if(mb_strlen($en_catname)>100) {
                throw new Exception("英文分類名稱不能超過100字元");
            }

            if($sortorder<0) {
                throw new Exception("排序數位無效,請填寫整數");
            }

            if(!in_array($state, [0, 1])) {
                throw new Exception("啟用狀態無效");
            }

            if(mb_strlen($seo_title)>255) {
                throw new Exception("SEO標題不能超過255字元");
            }

            if(mb_strlen($seo_keywords)>255) {
                throw new Exception("SEO關鍵字不能超過255字元");
            }

            if(mb_strlen($seo_desc)>255) {
                throw new Exception("SEO描述不能超過255字元");
            }

            $data = [
                "parentid" => $parentid,
                "catname" => $catname,
                "en_catname" => $en_catname,
                "icon" => $icon,
                "sortorder" => $sortorder,
                "state" => $state,
                "seo_title" => $seo_title,
                "seo_keywords" => $seo_keywords,
                "seo_desc" => $seo_desc,
                "update_at" => time(),
            ];

            if(!empty($cat_banner)) {
                $data["cat_banner"] = $cat_banner;
            }

            if(!empty($cat_banner_xs)) {
                $data["cat_banner_xs"] = $cat_banner_xs;
            }

            if($catid) {
                if(false === Db::name(CateModel::$tablename)->where("catid", $catid)->update($data)) {
                    throw new Exception("儲存失敗");
                }
            } else {
                if(false === Db::name(CateModel::$tablename)->insert($data)) {
                    throw new Exception("儲存失敗");
                }
            }

            toJSON([
                "code" => 0,
                "msg" => "儲存成功",
                "url" => admin_link("Category/index")
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

            if(Db::name(CategoryProductAss::$tablename)->where("catid", $id)->count()) {
                throw new Exception("此分類已關聯產品記錄,無法直接刪除");
            }

            if(!Db::name(CateModel::$tablename)->where("catid", $id)->count()) {
                throw new Exception("記錄無效");
            }

            if(false === Db::name(CateModel::$tablename)->where("catid", $id)->delete()) {
                throw new Exception("刪除失敗");
            }

            toJSON([
                "code" => 0,
                "msg" => "分類刪除成功"
            ]);
        } catch (Exception $ex) {
            toJSON([
                "code" => 1,
                "msg" => $ex->getMessage()
            ]);
        }
    }

    public function import()
    {
        $files = file("g:/htdocs/kscate.txt");
        $categories = [];
        if(!empty($files)) {
            foreach($files as $k => $cate) {
                $cate = trim($cate);
                if(empty($cate)) continue;
                if(!empty($cate)) {
                    $cates = explode("/", $cate);

                    if(isset($cates[0]) && !isset($categories[$cates[0]])){
                        $categories[$cates[0]] = [];
                    }

                    if(isset($cates[1]) && !isset($categories[$cates[0]][$cates[1]])){
                        $categories[$cates[0]][$cates[1]] = [];
                    }

                    if(isset($cates[2]) && !isset($categories[$cates[0]][$cates[1]][$cates[2]])){
                        $categories[$cates[0]][$cates[1]][$cates[2]] = [];
                    }
                }
            }
        }

        print_r($categories);
    }

}
