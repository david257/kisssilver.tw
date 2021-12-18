<?php

namespace ecpay;
use app\models\Countries;
use app\models\Customer;
use app\models\Order;
use app\models\OrderProduct;
use think\facade\Db;
use EcpayInvoice as EI;
use think\Exception;

//載入SDK(路徑可依系統規劃自行調整)
include('Ecpay_Invoice.php');

class ECPayInvoice {

    public $obj;
    public $isProd;
    public function __construct() {
        $config = get_setting();
        if(isset($config["setting"]["invoice"]["isProd"]) && !empty($config["setting"]["invoice"]["isProd"])) {
            $this->isProd = 1;
        } else {
            $this->isProd = 0;
        }
        $this->obj = new EI();

		if($this->isProd) {
			$ServiceURL = "https://einvoice.ecpay.com.tw/Invoice/Issue";
		} else {
			$ServiceURL = "https://einvoice-stage.ecpay.com.tw/Invoice/Issue";
		}

        $this->obj->Invoice_Method = "INVOICE";
        $this->obj->Invoice_Url = $ServiceURL;   //服務位置
        $this->obj->HashKey = $config["setting"]["invoice"]["hashkey"];                                           //測試用Hashkey，請自行帶入ECPay提供的HashKey
        $this->obj->HashIV = $config["setting"]["invoice"]["hashiv"];                                           //測試用HashIV，請自行帶入ECPay提供的HashIV
        $this->obj->MerchantID = $config["setting"]["invoice"]["merchantid"];                                                     //測試用MerchantID，請自行帶入ECPay提供的MerchantID

    }

