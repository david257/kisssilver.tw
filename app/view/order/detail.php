{include file="public/meta" /}
<link rel="stylesheet" type="text/css" href="/static/front/css/slider-pro.min.css" media="screen"/>
<link rel="stylesheet" type="text/css" href="/static/front/css/examples.css" media="screen"/>
<link rel="stylesheet" type="text/css" href="/static/front/css/list.css">
<link rel="stylesheet" href="/static/front/css/viewer.min.css">
<link rel="stylesheet" type="text/css" href="/static/front/css/user.css">
{include file="public/kefu" /}
<div class="index-box">
    {include file="public/header" /}
    <section class="sub">
        <div class="container">
            <div class="sub-loation">
                <ol class="breadcrumb">
                    <li><a href="{:front_link('Index/index')}">首頁</a></li>
                    <li><a href="#">會員中心</a></li>
                    <li><a href="{:front_link('Order/index')}">訂單記錄</a></li>
                    <li class="active">訂單資訊</li>
                </ol>
            </div>
        </div>
    </section>
    <section class="member">
        <div class="container">
    <div class="row">
        <div class="col-sm-12 col-md-2">
            <div class="list-group member-nav">
                <div class="nav-collapse">會員中心<span class="pull-right"><i class="iconfont icon-duiqi04"></i></span></div>
                {include file="public/customer_menu" /}
            </div>
        </div>
        <div class="col-sm-12 col-md-10">


            <div class="user-key"><h1>訂單資訊</h1></div>
            <div class="member-form">

                <div class="table-set">
                    <div class="location-table1 row text-center no-gutter">
                        <div class="col-xs-12 col-sm-12  col-md-6">
                            <dl>
                                <dt class="col-xs-5 col-md-5 justify">訂單編號</dt>
                                <dd class="col-xs-7 col-md-7 justify">{$order['oid']}</dd>
                            </dl>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <dl>
                                <dt class="col-xs-5 col-md-5 justify">Email</dt>
                                <dd class="col-xs-7 col-md-7 justify">{$order['billing_email']}</dd>
                            </dl>
                        </div>
                        <div class="col-xs-12 col-sm-12  col-md-6">
                            <dl>
                                <dt class="col-xs-5 col-md-5 justify">購買日期</dt>
                                <dd class="col-xs-7 col-md-7 justify">{:date("Y/m/d H:i",$order['create_date'])}</dd>
                            </dl></div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <dl>
                                <dt class="col-xs-5 col-md-5 justify">訂單狀態</dt>
                                <dd class="col-xs-7 col-md-7 justify">
                                    <?php $order_statuses = get_order_states(); ?>
                                    {:isset($order_statuses[$order['order_status']])?$order_statuses[$order['order_status']]:'N/A'}
                                </dd>
                            </dl>
                        </div>

                    </div>
                </div>
            </div>
			<div class="user-key"><h1>訂單備註</h1></div>
			<div class="member-form">
				{:empty($order['ordnote'])?'無備註':$order['ordnote']}
			</div>
            <div class="user-key"><h1>顧客資訊</h1></div>
            <div class="member-form">

                <div class="table-set">
                    <div class="location-table1 row text-center no-gutter">
                        <div class="col-xs-12 col-sm-12  col-md-6">
                            <dl>
                                <dt class="col-xs-5 col-md-5 justify">名稱</dt>
                                <dd class="col-xs-7 col-md-7 justify">{:$order['billing_name']}</dd>
                            </dl>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <dl>
                                <dt class="col-xs-5 col-md-5 justify">電話</dt>
                                <dd class="col-xs-7 col-md-7 justify">{:$order['billing_tel1']}</dd>
                            </dl>
                        </div>



                    </div>
                </div>
            </div>
            <div class="user-key"><h1>送貨資訊</h1></div>
            <div class="member-form">

                <div class="table-set">
                    <div class="location-table1 row text-center no-gutter">
                        <div class="col-xs-12 col-sm-12  col-md-12">
                            <dl>
                                <dt class="col-xs-12 col-md-3 justify text-lg-right">收件人姓名</dt>
                                <dd class="col-xs-12 col-md-9  text-left">{:$order['shipping_name']}</dd>
                            </dl>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <dl>
                                <dt class="col-xs-12 col-md-3 justify text-lg-right">收件人電話</dt>
                                <dd class="col-xs-12 col-md-9  text-left">{:$order['shipping_tel1']}</dd>
                            </dl>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <dl>
                                <dt class="col-xs-12 col-md-3 justify text-lg-right">配送方式</dt>
                                <dd class="col-xs-12 col-md-9  text-left">
                                    <?php
                                    $express_types = express_types();
                                    $LogisticsHomeSubTypes = LogisticsHomeSubTypes();
                                    ?>
                                    {:isset($LogisticsHomeSubTypes[$order['LogisticsSubType']])?$LogisticsHomeSubTypes[$order['LogisticsSubType']]:''}
                                    {:isset($express_types[$order['LogisticsType']])?$express_types[$order['LogisticsType']]:''}
                                    <?php if($order['LogisticsType'] != "SE") { ?>
                                    <a href="#logistics" class="btn btn-default btn-sm waves-effect waves-light">物流追蹤</a>
                                    <?php } ?>
                                </dd>

                            </dl>
                        </div>
                        <?php
                        if($order['LogisticsType']=="SE") {
                        ?>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <dl>
                                <dt class="col-xs-12 col-md-3 justify text-lg-right">門市取貨地址</dt>
                                <dd class="col-xs-12 col-md-9  text-left">{$se_address}</dd>
                            </dl>
                        </div>
                        <?php } else { ?>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <dl>
                                <dt class="col-xs-12 col-md-3 justify text-lg-right">送貨狀態</dt>
                                <dd class="col-xs-12 col-md-9  text-left">{$expressStatus}</dd>
                            </dl>
                        </div>
						<div class="col-xs-12 col-sm-12 col-md-12">
                            <dl>
                                <dt class="col-xs-12 col-md-3 justify text-lg-right">物流號</dt>
                                <dd class="col-xs-12 col-md-9  text-left">{$order['AllPayLogisticsID']}</dd>
                            </dl>
                        </div>
						<?php } ?>
                        <div class="col-xs-12 col-sm-12  col-md-12">
                            <dl>
                                <dt class="col-xs-12 col-md-3 justify text-lg-right">收件人地址</dt>
                                <dd class="col-xs-12 col-md-9  text-left">{:$order['shipping_zipcode']} {:$order['shipping_city']} {:$order['shipping_area']} {:$order['shipping_address']}</dd>
                            </dl></div>

                    </div>
                </div>
            </div>
            <div class="user-key"><h1>付款資訊</h1></div>
            <div class="member-form">

                <div class="table-set">
                    <div class="location-table1 row text-center no-gutter">
                        <div class="col-xs-12 col-sm-12  col-md-12">
                            <dl>
                                <dt class="col-xs-12 col-md-3 justify text-lg-right">付款方式</dt>
                                <dd class="col-xs-12 col-md-9  text-left">
                                    <?php
                                    $pay_types = get_pay_types();
                                    echo isset($pay_types[$order["pay_type"]])?$pay_types[$order["pay_type"]]['title']:'N/A';
                                    ?>
                                </dd>
                            </dl>
                        </div>
                        <div class="col-xs-12 col-sm-12  col-md-12">
                            <dl>
                                <dt class="col-xs-12 col-md-3 justify text-lg-right">付款狀態</dt>
                                <dd class="col-xs-12 col-md-9  text-left"><?php echo $order["pay_status"]?"已付款":"待付款";?></dd>
                            </dl>
                        </div>
                        <div class="col-xs-12 col-sm-12  col-md-12">
                            <dl>
                                <dt class="col-xs-12 col-md-3 justify text-lg-right">綠界交易編號</dt>
                                <dd class="col-xs-12 col-md-9  text-left">{$order['transid']}</dd>
                            </dl>
                        </div>

                        <div class="col-xs-12 col-sm-12  col-md-12">
                            <dl>
                                <dt class="col-xs-12 col-md-3 justify text-lg-right">發票聯式</dt>
                                <dd class="col-xs-12 col-md-9  text-left">
                                    {$order['invoice_type']}
                                </dd>
                            </dl>
                        </div>
                        <div class="col-xs-12 col-sm-12  col-md-12">
                            <dl>
                                <dt class="col-xs-12 col-md-3 justify text-lg-right">發票捐贈</dt>
                                <dd class="col-xs-12 col-md-9  text-left">
                                    <?php echo $order['donate_to']?(isset($donate_company[$order['donate_to']])?$donate_company[$order['donate_to']]:''):'不捐贈';?>
                                </dd>
                            </dl>
                        </div>
                        <div class="col-xs-12 col-sm-12  col-md-12">
                            <dl>
                                <dt class="col-xs-12 col-md-3 justify text-lg-right">發票抬頭</dt>
                                <dd class="col-xs-12 col-md-9  text-left">{$order['invoice_header']}</dd>
                            </dl>
                        </div>

                        <div class="col-xs-12 col-sm-12  col-md-12">
                            <dl>
                                <dt class="col-xs-12 col-md-3 justify text-lg-right">統一編號</dt>
                                <dd class="col-xs-12 col-md-9  text-left">{$order['invoice_no']}</dd>
                            </dl>
                        </div>
                        <?php
                        if(!empty($order['InvoiceNumber'])) {
                        ?>
                        <div class="col-xs-12 col-sm-12  col-md-12">
                            <dl>
                                <dt class="col-xs-12 col-md-3 justify text-lg-right">開票狀態</dt>
                                <dd class="col-xs-12 col-md-9  text-left">{:empty($order['InvoiceNumber'])?'':'已開立'}</dd>
                            </dl>
                        </div>

                        <div class="col-xs-12 col-sm-12  col-md-12">
                            <dl>
                                <dt class="col-xs-12 col-md-3 justify text-lg-right">發票號碼</dt>
                                <dd class="col-xs-12 col-md-9  text-left">{$order['InvoiceNumber']}</dd>
                            </dl>
                        </div>
                        <div class="col-xs-12 col-sm-12  col-md-12">
                            <dl>
                                <dt class="col-xs-12 col-md-3 justify text-lg-right">開立日期</dt>
                                <dd class="col-xs-12 col-md-9  text-left">{$order['InvoiceDate']}</dd>
                            </dl>
                        </div>
                        <?php } ?>

                    </div>
                </div>
            </div>
            <div class="user-key"><h1>訂單商品</h1></div>
            <div class="member-form">

                <div class="table-set">
                    <style>
                        .location-table dd i{ display:block; font-size:12px; padding:0 5px; text-align:left; color:#666; font-style:normal; line-height:16px;}
                    </style>

                    <?php
                    $qty = $total_amount = $total_points = 0;
                    $total_item_amount = 0;
                    if(!empty($order_products)) {
                    foreach ($order_products as $k => $row) {
                    $qty += $row["qty"];
                    $total_amount += $row["total_amount"];
                        $total_item_amount += $row["qty"]*$row["prod_price"];
                    ?>
                    <div class="location-table row text-center no-gutter">
                        <div class="col-xs-12 col-sm-6  col-md-2">
                            <dl>
                                <dt>商品圖片</dt>
                                <dd class="justify"><img src="{:showfile($row['prodimage'])}" width="80px" height="80px"></dd>
                            </dl>
                        </div>
                        <div class="col-xs-12 col-sm-6  col-md-3">
                            <dl>
                                <dt>商品名稱</dt>
                                <dd class="justify">{$row['prodname']}</dd>
                            </dl>
                        </div>

                        <div class="col-xs-6 col-sm-6 col-md-2">

                            <dl>
                                <dt>規格</dt>
                                <dd class="justify">
                                    <?php
                                    if(!empty($row["options"])) {
                                        $attries = [];
                                        $json_options = json_decode($row["options"], true);
                                        foreach($json_options as $vname => $options) {
                                            $attries[] = $options["valuename"];
                                        }
                                        echo implode(" / ", $attries);
                                    }
                                    ?>
                                </dd>
                            </dl>

                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-2">
                            <dl>
                                <dt>商品編號</dt>
                                <dd class="justify">{$row['sku']}</dd>
                            </dl>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-2">
                            <dl>
                                <dt>價格</dt>
                                <dd class="justify">$ {$row['prod_price']}</dd>
                            </dl>
                        </div>
                        <div class="col-xs-6 col-sm-12 col-md-1">
                            <dl>
                                <dt>數量</dt>
                                <dd class="justify">{$row['qty']}</dd>
                            </dl>
                        </div>
                    </div>
                    <?php } } ?>

                    <?php
                    if(!empty($gift)) {
                    ?>
                        <div class="location-table row text-center no-gutter">
                            <div class="col-xs-12 col-sm-6  col-md-2">
                                <dl>
                                    <dt>商品圖片</dt>
                                    <dd class="justify"><img src="{:showfile($gift['thumb_image'])}" width="80px" height="80px"></dd>
                                </dl>
                            </div>
                            <div class="col-xs-12 col-sm-6  col-md-3">
                                <dl>
                                    <dt>商品名稱</dt>
                                    <dd class="justify">{$gift['prodname']}</dd>
                                </dl>
                            </div>

                            <div class="col-xs-6 col-sm-6 col-md-2">

                                <dl>
                                    <dt>規格</dt>
                                    <dd class="justify">
                                        贈品
                                    </dd>
                                </dl>

                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-2">
                                <dl>
                                    <dt>商品編號</dt>
                                    <dd class="justify">{$gift['sku']}</dd>
                                </dl>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-2">
                                <dl>
                                    <dt>價格</dt>
                                    <dd class="justify">無</dd>
                                </dl>
                            </div>
                            <div class="col-xs-6 col-sm-12 col-md-1">
                                <dl>
                                    <dt>數量</dt>
                                    <dd class="justify">1</dd>
                                </dl>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="table-set">
                    <div class="location-table1 row text-center no-gutter">
                        <div class="col-xs-12 col-sm-12  col-md-12">
                            <dl>
                                <dt class="col-xs-6 col-md-10 justify text-right">商品小計</dt>
                                <dd class="col-xs-6 col-md-2 justify">$ {$total_item_amount}</dd>
                            </dl>
                        </div>
                        <div class="col-xs-12 col-sm-12  col-md-12">
                            <dl>
                                <dt class="col-xs-6 col-md-10 justify text-right">運費</dt>
                                <dd class="col-xs-6 col-md-2 justify">$ {$order['shipping_fee']}</dd>
                            </dl>
                        </div>
                        <div class="col-xs-12 col-sm-12  col-md-12">
                            <dl>
                                <dt class="col-xs-6 col-md-10 justify text-right">優惠券</dt>
                                <dd class="col-xs-6 col-md-2 justify">- $ {$order['coupon_amount']}</dd>
                            </dl>
                        </div>
                        <div class="col-xs-12 col-sm-12  col-md-12">
                            <dl>
                                <dt class="col-xs-6 col-md-10 justify text-right">紅利點數</dt>
                                <dd class="col-xs-6 col-md-2 justify">- $ {$order['credit_money']}</dd>
                            </dl>
                        </div>
                        <?php
                        if(!empty($order['promotion_rules'])) {
                            $promotion_rules = json_decode($order['promotion_rules'], true);
                            if(!empty($promotion_rules)) {
                        ?>
                        <div class="col-xs-12 col-sm-12  col-md-12">
                            <dl>
                                <dt class="col-xs-6 col-md-10 justify text-right">活動促銷</dt>
                                <dd class="col-xs-6 col-md-2 justify">
                                    <?php
                                    foreach($promotion_rules as $k => $promotion) {
                                    ?>
                                        {$promotion['title']}: - ${$promotion['amount']}<br/>
                                    <?php } ?>
                                </dd>
                            </dl>
                        </div>
                        <?php } } ?>
                        <div class="col-xs-12 col-sm-12  col-md-12">
                            <dl>
                                <dt class="col-xs-6 col-md-10 justify text-right">總計</dt>
                                <dd class="col-xs-6 col-md-2 justify">$ {$order['total_amount']}</dd>
                            </dl>
                        </div>

                    </div>
                </div>
            </div>


            <div class="user-key"><h1>訂單諮詢</h1></div>
            <div class="member-form">
                <div class="">
                    <form class="AjaxForm form-horizontal" action="{:front_link('sendMsg')}" enctype="multipart/form-data" method="post">
                        <input type="hidden" name="oid" value="{$order['oid']}"/>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-xs-12 col-md-12 control-label">諮詢內容</label>
                            <div class="col-xs-12 col-md-12">
                                <input type="text" class="form-control" name="question" id="inputEmail3" maxlength="1000" placeholder="請輸入諮詢內容……">
								<span class=" text-danger" style=" clear:both">客服回覆時間是下午一點～晚上十二點</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-xs-12 col-md-12 control-label"><span class="img-title">選擇圖片<span class=" text-danger img-tips">圖片最多上傳5張！</span></span></label>
                            <div class="col-xs-12 col-md-12 row img-upbox">
                                <div id="imgdiv1" class="col-xs-4 col-md-3 imgdiv row no-gutters">
                                    <label for="up_img1"><img src="" alt="" width="100%" id="imgShow1">
                                        <input type="file" id="up_img1" name="images[]" hidden="true">
                                        <span class="btn btn-danger col-md-12 waves-effect waves-light" style="margin:0;">選擇圖片</span></label>
                                </div>
                                <div id="imgdiv2" class="col-xs-4 col-md-3 imgdiv row no-gutters">
                                    <label for="up_img2"><img src="" alt="" width="100%" id="imgShow2">
                                        <input type="file" id="up_img2" name="images[]" hidden="true">
                                        <span class="btn btn-danger col-md-12 waves-effect waves-light" style="margin:0;">選擇圖片</span></label>
                                </div>
                                <div id="imgdiv3" class="col-xs-4 col-md-3 imgdiv row no-gutters">
                                    <label for="up_img3"><img src="" alt="" width="100%" id="imgShow3">
                                        <input type="file" id="up_img3" name="images[]" hidden="true">
                                        <span class="btn btn-danger col-md-12 waves-effect waves-light" style="margin:0;">選擇圖片</span></label>
                                </div>
                                <div id="imgdiv4" class="col-xs-4 col-md-3 imgdiv row no-gutters">
                                    <label for="up_img4"><img src="" alt="" width="100%" id="imgShow4">
                                        <input type="file" id="up_img4" name="images[]" hidden="true">
                                        <span class="btn btn-danger col-md-12 waves-effect waves-light" style="margin:0;">選擇圖片</span></label>
                                </div>
                                <div id="imgdiv5" class="col-xs-4 col-md-3 imgdiv row no-gutters">
                                    <label for="up_img5"><img src="" alt="" width="100%" id="imgShow5">
                                        <input type="file" id="up_img5" name="images[]" hidden="true">
                                        <span class="btn btn-danger col-md-12 waves-effect waves-light" style="margin:0;">選擇圖片</span></label>
                                </div>

                            </div>
                        </div>

                        <div class="form-group row">
                            <div class=" col-sm-12 col-md-12">
                                <button type="button" class="btn btn-default no-margin-l waves-effect waves-light" id="addimg">新增圖片</button>
                                <button type="submit" class="btn btn-default no-margin-l waves-effect waves-light">送  出</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="table-set">
                    <?php if(!empty($messages)) {
                        foreach($messages as $msg) {
                    ?>
                    <div class="location-table row text-center no-gutter">
                        <div class="col-xs-12 col-sm-12  col-md-2">
                            <dl>
                                <dt>加入日期</dt>
                                <dd class="justify">{:date('Y-m-d H:i:s', $msg['creat_at'])}</dd>
                            </dl>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-2">
                            <dl>
                                <dt>諮詢/回覆</dt>
                                <dd class="justify">{$msg['question']}</dd>
                            </dl></div>
                        <div class="col-xs-6 col-sm-6 col-md-2">
                            <dl>
                                <dt>處理人員</dt>
                                <dd class="justify">{$msg['fullname']}</dd>
                            </dl>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3">
                            <dl>
                                <dt>諮詢/回覆內容</dt>
                                <dd class="justify">
                                    {$msg['answer']}
                                    <br/>
                                    回覆日期：{:$msg['answer_date']>0?date('Y-m-d H:i:s', $msg['answer_date']):''}
                                </dd>
                            </dl>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3">
                            <dl>
                                <dt>圖片</dt>
                                <dd class="justify">
                                    <?php
                                    if(!empty($msg['images'])) {
                                       $images = explode(",", $msg["images"]);
                                       foreach($images as $k => $img) {
                                    ?>
                                    <a target="_blank" href="{:showfile($img)}"><img src="{:showfile($img)}" alt="" width="50"></a>
                                    <?php } } ?>
                                </dd>
                            </dl>
                        </div>
                    </div>
                    <?php } } ?>

                </div>

            </div>
            <?php if($order['LogisticsType'] != "SE") { ?>
            <div class="user-key" id="logistics"><h1>追蹤物流狀態</h1></div>
            <div class="member-form">

                <div class="table-set">

                    <?php
                    $list = get_order_express_status($order["AllPayLogisticsID"]);
                    if(!empty($list)) {
                    $express_types =  return_express_types();
                    $express_types_sub = [];
                    $express_types_sub = array_merge($express_types_sub, LogisticsCVSSubTypes());
                    $express_types_sub = array_merge($express_types_sub, LogisticsHomeSubTypes());
                    foreach ($list as $k => $row) {
                    ?>
                    <div class="location-table row text-center no-gutter">
                        <div class="col-xs-12 col-sm-12  col-md-3">
                            <dl>
                                <dt>物流類型</dt>
                                <dd class="justify"><?php echo isset($express_types[$row['LogisticsType1']])?$express_types[$row['LogisticsType1']]:""; ?></dd>
                            </dl>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-2">
                            <dl>
                                <dt>物流廠商</dt>
                                <dd class="justify"><?php echo isset($express_types_sub[$row['LogisticsType2']])?$express_types_sub[$row['LogisticsType2']]:""; ?></dd>
                            </dl></div>
                        <div class="col-xs-6 col-sm-6 col-md-2">
                            <dl>
                                <dt>狀態</dt>
                                <dd class="justify"><?php echo express_status($row["LogisticsType1"], $row["LogisticsType2"], $row["LogisticsStatus"]); ?></dd>
                            </dl>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-5">
                            <dl>
                                <dt>更新日期</dt>
                                <dd class="justify"><?php echo date('Y-m-d H:i:s', $row['TradeDate']); ?></dd>
                            </dl>
                        </div>
                    </div>
                    <?php } } ?>
					<?php echo $k;?>
					<?php if(!$k) { ?>			
					訂單處理中
					<?php } ?>
                </div>

            </div>
            <?php } ?>
        </div>
    </div>
        </div>
    </section>
    {include file="public/footer" /}
</div>
</div>
<script src="/static/front/js/uploadPreview.js" type="text/javascript"></script>
<script>
    window.onload = function () {
        new uploadPreview({ UpBtn: "up_img1", DivShow: "imgdiv1", ImgShow: "imgShow1" });
        new uploadPreview({ UpBtn: "up_img2", DivShow: "imgdiv2", ImgShow: "imgShow2" });
        new uploadPreview({ UpBtn: "up_img3", DivShow: "imgdiv3", ImgShow: "imgShow3" });
        new uploadPreview({ UpBtn: "up_img4", DivShow: "imgdiv4", ImgShow: "imgShow4" });
        new uploadPreview({ UpBtn: "up_img5", DivShow: "imgdiv5", ImgShow: "imgShow5" });
    }
</script>
<script>
    $(function(){
        $("#addimg").click(function(i){
            var i = 2,
                i = i++;
            if($(".imgdiv:visible").length <= 4){
                var addnum=$(".imgdiv:visible").length+1;
                $('#imgdiv'+addnum).show();
                $(".img-title").show();


            }
            else{
                $(".img-tips").fadeIn();
                setTimeout(function(){  $(".img-tips").fadeOut(); }, 3000);
            }

        });

    });
</script>
</body>
</html>