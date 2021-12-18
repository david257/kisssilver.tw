<?php
namespace app\admin\controller;
use app\extend\PHPTree;
use think\Exception;
use think\facade\Db;

use think\facade\Request;
use think\facade\View;

class Node extends Base
{

    public function index()
    {
       $list = Db::name("nodes")->order("sortorder ASC")->select(); 
       $menu_nodes = [];
       if(!empty($list)) {
            foreach($list as $v) {
                $menu_nodes[] = [
                    "id" => $v["nodeid"],
                    "parent_id" => $v["pid"],
                    "name" => $v["title"],
                    "url" => $v["url"],
                    "status" => $v["status"]
                ];
            }
       }

       if(!empty($menu_nodes)) {
           $menu_nodes = PHPTree::makeTreeForHtml($menu_nodes);
       }

       $data["nodes"] = $menu_nodes;
       
       View::assign($data);
       return View::fetch();
    }
    
    public function add()
    {
        $menu_nodes = static::get_nodes();
        $data["tree_nodes"] = [];
        if(!empty($menu_nodes)) {
            $data["tree_nodes"] = PHPTree::makeTreeForHtml($menu_nodes);
        }
        View::assign($data);
        return View::fetch("form");
    }
    
    public function edit()
    {
        $menu_nodes = static::get_nodes();
        $data["tree_nodes"] = [];
        if(!empty($menu_nodes)) {
            $data["tree_nodes"] = PHPTree::makeTreeForHtml($menu_nodes);
        }

        $nodeid = input("nodeid");
        $data["node"] = Db::name("nodes")->where("nodeid", $nodeid)->find();
        View::assign($data);
        return View::fetch("form");
    }
    
    public function save()
    {
        $nodeid = (int) input("nodeid");
        $pid = (int) input("pid");
        $title = input("title");
        $url = input("url");
        $sortorder = input("sortorder");
        $is_menu = (int) input("is_menu");
        $status = (int) input("status");


        
        $data = [
            "pid" => $pid,
            "title" => $title,
            "url" => $url,
            "is_menu" => $is_menu,
            "sortorder" => $sortorder,
            "status" => $status,
            "update_time" => time(),
        ];

        try {

            if(empty($title)) {
                throw new Exception("名稱不能為空");
            }

            if(mb_strlen($title)>30) {
                throw new Exception("名稱不能超過30字元");
            }

            if(empty($url)) {
                throw new Exception("URL不能為空");
            }

            if(mb_strlen($url)>100) {
                throw new Exception("URL最多100位");
            }

            if($nodeid) {
                if(false === Db::name("nodes")->where("nodeid", $nodeid)->update($data)) {
                    throw new Exception("更新失敗");
                }
            } else {
                $data["create_time"] = time();
                if(false === Db::name("nodes")->insert($data)) {
                    throw new Exception("儲存失敗");
                }
            }

            toJSON([
                "code" => 0,
                "msg" => "操作成功"
            ]);
        } catch(Exception $ex) {
            toJSON([
                "code" => 1,
                "msg" => $ex->getMessage()
            ]);
        }

    }
    
    public function delete()
    {
        try {
            $nodeid = (int) input("nodeid");
            if(Db::name("nodes")->where("pid", $nodeid)->count()) {
                throw new Exception("請先刪除子節點");
            }

            if(false === Db::name("nodes")->where("nodeid", $nodeid)->delete()) {
                throw new Exception("刪除失敗");
            }

            toJSON([
                "code" => 0,
                "msg" => "操作成功"
            ]);
        } catch (Exception $ex) {
            toJSON([
                "code" => 1,
                "msg" => $ex->getMessage()
            ]);
        }

    }
    
    //獲取節點
    private static function get_nodes()
    {
        $list = Db::name("nodes")->order("sortorder ASC")->select(); 
        $menu_nodes = [];
        if(!empty($list)) {
            foreach($list as $v) {
                $menu_nodes[] = [
                    "id" => $v["nodeid"],
                    "parent_id" => $v["pid"],
                    "name" => $v["title"],
                ];
            }
        }
        return $menu_nodes;
    }
}
