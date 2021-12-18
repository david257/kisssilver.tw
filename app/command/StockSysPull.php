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

            //查詢沒有規格的商品
            $products = Db::name(Products::$tablename)->where("stock_sync_date", "<", time()-1200)->field("prodid, void, prodcode")->select();
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
                                echo $product["prodcode"]."->庫存(".$ret["qty"].")同步成功\n";
                            } else {
                                echo $product["prodcode"]."->".$ret["msg"] . "\n";
                            }
                        } else { //多規格
                            echo $product["prodcode"]."->查詢多規格\n";
                            $subProducts = Db::name("product_variation_combinations")->where("vcproductid", $product["prodid"])->select();
                            if(!empty($subProducts)) {
                                foreach($subProducts as $sproduct) {
                                    $surl = $setting["stocksys"]["stock_sync_url"] . $sproduct["vcsku"];
                                    $ret = static::syncStock($surl);
                                    if (!$ret["code"]) {
                                        if(false === Db::name("product_variation_combinations")->where("combinationid", $sproduct["combinationid"])->update(["vcstock" => $ret["qty"]])) {
                                            throw new Exception("庫存更新失敗");
                                        }
                                        echo $product["prodcode"].":".$sproduct["vcsku"]."->庫存(".$ret["qty"].")同步成功\n";
                                    } else {
                                        echo $product["prodcode"].":".$sproduct["vcsku"]."->".$ret["msg"] . "\n";
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
