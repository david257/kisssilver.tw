<?php
namespace app\admin\controller;

use app\extend\Excel_XML;
use app\models\Attris;
use app\models\AttrValue;
use app\models\CategoryProductAss;
use app\models\Order;
use app\models\OrderProduct;
use app\models\ProductImages;
use app\models\ProductVideo;
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

class Product extends Base
{
    public function index()
    {
        $keyword = input("keyword");
        $state = input("state", "all");
        $fieldname = input("fieldname");
        $map  =[];
        if(!empty($keyword)) {
            $map[] = ["prodname|prodcode", "LIKE", "%".$keyword."%"];
        }
        if(is_numeric($state)) {
            $map[] = ["state", "=", $state];
        }
        if(!empty($fieldname)) {
            $map[] = [$fieldname, "=", 1];
        }
        $params["list_rows"] = 20;
        $params["query"] = [
            "keyword" => $keyword,
            "state" => $state,
            'fieldname' => $fieldname
        ];
        $query = Db::name(ProdModel::$tablename)->where($map)->order("prodid DESC")->paginate($params);
        $list = $query->all();
        $images = [];
        $product_videos = [];
        if(!empty($list)) {
            $prodIds = [];
            foreach($list as $v) {
                $prodIds[] = $v["prodid"];
            }
            $imageList = Db::name(ProductImages::$tablename)->where("productid", "IN", $prodIds)->where("is_main", 1)->select();
            if(!empty($imageList)) {
                foreach($imageList as $image) {
                    $images[$image["productid"]] = $image["image_tiny"];
                }
            }

           $videos = Db::name(ProductVideo::$tablename)->where("prodid", "in", $prodIds)->select();
            if(!empty($videos)) {
                foreach($videos as $video) {
                    if(!empty($video["video_src"])) {
                        $product_videos[$video['prodid']] = 1;
                    }
                }
            }
        }
        $data = [
            "pages" => $query->render(),
            "list" => $list,
            "keyword" => $keyword,
            "state" => $state,
            "fieldname" => $fieldname,
            "images" => $images,
            "product_videos" => $product_videos,
			"token" => md5(request()->ip().'8888')
        ];
        return View::fetch('', $data);
    }

    public function add()
    {
        $data["search_attris"] = self::getAttris(0);
        //$data["buy_attris"] = self::getAttris($void);

        $data["product_variations"] = self::getProductAttris();
        $data["zNodes"] = toJSON(CateModel::getZtree(), true);

        $config = get_setting();
        $setting = $config["setting"];
        $fee = isset($setting["shipping"]["fee"])?$setting["shipping"]["fee"]:0;
        $fee = (int) trim($fee);
        $data["shippingfee"] = $fee;
        return View::fetch("form", $data);
    }

    public function edit()
    {
        try {
            $prodid = input("prodid");
            $prod = Db::name(ProdModel::$tablename)->where("prodid", $prodid)->find();
            if(empty($prod)) {
                throw new Exception("記錄不存在");
            }

           $video = Db::name(ProductVideo::$tablename)->where("prodid", $prodid)->find();
            if(!empty($video)) {
                $prod["video"] = $video["video_src"];
                $prod["video_image"] = $video["video_image"];
            }

            $searchAttris = Db::name(SearchAttries::$tablename)->where("productid", $prodid)->select();
            $data["search_options"] = [];
            if(!empty($searchAttris)) {
                foreach($searchAttris as $searchoption) {
                    $data["search_options"][$searchoption['attrid']][] = $searchoption['valueid'];
                }
            }

            $vvalues = Db::name(VariationCombinations::$tablename)->where("vcproductid", $prodid)->select();
            $data["voptionvalues"] = [];
            if(!empty($vvalues)) {
                foreach($vvalues as $value) {
                    $data["voptionvalues"][$value["vcoptionids"]] = $value;
                }
            }

            $data["imageList"] = Db::name(ProductImages::$tablename)->where("productid", $prodid)->order("sortorder ASC")->select();

            $categories = Db::name(CategoryProductAss::$tablename)->where("prodid", $prodid)->select();
            $checkedCatIds = [];
            if (!empty($categories)) {
                foreach ($categories as $cate) {
                    $checkedCatIds[] = $cate["catid"];
                }
            }

            $data["search_attris"] = self::getAttris(0);
            $data["zNodes"] = toJSON(CateModel::getZtree($checkedCatIds), true);

            $data["prod"] = $prod;
            $data["product_variations"] = self::getProductAttris();
            return View::fetch("form", $data);
        } catch(Exception $ex) {
            abort(404, $ex->getMessage());
        }
    }

    public function getProductAttris()
    {
        return Db::name(VariationOptions::$tablename)->select();
    }

