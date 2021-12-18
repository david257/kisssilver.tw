<?php
namespace app\controller;

use app\BaseController;
use app\extend\PHPTree;
use app\models\Category as CatModel;
use app\models\CategoryProductAss;
use app\models\ProductImages;
use app\models\Products;
use app\models\ProductVideo;
use app\models\VariationCombinations;
use app\models\VariationOptions;
use think\Exception;
use think\facade\Db;
use think\facade\Request;
use think\facade\Session;
use think\facade\View;

class Product extends BaseController
{

    public function detail()
    {
        try {

            $prodid = input("prodid", 0);
            if (empty($prodid)) {
                throw new Exception("產品無效");
            }

            $product = Db::name(Products::$tablename)->where("prodid", $prodid)->find();

			if(empty($product)) {
				throw new Exception("產品不存在");
			}

            $video = Db::name(ProductVideo::$tablename)->where("prodid", $prodid)->find();
            $product["video"] = $video["video_src"]??'';
            $product["video_image"] = $video["video_image"]??'';
			$_token = md5(request()->ip().'8888');

			$token = input('token');
			if(empty($token) ||  $token != $_token) {
				if(!$product['state']) {
					throw new Exception("產品不存在");
				}
			}

            $data["product"] = $product;
            $data["images"] = self::getImages($prodid);
            $data["related_items"] = self::relatedItems($prodid);
            $data["viewed_items"] = Products::getRecentViewed();
            if($product["void"]) {
                $data["voptions"] = VariationOptions::getAttris($product["void"]);
                $data["variation_combins"] = VariationCombinations::getAttris($prodid);
            } else {
                $data["voptions"] = [];
                $data["variation_combins"] = [];
            }
            $data["wishlists"] = getWishlistByCustomerId();

            $list = Db::name(CategoryProductAss::$tablename)->where("prodid", $product["prodid"])->select();
            $subcateId = 0;
            $level = 0;
            if(!empty($list)) {
                $catIds = [];
                foreach($list as $v) {
                    $catIds[] = $v["catid"];
                }

               $list = Db::name(CatModel::$tablename)->where("catid", "IN", $catIds)->select();
                if(!empty($list)) {
                    $cates = [];
                    foreach($list as $v) {
                        $cates[] = [
                            "id" => $v["catid"],
                            "name" => $v["catname"],
                            "parent_id" => $v["parentid"]
                        ];
                    }

                    if(!empty($cates)) {
                        $_cates = PHPTree::makeTreeForHtml($cates);
                        foreach ($_cates as $k => $v) {
                            if ($level <= $v["level"]) {
                                $level = $v["level"];
                                $subcateId = $v["id"];
                            }
                        }
                    }
                }
            }

            $breadcrumbs = $subcateId>0?CatModel::getBreadCrumbs($subcateId):[];
            if(!empty($breadcrumbs)) {
                $breadcrumbs = array_reverse($breadcrumbs);
            }
            $data["breadcrumbs"] = $breadcrumbs;

            $data["MetaTitle"] = !empty($product["seo_title"])?$product["seo_title"]:$product["prodname"];
            $data["MetaKeywords"] = !empty($product["seo_keywords"])?$product["seo_keywords"]:$product["prodname"];
            $data["MetaDesc"] = !empty($product["seo_desc"])?$product["seo_desc"]:$product["prodname"];

            self::setRecentViewed($prodid);
            View::assign($data);
            return View::fetch();
        } catch (Exception $ex) {
            abort(404, $ex->getMessage());
        }
    }

    public function editCart()
    {
        try {
            $prodid = input("prodid", 0);
            $cartId = input("cartId");
            if (empty($prodid)) {
                throw new Exception("產品無效");
            }

            $product = Db::name(Products::$tablename)->where("prodid", $prodid)->where("state", 1)->find();
            if(empty($product)) {
                throw new Exception("產品不存在");
            }

            $data["product"] = $product;
            $data["images"] = self::getImages($prodid);
            if($product["void"]) {
                $data["voptions"] = VariationOptions::getAttris($product["void"]);
                $data["variation_combins"] = VariationCombinations::getAttris($prodid);
            } else {
                $data["voptions"] = [];
                $data["variation_combins"] = [];
            }
            $data["cartId"] = $cartId;
            View::assign($data);
            $html = View::fetch("edit_cart");
            return [
                "code" => 0,
                "html" => $html
            ];
        } catch (Exception $ex) {
            return [
                "code" => 1,
                "html" => $ex->getMessage()
            ];
        }
    }

    public function getImages($prodid)
    {
        return Db::name(ProductImages::$tablename)->where("productid", $prodid)->order("sortorder ASC")->select();
    }

    /**
     * get reration product from same category
     * @param $prodid
     * @return array|void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function relatedItems($prodid)
    {
        $list = Db::name(CategoryProductAss::$tablename)->where("prodid", $prodid)->select();
        $catids = [];
        if(!empty($list)) {
            foreach($list as $v) {
                $catids[] = $v["catid"];
            }
        }

        $prod_ids = [];
        if(!empty($catids)) {
            $list = Db::name(CategoryProductAss::$tablename)->where("catid", "IN", $catids)->where("prodid", "<>", $prodid)->distinct("prodid")->limit(20)->select();
            if(!empty($list)) {
                foreach($list as $v) {
                    $prod_ids[] = $v["prodid"];
                }
            }
        }

        if(!empty($prod_ids)) {
            return Products::getProductsByIds($prod_ids);
        }
        return;
    }

    /**
     * you has viewed
     * @param $prodid
     * @return array
     */
    public function setRecentViewed($prodid)
    {
        $viewed_items = Session::get("viewed_items");
        if(empty($viewed_items)) {
            $viewed_items = [];
        }

        if(!empty($viewed_items)) {
            array_unshift($viewed_items, $prodid);
        } else {
            $viewed_items[] = $prodid;
        }

        return Session::set("viewed_items", $viewed_items);
    }

}
