<?php
namespace app\models;

use think\Exception;
use think\facade\Db;
use think\facade\Log;
use think\facade\Session;

class Promotion
{
    static $tablename = "promotions";

    static function getPromotion($options=[])
    {
        $all_promotions = get_promotions();
        //分開促銷類型
        $bindCatPromotions = [];
        $promotions = [];
        $promotionCates = [];
        if(!empty($all_promotions)) {
            foreach($all_promotions as $ptype => $promotion_rows) {
                foreach($promotion_rows as $promotionR) {
                    if(!empty($promotionR["bind_cat_ids"])) {
                        $bind_cat_ids = explode(",", $promotionR["bind_cat_ids"]);
                        foreach($bind_cat_ids as $k => $catId) {
                            $promotionCates[$catId][] = $promotionR['ptid'];
                        }

                        $bindCatPromotions[$promotionR['ptid']][$ptype][] = $promotionR;
                    } else {
                        $promotions[$ptype][] = $promotionR;
                    }
                }
            }
        }

        $all_carts = Session::get("cart");
		$config = get_setting();
		$setting = $config['setting'];
        $all_cart_total_amount = 0;
        $totalItems = 0;
        $cart_total_qty = 0;
        $cart_total_amount = 0;
        $carts = [];

        $bind_cart_total_qty = [];
        $bind_cart_total_amount = [];
        $bindCarts = [];

        $total_shipping_fee = (int) isset($setting['shipping']['fee'])?$setting['shipping']['fee']:0;


        if(!empty($all_carts)) {
            foreach ($all_carts as $cartId => $cart) {
                $all_cart_total_amount += $cart["qty"] * $cart["prodprice"]; //購物車總額
                $totalItems += $cart["qty"]; //購物車商品數量
                $prodCates = Db::name(CategoryProductAss::$tablename)->where("prodid", $cart["prodid"])->select();
                $promotionIds = [];
                if(!empty($prodCates)) {
                    foreach($prodCates as $pcate) {
                        if(isset($promotionCates[$pcate["catid"]])) {
                            $promotionIds = $promotionCates[$pcate["catid"]];
                            break;
                        }
                    }
                }

                if(empty($promotionIds)) {
                    $cart_total_qty += $cart["qty"];
                    $cart_total_amount += $cart["qty"] * $cart["prodprice"];
                    $carts[$cartId] = $cart;
                } else {
                    foreach($promotionIds as $promotionId) {
                        if (!isset($bind_cart_total_qty[$promotionId])) {
                            $bind_cart_total_qty[$promotionId] = 0;
                            $bind_cart_total_amount[$promotionId] = 0;
                        }
                        $bind_cart_total_qty[$promotionId] += $cart["qty"];
                        $bind_cart_total_amount[$promotionId] += $cart["qty"] * $cart["prodprice"];
                        $bindCarts[$promotionId][$cartId] = $cart;
                    }
                }
                //$total_shipping_fee += $cart["shipping_fee"]*$cart["qty"];
            }
        }

        //$originCartAmount = $cart_total_amount;

        $total_sub_amount  =0;
        $promotion_rules = [];
        $gifts = [];

        if(isset($options["pay_type"]) && !empty($options["pay_type"])) { //如果減了運費才需要判斷付款方式
            $state_freeshipping = (isset($setting['paytype_freeshipping'][$options["pay_type"]]) && $setting['paytype_freeshipping'][$options["pay_type"]])?1:0;
        } else {
            $state_freeshipping = 1;
        }

		$hasSubShippingFee = 0;
        if(!empty($promotions)) {
            foreach($promotions as $ptype => $promotion_list) {
                $sub_total = 0;
                foreach($promotion_list as $v) {
                    $promotion_title = "";
                    $gifts = [];
                    if($v["ptype"]=="byqtysub") {
                        if($cart_total_qty>=$v["total"]) {
                            $promotion_title = $v["title"];
                            $sub_total = $v["sub_total"];
                            break;
                        }
                    }elseif($v["ptype"]=="byqtysubpercent") {
                        if($cart_total_qty>=$v["total"]) {
                            $promotion_title = $v["title"];
                            $sub_total = round($cart_total_amount*((100-$v["sub_total"])/100));
                            break;
                        }
                    }elseif($v["ptype"]=="byqtyfreeshipping") {
                        if($cart_total_qty>=$v["total"] && $state_freeshipping) {
                            $promotion_title = $v["title"];
                            $sub_total = $total_shipping_fee;
							$hasSubShippingFee = 1;
                            break;
                        }
                    }elseif($v["ptype"]=="byamountsub") {
                        if($cart_total_amount>=$v["total"]) {
                            $promotion_title = $v["title"];
                            $sub_total = $v["sub_total"];
                            break;
                        }
                    }elseif($v["ptype"]=="byamountsubpercent") {
                        if($cart_total_amount>=$v["total"]) {
                            $promotion_title = $v["title"];
                            $sub_total = round($cart_total_amount*((100-$v["sub_total"])/100));
                            break;
                        }
                    }elseif($v["ptype"]=="byamountfreeshiping") {
                        if($cart_total_amount>=$v["total"] && $state_freeshipping) {
                            $promotion_title = $v["title"];
                            $sub_total = $total_shipping_fee;
							$hasSubShippingFee = 1;
                            break;
                        }
                    }elseif($v["ptype"]=="byamountgetgift") {
                        if($cart_total_amount>=$v["total"]) {
                            $promotion_title = $v["title"];
                            $sub_total = 0;
                            $giftList = Db::name(Gift::$tablename)->where("state", 1)->where("stock", ">", 0)->select();
                            if(!empty($giftList)) {
                                foreach ($giftList as $gv) {
                                    $gifts[$gv['giftid']] = $gv;
                                }
                            }
                            break;
                        }
                    }
                }

                if(!empty($promotion_title)) {
                    $promotion_rules[] = [
                        "title" => $promotion_title,
                        "amount" => $sub_total,
                        "gifts" => $gifts
                    ];
                    $total_sub_amount += $sub_total;
                }
            }
        }

        //滿X件第X件打Y折
        if(isset($promotions["overqtysubpercent"]) && !empty($promotions["overqtysubpercent"])) {
            $overqtys = array_sort_columns($promotions["overqtysubpercent"], "total");
            array_multisort($overqtys,SORT_DESC, $promotions["overqtysubpercent"]);
            foreach($promotions["overqtysubpercent"] as $overrule) {
                if($cart_total_qty>=$overrule["total"]) {

                    $cartprodprice = array_sort_columns($carts, "prodprice");
                    array_multisort($cartprodprice,SORT_ASC, $carts);

                    $lower_price = current($carts);

                    $product_price = isset($lower_price["prodprice"])?$lower_price["prodprice"]:0;
                    $sub_total = round($product_price*((100-$overrule["sub_total"])/100))*($cart_total_qty-$overrule["total"]+1);
                    $promotion_rules[] = [
                        "title" => $overrule["title"],
                        "amount" => $sub_total,
                        "gifts" => []
                    ];
                    $total_sub_amount += $sub_total;
                    break;
                }
            }
        }

        //特定分類打折開始
        if(!empty($bindCatPromotions)) {
            foreach ($bindCatPromotions as $ptId => $catPrmotion) {
                foreach ($catPrmotion as $ptype => $promotion_list) {
                    $sub_total = 0;
                    foreach ($promotion_list as $v) {
                        $promotion_title = "";
                        $gifts = [];
                        if ($v["ptype"] == "byqtysub") {
                            if (isset($bind_cart_total_qty[$ptId]) && $bind_cart_total_qty[$ptId] >= $v["total"]) {
                                $promotion_title = $v["title"];
                                $sub_total = $v["sub_total"];
                                break;
                            }
                        } elseif ($v["ptype"] == "byqtysubpercent") {
                            if (isset($bind_cart_total_qty[$ptId]) && $bind_cart_total_qty[$ptId] >= $v["total"]) {
                                $promotion_title = $v["title"];
                                $sub_total = round($bind_cart_total_amount[$ptId] * ((100 - $v["sub_total"]) / 100));
                                break;
                            }
                        } elseif ($v["ptype"] == "byqtyfreeshipping") {
                            if(!$hasSubShippingFee) { //不能重复减运费
                                if (isset($bind_cart_total_qty[$ptId]) && $bind_cart_total_qty[$ptId] >= $v["total"] && $state_freeshipping) {
                                    $promotion_title = $v["title"];
                                    $sub_total = $total_shipping_fee;
                                    $hasSubShippingFee = 1;
                                    break;
                                }
                            }
                        } elseif ($v["ptype"] == "byamountsub") {
                            if (isset($bind_cart_total_amount[$ptId]) && $bind_cart_total_amount[$ptId] >= $v["total"]) {
                                $promotion_title = $v["title"];
                                $sub_total = $v["sub_total"];
                                break;
                            }
                        } elseif ($v["ptype"] == "byamountsubpercent") {
                            if (isset($bind_cart_total_amount[$ptId]) && $bind_cart_total_amount[$ptId] >= $v["total"]) {
                                $promotion_title = $v["title"];
                                $sub_total = round($bind_cart_total_amount[$ptId] * ((100 - $v["sub_total"]) / 100));
                                break;
                            }
                        } elseif ($v["ptype"] == "byamountfreeshiping") {
                            if(!$hasSubShippingFee) { //不能重复减运费
                                if (isset($bind_cart_total_amount[$ptId]) && $bind_cart_total_amount[$ptId] >= $v["total"] && $state_freeshipping) {
                                    $promotion_title = $v["title"];
                                    $sub_total = $total_shipping_fee;
                                    $hasSubShippingFee = 1;
                                    break;
                                }
                            }
                        } elseif ($v["ptype"] == "byamountgetgift") {
                            if (isset($bind_cart_total_amount[$ptId]) && $bind_cart_total_amount[$ptId] >= $v["total"]) {
                                $promotion_title = $v["title"];
                                $sub_total = 0;
                                $giftList = Db::name(Gift::$tablename)->where("state", 1)->where("stock", ">", 0)->select();
                                if (!empty($giftList)) {
                                    foreach ($giftList as $gv) {
                                        $gifts[$gv['giftid']] = $gv;
                                    }
                                }
                                break;
                            }
                        }
                    }
                    if (!empty($promotion_title)) {
                        $promotion_rules[] = [
                            "title" => $promotion_title,
                            "amount" => $sub_total,
                            "gifts" => $gifts
                        ];
                        $total_sub_amount += $sub_total;
                    }
                }

                //滿X件第X件打Y折
                if (isset($catPrmotion["overqtysubpercent"]) && !empty($catPrmotion["overqtysubpercent"])) {
                    $overqtys = array_sort_columns($catPrmotion["overqtysubpercent"], "total");
                    array_multisort($overqtys, SORT_DESC, $catPrmotion["overqtysubpercent"]);
                    foreach ($catPrmotion["overqtysubpercent"] as $overrule) {
                        if (isset($bind_cart_total_qty[$ptId]) && $bind_cart_total_qty[$ptId] >= $overrule["total"]) {

                            $cartprodprice = array_sort_columns($bindCarts[$ptId], "prodprice");
                            array_multisort($cartprodprice, SORT_ASC, $bindCarts[$ptId]);

                            $lower_price = current($bindCarts[$ptId]);

                            $product_price = isset($lower_price["prodprice"]) ? $lower_price["prodprice"] : 0;
                            $sub_total = round($product_price * ((100 - $overrule["sub_total"]) / 100))*($bind_cart_total_qty[$ptId] - $overrule["total"]+1);
                            $promotion_rules[] = [
                                "title" => $overrule["title"],
                                "amount" => $sub_total,
                                "gifts" => []
                            ];
                            $total_sub_amount += $sub_total;
                            break;
                        }
                    }
                }
            }
        }
        //特定分類打折結束

        if(!$hasSubShippingFee && $total_shipping_fee>0) {
            if(isset($options["logisticstype"]) && $options["logisticstype"] == "SE") {
                $promotion_rules[] = [
                    "title" => "門市取貨0運費",
                    "amount" => $total_shipping_fee,
                    "gifts" => []
                ];
                $total_sub_amount += $total_shipping_fee;
            }
        }

        //會員等級折扣放的位置很重要
        $customer = getLoginCustomer();
        if(!empty($customer)) {
            $group = getCustomerGroups();
            if (isset($group[$customer["group_id"]])) {
                if($group[$customer["group_id"]]["discount"]<1) {
                    $discount = $group[$customer["group_id"]]["discount"];
                } else {
                    $discount = 0;
                }
                Log::write($total_sub_amount);
                $promotion_title = $group[$customer["group_id"]]['title']."會員享受".($discount*100)."折";
                $leftItemAmount = $all_cart_total_amount-$total_sub_amount;
                if($hasSubShippingFee) {
                    $leftItemAmount += $total_shipping_fee;
                }
                $sub_total = round($leftItemAmount*(1-$group[$customer["group_id"]]["discount"]));
                $promotion_rules[] = [
                    "title" => $promotion_title,
                    "amount" => $sub_total,
                    "gifts" => []
                ];
                $total_sub_amount += $sub_total;
            }
        }

        //優惠券使用
        $coupon_code = isset($options["coupon_code"])?$options["coupon_code"]:"";
        Log::write($coupon_code);
        if(!empty($coupon_code)) {
            $ccode = Db::name(Coupon::$tablename)
                ->where("code", $coupon_code)
                ->where("state", 1)
                ->find();
            $sub_total = $ccode['amount'];
            $promotion_rules[] = [
                "title" => '優惠券('.$ccode["title"].')',
                "amount" => $sub_total,
                "gifts" => []
            ];
            $total_sub_amount += $sub_total;
        }

        //折扣碼是否啟用
        $code = isset($options["code"])?$options["code"]:"";
        if(!empty($code)) {
            $ccode = Db::name(CouponCodes::$tablename)
                ->where("code", $code)
                ->where("state", 1)
                ->find();
            if(!empty($ccode)) {
                $promotion_title = $ccode["title"]."(".$ccode["code"].")";
                if ($all_cart_total_amount >= $ccode["total"]) {
                    if ($ccode["ptype"] == "byamountsub") {
                        $sub_total = $ccode["sub_total"];
                    } elseif ($ccode["ptype"] == "byamountsubpercent") {
                        $sub_total = round($all_cart_total_amount * ((100 - $ccode["sub_total"]) / 100));
                    } else {
                        $sub_total = 0;
                    }
                } else {Log::write("2222");
                    $sub_total = 0;
                }
                if ($sub_total > 0) {
                    $promotion_rules[] = [
                        "title" => $promotion_title,
                        "amount" => $sub_total,
                        "gifts" => []
                    ];
                    $total_sub_amount += $sub_total;
                }
            }
        }



        return [
            "total_sub_amount" => $total_sub_amount,
            "promotion_rules" => $promotion_rules,
            "totalItems" => $totalItems, //購物車商品數量
            "cart_total_amount" => $all_cart_total_amount, //購物車總額
            "total_shipping_fee" => $total_shipping_fee,
			"hasSubShippingFee" => $hasSubShippingFee
        ];
    }
}