    public function update_state()
    {
        try {
            $prodid = (int) input("prodid", 0);
            $name = input("data_name", '');
            $state = (int) input("data_state", 0);

            if(empty($prodid) || !is_numeric($prodid)) {
                throw new Exception("記錄無效");
            }

            if(empty($name)) {
                throw new Exception("參數無效");
            }

            if(false === Db::name(ProdModel::$tablename)->where("prodid", $prodid)->update([$name => $state])) {
                throw new Exception("操作失敗");
            }

            toJSON([
                "code" => 0,
                "msg" => "操作成功"
            ]);
        } catch(Exception $ex) {
            toJSON([
                "code" => 1,
                "msg" => $ex->getMessage()
            ]);
        }

    }

    public function get_prod_vos()
    {
        try {
            $void = (int) input("void", 0);
            if(!$void) {
                throw new Exception("商品規格未選擇");
            }

            $ret = self::getAttris($void);
            toJSON([
                "code" => 0,
                "data" => $ret
            ]);
        } catch(Exception $ex) {
            toJSON([
                "code" => 1,
                "msg" => $ex->getMessage()
            ]);
        }
    }

    public function getAttris($void=0)
    {
        $attris = Db::name(Attris::$tablename)->where("state", 1)->where("void", $void)->order("sortorder ASC")->select();
        $attrList  =[];
        $attrNames = [];
        if(!empty($attris)) {
            foreach($attris as $attr) {
               $values = Db::name(AttrValue::$tablename)->where("attrid", $attr["attrid"])->where("state", 1)->order("sortorder ASC")->select()->toArray();
               if(!empty($values)) {
                   foreach($values as $value) {
                       $attrList[$attr["attrid"]][] = $value;
                   }
               }
                $attrNames[$attr["attrid"]] = $attr["name"];
            }
        }
        return ["attrList" => $attrList, "attrName" => $attrNames];
    }

