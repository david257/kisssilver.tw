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
                    <li class="active">訂單記錄</li>
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

                    <div class="user-key"><h1>訂單記錄</h1></div>
                    <div class="member-form">

                        <div class="table-set">
                            <?php
                            if(!empty($orders)) {
                            $pay_methods = get_pay_types();
                            $order_statuses = get_order_states();
                            foreach($orders as $order) {
                            ?>
                            <div class="location-table row text-center no-gutter">
                                <div class="col-xs-6 col-sm-6  col-md-2">
                                    <dl>
                                        <dt>訂單編號</dt>
                                        <dd class="justify"><?php echo $order["oid"];?></dd>
                                    </dl>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-2">
                                    <dl>
                                        <dt>購買日期</dt>
                                        <dd class="justify"><?php echo date("Y/m/d H:i", $order["create_date"]);?></dd>
                                    </dl></div>
                                <div class="col-xs-6 col-sm-6 col-md-2">
                                    <dl>
                                        <dt>訂單狀態</dt>
                                        <dd class="justify">
                                            <?php if($order['pay_status']==1 && in_array($order["order_status"], [0,1, 2])) { ?>
                                                <a class="AjaxTodo" href="{:front_link('Order/cancel_order', ['oid' =>$order['oid']])}">取消訂單</a>
                                            <?php } ?>
                                                {:isset($order_statuses[$order['order_status']])?$order_statuses[$order['order_status']]:'N/A'}
                                        </dd>
                                    </dl>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-2">
                                    <dl>
                                        <dt>訂單金額</dt>
                                        <dd class="justify"><?php echo format_price($order["total_amount"]);?></dd>
                                    </dl>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-2">
                                    <dl>
                                        <dt>付款狀態</dt>
                                        <dd class="justify">
                                            <?php echo $order["pay_status"]?"已付款":"待付款";?>
                                            <?php if($order["order_status"] ===0) { ?>
                                                <small>|</small><a href='<?php echo front_link('Checkout/repay', ['oid' => $order['oid']]);?>'>我要付款</a>
                                            <?php } ?>
                                        </dd>
                                    </dl>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-2">
                                    <dl>
                                        <dt>訂單明細</dt>
                                        <dd class="justify">
                                            <a href="{:front_link('Order/detail', ['oid' => $order['oid']])}">查看明細</a>
                                            <?php if($order['order_status']==5) { ?>
                                                <small>|</small><a href='<?php echo front_link('Order/return_order', ['oid' => $order['oid']]);?>'>我要退貨</a>
                                            <?php } ?>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                            <?php } } ?>
                        </div>
                    </div>
                </div>
                <div class="container">{:$pages}</div>
            </div>
        </div>
    </section>

    {include file="public/footer" /}
</div>
</div>
</body>
</html>