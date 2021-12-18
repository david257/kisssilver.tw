<?php
namespace app\controller;

use app\BaseController;
use app\models\CustomerGroup;
use app\models\LiveLucky;
use app\models\Lives as liveModel;
use app\models\Products;
use think\Exception;
use think\facade\Db;
use think\facade\View;

class Lives extends BaseController
{

    public function index()
    {
        try {
            $data["live"] = Db::name(liveModel::$tablename)->order("create_at DESC")->find();
            if(empty($data["live"])) {
                throw new Exception("未設置直播");
            }

            $list = Db::name(Products::$tablename)->where("is_live",1)->where("state", 1)->order("sortorder ASC")->select();
            $prod_ids = [];
            if(!empty($list)) {
                foreach ($list as $v) {
                    $prod_ids[] = $v['prodid'];
                }
            }

            $data["products"] = Products::getProductsByIds($prod_ids);

            $data["totalapply"] = Db::name(LiveLucky::$tablename)->where("liveid", $data["live"]["liveid"])->count();

            //提取已中獎的名單
            $data["lucylist"] = Db::table(LiveLucky::$tablename)->alias("ll")
                ->join(\app\models\Customer::$tablename." c", "c.customerid=ll.customerid")
                ->join(CustomerGroup::$tablename." g", "g.group_id=c.group_id")
                ->where("ll.liveid", $data["live"]["liveid"])
                ->where("ll.is_lucky", 1)
                ->field("c.vipcode, g.title")
                ->select()
                ->toArray();

            View::assign($data);
            return View::fetch();
        } catch (Exception $ex) {
            abort(404, $ex->getMessage());
        }


    }

    public function lottery()
    {
        try {
            $liveid = input("liveid", 0);
            if(empty($liveid)) {
                throw new Exception("無效操作");
            }

            $customer = getLoginCustomer();
            if(empty($customer)) {
                throw new Exception("請先登入會員再參與抽獎");
            }

            $live = Db::name(liveModel::$tablename)->where("liveid", $liveid)->find();
            if(empty($live)) {
                throw new Exception("無效操作");
            }

            if($live['jiangpin_qty']<=0) {
                throw new Exception("此場直播未設置抽獎活動");
            }

            if($live["start_date"]>time()) {
                throw new Exception("抽獎未開始");
            }
            if($live["end_date"]<time()) {
                throw new Exception("抽獎已結束");
            }

            if($live["end_date"]<time()) {
                throw new Exception("抽獎已結束");
            }

            if($live["has_lottery"]) {
                throw new Exception("已開獎,無法再抽獎");
            }

            $data = [
                "liveid" => $liveid,
                "customerid" => $customer["customerid"],
                "create_time" => time()
            ];

            if(Db::name(LiveLucky::$tablename)->where("liveid", $liveid)->where("customerid", $customer["customerid"])->count()) {
                throw new Exception("您已參與過本場直播抽獎了");
            }

            if(!Db::name(LiveLucky::$tablename)->insert($data)) {
                throw new Exception("抽獎失敗");
            }


            toJSON([
                "code" => 0,
                "msg" => "參與抽獎成功"
            ]);
        } catch (Exception $ex) {
            toJSON([
                "code" => 1,
                "msg" => $ex->getMessage()
            ]);
        }

    }
}
