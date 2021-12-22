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


class StockChangeNotify extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('StockChangeNotify')
            ->setDescription('商品庫存異動通知');
    }

    protected function execute(Input $input, Output $output)
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

}
