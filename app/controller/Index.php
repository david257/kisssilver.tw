<?php
namespace app\controller;

use app\BaseController;
use app\models\Banner;
use app\models\ProductImages;
use app\models\Products;
use app\models\ProductVideo;
use think\facade\Db;
use think\facade\View;

class Index extends BaseController
{
    public function index()
    {
        $data["header_banners"] = self::getBanners("home", "header", 10);
        $data["header_four_banners"] = self::getBanners("home", "header_fou", 10);
        $data["header_three_banners"] = self::getBanners("home", "header_thr", 10);
        $data["header_two_banners"] = self::getBanners("home", "header_two", 10);
        $data["header_one_banners"] = self::getBanners("home", "header_one", 10);
        $data["middle_one_banner"] = self::getBanners("home", "middle_one", 1);
        $data["middle_two_banner"] = self::getBanners("home", "middle_two", 1);
        $data["new_products"] = self::getNewProducts();
        $data["hot_products"] = self::getProductsBy(["is_hot" => 1]);
        $data["sale_products"] = self::getProductsBy(["is_sale" => 1]);
        $data["wishlists"] = getWishlistByCustomerId();

        $config = get_setting();
        $setting = $config["setting"];
        $data["newtip"] = isset($setting["homeblock"]["new"])?$setting["homeblock"]["new"]:'';
        $data["hottip"] = isset($setting["homeblock"]["hot"])?$setting["homeblock"]["hot"]:'';
        $data["saletip"] = isset($setting["homeblock"]["sale"])?$setting["homeblock"]["sale"]:'';
        View::assign($data);
        return View::fetch();
    }

    /**
     * get banners
     * @param $page
     * @param $location
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function getBanners($page, $location, $limit=10)
    {
        return Db::name(Banner::$tablename)
            ->where("page", $page)
            ->where("location", $location)
            ->where("state", 1)
            ->order("sortorder ASC")
            ->limit($limit)
            ->select();
    }

    private function getNewProducts()
    {
        $where=[
            "state" => 1
        ];

        $list = Db::name(Products::$tablename)->where($where)->order("create_at DESC")->limit(8)->select();

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
        return $products;
    }

    private function getProductsBy($where=[])
    {
        $where["state"] = 1;

        $list = Db::name(Products::$tablename)->where($where)->order("sortorder ASC")->limit(8)->select();

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
        return $products;
    }


}
