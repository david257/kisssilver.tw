{include file="public/meta" /}
<link rel="stylesheet" type="text/css" href="/static/front/css/list.css">
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
                    <li><a href="{:front_link('Order/returns')}">退貨記錄</a></li>
                    <li class="active">退貨物品明細</li>
                </ol>
            </div>
        </div>
    </section>
    <section class="member">
        <div class="container">

            <div class="row">
                <div class="col-sm-12 col-md-2">
                    <div class="list-group member-nav">
                        <div class="nav-collapse">會員中心<span class="pull-right"><i class="fa fa-bars"></i></span></div>
                        {include file="public/customer_menu" /}
                    </div>
                </div>
                <div class="col-sm-12 col-md-10">

                    <div class="user-key"><h1>退貨物品明細</h1></div>
                    <div class="member-form">
                        <div class="row">

                            <div class="col-xs-12  col-sm-12 col-md-9">

                                <div class="cart-tjtitle">退貨商品摘要<i>共 {$order['total_items']} 件</i></div>
                                <div class="cart-box clearfix">
                                    <hr>
                                    <?php
                                    if(!empty($products)) {
                                        foreach($products as $prod) {
                                    ?>
                                    <div class="cart-list row no-gutter">
                                        <div class="cart-pic col-xs-3 col-sm-3 col-md-2">
                                            <a href="{:front_link('Product/detail', ['prodid' => $prod['prodid']])}"><img src="{:showfile($prod['prodimage'])}" alt="{$prod['prodname']}"></a>
                                        </div>
                                        <div class="col-xs-8 col-sm-8 col-md-9">
                                            <div class="row">
                                                <div class="cart-content col-xs-12 col-sm-8 col-md-8 row no-gutter">
                                                    <p><a href="{:front_link('Product/detail', ['prodid' => $prod['prodid']])}">{$prod['prodname']}</a></p>
                                                    <?php
                                                    if(!empty($prod["options"])) {
                                                        $json_options = json_decode($prod["options"], true);
                                                        foreach($json_options as $vname => $options) {
                                                    ?>
                                                        <p class="col-xs-6"><?php echo $options["attrname"];?>: <i><?php echo $options["valuename"];?></i></p>
                                                    <?php
                                                        }
                                                    }
                                                    ?>

                                                    <p class="col-xs-6">單價：<i>$ {$prod['prod_price']}</i></p>
                                                    <p class="col-xs-6">數量：<i>{$prod['qty']}</i></p>
                                                </div>
                                                <div class="cart-num justify-lg justify-md text-md-right text-sm-right col-xs-12  col-sm-4 col-md-4">

                                                    <div class="cart-number pull-right-xs">
                                                        退款金額: $ {$prod['return_amount']}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <?php } } ?>
                                </div>

                            </div>

                            <div class="col-xs-12  col-sm-12 col-md-3 ">
                                <div class="cart-tj">
                                    <div class="cart-tjtitle">退貨原因</div>
                                    <div class="cart-tjbox ">
                                        <ul class="row">

                                            <li class="col-xs-12 col-md-12">
                                                {$order['return_type']}
                                            <li class="col-xs-12 col-md-12">
                                                備註： {$order['remark']}
                                            </li>
                                        </ul>

                                    </div>

                                </div>

                            </div>
                        </div>


                    </div>



                </div>
            </div>
        </div>
    </section>
    {include file="public/footer" /}
</div>
</div>
</body>
</html>