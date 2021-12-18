<?php
namespace app\admin\controller;

use think\Exception;
use think\facade\Db;
use think\facade\View;
use app\models\News as NewsModel;

class News extends Base
{
    public function index()
    {
        $where = [];
        $keywords = input("keywords");
        $params = [];
        $params["list_rows"] = 20;
        if(!empty($keywords)) {
            $where["title"] = ["LIKE", "%".$keywords."%"];
            $params["query"] = [
                "keywords" => $keywords
            ];
        }
        $query = Db::name(NewsModel::$tablename)->where($where)->order("create_at DESC")->paginate($params);
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
        $newsid = input("newsid");
        $news = Db::name(NewsModel::$tablename)->where("newsid", $newsid)->find();
        $data["news"] = $news;
        return View::fetch("form", $data);
    }

    public function save()
    {
        try {

            $newsid = (int) input("newsid", 0);
            $title = input("title", '');
            $thumb_image = input("thumb_image", "");
			$url = input("url", '');
            $content = input("content", '');
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
				"url" => $url,
                "content" => $content,
                "sortorder" => $sortorder,
                "state" => $state,
                "seo_title" => $seo_title,
                "seo_keywords" => $seo_keywords,
                "seo_desc" => $seo_desc,
            ];

            if(!empty($thumb_image)) {
                $data["thumb_image"] = $thumb_image;
            }

            if($newsid) {
                if(false === Db::name(NewsModel::$tablename)->where("newsid", $newsid)->update($data)) {
                    throw new Exception("儲存失敗");
                }
            } else {
                $data["create_at"] = time();
                if(false === Db::name(NewsModel::$tablename)->insert($data)) {
                    throw new Exception("儲存失敗");
                }
            }

            toJSON([
                "code" => 0,
                "msg" => "儲存成功",
                "url" => admin_link("News/index")
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
            $newsid = (int) input("newsid", 0);
            if(empty($newsid)) {
                throw new Exception("記錄無效");
            }

            if(false === Db::name(NewsModel::$tablename)->where("newsid", $newsid)->delete()) {
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