    public function save()
    {
        try {

            $prodid = (int)input("prodid", 0);
            $cateIds = input("cateIds", 0);
            $prodname = input("prodname", '');
            $prod_features = input("prod_features", '');
            $prodcode = input("prodcode", '');
            $prod_price = input("prod_price", 0);
            $prod_list_price = input("prod_list_price", 0);
            $discount_price = input("discount_price", 0);
            $prod_desc = input("prod_desc", '');
            $void = (int) input("void", 0);
            $stock = input("stock", 0);
            $stock_warning = input("stock_warning", 0);
            $shipping_fee_type = input("shipping_fee_type", '');
            $fixed_shipping_fee = input("fixed_shipping_fee", 0);
            $sortorder = (int)input("sortorder", 0);
            $state = (int)input("state", 0);
            $seo_title = input("seo_title", '');
            $seo_keywords = input("seo_keywords", '');
            $seo_desc = input("seo_desc", '');
            $page_ids = input("page_ids/a");
            $video = input("video");
            $video_image = input("video_image");

            if (empty($cateIds)) {
                throw new Exception("所屬分類未勾選");
            }

            if (empty($prodname)) {
                throw new Exception("產品名稱不能為空");
            }

            if (mb_strlen($prodname) > 255) {
                throw new Exception("產品名稱不能超過255字元");
            }

            if (mb_strlen($prod_features) > 1000) {
                throw new Exception("產品特性不能超過1000字元");
            }

            if (empty($prodcode)) {
                throw new Exception("產品編號未填寫");
            }

            if (mb_strlen($prodcode) > 64) {
                throw new Exception("產品編號不能超過64字元");
            }

            if (!is_numeric($prod_price) || $prod_price < 0) {
                throw new Exception("銷售價格必須大於等於0");
            }

            if ($prod_price > 9999999999) {
                throw new Exception("銷售價格超出交易範圍");
            }

            if (!is_numeric($prod_list_price) || $prod_list_price < 0) {
                throw new Exception("零售價格必須大於等於0");
            }

            if ($prod_list_price > 9999999999) {
                throw new Exception("零售價格超出交易範圍");
            }

            if ($prod_list_price > 0 && $prod_list_price < $prod_price) {
                throw new Exception("零售價格必須大於銷售價");
            }

            if ($discount_price > 9999999999) {
                throw new Exception("零售價格超出交易範圍");
            }

            if ($discount_price > 0 && $discount_price > $prod_price) {
                throw new Exception("折後價必須小於銷售價");
            }

            if (mb_strlen($prod_desc) > 65535) {
                throw new Exception("詳細規格不能超過65535字元");
            }

            if (!is_numeric($stock) || $stock < 0 || floor($stock) != $stock) {
                throw new Exception("庫存數量無效");
            }

            if ($stock > 2100000000) {
                throw new Exception("庫存不能超過21億");
            }

            if (!is_numeric($stock_warning) || $stock_warning < 0 || floor($stock_warning) != $stock_warning) {
                throw new Exception("預警庫存數量無效");
            }

            if ($stock_warning > 2100000000) {
                throw new Exception("預警庫存不能超過21億");
            }

            if ($stock_warning>0 && $stock_warning >= $stock) {
                throw new Exception("預警庫存不能大於等於現有庫存");
            }

            if ($shipping_fee_type == "fixed" && (!is_numeric($fixed_shipping_fee) || $fixed_shipping_fee < 0 || $fixed_shipping_fee > 999999999)) {
                throw new Exception("固定運費類型請填寫正確運費金額");
            }

            if (!is_numeric($sortorder) || $sortorder < 0 || $sortorder > 2100000000) {
                throw new Exception("排序數字無效,請填寫整數0-21億之間的數字");
            }

            if (!in_array($state, [0, 1])) {
                throw new Exception("啟用狀態無效");
            }

            if (mb_strlen($seo_title) > 255) {
                throw new Exception("SEO標題不能超過255字元");
            }

            if (mb_strlen($seo_keywords) > 255) {
                throw new Exception("SEO關鍵字不能超過255字元");
            }

            if (mb_strlen($seo_desc) > 255) {
                throw new Exception("SEO描述不能超過255字元");
            }

            $data = [
                "prodname" => $prodname,
                "prod_features" => $prod_features,
                "prodcode" => $prodcode,
                "prod_price" => $prod_price,
                "prod_list_price" => $prod_list_price,
                "discount_price" => $discount_price,
                "prod_desc" => $prod_desc,
                "void" => $void,
                "stock" => $stock,
                "stock_warning" => $stock_warning,
                "shipping_fee_type" => $shipping_fee_type,
                "fixed_shipping_fee" => $fixed_shipping_fee,
                "sortorder" => $sortorder,
                "state" => $state,
                "seo_title" => $seo_title,
                "seo_keywords" => $seo_keywords,
                "seo_desc" => $seo_desc,
                "page_ids" => !empty($page_ids)?implode(",", $page_ids):'0',
                "update_at" => time(),
            ];

            Db::startTrans();
            if (is_numeric($prodid) && $prodid > 0) {

                //update product table
                if (false === Db::name(ProdModel::$tablename)->where("prodid", $prodid)->update($data)) {
                    throw new Exception("儲存失敗");
                }

            } else {

                if(Db::name(ProdModel::$tablename)->where("prodcode", $prodcode)->count()) {
                    throw new Exception("商品編號不能重複");
                }

                $data["create_at"] = time();
                $prodid = Db::name(ProdModel::$tablename)->insertGetId($data);
                if (!$prodid) {
                    throw new Exception("儲存失敗");
                }
            }

            //if there has new image upload, insert to image table

            $images = request()->post("images/a");
            if(!empty($images)) {
                if(false === Db::name(ProductImages::$tablename)->where("productid", $prodid)->delete()) {
                    throw new Exception("圖片儲存失敗");
                }
                $product_images = [];
                foreach($images as $k => $imagename) {

                    if(empty($totalImages) && !$k) {
                        $isMain = 1;
                    } else {
                        $isMain = 0;
                    }

                    $imagename = str_replace("\\", "/", $imagename);

                    $pathinfo = pathinfo($imagename);
                    $name = $pathinfo["filename"];

                    $product_images[] = [
                        "productid" => $prodid,
                        "image_file" => $imagename,
                        "image_thumb" => str_replace($name, $name."_thumb", $imagename),
                        "image_std" => str_replace($name, $name."_std", $imagename),
                        "image_tiny" => str_replace($name, $name."_tiny", $imagename),
                        "is_main" => $isMain,
                        "sortorder" => $k+1
                    ];
                }
                if(false === Db::name(ProductImages::$tablename)->insertAll($product_images)) {
                    throw new Exception("圖片儲存失敗");
                }
            }

            //video upload
            $videoRow = Db::name(ProductVideo::$tablename)->where("prodid", $prodid)->find();
            if(!empty($videoRow)) {
                $newVideo = [
                    "video_image" => $videoRow["video_image"],
                    "video_src" => $videoRow["video_src"]
                ];
            } else {
                $newVideo = [];
            }
            $newVideo["prodid"] = $prodid;
            if(!empty($video)) {
                $newVideo["video_src"] = $video;
            }

            if(!empty($video_image)) {
                $newVideo["video_image"] = $video_image;
            }

            if(!empty($newVideo)) {
                Db::name(ProductVideo::$tablename)->where("prodid", $prodid)->delete();
                Db::name(ProductVideo::$tablename)->insert($newVideo);
            }

            //delete unlock option
            if(false === Db::name(SearchAttries::$tablename)->where("productid", $prodid)->delete()) {
                throw new Exception("商品屬性儲存失敗");
            }

            //now update attr options
            $search_attr_options = request()->post("search_attr_options/a", []);
            if(!empty($search_attr_options)) {
                $option_data = [];
                foreach($search_attr_options as $attrid => $values) {
                    if(!empty($values)) {
                        foreach ($values as $sk => $optionvalueid) {
                            $option_data[] = [
                                "productid" => $prodid,
                                "attrid" => $attrid,
                                "valueid" => $optionvalueid,
                            ];
                        }
                    }
                }
                if (false === Db::name(SearchAttries::$tablename)->insertAll($option_data)) {
                    throw new Exception("商品屬性儲存失敗");
                }
            }

            //update combin data, it must has combin option data
            if(false === Db::name(VariationCombinations::$tablename)->where("vcproductid", $prodid)->update(["is_deleted" => 1])) {
                throw new Exception("商品規格儲存失敗");
            }
            $vsku = request()->post("vsku/a");
            $vcenabled = request()->post("vcenabled/a");
            $vstock = request()->post("vstock/a");
            $vprice = request()->post("vprice/a");
            $vstock_warning = request()->post("vstock_warning/a");
            if(!empty($vsku)) {
                foreach($vsku as $combin_str => $sku) {
                    $vcoptionids = trim($combin_str,",");
                    $uniq_combin_key = md5($prodid.$vcoptionids);
                    $combin_data = [
                        "vcproductid" => $prodid,
                        "vcenabled" => isset($vcenabled[$combin_str])?1:0,
                        "vcoptionids" => $vcoptionids,
                        "vcsku" => $sku,
                        "vcprice" => $vprice[$combin_str],
                        "vstock_warning" => $vstock_warning[$combin_str],
                        "vcstock" => $vstock[$combin_str],
                        "is_deleted" => 0,
                    ];
                    if(Db::name(VariationCombinations::$tablename)->where("uniq_combin_key", $uniq_combin_key)->count()) { //exist
                        if(false === Db::name(VariationCombinations::$tablename)->where("uniq_combin_key", $uniq_combin_key)->update($combin_data)) {
                            throw new Exception("商品規格存儲失敗");
                        }
                    } else {
                        $combin_data["uniq_combin_key"] = $uniq_combin_key;
                        if(false === Db::name(VariationCombinations::$tablename)->insert($combin_data)) {
                            throw new Exception("商品規格存儲失敗");
                        }
                    }
                }
            }

            if(false === Db::name(VariationCombinations::$tablename)->where("vcproductid", $prodid)->where("is_deleted", 1)->delete()) {
                throw new Exception("商品規格儲存失敗");
            }

            //if has category ass, del it first
            if (false === Db::name(CategoryProductAss::$tablename)->where("prodid", $prodid)->delete()) {
                throw new Exception("分類刪除失敗");
            }

            //add cate ass to table
            $cat_Ids = explode(",", $cateIds);
            $_catIds = array_unique($cat_Ids);
            if(empty($_catIds)) {
                throw new Exception("請選擇產品分類");
            }

            $catProdIs = [];
            foreach($_catIds as $k => $catid) {
                $catProdIs[] = [
                    "prodid" => $prodid,
                    "catid" => $catid
                ];
            }

            if (false === Db::name(CategoryProductAss::$tablename)->insertAll($catProdIs)) {
                throw new Exception("分類儲存失敗");
            }

            Db::commit();
            toJSON([
                "code" => 0,
                "msg" => "儲存成功",
                "url" => admin_link("Product/index")
            ]);
        } catch (Exception $ex) {
            Db::rollback();
            toJSON([
                "code" => 1,
                "msg" => $ex->getMessage()
            ]);
        }
    }

