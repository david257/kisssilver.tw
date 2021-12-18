<?php
namespace app\admin\controller;
use app\extend\CouponCode;
use app\models\CouponCodeOrders;
use think\Exception;
use think\facade\Db;
use think\facade\View;
use think\facade\Request;
use app\models\CouponCodes as CCodes;

class CouponCodes extends Base
{
    public function index()
    {
        $keyword = input("keyword");
        $where = [];
        if(!empty($keyword)) {
            $where[] = ["cd.code", "LIKE", "%".$keyword."%"];
        }
        $query = Db::table(CCodes::$tablename)->alias("cd")
            ->leftJoin("(SELECT `code`, COUNT(oid) AS qty, SUM(total_amount) AS total_amount FROM coupon_code_orders GROUP BY `code`) co", "co.code=cd.code")
            ->field("cd.*, co.qty, co.total_amount")
            ->where($where)
            ->order("cd.ccid DESC")->paginate([
                "list_rows" => 20,
                "query" => [
                    "keyword" => $keyword,
                ]
            ]);
        $data["list"] = $query->all();
        $data["pages"] = $query->render();
        $data["keyword"] = $keyword;
        View::assign($data);
        return View::fetch();
    }

    public function add()
    {
        return View::fetch("form");
    }

    public function edit()
    {
        $ccid = input("ccid");
        $data["ccodes"] = Db::name(CCodes::$tablename)->where("ccid", $ccid)->find();
        View::assign($data);
        return View::fetch("form");
    }

    public function save()
    {
        try {
            $ccid = (int)Request::instance()->param("ccid");
            $title = Request::instance()->param("title");
            $ptype = Request::instance()->param("ptype");
            $total = (int)Request::instance()->param("total");
            $sub_total = (int)Request::instance()->param("sub_total");
            $start_date = Request::instance()->param("start_date");
            $end_date = Request::instance()->param("end_date");
            $is_reuse = (int)Request::instance()->param("is_reuse");
            $state = (int)Request::instance()->param("state");

            $data = [
                "title" => $title,
                "ptype" => $ptype,
                "code"  => (new CouponCode())->encodeID(rand(0,100000), 6),
                "total" => $total,
                "sub_total" => $sub_total,
                "start_date" => strtotime($start_date),
                "end_date" => strtotime($end_date),
                "is_reuse" => $is_reuse,
                "state" => $state,
                "update_time" => time(),
            ];

            if ($data["start_date"] < strtotime("-1 day")) {
                throw new Exception("開始日期必須大於等於今日");
            }

            if ($data["end_date"] < $data["start_date"]) {
                throw new Exception("截止日期必須大於開始日期");
            }

            if ($ccid) {
                if (false === Db::name(CCodes::$tablename)->where("ccid", $ccid)->update($data)) {
                    throw new Exception("更新失敗");
                }
            } else {
                $data["create_time"] = time();
                if (false === Db::name(CCodes::$tablename)->insert($data)) {
                    throw new Exception("儲存失敗");
                }
            }
            toJSON(["msg" => "儲存成功", "code" => 0]);
        } catch (Exception $ex) {
            toJSON(["msg" => $ex->getMessage(), "code" => 1]);
        }
    }

    public function delete()
    {
        $ccid = (int) Request::instance()->param("ccid");
        if(Db::name(CCodes::$tablename)->where("ccid", $ccid)->delete()) {
            toJSON(["msg" => "刪除成功", "code" => 0]);
        } else {
            toJSON(["msg" => "刪除失敗", "code" => 1]);
        }
    }

}