    public function makeInvoice($oid)
    {
        $order = Db::name(Order::$tablename)->where("oid", $oid)->find();
        $customer = Db::name(Customer::$tablename)->where("customerid", $order['customerid'])->find();
        if($order['invoice_zaiju'] == 'invoice_code') {
            $CarruerType = '3';
            $CarruerNum = $customer['invoice_code'];
            if(empty($CarruerNum)) {
                $CarruerType = "";
                $CarruerNum = "";
            }
        } elseif($order['invoice_zaiju'] == 'pc_code') {
            $CarruerType = '2';
            $CarruerNum = $customer['pc_code'];
            if(empty($CarruerNum)) {
                $CarruerType = "";
                $CarruerNum = "";
            }
        } else {
            $CarruerType = '';
            $CarruerNum = '';
        }

        $config = get_setting();
        $setting = $config['setting'];
        $taxtype = isset($setting["invoice"]["taxtype"])?$setting["invoice"]["taxtype"]:'';

        $donate_to = empty($order['donate_to'])?0:1;
        if($donate_to) { //捐贈出去
            $CarruerType = '';
            $CarruerNum = '';
        }

        if($order["invoice_type"] == 2) {
            if(!empty($CarruerType) || $donate_to) {
                $Print = 0;
            } else {
                $Print = 1;
				$CarruerType = "";
            }
        } else {
            $Print = 1;
			$CarruerType = "";
        }

        $city = Db::name(Countries::$tablename)->where("id", $customer['cityid'])->value("name");
        $area = Db::name(Countries::$tablename)->where("id", $customer['areaid'])->value("name");
        $address = $city.$area.$customer['address'];
        if(!empty($Print)) {
            if(empty($city) || empty($area) || empty($customer['address'])) {
                throw new Exception("客戶地址未填寫,請通知會員前往會員中心完善地址");
            }
        }

        $products = Db::name(OrderProduct::$tablename)->where("oid", $oid)->select();

        $total_item_amount = $order['total_item_amount'];
        $itemAmount = 0;
        if(!empty($products)) {
            foreach($products as $k => $prod) {
                $options = json_decode($prod["options"], true);
                $voptions = [];
                if(!empty($options)) {
                    foreach($options as $vk => $vopion) {
                        $voptions[] = $vopion["valuename"];
                    }
                }
                if(!empty($voptions)) {
                    $ItemRemark = implode(",", $voptions);
                } else {
                    $ItemRemark = '';
                }

                $itemAmount += $prod["total_amount"];
                $item = array(
                    'ItemName' => $prod["prodname"],
                    'ItemCount' => $prod['qty'],
                    'ItemWord' => '件',
                    'ItemPrice' => $prod["prod_price"],
                    'ItemTaxType' => 1,
                    'ItemAmount' => $prod["total_amount"]
                );
                if(!empty($ItemRemark)) {
                    $item["ItemRemark"] = $ItemRemark;
                }
                array_push($this->obj->Send['Items'], $item) ;
            }

            if($itemAmount>$total_item_amount) {
                $ItemPrice = $total_item_amount-$itemAmount;
                $item = array(
                    'ItemName' => "打折促銷",
                    'ItemCount' => 1,
                    'ItemWord' => '筆',
                    'ItemPrice' => $ItemPrice,
                    'ItemTaxType' => 1,
                    'ItemAmount' => $ItemPrice
                );
                array_push($this->obj->Send['Items'], $item) ;
            }
        }

		if($CarruerType == 1) {
			$CarruerNum = '';
		}

		if(empty($order['billing_mobile']) && empty($order['billing_email'])) {
            $order['billing_mobile'] = $customer["mobile"];
            $order['billing_email'] = $customer["custconemail"];
        }

        if(empty($order['billing_mobile']) && empty($order['billing_email'])) {
            throw new Exception("訂購人的手機號或者Email未填寫, 請通知訂購人到會員中心填寫再開票");
        }

        if($donate_to || !empty($CarruerType)) {
            $Print = 0;
        }

        $this->obj->Send['RelateNumber'] 			= $order["oid"];
        $this->obj->Send['CustomerID'] 			= $customer["vipcode"];
        $this->obj->Send['CustomerIdentifier'] 		= empty($order['invoice_no'])?'':$order['invoice_no'];
        $this->obj->Send['CustomerName'] 			= empty($order['invoice_no'])?$order['billing_name']:$order['invoice_header'];
        $this->obj->Send['CustomerAddr'] 			= $address;
        $this->obj->Send['CustomerPhone'] 			= $order['billing_mobile'];
        $this->obj->Send['CustomerEmail'] 			= $order['billing_email'];
        $this->obj->Send['ClearanceMark'] 			= '' ;
        $this->obj->Send['Print'] 				= $Print;
        $this->obj->Send['Donation'] 			= $donate_to;
        $this->obj->Send['LoveCode'] 			= empty($order['donate_to'])?'':$order['donate_to'];
        $this->obj->Send['CarruerType'] 			= $CarruerType;
        if(!empty($CarruerNum)) {
            //$this->obj->Send['CarruerNum'] = $CarruerNum;
        }
        $this->obj->Send['TaxType'] 			= $taxtype;
        $this->obj->Send['SalesAmount'] 			= $total_item_amount;
        $this->obj->Send['InvoiceRemark'] 			= '' ;
        $this->obj->Send['InvType'] 			= '07' ;
        $this->obj->Send['vat'] 				= '' ;

        $ret = $this->obj->Check_Out();
        return $ret;
    }

    public function notifyInvovice($InvoiceNo, $NotifyMail)
    {

        if($this->isProd) {
            $Invoice_Url = 'https://einvoice.ecpay.com.tw/Notify/InvoiceNotify';
        } else {
            $Invoice_Url = 'https://einvoice-stage.ecpay.com.tw/Notify/InvoiceNotify';
        }
        $ecpay_invoice = new EI();
        $ecpay_invoice->Invoice_Method 		= 'INVOICE_NOTIFY';
        $ecpay_invoice->Invoice_Url 		=  $Invoice_Url;
        $ecpay_invoice->MerchantID 		= $this->obj->MerchantID;
        $ecpay_invoice->HashKey 		= $this->obj->HashKey;
        $ecpay_invoice->HashIV 			= $this->obj->HashIV;

        $ecpay_invoice->Send['InvoiceNo'] 	= $InvoiceNo; 				// 發票號碼
        $ecpay_invoice->Send['NotifyMail'] 	= $NotifyMail; 			// 發送電子信箱
        $ecpay_invoice->Send ['Notify'] 	= 'E'; 			 			// 發送方式
        $ecpay_invoice->Send['InvoiceTag'] 	= 'I'; 					 	// 發送內容類型
        $ecpay_invoice->Send['Notified'] 	= 'C'; 					 	// 發送對象
        return $ecpay_invoice->Check_Out();
    }


}