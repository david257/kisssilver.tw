<?php
use think\facade\Db;
use think\facade\Session;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

// app common function file


function remove_xss($val) {
    // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
    // this prevents some character re-spacing such as <java\0script>
    // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
    //$val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);

    // straight replacements, the user should never need these since they're normal characters
    // this prevents like <IMG SRC=@avascript:alert('XSS')>
    $search = 'abcdefghijklmnopqrstuvwxyz';
    $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $search .= '1234567890!@#$%^&*()';
    $search .= '~`";:?+/={}[]-_|\'\\';
    for ($i = 0; $i < strlen($search); $i++) {
        // ;? matches the ;, which is optional
        // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars

        // @ @ search for the hex values
        $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
        // @ @ 0{0,7} matches '0' zero to seven times
        $val = preg_replace('/(�{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
    }

    // now the only remaining whitespace attacks are \t, \n, and \r
    $ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'ilayer', 'layer', 'bgsound', 'title', 'base');
    $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
    $ra = array_merge($ra1, $ra2);

    $found = true; // keep replacing as long as the previous round replaced something
    while ($found == true) {
        $val_before = $val;
        for ($i = 0; $i < sizeof($ra); $i++) {
            $pattern = '/';
            for ($j = 0; $j < strlen($ra[$i]); $j++) {
                if ($j > 0) {
                    $pattern .= '(';
                    $pattern .= '(&#[xX]0{0,8}([9ab]);)';
                    $pattern .= '|';
                    $pattern .= '|(�{0,8}([9|10|13]);)';
                    $pattern .= ')*';
                }
                $pattern .= $ra[$i][$j];
            }
            $pattern .= '/i';
            $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
            $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
            if ($val_before == $val) {
                // no replacements were made, so exit the loop
                $found = false;
            }
        }
    }
    return $val;
}

// get top 10 news
function get_top_news($limit=10) {
   return Db::name(\app\models\News::$tablename)->where("state", 1)->order("newsid DESC")->limit($limit)->select();
}

function get_all_categories()
{
    $list = Db::name(\app\models\Category::$tablename)->where("state", 1)->order("sortorder ASC")->select();
    $categories = [];
    if(!empty($list)) {
        foreach($list as $v) {
            $categories[$v["parentid"]][] = $v;
        }
    }
    return $categories;
}

//get level one categories
function get_categories($parentid=0, $limit=10) {
   return Db::name(\app\models\Category::$tablename)->where("parentid", $parentid)->where("state", 1)->order("sortorder ASC")->limit($limit)->select();
}

function cart_qtys()
{
    $carts = Session::get("cart");
    $totalItems = 0;
    $subTotal = 0;
    if(!empty($carts)) {
        foreach($carts as $cartId => $cart) {
            $totalItems += $cart["qty"];
            $subTotal += $cart["prodprice"]*$cart["qty"];
        }
    }

    return $totalItems;
}

function wishlist_qtys()
{
    $customerId = Session::get("customerId");
    if(empty($customerId)) {
        return 0;
    }
    return Db::name(\app\models\Wishlist::$tablename)->where("customerid", $customerId)->count();
}

//get footer pages
function get_pages() {
   $list = Db::name(app\models\Page::$tablename)->where("state", 1)->order("sortorder ASC")->select();
   $pages = [];
   if(!empty($list)) {
       foreach($list as $v) {
           $pages[$v['parentid']][] = $v;
       }
   }
   return $pages;
}

function showfile($str) {
    $rootpath = config("filesystem.disks.public.url");
    return $rootpath."/".str_replace("\\", "/", $str);
}

function getfile_realpath($str) {
    $rootpath = config("filesystem.disks.public.root");
    return $rootpath."/".$str;
}

function get_attr_type($attrid) {
    return \app\models\Attris::getAttrTypeById($attrid);
}

function format_date($datetime, $dimiter="-") {
    if(empty($datetime)) return;
    return date("Y".$dimiter."m".$dimiter."d", $datetime);
}

function getMaxVipCode()
{
    //$customerId = Db::name(\app\models\Customer::$tablename)->max("customerid");
    //return (int) $customerId;
    return "VIP".rand(10000000,99999999);
}

function getLoginCustomer()
{
   $customerId = Session::get("customerId");
   return Db::name(\app\models\Customer::$tablename)->where("customerid", $customerId)->find();
}

function getWishlistByCustomerId()
{
    $customerId = Session::get("customerId");
    $wishlist = [];
    $list = Db::name(\app\models\Wishlist::$tablename)->where("customerid", $customerId)->select();
    if(!empty($list)) {
        foreach($list as $v) {
            $wishlist[] = $v["prodid"];
        }
    }
    return $wishlist;
}

function getCustomerGroups()
{
    $list = Db::name(\app\models\CustomerGroup::$tablename)->order("sortorder ASC")->select();
    $groups = [];
    if(!empty($list)) {
        foreach($list as $v) {
            $groups[$v["group_id"]] = $v;
        }
    }
    return $groups;
}


/**
 * show state
 * @param int $state
 */
function get_states($state=0) {
    $states = [
        "0" => "禁用",
        "1" => "啟用"
    ];
    return isset($states[$state])?$states[$state]:$states;
}

function get_shipping_types() {
    return [
        "calc" => ["title" => "按運費規則"],
        "fixed" => ["title" => "固定運費"],
        "freeshipping" => ["title" => "免運費"],
    ];
}

/**
 * @param $str
 * @param array $params
 * @return string
 */
function admin_link($str, $params=[]) {
    return url($str, $params)->build();
}

function front_link($str, $params=[]) {
    return str_replace('/admin/', '/', url($str, $params)->build());
}

/**
 * @param array $data
 * @param bool $isReturn
 * @return false|string
 */
