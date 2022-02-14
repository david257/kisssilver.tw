<?php

namespace app\admin\controller;

use app\models\CreditLog;
use app\models\CustomerGroup;
use app\models\Order;
use app\models\OrderProduct;
use app\models\StorePaidOrder;
use think\Exception;
use think\facade\Db;
use think\facade\Request;
use app\models\Customer as CModel;
use think\facade\View;

class Customer extends Base {

    protected $request;

    public function _initialize() {
        parent::_initialize();
    }

    public function index() {
        $map = array();
        $keyword = input('keyword', '');
        $group_id = input("group_id", 0);
        $start_date = input("start_date");
        $end_date = input("end_date");

        if(!empty($keyword)) {
            $map[] = ["vipcode|fullname|custconemail|mobile", "LIKE", "%".$keyword."%"];
        }

        if(!empty($group_id)) {
            $map[] = ["group_id", "=", $group_id];
        }

        if(!empty($start_date)) {
            $map[] = ["create_at", ">=", strtotime($start_date)];
        }

        if(!empty($end_date)) {
            $map[] = ["create_at", "<=", strtotime($end_date." 23:59:59")];
        }

        $page_size = 20;
        $params["listRows"] = 20;
        $params["query"] = [
            "keyword" => $keyword,
            "group_id" => $group_id,
            "start_date" => $start_date,
            "end_date" => $end_date
        ];
        $query = Db::name(CModel::$tablename)->where($map)->order("customerid DESC")->paginate($page_size);
        $data["list"] = $query->all();

        $data["pages"] = $query->render();
        $data["totals"] = $query->total();
        $data["keyword"] = $keyword;
        $data["group_id"] = $group_id;
        $data["start_date"] = $start_date;
        $data["end_date"] = $end_date;

        View::assign($data);
        return View::fetch();
    }

    public function groups()
    {
        $data["list"] = Db::name(CustomerGroup::$tablename)->order("sortorder ASC")->select();
        View::assign($data);
        return View::fetch();
    }

    public function updateGroup()
    {
        $field = input("field");
        $groupid = input("group_id");
        $val = input("val");
        if(false === Db::name(CustomerGroup::$tablename)->where("group_id", $groupid)->update([$field => $val])) {
            toJSON([
                "code" => 1,
                "msg" => "儲存失敗"
            ]);
        } else {
            toJSON([
                "code" => 0,
                "msg" => "儲存成功"
            ]);
        }
    }
    
    //delete
    public function delete() {
        try {
            $customerid = (int) input('customerid');
            if(false === Db::name(CModel::$tablename)->where("customerid", $customerid)->delete()) {
                throw new Exception("刪除失敗");
            }

            toJSON([
                "code" => 0,
                "msg" => "刪除成功"
            ]);
        } catch(Exception $ex) {
            toJSON([
                "code" => 1,
                "msg" => $ex->getMessage()
            ]);
        }
    }

    public function changePasswd() {
        $customerid = (int) input('customerid');
        if(request()->isPost()) {
            try {
                $passwd = input('passwd');
                $passwd2 = input('passwd2');

                if(empty($passwd)) {
                    throw new Exception("請輸入新密碼");
                }

                if($passwd != $passwd2) {
                    throw new Exception("確認密碼不一致");
                }

                if(false === Db::name(CModel::$tablename)->where("customerid", $customerid)->update(["custpassword" => md5($passwd)])) {
                    throw new Exception("密碼變更失敗");
                }

                toJSON([
                    "code" => 0,
                    "msg" => "密碼變更成功",
                    "url" => admin_link("Customer/index")
                ]);
            } catch (Exception $ex) {
                toJSON([
                    "code" => 1,
                    "msg" => $ex->getMessage()
                ]);
            }
        }

        $data["customerid"] = $customerid;
        View::assign($data);
        return View::fetch();
    }
    
    public function chargeCredits()
    {
        $customerid = (int) input('customerid');
        if(request()->isPost()) {
            Db::startTrans();
            try {
                $credits = (int) input('credits');
                $msg = input('msg');

                if(empty($credits)) {
                    throw new Exception("請輸入儲值金額");
                }

                if(empty($msg)) {
                    throw new Exception("請輸入備註");
                }
                if(mb_strlen($msg)>500) {
                    throw new Exception("備註最多500字元");
                }

                CModel::changeCredits($customerid, $credits, '', $msg, $this->getUserId());

                Db::commit();
                toJSON([
                    "code" => 0,
                    "msg" => "紅利點數儲值成功",
                    "url" => admin_link("Customer/index")
                ]);
            } catch (Exception $ex) {
                Db::rollback();
                toJSON([
                    "code" => 1,
                    "msg" => $ex->getMessage()
                ]);
            }
        }

        $data["customerid"] = $customerid;
        View::assign($data);
        return View::fetch();
    }

