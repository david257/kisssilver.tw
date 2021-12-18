<?php

namespace ecpay;
use app\models\OrderProduct;
use think\facade\Request;
use think\facade\Db;
use think\Exception;

//載入SDK(路徑可依系統規劃自行調整)
include('ECPay.Logistics.Integration.php');

class ECPayExpress {
	
    private $senderName = "";
    private $senderPhone = "";
    private $senderMobile = "";
    private $senderZipCode = "";
    private $senderAddress = "";
    public function __construct() {

        $domain = request()->domain();

        $config = get_setting();
        $this->obj = new \ECPayLogistics();

        if(isset($config["setting"]["express"]["isProd"]) && $config["setting"]["express"]["isProd"]) {
            $isProd = 1;
        } else {
            $isProd = 0;
        }
        
        if($isProd) {
            $ServiceURL = "https://logistics.ecpay.com.tw";   //服務位置
        } else {
            $ServiceURL = "https://logistics-stage.ecpay.com.tw";   //服務位置
        }

        
        $this->obj->HashKey = $config["setting"]["express"]["hashkey"];                                           //測試用Hashkey，請自行帶入ECPay提供的HashKey
        $this->obj->HashIV = $config["setting"]["express"]["hashiv"];                                           //測試用HashIV，請自行帶入ECPay提供的HashIV
        $this->obj->MerchantID = $config["setting"]["express"]["merchantid"];                                                     //測試用MerchantID，請自行帶入ECPay提供的MerchantID
        


        $this->senderName = $config["setting"]["express"]["name"];
        $this->senderPhone = $config["setting"]["express"]["tel"];
        $this->senderMobile = $config["setting"]["express"]["mobile"];
        $this->senderZipCode = $config["setting"]["express"]["zipcode"];
        $this->senderAddress = $config["setting"]["express"]["address"];

        
        $this->ServiceURL = $ServiceURL;   //服務位置
        //門店返回數據
        $this->MapReplyURL = $domain."/ecpay_map.php";
        
        //非同步通知地址
        $this->ServerReplyURL = $domain.front_link("Ecpay/express_update");
        
        //此參數不為空時，當物流訂單建立後會將頁面導至此 URL。若要使用幕後建立訂單，此欄位請勿填寫。
        $this->ClientReplyURL = ""; //
        
        //當 User 選擇取貨門市有問題時，會透過此 URL 通知特店，請特店通知 User 重新選擇門市。物流子類型為 UNIMARTC2C(統一超商交貨便)時，此欄位不可為空。
        $this->LogisticsC2CReplyURL = $domain.front_link("Ecpay/express_c2c_reply");
    }
    
    //讀取發貨人信息
    public function getSenderInfo($oid)
    {
        return [
            "senderName" => $this->senderName,
            "senderPhone" => $this->senderPhone,
            "senderMobile" => $this->senderMobile,
            "senderZipCode" => $this->senderZipCode,
            "senderAddress" => $this->senderAddress,
        ];
    }
    