    public function delete()
    {
        try {


            $prodid = (int) input("prodid", 0);
            if(empty($prodid)) {
                throw new Exception("記錄無效");
            }

            Db::startTrans();

            //delete product
            if(false === Db::name(ProdModel::$tablename)->where("prodid", $prodid)->delete()) {
                throw new Exception("刪除產品基本資料失敗");
            }

            //delete image
            if(false === Db::name(ProductImages::$tablename)->where("productid", $prodid)->delete()) {
                throw new Exception("刪除產品圖片失敗");
            }

            //delete varation combinration
            if(false === Db::name(VariationCombinations::$tablename)->where("vcproductid", $prodid)->delete()) {
                throw new Exception("刪除產品規格失敗");
            }

            //delete category ass
            if(false === Db::name(CategoryProductAss::$tablename)->where("prodid", $prodid)->delete()) {
                throw new Exception("刪除產品關聯分類失敗");
            }

            Db::commit();
            toJSON([
                "code" => 0,
                "msg" => "刪除成功"
            ]);
        } catch (Exception $ex) {

            Db::rollback();
            toJSON([
                "code" => 1,
                "msg" => $ex->getMessage()
            ]);
        }
    }

    public function upload_image()
    {
        try {
            if(isset($_FILES['upload'])) {
                $file = request()->file('upload');
            } elseif(isset($_FILES['file'])) {
                $file = request()->file('file');
            }

            $savename = \think\facade\Filesystem::disk("public")->putFile( 'images', $file);
            $pathinfo = pathinfo($savename);
            $extension = $pathinfo["extension"];
            $name = $pathinfo["filename"];

            $imageObj = Image::open(getfile_realpath($savename));

            $image_std = "images/".date("Ymd")."/".$name."_std.".$extension;
            $image_std_file = getfile_realpath($image_std);
            $imageObj->thumb(1080, 1080)->save($image_std_file);

            $image_thumb = "images/".date("Ymd")."/".$name."_thumb.".$extension;
            $image_thumb_file = getfile_realpath($image_thumb);
            $imageObj->thumb(350, 350)->save($image_thumb_file);

            $image_tiny = "images/".date("Ymd")."/".$name."_tiny.".$extension;
            $image_tiny_file = getfile_realpath($image_tiny);
            $imageObj->thumb(100, 100)->save($image_tiny_file);

            toJSON([
                "fileName" => $file->getOriginalName(),
                "uploaded" => 1,
                "code" => 0,
                "saveName" => $savename,
                "url" => showfile($image_thumb),
            ]);
        } catch (Exception $ex) {
            toJSON([
                "fileName" => '',
                "uploaded" => 0,
                "code" => 1,
                "saveName" => "",
                "msg" => $ex->getMessage(),
                "url" => '',
            ]);
        }

    }

