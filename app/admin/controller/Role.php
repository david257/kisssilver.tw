<?php
namespace app\admin\controller;
use app\models\Nodes;
use think\Exception;
use think\facade\Session;
use think\facade\Db;
use think\facade\View;
use app\extend\PHPTree;
use app\extend\Page;

class Role extends Base
{
    public function index()
    {
       $map = 1;
       $keyword = input("keyword");
       if(!empty($keyword)) {
           $map .= " AND title LIKE '".$keyword."%'";
       }
       $params["list_rows"] = 20;
       $params["query"] = ["keyword" => $keyword];
       $query = Db::name("roles")->where($map)->paginate($params);
       $data["list"] = $query->all();
       $data["pages"] = $query->render();
       $data["keyword"] = $keyword;
       View::assign($data);
       return View::fetch();
    }
    
    public function add()
    {
        $menu_nodes = static::get_nodes();
        $data["tree_nodes"] = $menu_nodes;
        $data["role"] = [];
        View::assign($data);
        return View::fetch("form");
    }
    
    public function edit()
    {

        $roleid = input("roleid");
        $role = Db::name("roles")->where("roleid", $roleid)->find();
        $limits = explode(",", $role["limits"]);
        $menu_nodes = static::get_nodes($limits);
        $data["tree_nodes"] = $menu_nodes;
        $data["role"] = $role;
        View::assign($data);
        return View::fetch("form");
    }
    
    public function save()
    {
        
        $roleid = (int) input("roleid");
        $title = input("title");
        $limits = trim(input("limits"),",");
        $status = (int) input("status");
        
        $data = [
            "roleid" => $roleid,
            "title" => $title,
            "limits" => $limits,
            "status" => $status,
            "update_time" => time(),
        ];

        if(empty($title)) {
            throw new Exception("角色名稱未填寫");
        }

        if(mb_strlen($title)>30) {
            throw new Exception("角色名稱最多30字元");
        }

        try {
            if($roleid) {
                if(false === Db::name("roles")->where("roleid", $roleid)->update($data)) {
                    throw new Exception("更新失敗");
                }
            } else {
                $data["create_time"] = time();
                if(false === Db::name("roles")->insert($data)) {
                    throw new Exception("儲存失敗");
                }
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
    
    public function delete()
    {
        $roleid = (int) input("roleid");
        
        if(Db::name("roles")->where("roleid", $roleid)->delete()) {
            toJSON(["msg" => "刪除成功", "code" => 0]);
        } else {
            toJSON(["msg" => "刪除失敗", "code" => 1]);
        }
    }
    
    //獲取節點
    private static function get_nodes($selectedIds=[])
    {
        $list = Nodes::getNodes();
        $menu_nodes = [];
        if(!empty($list)) {
            foreach($list as $nodeid => $v) {
                if(in_array($nodeid, $selectedIds)) {
                    $checked = true;
                } else {
                    $checked = false;
                }
                $menu_nodes[] = [
                    "id" => $nodeid,
                    "pId" => 0,
                    "name" => $v["title"],
                    "checked" => $checked,
                    "open" => true,
                ];
                if(isset($v["child"]) && !empty($v["child"])) {
                    foreach($v["child"] as $snodeid => $sv) {
                        if(in_array($snodeid, $selectedIds)) {
                            $checked = true;
                        } else {
                            $checked = false;
                        }
                        $menu_nodes[] = [
                            "id" => $snodeid,
                            "pId" => $nodeid,
                            "name" => $sv["title"],
                            "checked" => $checked,
                            "open" => true,
                        ];
                    }
                }



            }
        }

        return $menu_nodes;
    }
    
}
