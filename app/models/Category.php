<?php
namespace app\models;

use think\facade\Db;

class Category
{
    static $tablename = "categories";

    static function getSelectOptions($selectedId=0)
    {
        $list = static::getList();
        $trlist = \PHPTree::makeTreeForHtml($list);
        $str = '';
        if(!empty($trlist)) {
            foreach($trlist as $v) {
                if($v['id'] == $selectedId) {
                    $selected = 'selected';
                } else {
                    $selected = '';
                }
                $str .= '<option value="'.$v['id'].'" '.$selected.'>'.str_repeat(' └', $v['level']).$v['name'].'</option>';
            }
        }

        return $str;
    }

    static function getZtree($selectedIds=[]):array
    {
        $list = static::getList();
        $ztreeNodes = [];
        if(!empty($list)) {
            foreach($list as $k => $v) {
                if(in_array($v["id"], $selectedIds)) {
                    $checked = true;
                } else {
                    $checked = false;
                }
                $ztreeNodes[] = [
                    "id" => $v["id"],
                    "name" => $v["name"],
                    "pId" => $v["parent_id"],
                    "checked" => $checked,
                    "open" => true
                ];
            }
        }
        return $ztreeNodes;
    }

    /**
     * get category data list
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    static function getList():array
    {
        $list = Db::name(static::$tablename)->order("sortorder ASC")->select();
        $cates = [];
        if(!empty($list)) {
            foreach($list as $v) {
                $cates[] = [
                    "id" => $v["catid"],
                    "name" => $v["catname"],
                    "parent_id" => $v["parentid"],
                    "sortorder" => $v["sortorder"],
                    "state" => $v["state"],
                    "editlink" => admin_link('edit', ['id' => $v["catid"]]),
                    "dellink" => admin_link('delete', ['id' => $v["catid"]]),
                ];
            }
        }
        return $cates;
    }

    static function getBreadCrumbs($catId=0)
    {
        static $breadcrumbs = [];
        $row = Db::name(static::$tablename)->where("catid", $catId)->find();
        $breadcrumbs[] = $row;
       if(!empty($row) && $row["parentid"]) {
           return static::getBreadCrumbs($row["parentid"]);
       } else {
           return $breadcrumbs;
       }
    }
}