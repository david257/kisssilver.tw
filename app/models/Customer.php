<?php
namespace app\models;

use think\Exception;
use think\facade\Db;

class Customer
{
    static $tablename = "customers";

    static function add($data=[])
    {
        return Db::name(static::$tablename)->insert($data);
    }

    static function changeCredits($customerId, $amount, $ordno, $msg, $userid=0)
    {
        if(false === Db::name(static::$tablename)->where("customerid", $customerId)->inc("credits", $amount)->update()) {
            throw new Exception("變更紅利點數失敗");
        }

        $balance_amount = Db::name(static::$tablename)->where("customerid", $customerId)->value("credits");

        $creditlogs = [
            "customerid" => $customerId,
            "change_amount" => $amount,
            "balance_amount" => $balance_amount,
            "ip" => request()->ip(),
            "ordno" => $ordno,
            "msg" => $msg,
            "userid" => $userid,
            "create_at" => time()
        ];
        if(false === Db::name(CreditLog::$tablename)->insert($creditlogs)) {
            throw new Exception("紅利點數變動日誌寫入失敗");
        }
    }
}
