<?php
namespace app\admin\controller;

use app\models\CustomerGroup;
use app\models\LiveLucky;
use think\Exception;
use think\facade\Db;
use think\facade\View;
use app\models\Lives as LivesModel;

class Lives extends Base
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
        $query = Db::name(LivesModel::$tablename)->where($where)->order("create_at DESC")->paginate($params);
        $list = $query->all();
        $live_lucky = [];
        $lucy_users = [];
        if(!empty($list)) {
            $liveids = [];
            foreach($list as $v) {
                $liveids[] = $v["liveid"];
            }

            //統計中獎人數
            $lucy_list = Db::name(LiveLucky::$tablename)->where("liveid", "IN", $liveids)->where("is_lucky", 1)->field("liveid, COUNT(liveid) AS t")->group("liveid")->select();
            if(!empty($lucy_list)) {
                foreach($lucy_list as $v) {
                    $lucy_users[$v["liveid"]] = $v["t"];
                }
            }

            //統計參與人數
            $lucys = Db::name(LiveLucky::$tablename)->where("liveid", "IN", $liveids)->field("liveid, COUNT(liveid) AS t")->group("liveid")->select();
            if(!empty($lucys)) {
                foreach($lucys as $v) {
                    $live_lucky[$v["liveid"]] = $v["t"];
                }
            }
        }
        $data["list"] = $query->all();
        $data["live_lucky"] = $live_lucky;
        $data["lucy_users"] = $lucy_users;
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
        $liveid = input("liveid");
        $live = Db::name(LivesModel::$tablename)->where("liveid", $liveid)->find();
        $data["live"] = $live;
        return View::fetch("form", $data);
    }

    public function detail()
    {
        $liveid = input("liveid");
        $live = Db::name(LivesModel::$tablename)->where("liveid", $liveid)->find();
        $data["live"] = $live;
        return View::fetch("detail", $data);
    }

    public function save()
    {
        try {

            $liveid = (int) input("liveid", 0);
            $title = input("title", '');
            $live_code = input("live_code", "");
            $content = input("content", '');
            $jiangpin_qty = (int) input("jiangpin_qty", 0);
            $start_date = input("start_date", 0);
            $end_date = input("end_date", 0);

            if(empty($title)) {
                throw new Exception("直播名稱不能為空");
            }

            if(mb_strlen($title)>255) {
                throw new Exception("直播名稱不能超過255字元");
            }

            if(empty($live_code)) {
                throw new Exception("直播代碼未貼入");
            }

            if(empty($start_date)) {
                throw new Exception("開始日期未設置");
            }

            if(empty($end_date)) {
                throw new Exception("截止日期未設置");
            }

            $data = [
                "title" => $title,
                "live_code" => $live_code,
                "content" => $content,
                "jiangpin_qty" => $jiangpin_qty,
                "start_date" => strtotime($start_date),
                "end_date" => strtotime($end_date),
                "update_at" => time(),
            ];

            if($liveid) {
                $live = Db::name(LivesModel::$tablename)->where("liveid", $liveid)->find();
                if(empty($live)) {
                    throw new Exception("直播記錄無效");
                }

                if($live["has_lottery"]) {
                    throw new Exception("已開獎的記錄只能查看");
                }

                if(false === Db::name(LivesModel::$tablename)->where("liveid", $liveid)->update($data)) {
                    throw new Exception("儲存失敗");
                }
            } else {
                $data["create_at"] = time();
                if(false === Db::name(LivesModel::$tablename)->insert($data)) {
                    throw new Exception("儲存失敗");
                }
            }

            toJSON([
                "code" => 0,
                "msg" => "儲存成功",
                "url" => admin_link("Lives/index")
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
            $liveid = (int) input("liveid", 0);
            if(empty($liveid)) {
                throw new Exception("記錄無效");
            }

            Db::transaction(function() use ($liveid) {

                if (false === Db::name(LivesModel::$tablename)->where("liveid", $liveid)->delete()) {
                    throw new Exception("刪除失敗");
                }

                if(false === Db::name(LiveLucky::$tablename)->where("liveid", $liveid)->delete()) {
                    throw new Exception("刪除抽獎名單失敗");
                }
            });

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

    public function makeLuckys()
    {
        try {
            $liveid = (int) input("liveid", 0);
            if(!is_numeric($liveid) || empty($liveid)) {
                throw new Exception("記錄無效");
            }

            $live = Db::name(LivesModel::$tablename)->where("liveid", $liveid)->find();
            if(empty($live)) {
                throw new Exception("記錄未找到");
            }

            if($live["start_date"]>time()) {
                throw new Exception("抽獎時間未開始, 沒有參與人員");
            }

            if($live["end_date"]>time()) {
                throw new Exception("抽獎時間未結束, 不能開獎");
            }

            if($live["jiangpin_qty"]<=0) {
                throw new Exception("獎品數量為0，不能開獎");
            }

            if($live["has_lottery"]) {
                throw new Exception("不要重複開獎");
            }

           $livelucky = Db::name(LiveLucky::$tablename)->where("liveid", $liveid)->find();
           if(empty($livelucky)) {
              throw new Exception("本場直播沒有抽獎參與人員");
           }

           Db::transaction(function() use ($liveid, $live) {

              $luckusers = Db::name(LiveLucky::$tablename)->where("liveid", $liveid)->orderRand()->limit($live["jiangpin_qty"])->select();
              if(empty($luckusers)) {
                  throw new Exception("抽獎參與人員為空");
              }

              $customerIds = [];
              foreach($luckusers as $v) {
                  $customerIds[] = $v["customerid"];
              }

              if(false === Db::name(LiveLucky::$tablename)->where("liveid", $liveid)->where("customerid", "IN", $customerIds)->update(["is_lucky" => 1])) {
                  throw new Exception("開獎失敗");
              }

              if(false === Db::name(LivesModel::$tablename)->where("liveid", $liveid)->update(["has_lottery" => 1, "update_at" => time()])) {
                  throw new Exception("開獎失敗");
              }

           });

           toJSON([
               "code" => 0,
               "msg" => "開獎成功"
           ]);
        } catch(Exception $ex) {
            toJSON([
                "code" => 1,
                "msg" => $ex->getMessage()
            ]);
        }

    }

    public function getUsers()
    {
        $liveid = input("liveid", 0);
        $is_lucky = input("is_lucky", 0);
        $map = [];
        $map[] = ["liveid", "=", $liveid];
        if(!empty($is_lucky)) {
            $map[] = ["is_lucky", "=", 1];
        }

        $query = Db::table(LiveLucky::$tablename)->alias("ll")
            ->join(\app\models\Customer::$tablename." c", "c.customerid=ll.customerid")
            ->join(CustomerGroup::$tablename." g", "g.group_id=c.group_id")
            ->where($map)
            ->field("c.*, g.title")
            ->paginate();
        $data["list"] = $query->all();
        $data["pages"] = $query->render();
        View::assign($data);
        return View::fetch("luck_users");
    }
}