function toJSON($data=[], $isReturn=false) {
    if($isReturn) {
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    exit(json_encode($data, JSON_UNESCAPED_UNICODE));
}

function price_label($prod=[]) {
    return 'NT.$ '.number_format($prod['prod_price']);
}

function price_label_list($prod=[]) {
    return 'NT.$ '.number_format($prod['prod_list_price']);
}

function wordscut($str, $len) {
    return mb_substr(strip_tags($str), 0, $len);
}

//below is new add funcs
function invoice_type() {
    return [
        //"1" => "發票捐贈",
        "2" => "二聯式發票",
        "3" => "三聯式發票"
    ];
}

function invoice_ship() {
    return [
        "1" => "發票隨貨附",
        "2" => "發票另寄地址",
    ];
}

function invoice_zaiju() {
    return [
        "invoice_code" => "手機條碼載具",
        "pc_code" => "自然人憑證載具"
    ];
}

//訂單狀態
function get_order_states() {
    return [
        "99" => "全部訂單",
        "0" => "訂單成立",
        "1" => "已付款",
        "3" => "訂單處理中",
        "2" => "<i class='blue'>等待取件</i>",
        "5" => "<i class='blue'>已出貨</i>",
        "100" => "<i class='green'>訂單完成</i>",
        //"-1" => "退貨申請",
        //"-5" => "退貨審核中",
        //"-10" => "退貨已受理",
       // "-20" => "退貨成功",
        "-50" => "訂單取消",
        "-100" => "訂單作廢",
    ];
}

//訂單狀態對應的郵件範本
function order_state_actions() {

    $email_temps = [
        "0" => [
            "title" => "訂單成立",
            "email_html"  => "/static/email/order_made.html",
        ],
        "1" => [
            "title" => "已付款",
            "email_html"  => "/static/email/order_paid.html",
        ],
        "3" => [
            "title" => "訂單處理中",
            "email_html"  => "/static/email/orderpadding.html",
        ],
        "5" => [
            "title" => "出貨通知",
            "email_html"  => "/static/email/order_shipped.html",
        ],
        "-20" => [
            "title" => "退貨成功",
            "email_html"  => "/static/email/order_returned.html",
        ],
        "-50" => [
            "title" => "訂單取消成功",
            "email_html"  => "/static/email/order_cancel.html",
        ],
        "regcomfirm" => [
            "title" => "郵件認證",
            "email_html"  => "/static/email/register_comfirm.html",
        ],
        "register" => [
            "title" => "註冊成功",
            "email_html"  => "/static/email/register_success.html",
        ],
        "forget_pass_active" => [
            "title" => "重置密碼",
            "email_html"  => "/static/email/forget_password_active.html",
        ],
        "forget_pass" => [
            "title" => "忘記密碼通知",
            "email_html"  => "/static/email/forget_password.html",
        ],

        "reply" => [
            "title" => "諮詢回覆",
            "email_html"  => "/static/email/reply.html",
        ],
		"nostock" => [
            "title" => "補貨通知-KissSilver.tw",
            "email_html"  => "/static/email/nostock.html",
        ],
		"ordercreate" => [
            "title" => "新訂單成立-KissSilver.tw",
            "email_html"  => "/static/email/ordernotify.html",
        ],
		"orderpaid" => [
            "title" => "訂單已付款-KissSilver.tw",
            "email_html"  => "/static/email/ordernotify.html",
        ],
        /*"has_stock" => [
            "title" => "到貨通知",
            "email_html"  => "/static/email/has_stock.html",
        ],*/
    ];

    $email_dbs = Db::name("email_templates")->select();
    if(!empty($email_dbs)) {
        foreach($email_dbs as $v) {
            if(isset($email_temps[$v["sku"]])) {
                $email_temps[$v["sku"]]["title"] = $v["subject"];
            }
        }
    }

    return $email_temps;
}

//促銷方式
function get_ptypes() {
    return [
        "byqtysub" => "滿<strong>X</strong>件減<strong>Y</strong>元",
		"byqtysubpercent" => "滿<strong>X</strong>件打<strong>Y</strong>折",
        "byamountsub" => "滿<strong>X</strong>元減<strong>Y</strong>元",
        "byamountsubpercent" => "滿<strong>X</strong>元打<strong>Y</strong>折",
        "byqtyfreeshipping" => "滿<strong>X</strong>件<strong>免運費</strong>",
        "byamountfreeshiping" => "滿<strong>X</strong>元<strong>免運費</strong>",
        "byamountgetgift" => "滿<strong>X</strong>元<strong>送贈品</strong>",
        "overqtysubpercent" => "第<strong>X</strong>件打<strong>X</strong>折（多件請設置多條，結算時只取滿足條件的那條）"
    ];
}

//折扣碼方式
function get_couponcodes_ptypes() {
    return [
        "byamountsub" => "滿<strong>X</strong>元減<strong>Y</strong>元",
        "byamountsubpercent" => "滿<strong>X</strong>元打<strong>Y</strong>折",
    ];
}

function array_sort_columns($input, $column_key=null, $index_key=null)
{
    $result = array();
    $i = 0;
    foreach ($input as $v)
    {
        $k = $index_key === null || !isset($v[$index_key]) ? $i++ : $v[$index_key];
        $result[$k] = $column_key === null ? $v : (isset($v[$column_key]) ? $v[$column_key] : null);
    }
    return $result;
}

//付款方式
function get_pay_types() {
    return [
        "-1" => [
            "title" => "紅利點數抵扣",
            "desc" => "",
            "type" => "",
            "min_amount" => 0,
            "max_amount" => 0,
            "state" => 0,
        ],

        "1" => [
            "title" => "ATM轉帳/臨櫃匯款",
            "desc" => "",
            "type" => "atm",
            "min_amount" => 10,
            "max_amount" => 2000000,
            "state" => 0,
        ],
        "2" => [
            "title" => "信用卡付款(一次付清)",
            "fenqi" => 0,
            "desc" => "",
            "type" => "credit",
            "min_amount" => 10,
            "max_amount" => 2000000,
            "state" => 0,
        ],
        "3" => [
            "title" => "信用卡分期付款(3期)",
            "fenqi" => 3,
            "desc" => "",
            "type" => "credit",
            "min_amount" => 30,
            "max_amount" => 20000,
            "state" => 0,
        ],
        "4" => [
            "title" => "信用卡分期付款(6期)",
            "fenqi" => 6,
            "desc" => "",
            "type" => "credit",
            "min_amount" => 30,
            "max_amount" => 20000,
            "state" => 0,
        ],
        "5" => [
            "title" => "貨到付款",
            "desc" => "",
            "type" => "cod",
            "min_amount" => 1,
            "max_amount" => 99999999999,
            "state" => 0,
        ],
        "6" => [
            "title" => "綠界支付",
            "desc" => "",
            "type" => "ALL",
            "min_amount" => 1,
            "max_amount" => 99999999999,
            "state" => 1,
        ],
        "20" => [
            "title" => "貨到付款",
            "desc" => "",
            "type" => "cod",
            "min_amount" => 1,
            "max_amount" => 99999999999,
            "state" => 1,
        ],
        "100" => [
            "title" => "超商取貨付款",
            "desc" => "",
            "type" => "cod",
            "min_amount" => 1,
            "max_amount" => 99999999999,
            "state" => 1,
        ],
        "105" => [
            "title" => "門市取貨付款",
            "desc" => "",
            "type" => "cod",
            "min_amount" => 1,
            "max_amount" => 99999999999,
            "state" => 1,
        ]
    ];
}

//配置物流對應的支付方式
function get_wuliu_paytypes()
{
    return [
        "HOME" => [6,20],
        "CVS" => [6,100],
        "SE" => [6,105]
    ];
}

//課稅類別
function get_tax_types()
{
    return [
        "1" => ["title" => "應稅", "SpecialTaxType" => 1],
        "2" => ["title" => "零稅率", "SpecialTaxType" => 1],
        "3" => ["title" => "免稅", "SpecialTaxType" => 8]
    ];
}

//獲取促銷模組
function get_promotions() {
    $rules = [];
    $list = Db::name("promotions")->where("start_date<=".time())->where("end_date>=".strtotime(date("Y-m-d")))->where("state", 1)->order("total DESC")->select();
    if(!empty($list)) {
        foreach($list as $v) {
            $rules[$v["ptype"]][] = $v;
        }
    }
    return $rules;
}

//生成唯一ID
function uuid() {
    $time = explode(" ", microtime());
    $micro_secs = $time[0]*100000000;
    return date("ymdHis", $time[1]).$micro_secs.rand(10000,99999);
}

/*
 * 密碼加密
 */
function password($str) {
    return md5($str);
}

/*
 * 根據許可權顯示OR隱藏節點
 */
function make_node($url="m/c/a", $var=[]) {
    $user = Session::get("user");
    if($user["username"] == "admin") { //特殊帳號不需要進行許可權判斷
        return admin_link($url, $var);
    }

    $node = Db::name("nodes")->where("url", $url)->find();
    if(!empty($node)) {
        if(!in_array($node["nodeid"], $user["limits"])) {
            return;
        }
    }
    return admin_link($url, $var);
}

/*
 * 判斷節點許可權
 */
function auth_node($pid=0, $url="m/c/a") {
    $admin_user = Session::get("admin_user");
    if(in_array($admin_user["username"], Config::get("unlimits_user"))) { //特殊帳號不需要進行許可權判斷
        return true;
    }

    $node = think\Db::name("nodes")->where("pid", $pid)->where("url", $url)->find();
    if(!empty($node)) {
        if(!in_array($node["nodeid"], $admin_user["limits"])) {
            return false;
        }
    }
    return true;
}

function get_total_points(){
    $user_info = session('user_info');
    if(empty($user_info) || !isset($user_info['user_id']) || empty($user_info['user_id'])){
        return -1;
    }

    $user_id = $user_info['user_id'];
    $account = new AccountUser();
    $account_user_info = $account->getInfoByUserId($user_id);
    if(empty($account_user_info) || !isset($account_user_info['total_points'])){
        return 0;
    }

    return $account_user_info['total_points'];
}

function express_coms()
{
    return $express_coms = [
        '0' => '無',
    ];
}

function send_stock_notify($content) {

	$order_action = order_state_actions();
    if(isset($order_action["nostock"])) {
        $source = [
            "{content}",
        ];

        $replace = [
            $content,
        ];

        $html_template = dirname(__FILE__)."/../public".$order_action["nostock"]["email_html"];
        if(!is_file($html_template)) {
            return false;
        }

        $html = file_get_contents($html_template);
        $body = str_replace($source, $replace, $html);
		$config = get_setting();
		$emailStr = $config['setting']['notify']['nostock'];
		$emails = explode(",", $emailStr);
		$_emails = [];
		if(!empty($emails)) {
			foreach($emails as $k => $email) {
				$email = trim($email);
				if(!empty($email)) {
					$_emails[] = $email;
				}
			}
		}

		$revEmail = isset($_emails[0])?$_emails[0]:'';

        $revs = [
            $revEmail,
        ];

		unset($_emails[0]);
		$ccs = $_emails;

		if(!empty($revEmail)) {
			send_email($order_action["nostock"]["title"], $body, "", $revs, $ccs);
		}
    }
}

function send_order_notify($emailTpl, $oid, $status) {

	$order_action = order_state_actions();
    if(isset($order_action[$emailTpl])) {
        $source = [
            "{oid}",
			"{status}",
        ];

        $replace = [
            $oid,
			$status
        ];

        $html_template = dirname(__FILE__)."/../public".$order_action[$emailTpl]["email_html"];
        if(!is_file($html_template)) {
            return false;
        }

        $html = file_get_contents($html_template);
        $body = str_replace($source, $replace, $html);
		$config = get_setting();
		$emailStr = $config['setting']['notify']['order'];
		$emails = explode(",", $emailStr);
		$_emails = [];
		if(!empty($emails)) {
			foreach($emails as $k => $email) {
				$email = trim($email);
				if(!empty($email)) {
					$_emails[] = $email;
				}
			}
		}

		$revEmail = isset($_emails[0])?$_emails[0]:'';

        $revs = [
            $revEmail,
        ];

		unset($_emails[0]);
		$ccs = $_emails;

		if(!empty($revEmail)) {
			send_email($order_action[$emailTpl]["title"], $body, "", $revs, $ccs);
		}
    }
}

function send_passwd_comfirm($fullname, $email, $code) {
    $order_action = order_state_actions();
    if(isset($order_action["forget_pass_active"])) {
        $source = [
            "{domain}",
            "{fullname}",
            "{email}",
            "{login_url}",
        ];

        $replace = [
            request()->domain(),
            $fullname,
            $email,
            request()->domain()."/Login/resetpasswd?code=".$code,
        ];

        $html_template = BASE_ROOT."/".$order_action["forget_pass_active"]["email_html"];
        if(!is_file($html_template)) {
            return false;
        }
        $html = file_get_contents($html_template);
        $body = str_replace($source, $replace, $html);
        $revs = [
            $email,
        ];
        send_email($order_action["forget_pass_active"]["title"], $body, "", $revs);
    }
}

//忘記密碼
function send_pass_email($fullname, $email, $passwd) {
        $order_action = order_state_actions();
        if(isset($order_action["forget_pass"])) {
            $source = [
                "{domain}",
                "{fullname}",
                "{email}",
                "{password}",
            ];

            $replace = [
                request()->domain(),
                $fullname,
                $email,
                $passwd,
            ];

            $html_template = BASE_ROOT."/".$order_action["forget_pass"]["email_html"];
            if(!is_file($html_template)) {
                return false;
            }

            $html = file_get_contents($html_template);
            $body = str_replace($source, $replace, $html);
            $revs = [
                $email,
            ];
            send_email($order_action["forget_pass"]["title"], $body, "", $revs);
        }
}

//發送開通郵件
function send_active_email($fullname, $custconemail, $custpassword, $active_code) {
        $order_action = order_state_actions();
        $source = [
            "{domain}",
            "{fullname}",
            "{email}",
            "{password}",
            "{active_url}",
        ];

        $replace = [
            request()->domain(),
            $fullname,
            $custconemail,
            $custpassword,
            request()->domain()."/Register/active?code=". $active_code
        ];

        $html_template = BASE_ROOT."/".$order_action["regcomfirm"]["email_html"];
        if(!is_file($html_template)) {
            return false;
        }
        $html = file_get_contents($html_template);
        $body = str_replace($source, $replace, $html);
        $revs = [
            $custconemail,
        ];
        send_email($order_action["regcomfirm"]["title"], $body, "", $revs);
}

//註冊成功
function send_register_email($customer) {
        $order_action = order_state_actions();
        if(isset($order_action["register"])) {
            $source = [
                "{domain}",
                "{fullname}",
                "{email}",
            ];

            $replace = [
                request()->domain(),
                $customer["fullname"],
                $customer["custconemail"],
            ];

            $html_template = BASE_ROOT."/".$order_action["register"]["email_html"];
            if(!is_file($html_template)) {
                return false;
            }
            $html = file_get_contents($html_template);
            $body = str_replace($source, $replace, $html);
            $revs = [
                $customer["custconemail"],
            ];
            send_email($order_action["register"]["title"], $body, "", $revs);
        }
}

function send_coupon_email($user_id, $coupon_title, $amount, $min_amount) {
    $user = Db::name("users")->where("user_id", $user_id)->find();
    if(strpos($user["username"], "@")!==false) {
        $email = $user["username"];
    } else {
        $email = $user["email"];
    }

    if(!empty($email)) {
        $order_action = order_state_actions();
        if(isset($order_action["weshang"])) {
            $source = [
                "{domain}",
                "{fullname}",
                "{username}",
                "{coupon_title}",
                "{amount}",
                "{min_amount}",
                "{sub_domain}",
            ];
            $setting = get_audits();
            $replace = [
                think\Request::instance()->host(),
                empty($user["fullname"])?$user["username"]:$user["fullname"],
                $user["username"],
                $coupon_title,
                $amount,
                $min_amount,
                "http://".$user["sub_domain"]
            ];

            $html_template = BASE_ROOT."/".$order_action["coupon"]["email_html"];
            if(!is_file($html_template)) {
                return false;
            }
            $html = file_get_contents($html_template);
            $body = str_replace($source, $replace, $html);
            $revs = [
                $email,
            ];
            return send_email($order_action["coupon"]["title"], $body, "", $revs);
        }
    }
}

function send_gwjin_email($user_id, $gwjin_amount) {
    $user = Db::name("users")->where("user_id", $user_id)->find();
    if(strpos($user["username"], "@")!==false) {
        $email = $user["username"];
    } else {
        $email = $user["email"];
    }

    if(!empty($email)) {
        $order_action = order_state_actions();
        if(isset($order_action["weshang"])) {
            $source = [
                "{domain}",
                "{fullname}",
                "{username}",
                "{gwjin_amount}",
                "{sub_domain}",
            ];
            $setting = get_audits();
            $replace = [
                think\Request::instance()->host(),
                empty($user["fullname"])?$user["username"]:$user["fullname"],
                $user["username"],
                $gwjin_amount,
                "http://".$user["sub_domain"]
            ];

            $html_template = BASE_ROOT."/".$order_action["gwjin"]["email_html"];
            if(!is_file($html_template)) {
                return false;
            }
            $html = file_get_contents($html_template);
            $body = str_replace($source, $replace, $html);
            $revs = [
                $email,
            ];
            return send_email($order_action["gwjin"]["title"], $body, "", $revs);
        }
    }
}


//發送訂單郵件
function send_order_email($oid) {
    $order = Db::name("orders")->where("oid", $oid)->find();
    $customer = Db::name(\app\models\Customer::$tablename)->where("customerid", $order["customerid"])->find();

    $order_action = order_state_actions();
    if(isset($order_action[$order["order_status"]])) {
        $source = [
            "{domain}",
            "{fullname}",
            "{order_date}",
            "{orderid}",
            "{total_amount}",
            "{shipping_type_name}"
        ];

        $replace = [
            request()->domain(),
            $customer["fullname"],
            date("Y/m/d", $order["create_date"]),
            $order["oid"],
            $order["total_amount"],
            $order["shipping_type_name"]
        ];

        $html_template = BASE_ROOT."/".$order_action[$order["order_status"]]["email_html"];
        if(!is_file($html_template)) {
            return false;
        }
        $html = file_get_contents($html_template);
        $body = str_replace($source, $replace, $html);
        $revs = [
            $customer["custconemail"],
        ];
        send_email($order_action[$order["order_status"]]["title"], $body, "", $revs);

    }
}

function get_stmp()
{
    $config = get_setting();
    $setting = $config["setting"];
    return [
        "host" => isset($setting['smtp']['host'])?$setting['smtp']['host']:'',
        "username" => isset($setting['smtp']['username'])?$setting['smtp']['username']:'',
        "password" => isset($setting['smtp']['password'])?$setting['smtp']['password']:'',
        "port" => isset($setting['smtp']['port'])?$setting['smtp']['port']:'',
        "secure" => isset($setting['smtp']['smtp_secure'])?$setting['smtp']['smtp_secure']:'',
        "is_auth" => (isset($setting['smtp']['smtp_auth']) && $setting['smtp']['smtp_auth'])?1:0,
    ];
}

//發送郵件
function send_email($subject, $body, $atta="", $rev=[], $cc=[])
{
    $config = get_stmp();
    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
    try {
        //Server settings
        $secure = trim($config["secure"]);
        $is_auth = (int) $config["is_auth"];
        $mail->isSMTP();
        $mail->CharSet = $mail::CHARSET_UTF8;
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                                 // Enable verbose debug output                           // Set mailer to use SMTP
        $mail->Host = trim($config["host"]);  // Specify main and backup SMTP servers
        $mail->SMTPAuth = $is_auth?true:false;                               // Enable SMTP authentication
        $mail->Username = trim($config["username"]);                 // SMTP username
        $mail->Password = trim($config["password"]);                           // SMTP password
        if(!empty($secure)) {
            $mail->SMTPSecure = $secure;                            // Enable TLS encryption, `ssl` also accepted
        } else {
            $mail->SMTPSecure = false;
        }

        $mail->Port = trim($config["port"]);                                    // TCP port to connect to
        $mail->Timeout = 60;
        //Recipients
        $mail->setFrom(trim($config["username"]), '');
        if(!empty($rev)) {
            foreach($rev as $email) {
                $mail->addAddress($email);
            }
        }

        if(!empty($cc)) {
            foreach($cc as $email) {
                $mail->addCC($email);
            }
        }

        //Attachments
        if(!empty($atta)) {
            $mail->addAttachment($atta);         // Add attachments
        }
        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    =  $body;

        if(!$mail->send()) {
            throw new Exception($mail->ErrorInfo);
        }
        return ["err_code" => 0, "msg" => "suc"];
    } catch (\Exception $e) {
        return ["err_code" => 300, "msg" => $e->getMessage()];
    }
}

function get_price_str() {
    return "$ ";
}

function format_price($val) {
    return get_price_str().round($val);
}

function coupon_types() {
    return [
        "birth" => [
            "title" => "生日禮券"
        ],
        "reg" => [
            "title" => "新人券"
        ],
        "online" => [
            "title" => "手動發放"
        ],
        "offline" => [
            "title" => "線下匯入"
        ]
    ];
}


function cart_qty() {
    $products = Session::get("carts");
    if(!empty($products)) {
        $qty = 0;
        foreach($products as $v) {
            $qty += $v["qty"];
        }
        return $qty;
    }
    return 0;
}

function get_setting()
{
    $setting = Db::name("settings")->where("sid", 1)->find();
    if(!empty($setting) && !empty($setting["content"])) {
        return json_decode($setting["content"], true);
    }
    return;
}

function get_coupons($amount=0) {
    $user_info = Session::get('user_info');
    if(!empty($user_info)) {
        return Db::name("coupons")
            ->where("userid", $user_info["user_id"])
            ->where("min_amount<=".$amount)
            ->where("state", 1)
            ->where("has_used", 0)
            ->where("start_time<=".time())
            ->where("end_time>=".strtotime(date("Y-m-d")))
            ->order("amount DESC")
            ->select();
    }
    return;
}

function get_banks() {
    $settings = get_setting();
    $new_banks = [];
    if(isset($settings["setting"]["common"]["banks"]) && !empty($settings["setting"]["common"]["banks"])) {
        $banks = explode("\n", $settings["setting"]["common"]["banks"]);
        if(!empty($banks)) {
            foreach($banks as $k => $v) {
                if(!in_array(trim($v), $new_banks)) {
                    $new_banks[] = trim($v);
                }
            }
        }
    }
    return $new_banks;
}

function get_invoice_to() {
    $settings = get_setting();
    $invoice = [];
    if(isset($settings["setting"]["common"]["invoice_to"]) && !empty($settings["setting"]["common"]["invoice_to"])) {
        $_invoice = explode("\n", $settings["setting"]["common"]["invoice_to"]);
        if(!empty($_invoice)) {
            foreach($_invoice as $k => $v) {
                $donateto = explode("|", $v);
                if(isset($donateto[0]) && isset($donateto[1])) {
                    $lovecode = trim($donateto[0]);
                    $title = trim($donateto[1]);
                    if(!empty($lovecode) && !empty($title)) {
                        $invoice[$lovecode] = $title;
                    }
                }
            }
        }
    }
    return $invoice;
}

function get_return_states()
{
    return [
        "padding" => "待處理",
        "confirm" => "受理退貨",
        "processing" => "退貨中",
        "complete" => "完成",
        "cancel" => "會員取消",
        "refused" => "拒絕售後"
    ];
}

function get_return_types() {
    $settings = get_setting();
    $invoice = [];
    if(isset($settings["setting"]["common"]["return_type"]) && !empty($settings["setting"]["common"]["return_type"])) {
        $_invoice = explode("\n", $settings["setting"]["common"]["return_type"]);
        if(!empty($_invoice)) {
            foreach($_invoice as $k => $v) {
                if(!in_array(trim($v), $invoice)) {
                    $invoice[] = trim($v);
                }
            }
        }
    }
    return $invoice;
}


function get_email_titles() {
    $settings = get_setting();
    $new_banks = [];
    if(isset($settings["setting"]["email"]["titles"]) && !empty($settings["setting"]["email"]["titles"])) {
        $banks = explode("\n", $settings["setting"]["email"]["titles"]);
        if(!empty($banks)) {
            foreach($banks as $k => $v) {
                if(!in_array(trim($v), $new_banks)) {
                    $new_banks[] = trim($v);
                }
            }
        }
    }
    return $new_banks;
}

function get_product_features() {
    $settings = get_setting();
    $new_features = [];
    if(isset($settings["setting"]["common"]["features"]) && !empty($settings["setting"]["common"]["features"])) {
        $features = explode("\n", $settings["setting"]["common"]["features"]);
        if(!empty($features)) {
            foreach($features as $k => $v) {
                $v = trim($v);
                if(!isset($new_features[md5($v)])) {
                    $new_features[md5($v)] = $v;
                }
            }
        }
    }
    return $new_features;
}

function get_audits() {
    return [
        "0" => "<i style='color:red'>待審核</i>",
        "1" => "<i style='color:green'>審核通過</i>",
        "-1" => "<i style='color:orange'>審核拒絕</i>"
    ];
}

function express_types() {
    return [
        "HOME" => "宅配",
        "CVS" => "超商取貨",
        "SE" => "門市取貨",
    ];
}

function return_express_types() {
    return [
        "HOME" => "上門取件",
        "CVS" => "送至超商",
    ];
}

//物流名稱
function LogisticsHomeSubTypes() {
    return [
        "TCAT" => "黑貓",
        "ECAN" => "宅配通",
    ];
}

function LogisticsCVSSubTypes() {

    $config = get_setting();
    if(isset($config["setting"]["ecpay"]["LogisticsCVSSubTypes"]) && $config["setting"]["ecpay"]["LogisticsCVSSubTypes"]=="B2C") {
        return [
            "FAMI"  => "全家",
            "UNIMART"=> "統一超商",
            "HILIFE" => "萊爾富",
        ];
    } else {
        return [
            //---C2C---
            "FAMIC2C" => "全家店到店",
            "UNIMARTC2C" => "統一超商交貨便",
            "HILIFEC2C" => "萊爾富店到店",
        ];
    }

}

function get_order_express_status($AllPayLogisticsID)
{
    return Db::name("express_status")->where("AllPayLogisticsID", $AllPayLogisticsID)->order("TradeDate DESC")->select();
}

function express_status($a, $b, $c)
{
    $data["CVS"]["FAMI"]["300"] = "訂單處理中(已收到訂單資料)";
    $data["CVS"]["FAMI"]["7007"] = "門市遺失";
    $data["CVS"]["FAMI"]["7006"] = "小物流遺失";
    $data["CVS"]["FAMI"]["7008"] = "小物流破損，退回物流中心";
    $data["CVS"]["FAMI"]["310"] = "上傳電子訂單檔案處理中";
    $data["CVS"]["FAMI"]["7009"] = "商品包裝不良（物流中心反應）";
    $data["CVS"]["FAMI"]["7010"] = "商品包裝不良（門市反應）";
    $data["CVS"]["FAMI"]["7011"] = "取件門市閉店，轉退回原寄件店";
    $data["CVS"]["FAMI"]["7012"] = "條碼錯誤，物流中心客服處理";
    $data["CVS"]["FAMI"]["7015"] = "條碼重複，物流中心客服處理";
    $data["CVS"]["FAMI"]["5009"] = "進貨門市發生緊急閉店，提早退貨至物流中心";
    $data["CVS"]["FAMI"]["7016"] = "超材";
    $data["CVS"]["FAMI"]["7013"] = "訂單超過驗收期限（商家未出貨）";
    $data["CVS"]["FAMI"]["7014"] = "商家未到貨（若訂單成立隔日未到貨即會發送，直到訂單失效刪除）";
    $data["CVS"]["FAMI"]["4001"] = "退貨商品已至門市交寄";
    $data["CVS"]["FAMI"]["4002"] = "退貨商品已至物流中心";
    $data["CVS"]["FAMI"]["3024"] = "貨品已至物流中心";
    $data["CVS"]["FAMI"]["3025"] = "退貨已退回物流中心";
    $data["CVS"]["FAMI"]["3020"] = "貨品未取退回物流中心";
    $data["CVS"]["FAMI"]["3021"] = "退貨商品未取退回物流中心";
    $data["CVS"]["FAMI"]["3032"] = "賣家已到門市寄件";
    $data["CVS"]["FAMI"]["3022"] = "買家已到店取貨";
    $data["CVS"]["FAMI"]["3023"] = "賣家已取買家未取貨";
    $data["CVS"]["FAMI"]["3018"] = "到店尚未取貨，簡訊通知取件";
    $data["CVS"]["FAMI"]["3029"] = "商品已轉換店（商品送達指定更換之取件店舖）";
    $data["CVS"]["FAMI"]["3019"] = "退件到店尚未取貨，簡訊通知取件";
    $data["CVS"]["FAMI"]["3031"] = "退貨商品已轉換店（退貨商品送達指定更換之取件店舖）";
    $data["CVS"]["UNIMART"]["300"] = "訂單處理中(已收到訂單資料)";
    $data["CVS"]["UNIMART"]["2030"] = "物流中心驗收成功";
    $data["CVS"]["UNIMART"]["2046"] = "貨件未取退回大智通物流中心";
    $data["CVS"]["UNIMART"]["2007"] = "商品類型為空";
    $data["CVS"]["UNIMART"]["2008"] = "訂單為空";
    $data["CVS"]["UNIMART"]["2009"] = "門市店號為空";
    $data["CVS"]["UNIMART"]["2010"] = "出貨日期為空";
    $data["CVS"]["UNIMART"]["2011"] = "出貨金額為空";
    $data["CVS"]["UNIMART"]["2012"] = "出貨編號不存在";
    $data["CVS"]["UNIMART"]["2013"] = "母廠商不存在";
    $data["CVS"]["UNIMART"]["2014"] = "子廠商不存在";
    $data["CVS"]["UNIMART"]["2015"] = "出貨編號已存在(單筆)";
    $data["CVS"]["UNIMART"]["2016"] = "門市已關轉店，將進行退貨處理";
    $data["CVS"]["UNIMART"]["2017"] = "出貨日期不符合規定";
    $data["CVS"]["UNIMART"]["2018"] = "服務類型不符規定(如只開取貨付款服務，確使用純取貨服務)";
    $data["CVS"]["UNIMART"]["2019"] = "商品類型不符規定";
    $data["CVS"]["UNIMART"]["2020"] = "廠商尚未申請店配服務";
    $data["CVS"]["UNIMART"]["2021"] = "同一批次出貨編號重覆(批次)";
    $data["CVS"]["UNIMART"]["2022"] = "出貨金額不符規定";
    $data["CVS"]["UNIMART"]["2023"] = "取貨人姓名為空";
    $data["CVS"]["UNIMART"]["2057"] = "車輛故障，後續配送中";
    $data["CVS"]["UNIMART"]["2058"] = "天候不佳，後續配送中";
    $data["CVS"]["UNIMART"]["2059"] = "道路中斷，後續配送中";
    $data["CVS"]["UNIMART"]["2060"] = "門市停業中，將進行退貨處理";
    $data["CVS"]["UNIMART"]["2061"] = "缺件(商品未至門市)";
    $data["CVS"]["UNIMART"]["2062"] = "門市報缺";
    $data["CVS"]["UNIMART"]["2047"] = "正常二退(退貨時間延長，在判賠期限內退回)";
    $data["CVS"]["UNIMART"]["2024"] = "物流作業驗收中";
    $data["CVS"]["UNIMART"]["2025"] = "門市轉店號(舊門市店號已更新)";
    $data["CVS"]["UNIMART"]["2026"] = "無此門市，將進行退貨處理";
    $data["CVS"]["UNIMART"]["2027"] = "門市指定時間不配送(六、日)";
    $data["CVS"]["UNIMART"]["2028"] = "門市關轉店，3日內未更新SUP(新店號)便至退貨流程";
    $data["CVS"]["UNIMART"]["2029"] = "門市尚未開店";
    $data["CVS"]["UNIMART"]["2031"] = "未到貨(物流端未收到該商品)";
    $data["CVS"]["UNIMART"]["2063"] = "門市配達";
    $data["CVS"]["UNIMART"]["2048"] = "商品瑕疵(商品在物流中心)";
    $data["CVS"]["UNIMART"]["2049"] = "門市關店，將進行退貨處理";
    $data["CVS"]["UNIMART"]["2050"] = "門市轉店，將進行退貨處理";
    $data["CVS"]["UNIMART"]["2051"] = "廠商要求提早退貨（廠商出錯商品）";
    $data["CVS"]["UNIMART"]["2052"] = "違禁品(退貨及罰款處理)";
    $data["CVS"]["UNIMART"]["2095"] = "天候路況不佳";
    $data["CVS"]["UNIMART"]["2065"] = "EC收退";
    $data["CVS"]["UNIMART"]["2066"] = "異常收退(商品破損、外袋破損、消費者取錯件、誤刷代收等，提早從門市退貨)";
    $data["CVS"]["UNIMART"]["2053"] = "刷A給B";
    $data["CVS"]["UNIMART"]["2054"] = "消費者要求提早拉退（消費者下訂單後又跟廠商取消）";
    $data["CVS"]["UNIMART"]["2032"] = "商品瑕疵(進物流中心)";
    $data["CVS"]["UNIMART"]["2033"] = "超材";
    $data["CVS"]["UNIMART"]["2034"] = "違禁品(退貨及罰款處理)";
    $data["CVS"]["UNIMART"]["2035"] = "訂單資料重覆上傳";
    $data["CVS"]["UNIMART"]["2036"] = "已過門市進貨日（未於指定時間內寄至物流中心）";
    $data["CVS"]["UNIMART"]["2038"] = "第一段標籤規格錯誤";
    $data["CVS"]["UNIMART"]["2039"] = "第一段標籤無法判讀";
    $data["CVS"]["UNIMART"]["2040"] = "第一段標籤資料錯誤";
    $data["CVS"]["UNIMART"]["2041"] = "物流中心理貨中";
    $data["CVS"]["UNIMART"]["2042"] = "商品遺失";
    $data["CVS"]["UNIMART"]["2043"] = "門市指定不配送(六、日)";
    $data["CVS"]["UNIMART"]["2045"] = "不正常到貨(商品提早到物流中心)";
    $data["CVS"]["UNIMART"]["2002"] = "出貨單號不合規則";
    $data["CVS"]["UNIMART"]["2003"] = "XML檔內出貨單號重複";
    $data["CVS"]["UNIMART"]["2004"] = "出貨單號重複上傳使用(驗收時發現)";
    $data["CVS"]["UNIMART"]["2005"] = "日期格式不符";
    $data["CVS"]["UNIMART"]["2006"] = "訂單金額或代收金額錯誤";
    $data["CVS"]["UNIMART"]["2071"] = "門市代碼格式錯誤";
    $data["CVS"]["UNIMART"]["2073"] = "商品配達買家取貨門市";
    $data["CVS"]["UNIMART"]["2074"] = "消費者七天未取，商品離開買家取貨門市";
    $data["CVS"]["UNIMART"]["2072"] = "商品配達賣家取退貨門市";
    $data["CVS"]["UNIMART"]["2075"] = "廠商未至門市取退貨，商品離開賣家取退貨門市";
    $data["CVS"]["UNIMART"]["2001"] = "檔案傳送成功";
    $data["CVS"]["UNIMART"]["310"] = "上傳電子訂單檔處理中";
    $data["CVS"]["UNIMART"]["4002"] = "退貨商品已至物流中心";
    $data["CVS"]["UNIMART"]["2094"] = "包裹異常不配送";
    $data["CVS"]["UNIMART"]["2037"] = "門市關轉(可使用SUP檔案更新原單號更新門市與出貨日)";
    $data["CVS"]["UNIMART"]["2070"] = "退回原寄件門市且已取件";
    $data["CVS"]["UNIMART"]["2067"] = "消費者成功取件";
    $data["CVS"]["UNIMART"]["7021"] = "商品捆包";
    $data["CVS"]["UNIMART"]["7022"] = "商品外袋透明";
    $data["CVS"]["UNIMART"]["7023"] = "多標籤";
    $data["CVS"]["UNIMART"]["2055"] = "更換門市";
    $data["CVS"]["UNIMART"]["7017"] = "取件包裹異常協尋中";
    $data["CVS"]["UNIMART"]["7018"] = "取件遺失進行賠償程式";
    $data["CVS"]["UNIMART"]["4001"] = "退貨商品已至門市交寄";
    $data["CVS"]["UNIMARTC2C"]["2030"] = "物流中心驗收成功";
    $data["CVS"]["UNIMARTC2C"]["7019"] = "寄件貨態異常協尋中";
    $data["CVS"]["UNIMARTC2C"]["2068"] = "交貨便收件(A門市收到件寄件商品)";
    $data["CVS"]["UNIMARTC2C"]["2069"] = "退貨便收件(商品退回指定C門市)";
    $data["CVS"]["UNIMARTC2C"]["7020"] = "寄件遺失進行賠償程式";
    $data["CVS"]["UNIMARTC2C"]["2073"] = "商品配達買家取貨門市";
    $data["CVS"]["UNIMARTC2C"]["2074"] = "消費者七天未取，商品離開買家取貨門市";
    $data["CVS"]["UNIMARTC2C"]["2101"] = "門市關轉店";
    $data["CVS"]["UNIMARTC2C"]["2102"] = "門市舊店號更新";
    $data["CVS"]["UNIMARTC2C"]["2103"] = "無取件門市資料";
    $data["CVS"]["UNIMARTC2C"]["2104"] = "門市臨時關轉店";
    $data["CVS"]["UNIMARTC2C"]["2076"] = "消費者七天未取，商品退回至大智通";
    $data["CVS"]["UNIMARTC2C"]["2077"] = "廠商未至門市取退貨，商品退回至大智通";
    $data["CVS"]["UNIMARTC2C"]["2078"] = "買家未取貨退回物流中心-驗收成功";
    $data["CVS"]["UNIMARTC2C"]["2079"] = "買家未取貨退回物流中心-商品瑕疵(進物流中心)";
    $data["CVS"]["UNIMARTC2C"]["2080"] = "買家未取貨退回物流中心-超材";
    $data["CVS"]["UNIMARTC2C"]["2081"] = "買家未取貨退回物流中心-違禁品(退貨及罰款處理)";
    $data["CVS"]["UNIMARTC2C"]["2082"] = "買家未取貨退回物流中心-訂單資料重覆上傳";
    $data["CVS"]["UNIMARTC2C"]["2083"] = "買家未取貨退回物流中心-已過門市進貨日（未於指定時間內寄至物流中心）";
    $data["CVS"]["UNIMARTC2C"]["2092"] = "買家未取退回物流中心-門市關轉";
    $data["CVS"]["UNIMARTC2C"]["2084"] = "買家未取貨退回物流中心-第一段標籤規格錯誤";
    $data["CVS"]["UNIMARTC2C"]["2085"] = "買家未取貨退回物流中心-第一段標籤無法判讀";
    $data["CVS"]["UNIMARTC2C"]["2086"] = "買家未取貨退回物流中心-第一段標籤資料錯誤";
    $data["CVS"]["UNIMARTC2C"]["2087"] = "買家未取貨退回物流中心-物流中心理貨中";
    $data["CVS"]["UNIMARTC2C"]["2088"] = "買家未取貨退回物流中心-商品遺失";
    $data["CVS"]["UNIMARTC2C"]["2089"] = "買家未取退回物流中心-門市指定不配送(六、日)";
    $data["CVS"]["UNIMARTC2C"]["2093"] = "買家未取退回物流中心-爆量";
    $data["CVS"]["UNIMARTC2C"]["2067"] = "消費者成功取件";
    $data["CVS"]["HILIFE"]["300"] = "訂單處理中(已收到訂單資料)";
    $data["CVS"]["HILIFE"]["310"] = "上傳電子訂單檔案處理中";
    $data["CVS"]["HILIFE"]["311"] = "上傳退貨電子訂單處理中";
    $data["CVS"]["HILIFE"]["325"] = "退貨訂單處理中(已收到訂單資料)";
    $data["CVS"]["HILIFE"]["2000"] = "出貨訂單修改";
    $data["CVS"]["HILIFE"]["2001"] = "檔案傳送成功";
    $data["CVS"]["HILIFE"]["2002"] = "出貨單號不合規則";
    $data["CVS"]["HILIFE"]["2003"] = "XML檔內出貨單號重複";
    $data["CVS"]["HILIFE"]["2004"] = "出貨單號重複上傳使用(驗收時發現)";
    $data["CVS"]["HILIFE"]["2005"] = "日期格式不符";
    $data["CVS"]["HILIFE"]["2006"] = "訂單金額或代收金額錯誤";
    $data["CVS"]["HILIFE"]["2007"] = "商品類型為空";
    $data["CVS"]["HILIFE"]["2009"] = "門市店號為空";
    $data["CVS"]["HILIFE"]["2010"] = "出貨日期為空";
    $data["CVS"]["HILIFE"]["2011"] = "出貨金額為空";
    $data["CVS"]["HILIFE"]["2012"] = "出貨編號不存在";
    $data["CVS"]["HILIFE"]["2013"] = "母廠商不存在";
    $data["CVS"]["HILIFE"]["2014"] = "子廠商不存在";
    $data["CVS"]["HILIFE"]["2015"] = "出貨編號已存在(單筆)";
    $data["CVS"]["HILIFE"]["2016"] = "門市已關轉店，將進行退貨處理";
    $data["CVS"]["HILIFE"]["2017"] = "出貨日期不符合規定";
    $data["CVS"]["HILIFE"]["2018"] = "服務類型不符規定(如只開取貨付款服務，確使用純取貨服務)";
    $data["CVS"]["HILIFE"]["2019"] = "商品類型不符規定";
    $data["CVS"]["HILIFE"]["2020"] = "廠商尚未申請店配服務";
    $data["CVS"]["HILIFE"]["2021"] = "同一批次出貨編號重覆(批次)";
    $data["CVS"]["HILIFE"]["2022"] = "出貨金額不符規定";
    $data["CVS"]["HILIFE"]["2023"] = "取貨人姓名為空";
    $data["CVS"]["HILIFE"]["2024"] = "物流作業驗收中";
    $data["CVS"]["HILIFE"]["2025"] = "門市轉店號(舊門市店號已更新)";
    $data["CVS"]["HILIFE"]["2026"] = "無此門市，將進行退貨處理";
    $data["CVS"]["HILIFE"]["2027"] = "門市指定時間不配送(六、日)";
    $data["CVS"]["HILIFE"]["2028"] = "門市關轉店，3日內未更新SUP(新店號)便至退貨流程";
    $data["CVS"]["HILIFE"]["2029"] = "門市尚未開店";
    $data["CVS"]["HILIFE"]["2030"] = "物流中心驗收成功";
    $data["CVS"]["HILIFE"]["2031"] = "未到貨(物流端未收到該商品)";
    $data["CVS"]["HILIFE"]["2032"] = "商品瑕疵(進物流中心)";
    $data["CVS"]["HILIFE"]["2033"] = "超材";
    $data["CVS"]["HILIFE"]["2034"] = "違禁品(退貨及罰款處理)";
    $data["CVS"]["HILIFE"]["2035"] = "訂單資料重覆上傳";
    $data["CVS"]["HILIFE"]["2036"] = "已過門市進貨日（未於指定時間內寄至物流中心）";
    $data["CVS"]["HILIFE"]["2037"] = "門市關轉(可使用SUP檔案更新原單號更新門市與出貨日)";
    $data["CVS"]["HILIFE"]["2038"] = "第一段標籤規格錯誤";
    $data["CVS"]["HILIFE"]["2039"] = "第一段標籤無法判讀";
    $data["CVS"]["HILIFE"]["2040"] = "第一段標籤資料錯誤";
    $data["CVS"]["HILIFE"]["2041"] = "物流中心理貨中";
    $data["CVS"]["HILIFE"]["2042"] = "商品遺失";
    $data["CVS"]["HILIFE"]["2043"] = "門市指定不配送(六、日)";
    $data["CVS"]["HILIFE"]["2045"] = "不正常到貨(商品提早到物流中心)";
    $data["CVS"]["HILIFE"]["2046"] = "貨件未取退回大智通物流中心";
    $data["CVS"]["HILIFE"]["2047"] = "正常二退(退貨時間延長，在判賠期限內退回)";
    $data["CVS"]["HILIFE"]["2048"] = "商品瑕疵(商品在物流中心)";
    $data["CVS"]["HILIFE"]["2049"] = "門市關店，將進行退貨處理";
    $data["CVS"]["HILIFE"]["2050"] = "門市轉店，將進行退貨處理";
    $data["CVS"]["HILIFE"]["2051"] = "廠商要求提早退貨（廠商出錯商品）";
    $data["CVS"]["HILIFE"]["2052"] = "違禁品(退貨及罰款處理)";
    $data["CVS"]["HILIFE"]["2053"] = "刷A給B";
    $data["CVS"]["HILIFE"]["2054"] = "消費者要求提早拉退（消費者下訂單後又跟廠商取消）";
    $data["CVS"]["HILIFE"]["2055"] = "更換門市";
    $data["CVS"]["HILIFE"]["2057"] = "車輛故障，後續配送中";
    $data["CVS"]["HILIFE"]["2058"] = "天候不佳，後續配送中";
    $data["CVS"]["HILIFE"]["2059"] = "道路中斷，後續配送中";
    $data["CVS"]["HILIFE"]["2060"] = "門市停業中，將進行退貨處理";
    $data["CVS"]["HILIFE"]["2061"] = "缺件(商品未至門市)";
    $data["CVS"]["HILIFE"]["2062"] = "門市報缺";
    $data["CVS"]["HILIFE"]["2063"] = "門市配達";
    $data["CVS"]["HILIFE"]["2065"] = "EC收退";
    $data["CVS"]["HILIFE"]["2066"] = "異常收退(商品破損、外袋破損、消費者取錯件、誤刷代收等，提早從門市退貨)";
    $data["CVS"]["HILIFE"]["2067"] = "消費者成功取件";
    $data["CVS"]["HILIFE"]["2068"] = "交貨便收件(A門市收到件寄件商品)";
    $data["CVS"]["HILIFE"]["2069"] = "退貨便收件(商品退回指定C門市)";
    $data["CVS"]["HILIFE"]["2070"] = "退回原寄件門市且已取件";
    $data["CVS"]["HILIFE"]["2071"] = "門市代碼格式錯誤";
    $data["CVS"]["HILIFE"]["2072"] = "商品配達賣家取退貨門市";
    $data["CVS"]["HILIFE"]["2073"] = "商品配達買家取貨門市";
    $data["CVS"]["HILIFE"]["2074"] = "消費者七天未取，商品離開買家取貨門市";
    $data["CVS"]["HILIFE"]["2075"] = "廠商未至門市取退貨，商品離開賣家取退貨門市";
    $data["CVS"]["HILIFE"]["2101"] = "門市關轉店";
    $data["CVS"]["HILIFE"]["2102"] = "門市舊店號更新";
    $data["CVS"]["HILIFE"]["2103"] = "無取件門市資料";
    $data["CVS"]["HILIFE"]["2104"] = "門市臨時關轉店";
    $data["CVS"]["HILIFE"]["3001"] = "轉運中(即集貨)";
    $data["CVS"]["HILIFE"]["3002"] = "不在家";
    $data["CVS"]["HILIFE"]["3003"] = "配完";
    $data["CVS"]["HILIFE"]["3004"] = "送錯BASE (送錯營業所)";
    $data["CVS"]["HILIFE"]["3005"] = "送錯CENTER(送錯轉運中心)";
    $data["CVS"]["HILIFE"]["3006"] = "配送中";
    $data["CVS"]["HILIFE"]["3007"] = "公司行號休息";
    $data["CVS"]["HILIFE"]["3008"] = "地址錯誤，聯繫收件人";
    $data["CVS"]["HILIFE"]["3009"] = "搬家";
    $data["CVS"]["HILIFE"]["3010"] = "轉寄(如原本寄到A，改寄B)";
    $data["CVS"]["HILIFE"]["3011"] = "暫置營業所(收件人要求至營業所取貨)";
    $data["CVS"]["HILIFE"]["3012"] = "到所(收件人要求到站所取件)";
    $data["CVS"]["HILIFE"]["3013"] = "當配下車(當日配送A至B營業所，已抵達B營業所)";
    $data["CVS"]["HILIFE"]["3014"] = "當配上車(當日配送從A至B營業所，已抵達A營業所)";
    $data["CVS"]["HILIFE"]["3015"] = "空運配送中";
    $data["CVS"]["HILIFE"]["3016"] = "配完狀態刪除";
    $data["CVS"]["HILIFE"]["3017"] = "退回狀態刪除(代收退貨刪除)";
    $data["CVS"]["HILIFE"]["3018"] = "到店尚未取貨，簡訊通知取件";
    $data["CVS"]["HILIFE"]["3019"] = "退件到店尚未取貨，簡訊通知取件";
    $data["CVS"]["HILIFE"]["3020"] = "貨件未取退回物流中心";
    $data["CVS"]["HILIFE"]["3021"] = "退貨商品未取退回物流中心";
    $data["CVS"]["HILIFE"]["3022"] = "買家已到店取貨";
    $data["CVS"]["HILIFE"]["3023"] = "賣家已取買家未取貨";
    $data["CVS"]["HILIFE"]["3024"] = "貨件已至物流中心";
    $data["CVS"]["HILIFE"]["3025"] = "退貨已退回物流中心";
    $data["CVS"]["HILIFE"]["3029"] = "商品已轉換店（商品送達指定更換之取件店舖）";
    $data["CVS"]["HILIFE"]["3031"] = "退貨商品已轉換店（退貨商品送達指定更換之取件店舖）";
    $data["CVS"]["HILIFE"]["3032"] = "賣家已到門市寄件";
    $data["CVS"]["HILIFE"]["4001"] = "退貨商品已至門市交寄";
    $data["CVS"]["HILIFE"]["4002"] = "退貨商品已至物流中心";
    $data["CVS"]["HILIFE"]["5001"] = "損壞，站所將協助退貨";
    $data["CVS"]["HILIFE"]["5002"] = "遺失";
    $data["CVS"]["HILIFE"]["5003"] = "BASE列管(寄件人和收件人聯絡不到)";
    $data["CVS"]["HILIFE"]["5004"] = "一般單退回";
    $data["CVS"]["HILIFE"]["5005"] = "代收退貨";
    $data["CVS"]["HILIFE"]["5006"] = "代收毀損";
    $data["CVS"]["HILIFE"]["5007"] = "代收遺失";
    $data["CVS"]["HILIFE"]["5008"] = "退貨配完";
    $data["CVS"]["HILIFE"]["5009"] = "進貨門市發生緊急閉店，提早退貨至物流中心";
    $data["CVS"]["HILIFE"]["7001"] = "超大(通常發生於司機取件，不取件)";
    $data["CVS"]["HILIFE"]["7002"] = "超重(通常發生於司機取件，不取件)";
    $data["CVS"]["HILIFE"]["7003"] = "地址錯誤，聯繫收件人";
    $data["CVS"]["HILIFE"]["7004"] = "航班延誤";
    $data["CVS"]["HILIFE"]["7005"] = "託運單刪除";
    $data["CVS"]["HILIFE"]["7006"] = "小物流遺失";
    $data["CVS"]["HILIFE"]["7007"] = "門市遺失";
    $data["CVS"]["HILIFE"]["7008"] = "小物流破損，退回物流中心";
    $data["CVS"]["HILIFE"]["7009"] = "商品包裝不良（物流中心反應）";
    $data["CVS"]["HILIFE"]["7010"] = "商品包裝不良（門市反應）";
    $data["CVS"]["HILIFE"]["7011"] = "取件門市閉店，轉退回原寄件店";
    $data["CVS"]["HILIFE"]["7012"] = "條碼錯誤，物流中心客服處理";
    $data["CVS"]["HILIFE"]["7013"] = "訂單超過驗收期限（商家未出貨）";
    $data["CVS"]["HILIFE"]["7014"] = "商家未到貨（若訂單成立隔日未到貨即會發送，直到訂單失效刪除）";
    $data["CVS"]["HILIFE"]["9001"] = "退貨已取";
    $data["CVS"]["HILIFE"]["9002"] = "退貨已取";
    $data["CVS"]["HILIFE"]["9999"] = "訂單取消";
    $data["HOME"]["ECAN"]["325"] = "退貨訂單處理中";
    $data["HOME"]["ECAN"]["3003"] = "配完";
    $data["HOME"]["ECAN"]["3110"] = "轉配郵局";
    $data["HOME"]["ECAN"]["3111"] = "配送外包";
    $data["HOME"]["ECAN"]["3006"] = "配送中";
    $data["HOME"]["ECAN"]["3112"] = "再配";
    $data["HOME"]["ECAN"]["3113"] = "異常";
    $data["HOME"]["ECAN"]["3114"] = "再取";
    $data["HOME"]["ECAN"]["3003"] = "配完";
    $data["HOME"]["ECAN"]["3110"] = "轉配郵局";
    $data["HOME"]["ECAN"]["3111"] = "配送外包";
    $data["HOME"]["ECAN"]["3006"] = "配送中";
    $data["HOME"]["ECAN"]["3112"] = "再配";
    $data["HOME"]["ECAN"]["3113"] = "異常";
    $data["HOME"]["ECAN"]["3114"] = "再取";
    $data["HOME"]["ECAN"]["3003"] = "配完";
    $data["HOME"]["ECAN"]["3110"] = "轉配郵局";
    $data["HOME"]["ECAN"]["3111"] = "配送外包";
    $data["HOME"]["ECAN"]["3006"] = "配送中";
    $data["HOME"]["ECAN"]["3112"] = "再配";
    $data["HOME"]["ECAN"]["3113"] = "異常";
    $data["HOME"]["ECAN"]["3114"] = "再取";
    $data["HOME"]["TCAT"]["311"] = "上傳退貨電子訂單處理中";
    $data["HOME"]["TCAT"]["300"] = "訂單處理中(已收到訂單資料)";
    $data["HOME"]["TCAT"]["310"] = "上傳電子訂單檔處理中";
    $data["HOME"]["TCAT"]["325"] = "退貨訂單處理中(已收到訂單資料)";
    $data["HOME"]["TCAT"]["3001"] = "轉運中(即集貨)";
    $data["HOME"]["TCAT"]["3002"] = "不在家";
    $data["HOME"]["TCAT"]["3004"] = "送錯BASE (送錯營業所)";
    $data["HOME"]["TCAT"]["3005"] = "送錯CENTER(送錯轉運中心)";
    $data["HOME"]["TCAT"]["3006"] = "配送中";
    $data["HOME"]["TCAT"]["3007"] = "公司行號休息";
    $data["HOME"]["TCAT"]["3008"] = "地址錯誤，聯繫收件人";
    $data["HOME"]["TCAT"]["3009"] = "搬家";
    $data["HOME"]["TCAT"]["3010"] = "轉寄(如原本寄到A，改寄B)";
    $data["HOME"]["TCAT"]["3011"] = "暫置營業所(收件人要求至營業所取貨)";
    $data["HOME"]["TCAT"]["7002"] = "超重(通常發生於司機取件，不取件)";
    $data["HOME"]["TCAT"]["7003"] = "地址錯誤，聯繫收件人";
    $data["HOME"]["TCAT"]["5005"] = "代收退貨";
    $data["HOME"]["TCAT"]["3017"] = "退回狀態刪除(代收退貨刪除)";
    $data["HOME"]["TCAT"]["3003"] = "配完";
    $data["HOME"]["TCAT"]["5001"] = "損壞，站所將協助退貨";
    $data["HOME"]["TCAT"]["5002"] = "遺失";
    $data["HOME"]["TCAT"]["5003"] = "BASE列管(寄件人和收件人聯絡不到)";
    $data["HOME"]["TCAT"]["5004"] = "一般單退回";
    $data["HOME"]["TCAT"]["3015"] = "空運配送中";
    $data["HOME"]["TCAT"]["3016"] = "配完狀態刪除";
    $data["HOME"]["TCAT"]["7005"] = "託運單刪除";
    $data["HOME"]["TCAT"]["5006"] = "代收毀損";
    $data["HOME"]["TCAT"]["5007"] = "代收遺失";
    $data["HOME"]["TCAT"]["5008"] = "退貨配完";
    $data["HOME"]["TCAT"]["3012"] = "到所(收件人要求到站所取件)";
    $data["HOME"]["TCAT"]["3013"] = "當配下車(當日配送A至B營業所，已抵達B營業所)";
    $data["HOME"]["TCAT"]["3014"] = "當配上車(當日配送從A至B營業所，已抵達A營業所)";
    $data["HOME"]["TCAT"]["7001"] = "超大(通常發生於司機取件，不取件)";
    $data["HOME"]["TCAT"]["7004"] = "航班延誤";
    $data["HOME"]["TCAT"]["7024"] = "另約時間";
    $data["HOME"]["TCAT"]["7025"] = "電聯不上";
    $data["HOME"]["TCAT"]["7026"] = "資料有誤";
    $data["HOME"]["TCAT"]["7027"] = "無件可退";
    $data["HOME"]["TCAT"]["7028"] = "超大超重";
    $data["HOME"]["TCAT"]["7029"] = "已回收";
    $data["HOME"]["TCAT"]["7030"] = "別家收走";
    $data["HOME"]["TCAT"]["7031"] = "商品未到";

    if(isset($data[$a][$b][$c])) {
        return $data[$a][$b][$c];
    } else {
        return;
    }
}

function GetCountryName($id) {
   return Db::name(\app\models\Countries::$tablename)->where("id", $id)->value("name");
}

function sendSms($phone, $content)
{
    $config = get_setting();
    $setting = $config["setting"];

    $url = "https://api.kotsms.com.tw/kotsmsapi-1.php?username=".$setting['sms']['username']."&password=".$setting['sms']['passwd']."&dstaddr=".$phone."&smbody=".iconv("utf-8", "big5", $content);
    $content = file_get_contents($url);
    \think\facade\Log::write($content);
    if(stripos($content, "kmsgid") !== false) {
        $ret = explode('=', $content);
        return isset($ret[1]) ? trim($ret[1]) : -1;
    }
    return -1;
}

/**
 * 笛卡爾積，可變參數
 * @return array
 */
function combineDika() {
    $data = func_get_args();
    $cnt = count($data);
    $result = array();

    foreach($data as $k => $items) {
        if(!empty($items)) {
            foreach($items as $kk => $item) {
                $result[] = array($item);
            }
            break;
        }
    }

    for($i = $k+1; $i < $cnt; $i++) {
        if(empty($data[$i])) continue;
        $result = combineArray($result,$data[$i]);
    }

    return $result;
}

/**
 * 兩個數組的笛卡爾積
 *
 * @param unknown_type $arr1
 * @param unknown_type $arr2
 */
function combineArray($arr1,$arr2) {
    $result = array();
    foreach ($arr1 as $item1) {
        foreach ($arr2 as $item2) {
            $temp = $item1;
            $temp[] = $item2;
            $result[] = $temp;
        }
    }
    return $result;
}

//單頁同步到頁面
function pagetypes()
{
    return [
        "add_to_cart" => "加入購物車",
    ];
}

//通過頁面類型提取頁面
function get_pages_bytype($type)
{
   $list = Db::name(\app\models\Page::$tablename)
    ->where("state", 1)
    ->whereRaw("FIND_IN_SET('".$type."', pagetype)")
    ->order("sortorder ASC")
    ->select();

   return $list;
}

function get_pages_byids($ids='')
{
    $pageids = explode(",", $ids);
    if(empty($pageids)) return;
    $list = Db::name(\app\models\Page::$tablename)
        ->where("state", 1)
        ->where("pageid", "IN", $pageids)
        ->order("sortorder ASC")
        ->select();

    return $list;
}