    //電子地圖
    public function map($LogisticsSubType)
    {
        try {
            $this->obj->Send = array(
                'MerchantID' => $this->obj->MerchantID,
                'MerchantTradeNo' => '', // 'no' . date('YmdHis'),
                'LogisticsSubType' => $LogisticsSubType,
                'IsCollection' => \IsCollection::NO,
                'ServerReplyURL' => $this->MapReplyURL,
                'ExtraData' => '',
                'Device' => \Device::PC
            );
            // CvsMap(Button名稱, Form target)
            $html = $this->obj->CvsMap('電子地圖');
            echo $html;
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }
    
    /*
    //一般物流訂單建立,直接產生HTML表單
    public function CvsCreateShippingOrder($order=[])
    {
        try {
            $this->obj->Send = array(
                'MerchantID' => $this->obj->MerchantID,
                'MerchantTradeNo' => $order["oid"],
                'MerchantTradeDate' => date('Y/m/d H:i:s'),
                'LogisticsType' => $order["LogisticsType"],
                'LogisticsSubType' => $order["LogisticsSubType"],
                'GoodsAmount' => (int) $order["total_amount"],
                'CollectionAmount' => (int) $order["total_amount"],
                'IsCollection' => \IsCollection::NO,
                'GoodsName' => '測試商品',
                'SenderName' => $this->senderName,
                'SenderPhone' => $this->senderPhone,
                'SenderCellPhone' => $this->senderMobile,
                'ReceiverName' => $order["shipping_name"],
                'ReceiverPhone' => $order["shipping_tel1"],
                'ReceiverCellPhone' => $order["shipping_mobile"],
                'ReceiverEmail' => $order["shipping_email"],
                'TradeDesc' => '',
                'ServerReplyURL' => $this->ServerReplyURL,
                'ClientReplyURL' => $this->ClientReplyURL,
                'LogisticsC2CReplyURL' => $this->LogisticsC2CReplyURL,
                'Remark' => '',
                'PlatformID' => '',
            );
            $this->obj->SendExtend = array(
                'ReceiverStoreID' => $order["CVSStoreID"],
                'ReturnStoreID' => '',
            );
            // CreateShippingOrder()
            $Result = $this->obj->CreateShippingOrder();
            echo '<pre>' . print_r($Result, true) . '</pre>';
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }
    */

    public function match_chinese($chars, $encoding='utf8') {
        $pattern = ($encoding=='utf8')?'/[\x{4e00}-\x{9fa5}a-zA-Z0-9_]/u':'/[\x80-\xFF]/';
        preg_match_all($pattern, $chars, $result);
        $temp = join('', $result[0]);
        return $temp;
    }

    public function GetGoodsName($oid)
    {
       $list = Db::name(OrderProduct::$tablename)->where("oid", $oid)->select();
       $goodsName = [];
       if(!empty($list)) {
           foreach($list as $v) {
               $optionStr = '';
               if(!empty($v["options"])) {
                  $voptions = json_decode($v["options"], true);
                  $vptionsStr = [];
                  if(!empty($voptions)) {
                      foreach($voptions as $k => $vp) {
                          $vptionsStr[] = $vp["attrname"].":".$vp["valuename"];
                      }
                  }
                  if(!empty($vptionsStr)) {
                      $optionStr = " (".implode(",",$vptionsStr).")";
                  }
               }
               $goodsName[] = $v["prodname"].$optionStr;
           }
       }
       return wordscut(self::match_chinese(implode(" ", $goodsName)), 25);
    }
    
    //超商取貨物流訂單幕後建立
    public function BGCvsCreateShippingOrder($order)
    {
        try {
            $sender = $this->getSenderInfo($order["oid"]);
            if(empty($sender)) {
                throw new Exception("未設置發貨人資訊");
            }

			$ReceiverPhone = $order["shipping_tel1"].$order["shipping_tel2"];
			if(strlen($order["shipping_tel2"]) != 8) {
				$ReceiverPhone ='';
			}

            $this->obj->Send = array(
                'MerchantID' => $this->obj->MerchantID,
                'MerchantTradeNo' => $order["oid"],
                'MerchantTradeDate' => date('Y/m/d H:i:s'),
                'LogisticsType' => $order["LogisticsType"],
                'LogisticsSubType' => $order["LogisticsSubType"],
                'GoodsAmount' => (int) $order["total_amount"],
                'CollectionAmount' => (int) $order["total_amount"],
                'IsCollection' => ($order["pay_type"]==100)?\IsCollection::YES:\IsCollection::NO,
                'GoodsName' => self::GetGoodsName($order["oid"]),
                'SenderName' => $sender["senderName"],
                'SenderPhone' => $sender["senderPhone"],
                'SenderCellPhone' => $sender["senderMobile"],
                'ReceiverName' => $order["shipping_name"],
                'ReceiverPhone' => '',
                'ReceiverCellPhone' => $order["shipping_mobile"],
                'ReceiverEmail' => $order["shipping_email"],
                'TradeDesc' => '',
                'ServerReplyURL' => $this->ServerReplyURL,
                'LogisticsC2CReplyURL' => $this->LogisticsC2CReplyURL,
                'Remark' => '',
                'PlatformID' => '',
            );


            $this->obj->SendExtend = array(
                'ReceiverStoreID' => $order["CVSStoreID"],
                'ReturnStoreID' => $order["CVSStoreID"]
            );
            $ret = $this->obj->BGCreateShippingOrder();
            $ret["err_code"] = 0;
            return $ret;
        } catch(Exception $e) {
            return [
                "err_code" => 300,
                "msg" => $e->getMessage()
            ];
        }
    }
    
    //宅配物流訂單幕後建立
    public function BGHomeCreateShippingOrder($order)
    {
        try {
            $sender = $this->getSenderInfo($order["oid"]);
            if(empty($sender)) {
                throw new Exception("未設置發貨人資訊");
            }

			$ReceiverPhone = $order["shipping_tel1"].$order["shipping_tel2"];
			if(strlen($order["shipping_tel2"]) != 8) {
				$ReceiverPhone ='';
			}

            $this->obj->Send = array(
                'MerchantID' => $this->obj->MerchantID,
                'MerchantTradeNo' => $order["oid"], //'no' . date('YmdHis'),
                'MerchantTradeDate' => date('Y/m/d H:i:s'),
                'LogisticsType' => \LogisticsType::HOME,
                'LogisticsSubType' => $order["LogisticsSubType"],
                'GoodsAmount' => (int) $order["total_amount"],
                'CollectionAmount' => (int) $order["total_amount"],
                'IsCollection' => \IsCollection::NO,
                'GoodsName' => self::GetGoodsName($order["oid"]),
                'SenderName' => $sender["senderName"],
                'SenderPhone' =>$sender["senderPhone"],
                'SenderCellPhone' => $sender["senderMobile"],
                'ReceiverName' => $order["shipping_name"],
                'ReceiverPhone' => '',
                'ReceiverCellPhone' => $order["shipping_mobile"],
                'ReceiverEmail' => $order["shipping_email"],
                'TradeDesc' => '',
                'ServerReplyURL' => $this->ServerReplyURL,
                'LogisticsC2CReplyURL' => $this->LogisticsC2CReplyURL,
                'Remark' => '',
                'PlatformID' => '',
            );

            $this->obj->SendExtend = array(
                'SenderZipCode' => $sender["senderZipCode"],
                'SenderAddress' => $sender["senderAddress"],
                'ReceiverZipCode' => $order["shipping_zipcode"],
                'ReceiverAddress' => $order["shipping_city"].$order["shipping_area"].$order["shipping_address"],
                'Temperature' => \Temperature::ROOM,
                'Distance' => \Distance::SAME,
                'Specification' => \Specification::CM_150,
                'ScheduledDeliveryTime' => \ScheduledDeliveryTime::TIME_17_20,
                'ScheduledDeliveryDate' => date("Y/m/d", strtotime("+3 day")),
            );

            $ret = $this->obj->BGCreateShippingOrder();
            $ret["err_code"] = 0;
            return $ret;
        } catch(Exception $e) {
            return [
                "err_code" => 300,
                "msg" => $e->getMessage()
            ];
        }
    }
    
    //B2c
    /*
    public function PrintTradeDoc($AllPayLogisticsID)
    {
        try {
            $this->obj->Send = array(
                'MerchantID' => $this->obj->MerchantID,
                'AllPayLogisticsID' => $AllPayLogisticsID,
                'PlatformID' => ''
            );
            // PrintTradeDoc(Button名稱, Form target)
            $html = $this->obj->PrintTradeDoc('產生托運單/一段標');
            echo $html;
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }
    */
    
    public function PrintTradeDoc($order=[])
    {
        try {
            

            if($order["LogisticsType"]=="CVS") {
                if($order["LogisticsSubType"] == "FAMIC2C") {
                    $this->obj->Send = array(
                        'MerchantID' => $this->obj->MerchantID,
                        'AllPayLogisticsID' => $order["AllPayLogisticsID"],
                        'CVSPaymentNo' => $order["CVSPaymentNo"],
                        'PlatformID' => ''
                    );
                    $html = $this->obj->PrintFamilyC2CBill('全家列印小白單(全家超商C2C)');
                } elseif($order["LogisticsSubType"] == "UNIMARTC2C") {
                    $this->obj->Send = array(
                        'MerchantID' => $this->obj->MerchantID,
                        'AllPayLogisticsID' => $order["AllPayLogisticsID"],
                        'CVSPaymentNo' => $order["CVSPaymentNo"],
                        'CVSValidationNo' => $order["CVSValidationNo"],
                        'PlatformID' => ''
                    );
                    // PrintUnimartC2CBill(Button名稱, Form target)
                    $html = $this->obj->PrintUnimartC2CBill('列印繳款單(統一超商C2C)');
                } elseif($order["LogisticsSubType"] == "HILIFEC2C") {
                    $this->obj->Send = array(
                        'MerchantID' => $this->obj->MerchantID,
                        'AllPayLogisticsID' => $order["AllPayLogisticsID"],
                        'CVSPaymentNo' => $order["CVSPaymentNo"],
                        'PlatformID' => ''
                    );
                    // PrintHiLifeC2CBill(Button名稱, Form target)
                    $html = $this->obj->PrintHiLifeC2CBill('萊爾富列印小白單(萊爾富超商C2C)');
                } else {
                    //throw new Exception("未知物流類型");
                    //B2C
                    $this->obj->Send = array(
                        'MerchantID' => $this->obj->MerchantID,
                        'AllPayLogisticsID' => $order["AllPayLogisticsID"],
                        'PlatformID' => ''
                    );
                    // PrintTradeDoc(Button名稱, Form target)
                    $html = $this->obj->PrintTradeDoc('產生托運單/一段標');
                }
            } else {
                $this->obj->Send = array(
                    'MerchantID' => $this->obj->MerchantID,
                    'AllPayLogisticsID' => $order["AllPayLogisticsID"],
                    'PlatformID' => ''
                );
                // PrintTradeDoc(Button名稱, Form target)
                $html = $this->obj->PrintTradeDoc('產生托運單/一段標');
            }
            echo $html;
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }
    
    // 取消訂單(統一超商C2C)
    /*public function CancelUnimartLogisticsOrder()
    {
        try {
            $AL = new ECPayLogistics();
            $AL->HashKey = '5294y06JbISpM5x9';
            $AL->HashIV = 'v77hoKGq4kWxNNIS';
            $AL->Send = array(
                'MerchantID' => '2000132',
                'AllPayLogisticsID' => '15474',
                'CVSPaymentNo' => 'F0015091',
                'CVSValidationNo' => '3207',
                'PlatformID' => ''
            );
            // CancelUnimartLogisticsOrder()
            $Result = $AL->CancelUnimartLogisticsOrder();
            echo '<pre>' . print_r($Result, true) . '</pre>';
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }*/
    
    // 宅配逆物流訂單建立
    public function CreateHomeReturnOrder($return_order=[])
    {
        try {
            $sender = $this->getSenderInfo($return_order["oid"]);
            if(empty($sender)) {
                throw new Exception("供應商未設置發貨人資訊");
            }
            $this->obj->Send = array(
                'MerchantID' => $this->obj->MerchantID,
                'AllPayLogisticsID' => $return_order["AllPayLogisticsID"],
                "LogisticsSubType" => $return_order["LogisticsSubType"],
                'SenderName' => $return_order["shipping_name"],
                'SenderPhone' => $return_order["shipping_tel1"].$return_order["shipping_tel2"],
                'SenderCellPhone' => $return_order["shipping_mobile"],
                'SenderZipCode' => $return_order["shipping_zipcode"],
                'SenderAddress' => $return_order["shipping_city"].$return_order["shipping_area"].$return_order["shipping_address"],
                'ReceiverName' => $sender["senderName"],
                'ReceiverPhone' => $sender["senderPhone"],
                'ReceiverCellPhone' => $sender["senderMobile"],
                'ReceiverEmail' => '',
                'ReceiverZipCode' => $sender["senderZipCode"],
                'ReceiverAddress' => $sender["senderAddress"],
                'ServerReplyURL' => $this->ServerReplyURL,
                'GoodsAmount' => (int) $return_order["total_amount"],
                'PlatformID' => '',
            );
            // CreateHomeReturnOrder()
            $ret = $this->obj->CreateHomeReturnOrder();
            $ret["err_code"] = 0;
            return $ret;
        } catch(Exception $e) {
            return [
                "err_code" => 300,
                "msg" => $e->getMessage()
            ];
        }
    }
    
    // 超商取貨逆物流訂單(全家超商B2C)
    public function CreateFamilyB2CReturnOrder($return_order)
    {
        try {
            $this->obj->Send = array(
                'MerchantID' => $this->obj->MerchantID,
                'AllPayLogisticsID' => $return_order["AllPayLogisticsID"],
                'ServerReplyURL' => $this->ServerReplyURL,
                'GoodsName' => '',
                'GoodsAmount' => (int) $return_order["total_amount"],
                'SenderName' => $return_order["shipping_name"],
                'SenderPhone' => $return_order["shipping_tel1"].$return_order["shipping_tel2"],
                'Remark' => '',
                'Quantity' => '',
                'Cost' => '',
                'PlatformID' => '',
            );
            // CreateFamilyB2CReturnOrder()
            $ret = $this->obj->CreateFamilyB2CReturnOrder();
            $ret["err_code"] = 0;
            return $ret;
        } catch(Exception $e) {
            return [
                "err_code" => 300,
                "msg" => $e->getMessage()
            ];
        }
    }
    
    // 超商取貨逆物流訂單(萊爾富超商B2C)
    public function CreateHiLifeB2CReturnOrder($return_order)
    {
        try {
            $this->obj->Send = array(
                'MerchantID' => $this->obj->MerchantID,
                'AllPayLogisticsID' => $return_order["AllPayLogisticsID"],
                'ServerReplyURL' => $this->ServerReplyURL,
                'GoodsName' => '',
                'GoodsAmount' => (int) $return_order["total_amount"],
                'SenderName' => $return_order["shipping_name"],
                'SenderPhone' => $return_order["shipping_tel1"].$return_order["shipping_tel2"],
                'Remark' => '',
                'Quantity' => '',
                'Cost' => '',
                'PlatformID' => '',
            );
            // CreateHiLifeB2CReturnOrder()
            $ret = $this->obj->CreateHiLifeB2CReturnOrder();
            $ret["err_code"] = 0;
            return $ret;
        } catch(Exception $e) {
            return [
                "err_code" => 300,
                "msg" => $e->getMessage()
            ];
        }
    }
    
    // 超商取貨逆物流訂單(統一超商B2C)
    public function CreateUnimartB2CReturnOrder($return_order)
    {
        try {
            $this->obj->Send = array(
                'MerchantID' => $this->obj->MerchantID,
                'AllPayLogisticsID' => $return_order["AllPayLogisticsID"],
                'ServerReplyURL' => $this->ServerReplyURL,
                'GoodsName' => '',
                'GoodsAmount' => (int) $return_order["total_amount"],
                'SenderName' => $return_order["shipping_name"],
                'SenderPhone' => $return_order["shipping_tel1"].$return_order["shipping_tel2"],
                'Remark' => '',
                'Quantity' => '',
                'Cost' => '',
                'PlatformID' => '',
            );
            // CreateUnimartB2CReturnOrder()
            $ret = $this->obj->CreateUnimartB2CReturnOrder();
            $ret["err_code"] = 0;
            return $ret;
        } catch(Exception $e) {
            return [
                "err_code" => 300,
                "msg" => $e->getMessage()
            ];
        }
    }
    
    // 物流狀態通知
    public function ServerReplyLogisticsStatus()
    {
        try {
            // 收到綠界科技的物流狀態，並判斷檢查碼是否相符
            //$this->obj->CheckOutFeedback($_POST);
            // 以物流狀態進行相對應的處理
            /** 
            回傳的綠界科技的物流狀態如下:
            Array
            (
                [AllPayLogisticsID] =>
                [BookingNote] =>
                [CheckMacValue] =>
                [CVSPaymentNo] =>
                [CVSValidationNo] =>
                [GoodsAmount] =>
                [LogisticsSubType] =>
                [LogisticsType] =>
                [MerchantID] =>
                [MerchantTradeNo] =>
                [ReceiverAddress] =>
                [ReceiverCellPhone] =>
                [ReceiverEmail] =>
                [ReceiverName] =>
                [ReceiverPhone] =>
                [RtnCode] =>
                [RtnMsg] =>
                [UpdateStatusDate] =>
            )
            */
            $data = [
                "AllPayLogisticsID" => $_POST["AllPayLogisticsID"],
                "LogisticsType1" => $_POST["LogisticsType"],
                "LogisticsType2" => $_POST["LogisticsSubType"],
                "LogisticsStatus" => $_POST["RtnCode"],
                "ShipmentNo" => "",
                "TradeDate" => strtotime($_POST["UpdateStatusDate"])
            ];
            if(!Db::name("express_status")->where($data)->count()) {
                Db::name("express_status")->insert($data);
            }
            // 在網頁端回應 1|OK
            echo '1|OK';
        } catch(Exception $e) {
            echo '0|' . $e->getMessage();
        }
    }
    
     // 逆物流狀態通知
    public function ServerReplyLogisticsStatusOfReturnOrder()
    {
         try {
            // 收到綠界科技的逆物流狀態，並判斷檢查碼是否相符
            $AL = new ECPayLogistics();
            $AL->HashKey = $this->obj->HashKey;
            $AL->HashIV = $this->obj->HashIV;
            $AL->CheckOutFeedback($_POST);
            // 以逆物流狀態進行相對應的處理
            /** 
            回傳的綠界科技的逆物流狀態如下:
            Array
            (
                [AllPayLogisticsID] =>
                [BookingNote] =>
                [CheckMacValue] =>
                [GoodsAmount] =>
                [MerchantID] =>
                [RtnCode] =>
                [RtnMerchantTradeNo] =>
                [RtnMsg] =>
                [UpdateStatusDate] =>
            )
            */
            // 在網頁端回應 1|OK
            echo '1|OK';
        } catch(Exception $e) {
            echo '0|' . $e->getMessage();
        }
    }
    
    // 物流訂單查詢
    public function QueryLogisticsInfo($AllPayLogisticsID)
    {
        try {
            $this->obj->Send = array(
                'MerchantID' => $this->obj->MerchantID,
                'AllPayLogisticsID' => $AllPayLogisticsID,
                'PlatformID' => ''
            );
            // QueryLogisticsInfo()
            $ret = $this->obj->QueryLogisticsInfo();
            $types = explode("_", $ret["LogisticsType"]);
            $data = [
                "AllPayLogisticsID" => $ret["AllPayLogisticsID"],
                "LogisticsType1" => $types[0],
                "LogisticsType2" => $types[1],
                "LogisticsStatus" => $ret["LogisticsStatus"],
                "TradeDate" => strtotime($ret["TradeDate"])
            ];

            if(!Db::name("express_status")->where($data)->count()) {
                $data["ShipmentNo"] = $ret["ShipmentNo"];
                Db::name("express_status")->insert($data);
            }
        } catch(Exception $e) {

        }
    }

}

?>