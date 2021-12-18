<?php
namespace app\controller;

use app\BaseController;
use app\models\Attris;
use app\models\AttrValue;
use app\models\Products;
use app\models\Promotion;
use app\models\VariationCombinations;
use think\Exception;
use think\facade\Db;
use think\facade\Session;
use think\facade\View;

class Cart extends BaseController
{

    public function index()
    {
        $carts = Session::get("cart");
        $totalItems = 0;
        /*$subTotal = 0;
        if(!empty($carts)) {
            foreach($carts as $cartId => $cart) {
                $totalItems += $cart["qty"];
                $subTotal += $cart["prodprice"]*$cart["qty"];
            }
        }*/


        $data["carts"] = $carts;
        $data["viewed_items"] = Products::getRecentViewed();
        //promotion calc
        $promotion = Promotion::getPromotion();
        $data["totalItems"] = $promotion["totalItems"];
        $data["subTotal"] = $promotion["cart_total_amount"];
        $data["promotion_rules"] = $promotion["promotion_rules"];
        $data["total_shipping_fee"] = $promotion["total_shipping_fee"];
        $data["total_sub_amount"] = $promotion["total_sub_amount"];

        View::assign($data);
        return View::fetch();
    }



    /**
     * add to cart
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function save()
    {
        try {
            $prodid = (int) input("prodid", 0);
            $qty = (int) input("qty", 0);
            $voption = input("voption", "");
            $editCartId = input("cartId");

            if(empty($prodid) || $prodid<=0) {
                throw new Exception("商品無效");
            }

            if(empty($qty) || $qty<=0) {
                throw new Exception("請選擇購買數量");
            }

            $product = Db::name(Products::$tablename)->where("prodid", $prodid)->where("state", 1)->find();
            if(empty($product)) {
                throw new Exception("商品已下架");
            }

            if($product["stock"]<=0) {
                throw new Exception("庫存為0,無法購買");
            }

            if(empty($voption)) {
                //check stock at product
                if($product["stock"]<$qty) {
                    throw new Exception("庫存不足, 最多只能購買".$product["stock"]."件");
                }
                $prodPrice = $product["prod_price"];
                $sku = $product["prodcode"];
                $combinId = 0;
            } else {
                $voptions = explode(",", $voption);
				if(empty($voptions)) {
					throw new Exception("商品規格無效");
				}

				$where = [];
				foreach($voptions as $vk => $optionid) {
					$where[] = "FIND_IN_SET(".$optionid.", vcoptionids)";
				}

				$voptionRow = Db::name(VariationCombinations::$tablename)->where("vcproductid", $prodid)->whereRaw(implode(" AND ", $where))->find();
                if(empty($voptionRow)) {
                    throw new Exception("商品規格選擇無效");
                }

                if($voptionRow["vcstock"]<=0) {
                    throw new Exception("庫存為0, 無法購買");
                }

                if($voptionRow["vcstock"]<$qty) {
                    throw new Exception("庫存不足, 最多只能購買".$voptionRow["vcstock"]."件");
                }
                $prodPrice = $voptionRow["vcprice"];
                $sku = !empty($voptionRow["vcsku"])?$voptionRow["vcsku"]:$product["prodcode"];
                $combinId = $voptionRow["combinationid"];
            }

            $prodimage = Products::getMainImage($prodid);
            $voptionName = [];
            if(!empty($voption)) {
                $valueIds = explode(",", $voption);
                $list = Db::name(AttrValue::$tablename)->where("valueid", "IN", $valueIds)->select();
                if(!empty($list)) {
                    foreach($list as $v) {
                        $attrName = Db::name(Attris::$tablename)->where("attrid", $v["attrid"])->value("name");
                        $voptionName[] = [
                            "attrname" => $attrName,
                            "valuename" => $v["name"]
                        ];

                        if(!empty($v["imagefile"])) {
                            $prodimage = $v["imagefile"];
                        }
                    }
                }
            }

            $cartId = $prodid."_".$combinId;

            $carts = Session::get("cart");

            if(!empty($editCartId)) {
                unset($carts[$editCartId]);
            }

            if($prodPrice<=0) {
                throw new Exception("產品:".$product["prodname"]."售價必須大於0");
            }

            if(isset($carts[$cartId])) {
                $carts[$cartId]["qty"] += $qty;
            } else {
                $carts[$cartId] = [
                    "prodid" => $prodid,
                    "qty" => $qty,
                    "prodimage" => $prodimage,
                    "prodname" => $product["prodname"],
                    "prodprice" => $prodPrice,
                    "shipping_fee" => $product["fixed_shipping_fee"],
                    "prodcode" => $sku,
                    "voptions" => $voptionName,
                    "combinId" => $combinId
                ];
            }

            Session::set("cart", $carts);
            return json([
                "code" => 0,
                "msg" => "加入購物車成功",
                "url" => front_link("Cart/index")
            ]);
        } catch (Exception $ex) {
            toJSON([
                "code" => 1,
                "msg" => $ex->getMessage()
            ]);
        }

    }

    public function updateQty()
    {
        try {
            $cartId = input("cartId");
            $qty = (int) input("qty", 0);
            $carts = Session::get("cart");
            if($qty>0) {
                $carts[$cartId]["qty"] = $qty;
            }

            Session::set("cart", $carts);

            return json([
                "code" => 0,
                "msg" => "數量更新成功"
            ]);
        } catch (Exception $ex) {
            return json([
                "code" => 1,
                "msg" => $ex->getMessage()
            ]);
        }

    }

    public function delete()
    {
        $cartId = input("cartId");
        try {
            $carts = Session::get("cart");
            if(isset($carts[$cartId])) {
                unset($carts[$cartId]);
            }

            Session::set("cart", $carts);

            return json([
                "code" => 0,
                "msg" => "刪除成功"
            ]);
        } catch(Exception $ex) {
            return json([
                "code" => 0,
                "msg" =>  $ex->getMessage()
            ]);
        }
    }

    public function setLogisticsType()
    {
        try {
            $LogisticsType = input("LogisticsType");
            if(empty($LogisticsType)) {
                throw new Exception("請選擇配送方式");
            }

            Session::set("LogisticsType", $LogisticsType);
            return json([
                "code" => 0,
            ]);
        } catch (Exception $ex) {
            return json([
                "code" => 1,
                "msg" =>  $ex->getMessage()
            ]);
        }

    }

    public function get_logistics_subtype()
    {
        $LogisticsType = input("LogisticsType");
        if($LogisticsType=="HOME") {
            $types = LogisticsHomeSubTypes();
            $new_win  = 0;
        } elseif($LogisticsType=="CVS") {
            $types = LogisticsCVSSubTypes();
            $new_win  = 1;
        }elseif($LogisticsType=="SE") {
            $types = [];
            $new_win  = 0;
        }

        if(!empty($types)) {
            $options = '<option value="0">請選擇</option>';
            foreach ($types as $k => $v) {
                $options .= '<option rel="' . $new_win . '" value="' . $k . '">' . $v . '</option>';
            }
        } else {
            $options = '<option value="0">無</option>';
        }
        exit($options);
    }

}
