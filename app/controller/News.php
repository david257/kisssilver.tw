<?php
namespace app\controller;

use app\BaseController;
use app\extend\Page;
use app\models\News as NewsModel;
use think\Exception;
use think\facade\Db;
use think\facade\View;

class News extends BaseController
{
    private $pageSize = 20;
    public function index()
    {
        $create_at = input("date");
        $where = [];
        $where[] = ["state", "=", 1];
        if(!empty($create_at)) {
            $where[] = ["create_at", "BETWEEN", [strtotime($create_at), strtotime($create_at." 23:59:59")]];
        }
        $params = [
            "date" => $create_at
        ];
        $query = Db::name(NewsModel::$tablename)->where($where)->order("create_at DESC")->paginate($this->pageSize, false);
        $data["list"] = $query->all();
        $data["pages"] = Page::make($query->currentPage(), $query->lastPage(), $params);
        $data["date"] = $create_at;
        View::assign($data);
        return View::fetch("index");
    }

    public function detail()
    {
        try {
            $newsid = input("newsid", 0);
            if(empty($newsid)) {
                throw new Exception("記錄不存在");
            }

            $news = Db::name(NewsModel::$tablename)->where("newsid", $newsid)->where("state", 1)->find();
            if(empty($news)) {
                throw new Exception("記錄不存在");
            }

            $data["news_detail"] = $news;
            $data["prev_link"] = self::PrevNextNews($newsid);
            $data["next_link"] = self::PrevNextNews($newsid, "next");


            $data["MetaTitle"] = !empty($news["seo_title"])?$news["seo_title"]:$news["title"];
            $data["MetaKeywords"] = !empty($news["seo_keywords"])?$news["seo_keywords"]:$news["title"];
            $data["MetaDesc"] = !empty($news["seo_desc"])?$news["seo_desc"]:$news["title"];

            View::assign($data);
            return View::fetch("detail");
        } catch (Exception$ex) {
            abort(404);
        }


    }

    public function PrevNextNews($newsid=0, $pos="prev")
    {
        $where[] = ["state", "=", 1];
        if($pos=="prev") {
            $where[] = ["newsid", "<", $newsid];
        } else {
            $where[] = ["newsid", ">", $newsid];
        }

        $row = Db::name(NewsModel::$tablename)->where($where)->order("create_at DESC")->find();
        return $row;
    }

}
