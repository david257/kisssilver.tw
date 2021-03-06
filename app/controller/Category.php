<?php
namespace app\controller;

use app\BaseController;
use app\models\Attris;
use app\models\AttrValue;
use app\models\CategoryProductAss;
use app\models\Category as CatModel;
use app\models\ProductImages;
use app\models\Products;
use app\models\ProductVideo;
use app\models\SearchAttries;
use think\Exception;
use think\facade\Db;
use think\facade\View;

class Category extends BaseController
{

    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        View::assign("keyword", input("keyword"));
    }

    public function index()
    {
        try {
            $catid = input("catid", 0);
            $filter = input("filter","");
            if (empty($catid)) {
                throw new Exception("分類無效");
            }

            if(!empty($filter)) {
                $filter = explode("-", $filter);
            } else {
                $filter = [];
            }

            $category = Db::name(CatModel::$tablename)->where("catid", $catid)->where("state", 1)->find();
            if(empty($category)) {
                throw new Exception("分類不存在");
            }

            $attries = self::getAttries();
            $searchAttris = [];
            if(!empty($filter)) {
                foreach($attries["voptions"] as $attrid => $values) {
                    if(!empty($values)) {
                        foreach ($values as $k => $value) {
                            if (in_array($value["valueid"], $filter)) {
                                $searchAttris[$attrid][] = $value["valueid"];
                            }
                        }
                    }
                }
            }

            $checkedAttris = [];
            if(!empty($filter)) {
                $list = Db::name(AttrValue::$tablename)->where("valueid", "IN", $filter)->field("name")->select();
                if(!empty($list)) {
                    foreach($list as $v) {
                        $checkedAttris[] = $v["name"];
                    }
                }
            }

            //排序
            $sortby = input("sortby");
            if(empty($sortby)) {
                $orderBy = "sortorder ASC, create_at DESC";
            } else {
                $orderBy = str_replace(".", " ", $sortby);
            }

            $params = [
                "catid" => $catid,
                "filter" => !empty($filter)?implode("-", $filter):'',
                "sortby" => $sortby
            ];

            $where = [];
            $where[] = ["state", "=", 1];
            $where[] = ["as.catid", "=", $catid];

            $productIds = [];
            if(!empty($searchAttris)) {
                $map = [];
                foreach($searchAttris as $attrid => $valueIds) {
                    $map[] = ["attrid", "=", $attrid];
                    $map[] = ["valueid", "IN", $valueIds];
                }

                $list = Db::name(SearchAttries::$tablename)->where($map)->distinct("productid")->select();
                if(!empty($list)) {
                    foreach($list as $v) {
                        $productIds[] = $v["productid"];
                    }
                }
            }

            if(!empty($productIds)) {
                $where[] = ["p.prodid", "IN", $productIds];
            }

            $query = Db::table(config("database.prefix") . Products::$tablename)->alias("p")
                ->join(config("database.prefix") . CategoryProductAss::$tablename . " as", "as.prodid=p.prodid")
                ->where($where)
                ->field("p.*")
                ->order($orderBy)
                ->paginate(20);

            $list = $query->all();
            $products = [];
            $prod_ids = [];
            if(!empty($list)) {
                foreach($list as $v) {
                    $products[$v['prodid']] = $v;
                    $prod_ids[] = $v["prodid"];
                }
                $images = Db::name(ProductImages::$tablename)->where("productid", "IN", $prod_ids)->where("is_main", 1)->select();
                if(!empty($images)) {
                    foreach ($images as $image) {
                        $products[$image["productid"]]["image"] = showfile($image["image_thumb"]);
                    }
                }

                //get the second image
                $images = Db::name(ProductImages::$tablename)->where("productid", "IN", $prod_ids)->where("is_main", 0)->where("sortorder", 2)->select();
                if(!empty($images)) {
                    foreach ($images as $image) {
                        $products[$image["productid"]]["image2"] = showfile($image["image_thumb"]);
                    }
                }

                $videos = Db::name(ProductVideo::$tablename)->where("prodid", "in", $prod_ids)->select();
                if(!empty($videos)) {
                    foreach($videos as $video) {
                        $products[$video["prodid"]]["video"] = $video["video_src"];
                        $products[$video["prodid"]]["video_image"] = $video["video_image"];
                    }
                }
            }

            $data["list"] = $products;
            $data["pages"] = \app\extend\Page::make($query->currentPage(), $query->lastPage(), $params);
            $data["totalItems"] = $query->total();
            $data["totalpages"] = (int) $query->lastPage();
            $data["nextPage"] = front_link("index", $params);
            $data["category"] = $category;
            $data["sub_cates"] = self::getSubCats($catid);
            $data["catItems"] = self::calcCateProducts();
            $data["filterAttries"] = $attries;
            $data["attrItems"] = self::calcAttriItems($catid);
            $data["wishlists"] = getWishlistByCustomerId();
            $data["filter"] = $filter;
            $data["checkedAttris"] = $checkedAttris;
            $breadcrumbs = $catid>0?CatModel::getBreadCrumbs($catid):[];
            if(!empty($breadcrumbs)) {
                $breadcrumbs = array_reverse($breadcrumbs);
            }
            $data["breadcrumbs"] = $breadcrumbs;

            $data["MetaTitle"] = !empty($category["seo_title"])?$category["seo_title"]:$category["catname"];
            $data["MetaKeywords"] = !empty($category["seo_keywords"])?$category["seo_keywords"]:$category["catname"];
            $data["MetaDesc"] = !empty($category["seo_desc"])?$category["seo_desc"]:$category["catname"];

            View::assign($data);

            if(request()->isAjax()) {
                $tpl = "item";
            } else {
                $tpl  = "index";
            }

            return View::fetch($tpl);
        } catch (Exception $ex) {
            abort(404, $ex->getMessage());
        }
    }

    public function search()
    {
        try {
            $map = [];
            $keyword = input("keyword");
            $tag = input("tag");
            $map[] = ["state", "=", 1];
            if(!empty($keyword)) {
                $map[] = ["prodcode|prodname|prod_desc", "LIKE", "%".$keyword."%"];
            }

            $title = $keyword;
            if(!empty($tag)) {
                if (!in_array($tag, ['is_hot', 'is_sale', 'is_live'])) {
                    throw new Exception("頁面無效");
                }

                if($tag == "is_hot") {
                    $title = "熱銷商品";
                }
                if($tag == "is_sale") {
                    $title = "促銷商品";
                }
            }

            if(!empty($tag)) {
                $map[] = [$tag, "=", 1];
            }

            $params = [
                "keyword" => $keyword,
                "tag" => $tag
            ];

            $query = Db::table(config("database.prefix") . Products::$tablename)->alias("p")
                ->where($map)
                ->field("p.*")
                ->order("sortorder ASC, create_at DESC")
                ->paginate(20);

            $list = $query->all();
            $products = [];
            $prod_ids = [];
            if(!empty($list)) {
                foreach($list as $v) {
                    $products[$v['prodid']] = $v;
                    $prod_ids[] = $v["prodid"];
                }
                $images = Db::name(ProductImages::$tablename)->where("productid", "IN", $prod_ids)->where("is_main", 1)->select();
                if(!empty($images)) {
                    foreach ($images as $image) {
                        $products[$image["productid"]]["image"] = showfile($image["image_thumb"]);
                    }
                }

                //get the second image
                $images = Db::name(ProductImages::$tablename)->where("productid", "IN", $prod_ids)->where("is_main", 0)->where("sortorder", 2)->select();
                if(!empty($images)) {
                    foreach ($images as $image) {
                        $products[$image["productid"]]["image2"] = showfile($image["image_thumb"]);
                    }
                }

                $videos = Db::name(ProductVideo::$tablename)->where("prodid", "in", $prod_ids)->select();
                if(!empty($videos)) {
                    foreach($videos as $video) {
                        $products[$video["prodid"]]["video"] = $video["video_src"];
                        $products[$video["prodid"]]["video_image"] = $video["video_image"];
                    }
                }
            }

            $data["list"] = $products;
            $data["pages"] = \app\extend\Page::make($query->currentPage(), $query->lastPage(), $params);
            $data["totalItems"] = $query->total();
            $data["totalpages"] = (int) $query->lastPage();
            $data["nextPage"] = front_link("search", $params);
            $data["wishlists"] = getWishlistByCustomerId();
            $data["title"] = $title;
            View::assign($data);

            if(request()->isAjax()) {
                $tpl = "item";
            } else {
                $tpl  = "search";
            }

            return View::fetch($tpl);
        } catch (Exception $ex) {
            abort(404, $ex->getMessage());
        }
    }

    /**
     * get sub category
     * @param int $catid
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getSubCats($catid=0)
    {
        return Db::name(CatModel::$tablename)->where("parentid", $catid)->where("state", 1)->order("sortorder ASC")->select();
    }

    /**
     * calc category product qty
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function calcCateProducts()
    {
        $list = Db::table(config("database.prefix").CategoryProductAss::$tablename)->alias("as")
            ->join(config("database.prefix").Products::$tablename." p", "p.prodid=as.prodid")
            ->where("p.state", 1)
            ->field("as.catid, COUNT(distinct p.prodid) as t")
            ->group("as.catid")
            ->select();

        $catItems = [];
        if(!empty($list)) {
            foreach($list as $v) {
                $catItems[$v['catid']] = $v["t"];
            }
        }
        return $catItems;
    }

    /**
     * get filter attributes
     * @return array[]
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAttries()
    {
        $list = Db::table(config("database.prefix").Attris::$tablename)->alias("a")
            ->join(config("database.prefix").AttrValue::$tablename." av", "av.attrid=a.attrid")
            ->field("a.attrid, a.name, a.attrtype, av.valueid, av.name as avName")
            ->order("a.sortorder ASC, av.sortorder ASC")
            ->where("a.void", 0)
            ->select();
        $attries = [];
        $voptions = [];
        if(!empty($list)) {
            foreach($list as $v) {
                $attries[$v['attrid']] = [
                    "title" => $v["name"],
                    "attrtype" => $v["attrtype"],
                ];
                $voptions[$v['attrid']][] = [
                    "valueid" => $v["valueid"],
                    "name" => $v["avName"]
                ];
            }
        }

        return [
            "attries" => $attries,
            "voptions" => $voptions
        ];
    }

    public function calcAttriItems($catid)
    {
        $list = Db::table(config("database.prefix").CategoryProductAss::$tablename)->alias("as")
            ->join(config("database.prefix").Products::$tablename." p", "p.prodid=as.prodid")
            ->join(config("database.prefix").SearchAttries::$tablename." sa", "sa.productid=p.prodid")
            ->where("p.state", 1)
            ->where("as.catid", $catid)
            ->field("sa.valueid, COUNT(p.prodid) as t")
            ->group("sa.valueid")
            ->select();
        $attrItems = [];
        if(!empty($list)) {
            foreach($list as $v) {
                $attrItems[$v['valueid']] = $v["t"];
            }
        }
        return $attrItems;
    }


}