    public function clogs()
    {
        $customerid = input("customerid", 0);
        $params["list_rows"] = 10;
        $params["query"] = [
            "customerid" =>$customerid
        ];
        $query = Db::table(CreditLog::$tablename)->alias("cl")
            ->join(\app\models\User::$tablename." u", "u.userid=cl.userid", "LEFT")
            ->where("cl.customerid", $customerid)
            ->field("cl.*, u.fullname")
            ->order("cl.create_at DESC")
            ->paginate($params);

        $data["creditLogs"] = $query->all();
        $data["pages"] = $query->render();
        View::assign($data);
        return View::fetch("clogs");
    }

    public function detail()
    {
        $customerid = (int) input("customerid", 0);
        $data["customer"] = Db::name(CModel::$tablename)->where("customerid", $customerid)->find();
        $ids = [];
        $ids[] = $data["customer"]["cityid"];
        $ids[] = $data["customer"]["areaid"];
        $list = Db::name("countries")->where("id", "IN", $ids)->select();
        $countries = [];
        if(!empty($list)) {
            foreach($list as $v) {
                $countries[$v["id"]] = $v["name"];
            }
        }
        $data["countries"] = $countries;
        View::assign($data);
        return View::fetch();
    }

    public function orders()
    {
        $keyword = input("keyword");
        $prodid = input("prodid", 0);
        $group_id = input("group_id", 0);
        $start_date = input("start_date");
        $end_date = input("end_date");

        $where = [];
        $where[] = ["o.order_status", "=", 100];

        if(!empty($keyword)) {
            $where[] = ["c.fullname|c.vipcode|c.custconemail", "LIKE", "%".$keyword."%"];
        }

        if(!empty($group_id)) {
            $where[] = ["c.group_id", "=", $group_id];
        }

        if(!empty($prodid)) {
            $list = Db::name(OrderProduct::$tablename)->where("prodid", $prodid)->field("DISTINCT oid")->select();
            if(!empty($list)) {
                foreach($list as $l) {
                    $oids[] = $l["oid"];
                }
                $maps[] = "o.oid IN(".implode(",", $oids).")";
            } else {
                $maps[] = "o.oid IN(-1)";
            }
        }

        if(!empty($start_date)) {
            $where[] = ["o.create_date", ">=", strtotime($start_date)];
        }
        if(!empty($end_date)) {
            $where[] = ["o.create_date", "<=", strtotime($end_date." 23:59:59")];
        }

        $params["list_rows"] = 1000;
        $params["query"] = [
            "start_date" => $start_date,
            "end_date" => $end_date,
            "keyword" => $keyword,
            "group_id" => $group_id
        ];

        $query = Db::table(CModel::$tablename)->alias("c")
            ->join(Order::$tablename." o", "o.customerid=c.customerid", "LEFT")
            ->where($where)
            ->field("c.customerid, c.vipcode, c.custconemail, c.fullname, c.group_id, count(distinct o.oid) AS total, sum(o.total_amount) as totalAmount")
            ->group("o.customerid")
            ->order("totalAmount DESC")
            ->paginate($params);

        $list = $query->all();
        if(!empty($list)) {
            $customerIds = [];
            foreach($list as $v) {
                $customerIds[] = $v["customerid"];
            }
            $map = [];
            $map[] = ["spo.customerid", "IN", $customerIds];
            if(!empty($start_date)) {
                $map[] = ["spo.create_at", ">=", strtotime($start_date)];
            }
            if(!empty($end_date)) {
                $map[] = ["spo.create_at", "<=", strtotime($end_date." 23:59:59")];
            }
            $spolist = Db::table(StorePaidOrder::$tablename)->alias("spo")
                ->where($map)
                ->field("spo.customerid, count(spo.spoid) as total, sum(total_amount) as totalAmount")
                ->group("spo.customerid")
                ->select();
        }

        $data["list"] = $list;
        $data["pages"] = $query->render();
        $data["keyword"] = $keyword;
        $data["start_date"] = $start_date;
        $data["end_date"] = $end_date;
        $data["group_id"] = $group_id;
        View::assign($data);
        if(!empty($prodid)) {
            $tpl = "order_product";
        } else {
            $tpl = "orders";
        }
        return View::fetch($tpl);
    }

}

?>
