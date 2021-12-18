<?php
declare (strict_types = 1);

namespace app\command;

use app\extend\CouponCode;
use app\models\Coupon;
use app\models\CouponAutoRule;
use app\models\Customer;
use think\console\Command;
use think\console\Input;

use think\console\Output;
use think\facade\Db;

class SendCoupons extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('coupons')
            ->setDescription('自動發放優惠券');
    }

    protected function execute(Input $input, Output $output)
    {
        $autoCouponRule = Db::name(CouponAutoRule::$tablename)->where("auto_type", "birthday")->where("state", 1)->find();
        if(!empty($autoCouponRule)) {
            $maxdatetime = strtotime("+30 day");
            $mindatetime = time();

            $minyear = date("Y", $mindatetime);
            $minmonth = (int) date("m", $mindatetime);

            $maxyear = date("Y", $maxdatetime);
            $maxmonth = (int) date("m", $maxdatetime);
            $monthes = [];
            $years = [];
            $years[] = $minyear;
            if($maxyear>$minyear) {
                for($i=$minmonth; $i<=12; $i++) {
                    $monthes[] = $i;
                }

                for($i=1; $i<=$maxmonth; $i++) {
                    $monthes[] = $i;
                }

                $years[] = $maxyear;
            } else {
                for($i=$minmonth; $i<=$maxmonth; $i++) {
                    $monthes[] = $i;
                }
            }

            $list = Db::name(Customer::$tablename)->where("birth_year", "in", $years)->where("birth_month", "in", $monthes)->select();
            foreach ($list as $customer) {
                foreach($years as $k => $year) {
                    $coupon = Db::name(Coupon::$tablename)->where("customerid", $customer["customerid"])->where("coupon_type", "birth")->where("birthyear", $year)->find();
                    if (empty($coupon)) {
                        $code_dao = new CouponCode();
                        $data = [
                            "title" => $autoCouponRule["title"],
                            "customerid" => $customer["customerid"],
                            "coupon_type" => "birth",
                            "code" => $code_dao->encodeID(rand(1000000, 9999999)),
                            "min_amount" => $autoCouponRule["min_amount"],
                            "amount" => $autoCouponRule["amount"],
                            "start_time" => time(),
                            "end_time" => strtotime("+" . $autoCouponRule['expired_days'] . " day"),
                            "birthyear" => $year,
                            "create_time" => time(),
                        ];
                        $ret = Db::name("coupons")->insert($data);
                        var_dump($ret);
                    }
                }
            }
        }

        echo "send complete\n";
    }

}
