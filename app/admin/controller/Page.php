<?php
namespace app\admin\controller;

use think\Exception;
use think\facade\Db;
use think\facade\View;
use app\models\Page as PageModel;

class Page extends Base
{
    public function index()
    {
        $where = [];
        $keywords = input("keywords");
        $params = [];
        $params["list_rows"] = 500;
        if(!empty($keywords)) {
            $where["title"] = ["LIKE", "%".$keywords."%"];
            $params["query"] = [
                "keywords" => $keywords
            ];
        }
        $query = Db::name(PageModel::$tablename)->where($where)->paginate($params);
        $data["list"] = $query->all();
        $data["cates"] = self::getCates();
        $data["pages"] = $query->render();
        $data["keywords"] = $keywords;
        return View::fetch('', $data);
    }

    public function add()
    {
        $data["cates"] = self::getCates();
        return View::fetch("form", $data);
    }

    public function edit()
    {
        $pageid = input("pageid");
        $page = Db::name(PageModel::$tablename)->where("pageid", $pageid)->find();
        $data["page"] = $page;
        $data["cates"] = self::getCates();
        return View::fetch("form", $data);
    }

    public function save()
    {
        try {

            $pageid = (int) input("pageid", 0);
            $title = input("title", '');
            $parentid = (int) input("parentid", 0);
            $content = input("content", '');
            $pagetype = input("pagetype/a");
            $sortorder = (int) input("sortorder", 0);
            $state = (int) input("state", 0);
            $seo_title = input("seo_title", '');
            $seo_keywords = input("seo_keywords", '');
            $seo_desc = input("seo_desc", '');

            if(empty($title)) {
                throw new Exception("分類名稱不能為空");
            }

            if(mb_strlen($title)>255) {
                throw new Exception("標題不能超過255字元");
            }

            if($sortorder<0) {
                throw new Exception("排序數字無效,請填寫整數");
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
                "title" => $title,
                "parentid" => $parentid,
                "content" => $content,
                "pagetype" => !empty($pagetype)?implode(",", $pagetype):'',
                "sortorder" => $sortorder,
                "state" => $state,
                "seo_title" => $seo_title,
                "seo_keywords" => $seo_keywords,
                "seo_desc" => $seo_desc,
                "create_at" => time(),
            ];

            if($pageid) {
                if(false === Db::name(PageModel::$tablename)->where("pageid", $pageid)->update($data)) {
                    throw new Exception("儲存失敗");
                }
            } else {
                if(false === Db::name(PageModel::$tablename)->insert($data)) {
                    throw new Exception("儲存失敗");
                }
            }

            toJSON([
                "code" => 0,
                "msg" => "儲存成功",
                "url" => admin_link("Page/index")
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
            $pageid = (int) input("pageid", 0);
            if(empty($pageid)) {
                throw new Exception("記錄無效");
            }

            if(Db::name(PageModel::$tablename)->where("parentid", $pageid)->count()) {
                throw new Exception("存在子級，請先刪除子級");
            }

            if(false === Db::name(PageModel::$tablename)->where("pageid", $pageid)->delete()) {
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

    public function getCates()
    {
       $list = Db::name(PageModel::$tablename)->where("parentid", 0)->order("sortorder ASC")->select();
        $cates = [];
       if(!empty($list)) {
           foreach($list as $v) {
               $cates[$v['pageid']] = $v["title"];
           }
       }
       return $cates;
    }

}
