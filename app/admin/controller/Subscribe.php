<?php
namespace app\admin\controller;

use app\extend\Excel_XML;
use think\Exception;
use think\facade\Db;
use think\facade\View;

class Subscribe extends Base
{
    public function index()
    {
        $where = [];
        $email = input("email");
        $export = input("export", 0);
        if(!empty($email)) {
            $where[] = ["email", "=", $email];
        }

        if($export) {
            $limit = 1000000;
        } else {
            $limit = 20;
        }

        $params["list_rows"] = $limit;
        $params["query"] = [
            "email" => $email
        ];
        $query = Db::name("subscribes")->where($where)->order("create_at DESC")->paginate($params);

        if($export) {
            $export_list = [];
            $export_list[] = [
                "Email", "訂閱日期"
            ];
            $list  =$query->all();
            if(!empty($list)) {
                foreach($list as $v) {
                    $export_list[] = [
                        $v['email'],
                        date('Y-m-d', $v["create_at"])
                    ];
                }
            }
            $excel = new Excel_XML('UTF-8', FALSE, '訂閱列表');
            $filename = "訂閱列表";
            $excel->addArray($export_list);
            $excel->generateXML($filename);
            exit;
        }

        $data["list"] = $query->all();
        $data["pages"] = $query->render();
        $data["email"] = $email;
        return View::fetch('', $data);
    }

    public function delete()
    {
        try {
            $email = input("email", 0);
            if(empty($email)) {
                throw new Exception("記錄無效");
            }

            if(false === Db::name("subscribes")->where("email", $email)->delete()) {
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