    private function getSelectOptions($selectedId=0)
    {
        $list = $this->getList();
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

    /**
     * get category data list
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function getList():array
    {
        $list = Db::name(CateModel::$tablename)->order("sortorder ASC")->select();
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

    public function saleReport()
    {
        $keyword = input("keyword");
        $start_date = input("start_date");
        $end_date = input("end_date");

        $where = [];
        $where[] = ["o.order_status", "=", 100];

        if(!empty($keyword)) {
            $where[] = ["p.prodname|p.prodcode", "LIKE", "%".$keyword."%"];
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
        ];

        $data["total"] = Db::table(Order::$tablename)->alias("o")
            ->join(OrderProduct::$tablename." op", "op.oid=o.oid")
            ->join(ProdModel::$tablename." p", "p.prodid=op.prodid")
            ->where($where)
            ->field("sum(op.qty) AS total, sum(op.total_amount) as totalAmount")
            ->find();

        $query = Db::table(Order::$tablename)->alias("o")
            ->join(OrderProduct::$tablename." op", "op.oid=o.oid")
            ->join(ProdModel::$tablename." p", "p.prodid=op.prodid")
            ->where($where)
            ->field("p.prodid, p.prodcode, p.prodname, sum(op.qty) AS total, sum(op.total_amount) as totalAmount")
            ->group("op.prodid")
            ->order("totalAmount DESC")
            ->paginate($params);

        $data["list"] = $query->all();
        $data["pages"] = $query->render();
        $data["keyword"] = $keyword;
        $data["start_date"] = $start_date;
        $data["end_date"] = $end_date;
        View::assign($data);
        return View::fetch("sale_report");
    }

    public function saleDetail()
    {
        $prodid = input("prodid");
        $start_date = input("start_date");
        $end_date = input("end_date");

        $where = [];
        $where[] = ["o.order_status", "=", 100];

        if(!empty($prodid)) {
            $where[] = ["p.prodid", "=", $prodid];
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
            "prodid" => $prodid,
        ];
        $query = Db::table(Order::$tablename)->alias("o")
            ->join(OrderProduct::$tablename." op", "op.oid=o.oid")
            ->join(ProdModel::$tablename." p", "p.prodid=op.prodid")
            ->where($where)
            ->field("op.*")
            ->paginate($params);

        $data["list"] = $query->all();
        $data["pages"] = $query->render();
        $data["prodid"] = $prodid;
        $data["start_date"] = $start_date;
        $data["end_date"] = $end_date;
        View::assign($data);
        return View::fetch("sale_detail");
    }

    public function import()
    {
        if(request()->isPost()) {
            $config = get_setting();
            $setting = $config["setting"];
            $fee = isset($setting["shipping"]["fee"])?$setting["shipping"]["fee"]:0;
            $fee = (int) trim($fee);

            try {
                $file = request()->file("product");
                $savename = \think\facade\Filesystem::disk("public")->putFile( 'import', $file);
                $filename = BASE_ROOT."/storage/".$savename;
                $reader = IOFactory::createReaderForFile($filename);
                $reader->setReadDataOnly(true);
                $spsheet = $reader->load($filename);
                $spsheet->setActiveSheetIndex(0);
                $list = $spsheet->getActiveSheet()->toArray();
                $suc = 0;
                $faild = 0;
                $jump = 0;
                $repeat = 0;

                if(isset($list[0])) { //第一行標題，跳過
                    $sheettitle = $list[0];
                    if(mb_stripos($sheettitle[0], "產品編號") === false) {
                        throw new Exception("文件格式錯誤,請仔細閱讀匯入畫面的提示01");
                    }
                    if(mb_stripos($sheettitle[1], "名稱") === false) {
                        throw new Exception("文件格式錯誤,請仔細閱讀匯入畫面的提示02");
                    }
                    if(mb_stripos($sheettitle[2], "產品簡介") === false) {
                        throw new Exception("文件格式錯誤,請仔細閱讀匯入畫面的提示03");
                    }
                    if(mb_stripos($sheettitle[3], "價") === false) {
                        throw new Exception("文件格式錯誤,請仔細閱讀匯入畫面的提示04");
                    }
                    if(mb_stripos($sheettitle[4], "價") === false) {
                        throw new Exception("文件格式錯誤,請仔細閱讀匯入畫面的提示05");
                    }
                    if(mb_stripos($sheettitle[5], "材質") === false) {
                        throw new Exception("文件格式錯誤,請仔細閱讀匯入畫面的提示06");
                    }
                    if(mb_stripos($sheettitle[6], "顏色") === false) {
                        throw new Exception("文件格式錯誤,請仔細閱讀匯入畫面的提示07");
                    }
                    if(mb_stripos($sheettitle[7], "尺寸") === false) {
                        throw new Exception("文件格式錯誤,請仔細閱讀匯入畫面的提示08");
                    }
                    if(mb_stripos($sheettitle[8], "長寬") === false) {
                        throw new Exception("文件格式錯誤,請仔細閱讀匯入畫面的提示09");
                    }
                    if(mb_stripos($sheettitle[9], "產品分類") === false) {
                        throw new Exception("文件格式錯誤,請仔細閱讀匯入畫面的提示10");
                    }

                    unset($list[0]);
                }
                if(!empty($list)) {
                    foreach($list as $prod) {
                        $sku = trim($prod[0]);
                        $prodname = trim($prod[1]);
                        $proddesc = trim($prod[2]);
                        $prod_list_price = trim($prod[3]);
                        $prod_price = trim($prod[4]);
                        $caizhi = trim($prod[5]);
                        $yanse = trim($prod[6]);
                        $chicun = trim($prod[7]);
                        $changkuan = trim($prod[8]);
                        $fenlei = trim($prod[9]);
                        if(empty($sku) || empty($prodname)) {
                            $jump++;
                            continue;
                        }

                        if(Db::name(ProdModel::$tablename)->where("prodcode", $sku)->count()) {
                            $repeat++;
                            continue;
                        }

                        $caizhiArr = [];
                        $yanseArr  =[];
                        $chicunArr = [];
                        $changkuanArr = [];
                        if(stripos($caizhi,",") !== false) {
                          $_caizhiArr = explode(",", $caizhi);
                          if(!empty($_caizhiArr)) {
                              foreach($_caizhiArr as $_caizhi) {
                                  $_caizhi = trim($_caizhi);
                                  if(!empty($_caizhi)) {
                                      $caizhiArr[] = $_caizhi;
                                  }
                              }
                          }
                        }

                        if(stripos($yanse,",") !== false) {
                            $_yanseArr = explode(",", $yanse);
                            if(!empty($_yanseArr)) {
                                foreach($_yanseArr as $_yanse) {
                                    $_yanse = trim($_yanse);
                                    if(!empty($_yanse)) {
                                        $yanseArr[] = $_yanse;
                                    }
                                }
                            }
                        }

                        if(stripos($chicun,",") !== false) {
                            $_chicunArr = explode(",", $chicun);
                            if(!empty($_chicunArr)) {
                                foreach($_chicunArr as $_chicun) {
                                    $_chicun = trim($_chicun);
                                    if(!empty($_chicun)) {
                                        $chicunArr[] = $_chicun;
                                    }
                                }
                            }
                        }

                        if(stripos($changkuan,",") !== false) {
                            $_changkuanArr = explode(",", $changkuan);
                            if(!empty($_changkuanArr)) {
                                foreach($_changkuanArr as $_changkuan) {
                                    $_changkuan = trim($_changkuan);
                                    if(!empty($_changkuan)) {
                                        $changkuanArr[] = $_changkuan;
                                    }
                                }
                            }
                        }

                        try {
                            Db::startTrans();

                            $caizhiTotal = count($caizhiArr);
                            $yanseTotal = count($yanseArr);
                            $chicunTotal = count($chicunArr);
                            $changkuanTotal = count($changkuanArr);
                            $attrvalues = [];
                            $attrvalues[0] = [];
                            $attrvalues[1] = [];
                            $attrvalues[2] = [];
                            $attrvalues[3] = [];
                            $void = 0;
                            if($caizhiTotal || $yanseTotal || $chicunTotal || $changkuanTotal) { //如果存在則需要增加屬性

                                 //建立規格關聯
                                 $void = Db::name(VariationOptions::$tablename)->where("vname", $sku)->value("void");
                                 if(empty($void)) {
                                     $voption_data = [
                                         "vname" => $sku
                                     ];
                                     $void = Db::name(VariationOptions::$tablename)->insertGetId($voption_data);
                                     if(!$void) {
                                         throw new Exception("建立規格失敗");
                                     }

                                     //建立規格名稱
                                     if(!empty($caizhiTotal)) {
                                         $attrvalues[0] = self::makeAttris($void, "材質", $caizhiArr);
                                     }

                                     if(!empty($yanseTotal)) {
                                         $attrvalues[1] = self::makeAttris($void, "顏色", $yanseArr);
                                     }

                                     if(!empty($chicunTotal)) {
                                         $attrvalues[2] = self::makeAttris($void, "尺寸", $chicunArr);
                                     }

                                     if(!empty($changkuanTotal)) {
                                         $attrvalues[3] = self::makeAttris($void, "長寬", $changkuanArr);
                                     }
                                 } else {
                                    $attris = Db::name(Attris::$tablename)->where("void", $void)->where("state", 1)->order("sortorder ASC")->select();
                                    foreach($attris as $_k => $v) {
                                        $list = Db::name(AttrValue::$tablename)->where("attrid", $v['attrid'])->order("sortorder ASC")->select();
                                        $valueIds = [];
                                        foreach($list as $v) {
                                            $valueIds[] = $v["valueid"];
                                        }
                                        $attrvalues[$_k] = $valueIds;
                                    }
                                 }

                            }

                            $_prod_desc = "";
                            if(!empty($caizhi)) {
                                $_prod_desc .= "<div class='detail_attris'>材質: ".$caizhi."</div>";
                            }
                            if(!empty($yanse)) {
                                $_prod_desc .= "<div class='detail_attris'>顏色: ".$yanse."</div>";
                            }
                            if(!empty($chicun)) {
                                $_prod_desc .= "<div class='detail_attris'>尺寸: ".$chicun."</div>";
                            }
                            if(!empty($changkuan)) {
                                $_prod_desc .= "<div class='detail_attris'>長寬: ".$changkuan."</div>";
                            }

                            $prod_desc = $_prod_desc.$proddesc;

                            //儲存產品基本信息
                            $productData = [
                                "prodcode" => $sku,
                                "prodname" => $prodname,
                                "prod_price" => $prod_price,
                                "prod_list_price" => $prod_list_price,
                                "prod_desc" => $prod_desc,
                                "stock" => 100,
                                "void" => $void,
                                "shipping_fee_type" => "fixed",
                                "fixed_shipping_fee" => $fee,
                                "sold_qty" => 0,
                                "state" => 0,
                                "update_at" => time(),
                                "create_at" => time()
                            ];

                            $prodid = Db::name(ProdModel::$tablename)->insertGetId($productData);
                            if(!$prodid) {
                                throw new Exception("商品基本資料儲存失敗");
                            }

                            $combinAttris = combineDika($attrvalues[0], $attrvalues[1], $attrvalues[2], $attrvalues[3]);

                            $combinData = [];
                            if(!empty($combinAttris)) {
                                foreach($combinAttris as $k => $voptions) {
                                    $voptionStr = implode(",", $voptions);
                                    $combinData[] = [
                                        "vcproductid" => $prodid,
                                        "vcenabled" => 1,
                                        "vcoptionids" => $voptionStr,
                                        "uniq_combin_key" => md5($prodid.$voptionStr),
                                        "vcprice" => $prod_price,
                                        "vcstock" => 100,
                                        "is_deleted" => 0
                                    ];
                                }

                                if(false === Db::name(VariationCombinations::$tablename)->insertAll($combinData)) {
                                    throw new Exception("商品規格組合失敗");
                                }

                            }

                            //關聯分類
                            if(!empty($fenlei)) {
                                  $cates = explode("/", $fenlei);
                                  $catIds = [];
                                  $pid = 0;
                                  foreach($cates as $k => $catName) {
                                      $findCatId = self::getSubCateId($pid, $catName);
                                      if(!empty($findCatId)) {
                                          $catIds[] = ["prodid" => $prodid, "catid" => $findCatId];
                                          $pid = $findCatId;
                                      }
                                  }

                                  if(!empty($catIds)) {
                                      if (false === Db::name(CategoryProductAss::$tablename)->insertAll($catIds)) {
                                          throw new Exception("分類關聯失敗");
                                      }
                                  }
                            }

                            Db::commit();
                            $suc++;
                        } catch (Exception $ex) {
                            $faild++;
                            Db::rollback();
                        }

                    }

                }

                toJSON([
                    "code" => 0,
                    "msg" => "匯入完成, 資料缺失(".$jump."), 重複(".$repeat."), 成功(".$suc."), 失敗(".$faild.")"
                ]);
            } catch (Exception $ex) {
                toJSON([
                    "code" => 1,
                    "msg" => $ex->getMessage()
                ]);
            }
        }
        return View::fetch();
    }

    private function getSubCateId($pid, $catname)
    {
        $catid = Db::name(CateModel::$tablename)->where("parentid", $pid)->where("catname", $catname)->value("catid");
        return $catid;
    }

    private function makeAttris($void, $name, $values)
    {
        $attrData = [
            "void" => $void,
            "name" => $name,
            "sortorder" => 1,
            "attrtype" => "text",
            "state" => 1
        ];
        $attrid = Db::name(Attris::$tablename)->insertGetId($attrData);
        if(!$attrid) {
            throw new Exception("建立規格失敗");
        }

        //建立規格值
        $attrValues = [];
        foreach ($values as $k => $Name) {
            $attrValues[] = [
                "attrid" => $attrid,
                "name" => $Name,
                "sortorder" => $k+1,
                "state" => 1,
            ];
        }
        if(false === Db::name(AttrValue::$tablename)->insertAll($attrValues)) {
            throw new Exception("建立規格選項失敗");
        }

        $list = Db::name(AttrValue::$tablename)->where("attrid", $attrid)->order("sortorder ASC")->select();
        $valueIds = [];
        foreach($list as $v) {
            $valueIds[] = $v["valueid"];
        }
        return $valueIds;
    }

    public function export()
    {
       $list = Db::name(ProdModel::$tablename)->select();
        $dataList = [];
        $dataList[] = [
            'RecId
商品條碼',
            'Bcode
商品條碼',
            'CommID
商品條碼',
            'CommName
商品品名',
            'Psale
價格',
            'PMemberSale',
            'GiftUnit',
            'Cunit 
商品單位',
            'DisCountRate',
            'ConvRate',
            'FlgTB',
            'QFactInv',
            'IsStock',
            'FlgCalMemberScore',
            'QRUDiscount',
            'FlgBatch',
            'SaleTaxRate',
            'FlgSaleRtn',
            'CommTypeID',
            'InvUnit',
            'FlgCalMemberRateToType',
            'PCSType',
            'MemberRate',
            'CounterActRate',
            'JJ_Price',
            'PSRate',
            'TAX_INDEX1',
            'TAX_INDEX2',
            'salespromotion',
            'Excludepayment',
            'ResTack',
            'FlgCounterAct',
            'availability',
            'DEP_ID',
            'TAX_INDEX',
            'HalfPrice',
            'QtrPrice',
            'TareWeight',
            'WeightUnit',
            'WarnStock',
            'RejiggerPrice'
        ];
       if(!empty($list)) {
           foreach($list as $prod) {
               $dataList[] = [
                   $prod["prodcode"],
                   $prod["prodcode"],
                   $prod["prodcode"],
                   $prod["prodname"],
                   $prod["prod_price"],
                   '',
                   '',
                   '件',
                   '',
                   '',
                   '',
                   '',
                   '',
                   '',
                   '',
                   '',
                   '',
                   '',
                   '',
                   '',
                   '',
                   '',
                   '',
                   '',
                   '',
                   '',
                   '',
                   '',
                   '',
                   '',
                   '',
                   '',
                   '',
                   '',
                   '',
                   '',
                   '',
                   '',
                   '',
                   '',
                   '',
               ];
           }
       }
        $excel = new Excel_XML('UTF-8', FALSE, '品項建檔');
        $filename = "品項建檔-吻銀企業社";
        $excel->addArray($dataList);
        $excel->generateXML($filename);
    }

    public function removeVideo() {
        try {
            $prodid = (int) input("prodid", 0);
            if(false === Db::name(ProductVideo::$tablename)->where("prodid", $prodid)->update(["video_src" => ""])) {
                throw new Exception("刪除影片失敗");
            }

            toJSON([
                "code" => 0,
                "msg" => "影片刪除成功"
            ]);
        } catch (Exception $ex) {
            toJSON([
                "code" => 1,
                "msg" => $ex->getMessage()
            ]);
        }
    }
    public function removeVideoImage() {
        try {
            $prodid = (int) input("prodid", 0);
            if(false === Db::name(ProductVideo::$tablename)->where("prodid", $prodid)->update(["video_image" => ""])) {
                throw new Exception("刪除影片封面失敗");
            }

            toJSON([
                "code" => 0,
                "msg" => "影片封面刪除成功"
            ]);
        } catch (Exception $ex) {
            toJSON([
                "code" => 1,
                "msg" => $ex->getMessage()
            ]);
        }
    }

}
