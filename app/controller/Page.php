<?php
namespace app\controller;

use app\BaseController;
use app\models\Page as pageModel;
use think\Exception;
use think\facade\Db;
use think\facade\View;

class Page extends BaseController
{

    public function detail()
    {
        try {
            $pageid = input("pageid", 0);
            if(empty($pageid)) {
                throw new Exception("記錄不存在");
            }

            $page = Db::name(pageModel::$tablename)->where("pageid", $pageid)->where("state", 1)->find();

            if(empty($page)) {
                throw new Exception("記錄不存在");
            }
            $data["PageMenu"] = self::GetParentPageTitle($pageid);
            $data["page_detail"] = $page;

            $data["MetaTitle"] = !empty($page["seo_title"])?$page["seo_title"]:$page["title"];
            $data["MetaKeywords"] = !empty($page["seo_keywords"])?$page["seo_keywords"]:$page["title"];
            $data["MetaDesc"] = !empty($page["seo_desc"])?$page["seo_desc"]:$page["title"];
            View::assign($data);
            return View::fetch("detail");
        } catch (Exception$ex) {
            abort(404);
        }


    }

    public function GetParentPageTitle($pageid=0)
    {
        $row = Db::name(pageModel::$tablename)->where("pageid", $pageid)->find();
        $list = [];
        if($row["parentid"]>0) {
            $nowRow = Db::name(pageModel::$tablename)->where("pageid", $row["parentid"])->find();
            $list = Db::name(pageModel::$tablename)->where("parentid", $row["parentid"])->where("state", 1)->order("sortorder ASC, create_at DESC")->select();
        }
        return [
            "title" => $nowRow["title"],
            "list" => $list
        ];
    }

}
