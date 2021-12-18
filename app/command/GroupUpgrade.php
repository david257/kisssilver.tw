<?php
declare (strict_types = 1);

namespace app\command;

use app\models\Customer;
use app\models\Order;
use think\console\Command;
use think\console\Input;

use think\console\Output;
use think\Exception;
use think\facade\Db;

class GroupUpgrade extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('groups')
            ->setDescription('會員等級升降級');
    }

    protected function execute(Input $input, Output $output)
    {

        try {
            $starttime = strtotime("-1 year");
            $list = Db::name(Order::$tablename)->where("order_status", 100)
                ->where("create_date", ">=", $starttime)
                ->field("customerid, SUM(total_amount) as totalAmount")
                ->group("customerid")
                ->select();
            $groups = getCustomerGroups();
            if (!empty($list)) {
                foreach ($list as $v) {
                    $_groupid = 0;
                    foreach ($groups as $group_id => $group) {
                        if ($v['totalAmount'] >= $group["order_amount"]) {
                            $_groupid = $group_id;
                        }
                    }

                    if (false === Db::name(Customer::$tablename)->where("customerid", $v["customerid"])->update(["group_id" => $_groupid])) {
                        throw new Exception($v["customerid"] . "升降級失敗");
                    }

                    echo $v["customerid"]."升降級成功\n";
                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage()."\n";
        }

        echo "customer upgrade complete\n";
    }

}
