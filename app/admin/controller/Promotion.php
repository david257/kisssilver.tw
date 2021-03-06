<?php
namespace app\admin\controller;
use app\models\Category as CateModel;
use think\Exception;
use think\facade\Db;
use think\facade\View;
use think\facade\Request;

class Promotion extends Base
{
    public function index()
    {
       $list = Db::name("promotions")->order("state DESC")->select();
       $data["list"] = $list;
       View::assign($data);
       return View::fetch();
    }
    
    public function add()
    {
        $data["zNodes"] = toJSON(CateModel::getZtree(), true);
        View::assign($data);
        return View::fetch("form");
    }
    
    public function edit()
    {
        $ptid = input("ptid");
        $promotion = Db::name("promotions")->where("ptid", $ptid)->find();
        $data["promotion"] = $promotion;
        $categories = explode(",", $promotion["bind_cat_ids"]);
        $checkedCatIds = [];
        if (!empty($categories)) {
            foreach ($categories as $catid) {
                $checkedCatIds[] = $catid;
            }
        }
        $data["zNodes"] = toJSON(CateModel::getZtree($checkedCatIds), true);
        View::assign($data);
        return View::fetch("form");
    }
    
    public function save()
    {
        try {
            $ptid = (int)Request::instance()->param("ptid");
            $title = Request::instance()->param("title");
            $ptype = Request::instance()->param("ptype");
            $total = (int)Request::instance()->param("total");
            $sub_total = (int)Request::instance()->param("sub_total");
            $start_date = Request::instance()->param("start_date");
            $end_date = Request::instance()->param("end_date");
            $state = (int)Request::instance()->param("state");
            $cateIds = input("cateIds", 0);

            /*if(!empty($cateIds)) {
                $all_promotions = get_promotions();
                $promotionCates = [];
                if (!empty($all_promotions)) {
                    foreach ($all_promotions as $ptype => $promotion_rows) {
                        foreach ($promotion_rows as $promotionR) {
                            if (!empty($promotionR["bind_cat_ids"])) {
                                $bind_cat_ids = explode(",", $promotionR["bind_cat_ids"]);
                                foreach ($bind_cat_ids as $k => $catId) {
                                    $promotionCates[$catId] = [
                                        'ptid' => $promotionR['ptid'],
                                        'title' => $promotionR['title']
                                    ];
                                }
                            }
                        }
                    }
                }

               $bindCateIds = explode(",", $cateIds);
                foreach($bindCateIds as $k => $catId) {
                    if(isset($promotionCates[$catId]) && (empty($ptid) || $promotionCates[$catId]['ptid'] != $ptid)) {
                        $cat = Db::name(CateModel::$tablename)->where("catid", $catId)->find();
                        throw new Exception("?????????".$cat["catname"]."??????????????????????????????".$promotionCates[$catId]['title']."?????????, ????????????????????????????????????");
                    }
                }
            }*/

            $data = [
                "title" => $title,
                "ptype" => $ptype,
                "total" => $total,
                "sub_total" => $sub_total,
                "start_date" => strtotime($start_date),
                "end_date" => strtotime($end_date),
                "state" => $state,
                "bind_cat_ids" => trim($cateIds, ","),
                "update_time" => time(),
            ];

            if ($data["start_date"] < strtotime("-1 day")) {
                throw new Exception("????????????????????????????????????");
            }

            if ($data["end_date"] < $data["start_date"]) {
                throw new Exception("????????????????????????????????????");
            }

            if ($ptid) {
                if (false === Db::name("promotions")->where("ptid", $ptid)->update($data)) {
                    throw new Exception("????????????");
                }
            } else {
                $data["create_time"] = time();
                if (false === Db::name("promotions")->insert($data)) {
                    throw new Exception("????????????");
                }
            }
            toJSON(["msg" => "????????????", "code" => 0]);
        } catch (Exception $ex) {
            toJSON(["msg" => $ex->getMessage(), "code" => 1]);
        }
    }
    
    public function delete()
    {
        $ptid = (int) Request::instance()->param("ptid");
        if(Db::name("promotions")->where("ptid", $ptid)->delete()) {
            toJSON(["msg" => "????????????", "code" => 0]);
        } else {
            toJSON(["msg" => "????????????", "code" => 1]);
        }
    }

}