<?php
namespace app\models;

use think\facade\Db;
use think\facade\Session;

class Products
{
    static $tablename = "products";

    /**
     * get product by ids
     * @param array $prod_ids
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    static function getProductsByIds($prod_ids=[])
    {
        if(empty($prod_ids)) return ;
        $where=[];
        $where[] = ["state", "=", 1];
        $where[] = ["prodid", "IN", $prod_ids];

        $list = Db::name(static::$tablename)->where($where)->limit(8)->select();

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

            if(!empty($products)) {
                $videos = Db::name(ProductVideo::$tablename)->where("prodid", "in", $prod_ids)->select();
                if (!empty($videos)) {
                    foreach ($videos as $video) {
                        $products[$video["prodid"]]["video"] = $video["video_src"];
                        $products[$video["prodid"]]["video_image"] = $video["video_image"];
                    }
                }
            }
        }
        return $products;
    }

    /**
     * get recent viewed
     * @return array
     */
    static function getRecentViewed()
    {
        $viewed_items = Session::get("viewed_items");
        if(empty($viewed_items)) {
            $viewed_items = [];
        }

        $viewedProducts = [];
        $viewed_items = array_unique($viewed_items);
        if(!empty($viewed_items)) {
            $viewedProducts = static::getProductsByIds($viewed_items);
        }
        return $viewedProducts;
    }

    static function getMainImage($prodid)
    {
        return Db::name(ProductImages::$tablename)->where("productid", $prodid)->where("is_main", 1)->value("image_thumb");
    }


}