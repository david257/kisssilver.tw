<?php
declare (strict_types = 1);

namespace app\command;

use app\models\Order;
use app\models\Products;
use app\models\StockPushLogs;
use think\console\Command;
use think\console\Input;

use think\console\Output;
use think\Exception;
use think\facade\Db;
use think\facade\View;


class StockSysPull extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('StockSysPull')
            ->setDescription('商品庫存同步');
    }

    protected function execute(Input $input, Output $output)
    {
        self::pull();
        echo "stock pull compelate\n";
    }

    /**
     * save order to stock system
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function pull()
    {
        $config = get_setting();
        $setting = $config["setting"];
        try {

            if (!isset($setting["stocksys"]) && empty($setting["stocksys"])) {
                throw new Exception("未設置庫存系統參數，請前往控制台設置");
            }

            if (empty($setting["stocksys"]["stock_sync_url"])) {
                throw new Exception("未設置庫存同步網址");
            }

            $bactNo = date('YmdHis');
            //查詢沒有規格的商品
            $products = Db::name(Products::$tablename)->select();
            if(!empty($products)) {
                foreach($products as $product) {
                    try {
                        if (!$product["void"]) { //無規格
                            $url = $setting["stocksys"]["stock_sync_url"] . $product["prodcode"];
                            $ret = static::syncStock($url);
                            if (!$ret["code"]) {
                                if(false === Db::name(Products::$tablename)->where("prodid", $product["prodid"])->update(["stock" => $ret["qty"]])) {
                                    throw new Exception("庫存更新失敗");
                                }

                                if(!Db::name("stock_change_log")->where("sku", $product["prodcode"])->where("batch_no", $bactNo)->count()) {
                                    $stock_change_log = [
                                        "sku" => $product["prodcode"],
                                        "sync_before_qty" => $product["stock"],
                                        "sync_after_qty" => $ret["qty"],
                                        "batch_no" => $bactNo,
                                    ];
                                    Db::name("stock_change_log")->insert($stock_change_log);
                                }
                                echo $product["prodcode"]."->庫存(".$ret["qty"].")同步成功\n";
                            } else {
                                echo $product["prodcode"]."->".$ret["msg"] . "\n";
                            }
                        } else { //多規格
                            echo $product["prodcode"]."->查詢多規格\n";
                            $subProducts = Db::name("product_variation_combinations")->where("vcproductid", $product["prodid"])->select();
                            if(!empty($subProducts)) {
                                foreach($subProducts as $sproduct) {
                                    try {
                                        $surl = $setting["stocksys"]["stock_sync_url"] . $sproduct["vcsku"];
                                        $ret = static::syncStock($surl);
                                        if (!$ret["code"]) {
                                            if (false === Db::name("product_variation_combinations")->where("combinationid", $sproduct["combinationid"])->update(["vcstock" => $ret["qty"]])) {
                                                throw new Exception("庫存更新失敗");
                                            }
                                            if(!Db::name("stock_change_log")->where("sku", $sproduct["vcsku"])->where("batch_no", $bactNo)->count()) {
                                                $stock_change_log = [
                                                    "sku" => $sproduct["vcsku"],
                                                    "sync_before_qty" => $sproduct["vcstock"],
                                                    "sync_after_qty" => $ret["qty"],
                                                    "batch_no" => $bactNo,
                                                ];
                                                Db::name("stock_change_log")->insert($stock_change_log);
                                            }
                                            echo $product["prodcode"] . ":" . $sproduct["vcsku"] . "->庫存(" . $ret["qty"] . ")同步成功\n";
                                        } else {
                                            echo $product["prodcode"] . ":" . $sproduct["vcsku"] . "->" . $ret["msg"] . "\n";
                                        }
                                    } catch(Exception $ex) {
                                        echo $ex->getMessage()."\n";
                                    }
                                }
                            }
                            echo $product["prodcode"]."->查詢多規格結束\n";
                        }
                        if(false === Db::name(Products::$tablename)->where("prodid", $product["prodid"])->update(["stock_sync_date" => time()])) {
                            throw new Exception("庫存更新失敗");
                        }
                    } catch (Exception $ex) {
                        echo $product["prodcode"]."->庫存同步失敗".$ex->getLine()."\n";
                    }
                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage()."\n";
        }

        self::sendNotify();
    }

    private function sendNotify()
    {
        $list = Db::name("stock_change_log")
            ->where("notify_state", 0)
            ->where("sync_before_qty", 0)
            ->where("sync_after_qty", ">", 0)
            ->select();
        if(!empty($list)) {
            $prodCodes = [];
            $products = [];
            foreach($list as $l) {
                $prodCodes[] = $l["sku"];
                $products[$l["sku"]] = $l;
            }
            if(!empty($prodCodes)) {
                $productlist = Db::name("view_product_stocks")->where("prodcode|scode", "in", $prodCodes)->select();
                if (!empty($productlist)) {
                    foreach ($productlist as $prod) {
                        if (isset($products[$prod["prodcode"]])) {
                            $products[$prod["prodcode"]]["name"] = $prod["prodname"];
                        } elseif (isset($products[$prod["scode"]])) {
                            $products[$prod["scode"]]["name"] = $prod["prodname"];
                        }
                    }
                }

                $data['products'] = $products;
                View::assign($data);
                $html = View::fetch("notify/stock_change");
                send_stock_change_notify($html);
            }
        }
        echo "stock change notify compelate\n";
    }

    private static function syncStock($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if($httpCode==200) {
            $ret = json_decode($response,true);
            if($ret["retCode"]=="Success") {
                return [
                    "code" => 0,
                    "qty" => $ret["retObj"]
                ];
            } else {
                return [
                    "code" => 1,
                    "msg" => $ret["errMsg"]
                ];
            }
        } else {
            return [
                "code" => 1,
                "msg" => "ERP異常"
            ];
        }
    }

}
