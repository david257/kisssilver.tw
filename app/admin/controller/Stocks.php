<?php
namespace app\admin\controller;

use app\extend\Excel_XML;
use app\models\Attris;
use app\models\AttrValue;
use app\models\CategoryProductAss;
use app\models\Order;
use app\models\OrderProduct;
use app\models\ProductImages;
use app\models\SearchAttries;
use app\models\VariationCombinations;
use app\models\VariationOptions;
use PhpOffice\PhpSpreadsheet\IOFactory;
use think\Exception;
use think\facade\Db;
use think\facade\View;
use app\models\Category as CateModel;
use app\models\Products as ProdModel;
use think\Image;

class Stocks extends Base
{
    public function index()
    {
        $keyword = input("keyword");
        $state = input("state", "all");
        $map  =[];
        if(!empty($keyword)) {
            $map[] = ["prodname|prodcode", "LIKE", "%".$keyword."%"];
        }
        if(is_numeric($state)) {
            $map[] = ["state", "=", $state];
        }
        $params["list_rows"] = 50;
        $params["query"] = [
            "keyword" => $keyword,
            "state" => $state,
        ];
        $query = Db::name("view_product_stocks")->where($map)->order("prodcode DESC, state DESC, vcenabled DESC, stock DESC")->paginate($params);
        $list = $query->all();
        $data = [
            "pages" => $query->render(),
            "list" => $list,
            "keyword" => $keyword,
            "state" => $state,
        ];
        return View::fetch('', $data);
    }

}
