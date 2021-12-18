<?php
namespace app\models;

use think\facade\Db;

class CreditLog
{
    static $tablename = "customer_credit_logs";

    static function moneyToCredits($money)
    {
        $config = get_setting();
        $rate = isset($config["setting"]["creditsRate"]["moneyToCredit"])?$config["setting"]["creditsRate"]["moneyToCredit"]:0;
        return bcmul($money, $rate);
    }

    static function creditsToMoney($credits)
    {
        $config = get_setting();
        $rate = isset($config["setting"]["creditsRate"]["CreditToMoney"])?$config["setting"]["creditsRate"]["CreditToMoney"]:0;
        return bcmul($credits, $rate);
    }

    static function orderToCredit($orderAmount=0)
    {
        $config = get_setting();
        $rate = isset($config["setting"]["creditsRate"]["orderToCredit"])?$config["setting"]["creditsRate"]["orderToCredit"]:0;
        return bcmul($orderAmount, $rate);
    }
}