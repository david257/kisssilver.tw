<?php
declare (strict_types = 1);

namespace app\command;

use app\models\Order;
use app\models\StockPushLogs;
use think\console\Command;
use think\console\Input;

use think\console\Output;
use think\Exception;
use think\facade\Db;


class StockSysPush extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('StockSysPush')
            ->setDescription('訂單庫存管理系統同步');
    }

    protected function execute(Input $input, Output $output)
    {
        self::push();
        self::cancel();
        echo "stock pushed compelate\n";
    }

    /**
     * save order to stock system
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function push()
    {
        $config = get_setting();
        $setting = $config["setting"];
        try {

            if (!isset($setting["stocksys"]) && empty($setting["stocksys"])) {
                throw new Exception("未設置庫存系統參數，請前往控制台設置");
            }

            if (empty($setting["stocksys"]["url"])) {
                throw new Exception("未設置訂單接收網址");
            }

            if (!is_array($setting["stocksys"]["order_push_states"]) || empty($setting["stocksys"]["order_push_states"])) {
                throw new Exception("未設置滿足推送的訂單狀態");
            }

            //get push orders
            $orders = Db::table(Order::$tablename)->alias("o")
                ->leftJoin(StockPushLogs::$tablename . " spl", "spl.oid=o.oid")
                ->field("o.*, spl.state")
                ->where("o.order_status", "IN", $setting["stocksys"]["order_push_states"])
                ->where("spl.state IS NULL OR spl.state=0")
                ->limit(100)
                ->select();

            if (!empty($orders)) {
                foreach ($orders as $order) {
                    StockPushLogs::sendOrderToStock($order);
                }

            } else {
                echo "沒有符合要求的訂單需要推送至庫存系統\n";
            }
        } catch (Exception $ex) {
            echo $ex->getMessage()."\n";
        }
    }

    /**
     * cancel order
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function cancel()
    {
        $config = get_setting();
        $setting = $config["setting"];
        try {

            if (!isset($setting["stocksys"]) && empty($setting["stocksys"])) {
                throw new Exception("未設置庫存系統參數，請前往控制台設置");
            }

            if (empty($setting["stocksys"]["url"])) {
                throw new Exception("未設置訂單接收網址");
            }


            if (!is_array($setting["stocksys"]["order_cancel_states"]) || empty($setting["stocksys"]["order_cancel_states"])) {
                throw new Exception("未設置滿足取消的訂單狀態");
            }
            //get push orders
            $orders = Db::table(Order::$tablename)->alias("o")
                ->leftJoin(StockPushLogs::$tablename . " spl", "spl.oid=o.oid")
                ->field("o.*, spl.state")
                ->where("o.order_status", "IN", $setting["stocksys"]["order_cancel_states"])
                ->where("spl.state IS NULL OR spl.state=0")
                ->limit(1)
                ->select();

            if (!empty($orders)) {
                foreach ($orders as $order) {
                    StockPushLogs::cancelOrderToStock($order);
                }

            } else {
                echo "沒有符合要求的無效訂單需要推送至庫存系統\n";
            }
        } catch (Exception $ex) {
            echo $ex->getMessage()."\n";
        }
    }

}
