<?php
namespace app\controller;
use app\BaseController;
use think\Exception;
use think\facade\Db;
use think\facade\Log;

class Ecpay extends BaseController
{
    
    public function _initialize() {
        parent::_initialize();
    }

    public function pay_result()
    {
        try {
            $data = $_REQUEST;
            Log::write(json_encode($data));
            if(empty($data)) {
                throw new Exception("訂單付款返回結果為空");
            }

            if(!$this->checkPay($data)) {
                throw new Exception("校驗失敗");
            }
            
            if(empty($data["TradeNo"])) {
                throw new Exception("付款未成功");
            }

            $update_data = [
                "PaymentDate" => $data["PaymentDate"],
                "PaymentType" => $data["PaymentType"],
                "PaymentTypeChargeFee" => $data["PaymentTypeChargeFee"],
                "RtnCode" => $data["RtnCode"],
                "RtnMsg" => $data["RtnMsg"],
                "TradeAmt" => $data["TradeAmt"],
                "TradeDate" => $data["TradeDate"],
                "TradeNo" => $data["TradeNo"],
                "CheckMacValue" => isset($data["CheckMacValue"])??'',
                "update_date" => time(),
            ];
            
            if($data["RtnCode"]==1) {
                $update_data["pay_status"] = 1;
                $update_data["order_status"] = 1;

				
            }

            $payoids = explode("V", $data["MerchantTradeNo"]);
            $oid = trim($payoids[0]);

            if(empty($oid) || !is_numeric($oid)) {
                throw new Exception("訂單號錯誤");
            }

            if(!Db::name("orders")->where("oid", $oid)->update($update_data)) {
                throw new Exception("訂單付款失敗");
            }

			send_order_notify('orderpaid', $oid, "訂單已付款");
            
            header("location:".front_link("Checkout/order_finish", ["payoid" => $data["MerchantTradeNo"]]));
        } catch(Exception $e) {
			Log::write($e->getMessage());
            exit($e->getMessage());
        }
    }
    
    //付款非同步通知
    public function sync_notice()
    {
       try {
            $data = $_REQUEST;
            Log::write(json_encode($data));
            if(empty($data)) {
                throw new Exception("訂單付款返回結果為空");
            }

           if(!$this->checkPay($data)) {
               throw new Exception("校驗失敗");
           }
            
            if(empty($data["TradeNo"])) {
                throw new Exception("付款失敗");
            }

           $payoids = explode("V", $data["MerchantTradeNo"]);
           $oid = trim($payoids[0]);
           if(empty($oid) || !is_numeric($oid)) {
               throw new Exception("訂單號錯誤");
           }
            
            $update_data = [
                "PaymentDate" => $data["PaymentDate"]??'',
                "PaymentType" => $data["PaymentType"]??'',
                "PaymentTypeChargeFee" => $data["PaymentTypeChargeFee"]??'',
                "RtnCode" => $data["RtnCode"]??'',
                "RtnMsg" => $data["RtnMsg"]??'',
                "TradeAmt" => $data["TradeAmt"]??'',
                "TradeDate" => $data["TradeDate"]??'',
                "TradeNo" => $data["TradeNo"]??'',
                "CheckMacValue" => isset($data["CheckMacValue"])??'',
                "update_date" => time(),
            ];

           if($data["RtnCode"]==1) {
                $update_data["pay_status"] = 1;
                $update_data["order_status"] = 1;
            }
            
            if(!Db::name("orders")->where("oid", $oid)->update($update_data)) {
                throw new Exception("訂單付款失敗");
            }
            
			send_order_notify('orderpaid', $oid, "訂單已付款");
            exit("1|OK");
        } catch(\Exception $e) {
            exit($e->getMessage());
        }
    }
    
    //電子地圖選中回傳
    public function express_update()
    {
        (new \ecpay\ECPayExpress())->ServerReplyLogisticsStatus();
    }

    /**
     * check if is change by hack
     * @param array $data
     * @return bool
     */
    private function checkPay($data=[])
    {
        $config = get_setting();
        $HashKey = $config["setting"]["pay"]["hashkey"];                                           //測試用Hashkey，請自行帶入ECPay提供的HashKey
        $HashIV = $config["setting"]["pay"]["hashiv"];
        $nowcheckvalue = md5($HashKey.$data['MerchantTradeNo'].$data['TradeAmt'].$HashIV);
        if($nowcheckvalue != $data['CustomField1']) {
            return false;
        }
        return true;
    }

    public function express_c2c_reply()
    {
        $ret = $_POST;
        $oid = isset($ret["MerchantTradeNo"])?$ret["MerchantTradeNo"]:'';
        $data = [
            "CVSRtnMsg" => isset($ret["RtnMsg"])?$ret["RtnMsg"]:'',
        ];

        if(!empty($oid)) {
            Db::name("orders")->where("oid", $oid)->update($data);
        }
    }
   
}
