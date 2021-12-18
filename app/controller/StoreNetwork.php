<?php
namespace app\controller;

use app\BaseController;
use think\Exception;
use think\facade\Db;
use think\facade\View;
use app\models\StoreNetwork as SNModel;

class StoreNetwork extends BaseController
{

    public function index()
    {
        try {
            $query = Db::name(SNModel::$tablename)->order("sortorder ASC")->paginate(20);

            $data["list"] = $query->all();
            $data["pages"] = \app\extend\Page::make($query->currentPage(), $query->lastPage());
            View::assign($data);
            return View::fetch();
        } catch (Exception$ex) {
            abort(404);
        }

    }

    public function detail()
    {
        try {
            $id = (int) input("id", 0);
            if(empty($id)) {
                throw new Exception("記錄不存在");
            }

            $store = Db::name(SNModel::$tablename)->where("id", $id)->find();
            if(empty($store)) {
                throw new Exception("記錄不存在");
            }

            $data["store"] = $store;
            View::assign($data);
            return View::fetch();
        } catch (Exception $ex) {
            abort(404);
        }
    }
}
