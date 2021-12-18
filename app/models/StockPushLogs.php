<?php
namespace app\models;

use think\Exception;
use think\facade\Db;

class StockPushLogs
{
    static $tablename = "stock_push_logs";

    public static function sendOrderToStock($order=[])
    {
        try {
            $config = get_setting();
            $setting = $config["setting"];
            $data["PlatformName"] = $setting["site"]["title"];
            $data["Order_Number"] = $order["oid"];
            $data["Order_Amount"] = $order["total_amount"];
            $data["Order_Remark"] = $order["ordnote"];

            //提取商品信息
            $items = [];
            $products = Db::name(OrderProduct::$tablename)->where("oid", $order["oid"])->select();
            if (!empty($products)) {
                foreach ($products as $product) {

                    $premark = "";
                    if (!empty($product["options"])) {
                        $options = json_decode($product["options"], true);
                        $ops = [];
                        if (!empty($options)) {
                            foreach ($options as $kk => $opp) {
                                $ops[] = $opp["attrname"] . ":" . $opp["valuename"];
                            }
                        }
                        if (!empty($ops)) {
                            $premark = "(" . toJSON($ops, true) . ")";
                        }
                    }
                    $items[] = [
                        "Goods_ID" => $product["sku"],
                        "Unit_Price" => $product["prod_price"],
                        "Amount" => $product["qty"],
                        "Total" => $product["total_amount"],
                        "Remark" => $product["prodname"] . $premark
                    ];
                }
            }
            $data["Order_Item"] = $items;
            //提取發票信息
            //$customer = Db::name(Customer::$tablename)->where("customerid", $order["customerid"])->find();
            if (!empty($order["InvoiceNumber"])) {
                $data["Invoice"] = [
                    "InvoiceNumber" => $order["InvoiceNumber"],
                    "BuyerBAN" => "",//$customer["vipcode"],
                    "BuyerName" => "",//$order["billing_name"],
                    "CarrierId" => "",//$customer["invoice_code"],
                    "InvoiceDate" => !empty($order["InvoiceDate"]) ? mb_substr($order["InvoiceDate"], 0, 10) : "",
                    "NPOBAN" => "",//$order["donate_to"], //發票捐贈機構碼
                ];
            } else {
                $data["Invoice"] = null;
            }

            $pushJson = toJSON($data, true);
            $pushUrl = $setting["stocksys"]["url"] . "SaveWebOrder";
            $ret = static::httpPostJson($pushUrl, $pushJson);
            if (!is_array($ret)) {
                throw new Exception("請求遠端接口錯誤");
            }

            if(!isset($ret[0])) {
                throw new Exception("請求遠端接口錯誤");
            }

            if($ret[0] != 200) {
                throw new Exception("請求遠端接口錯誤");
            }

            if(!isset($ret[1])) {
                throw new Exception("請求遠端接口錯誤");
            }

            $retJsonArr = json_decode($ret[1], true);
            if(!is_array($retJsonArr)) {
                throw new Exception("請求返回無效");
            }

            $pushLog = [
                "push_state" => "send",
                "push_url" => $pushUrl,
                "push_data" => $pushJson,
                "ret_code" => $retJsonArr["retCode"],
                "ret_obj" => $ret[1],
                "err_msg" => $retJsonArr["errMsg"],
                "state" => 1,
                "update_time" => time()
            ];

            if(Db::name(StockPushLogs::$tablename)->where("oid", $order["oid"])->count()) {
                $ok = Db::name(StockPushLogs::$tablename)->where("oid", $order["oid"])->update($pushLog);
                var_dump($order["oid"]."更新->".$ok);
            } else {
                $pushLog["oid"] = $order["oid"];
                $ok = Db::name(StockPushLogs::$tablename)->insert($pushLog);
                var_dump($order["oid"]."插入->".$ok);
            }

        } catch (Exception $ex) {
            echo $ex->getMessage()."\n";
        }
    }


    /**
     * cancel order
     * @param array $order
     */
    public static function cancelOrderToStock($order=[])
    {
        try {
            $config = get_setting();
            $setting = $config["setting"];
            $data["PlatformName"] = $setting["site"]["title"];
            $data["Order_Number"] = $order["oid"];

            if(!Db::name(StockPushLogs::$tablename)->where("oid", $order["oid"])->count()) {
                throw new Exception("訂單".$order["oid"]."未推送，無需取消");
            }

            $pushJson = toJSON($data, true);
            $pushUrl = $setting["stocksys"]["url"] . "CancelWebOrder";
            $ret = static::httpPostJson($pushUrl, $pushJson);
            if (!is_array($ret)) {
                throw new Exception("請求遠端接口錯誤");
            }

            if (!isset($ret[0])) {
                throw new Exception("請求遠端接口錯誤");
            }

            if ($ret[0] != 200) {
                throw new Exception("請求遠端接口錯誤");
            }

            if (!isset($ret[1])) {
                throw new Exception("請求遠端接口錯誤");
            }

            $retJsonArr = json_decode($ret[1], true);
            if (!is_array($retJsonArr)) {
                throw new Exception("請求返回無效");
            }

            $pushLog = [
                "push_state" => "cancel",
                "push_url" => $pushUrl,
                "push_data" => $pushJson,
                "ret_code" => $retJsonArr["retCode"],
                "ret_obj" => $ret[1],
                "err_msg" => $retJsonArr["errMsg"],
                "state" => 1,
                "update_time" => time()
            ];

            if (Db::name(StockPushLogs::$tablename)->where("oid", $order["oid"])->count()) {
                $ok = Db::name(StockPushLogs::$tablename)->where("oid", $order["oid"])->update($pushLog);
                var_dump($order["oid"] . "更新->" . $ok);
            } else {
                $pushLog["oid"] = $order["oid"];
                $ok = Db::name(StockPushLogs::$tablename)->insert($pushLog);
                var_dump($order["oid"] . "插入->" . $ok);
            }
        } catch (Exception $ex) {
            echo $ex->getMessage()."\n";
        }
    }

    public static function httpPostJson($url, $jsonStr)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStr);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            'Content-Length: ' . strlen($jsonStr)

        ));
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return array($httpCode, $response);
    }
}