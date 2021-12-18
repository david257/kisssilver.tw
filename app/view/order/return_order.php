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
                    <li><a href="{:front_link('Order/index')}">訂單記錄</a></li>
                    <li class="active">商品退貨</li>
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

                    <div class="user-key"><h1>請選擇退貨商品</h1></div>
                    <div class="member-form">
                        <div class="row">

                            <div class="col-xs-12  col-sm-12 col-md-9">

                                <div class="cart-tjtitle">退貨商品摘要<i>共 {$totalItems} 件</i></div>
                                <div class="cart-box clearfix">
                                    <hr>
                                    <?php
                                    if(!empty($order_products)) {
                                        foreach($order_products as $prod) {
                                    ?>
                                    <div class="cart-list row no-gutter">
                                        <div class="cart-pic col-xs-3 col-sm-3 col-md-2">
                                            <a href="detail.html"><img src="{:showfile($prod['prodimage'])}" alt="{$prod['prodname']}"></a>
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
                                                        <select class="ret_qty" data-opid="{$prod['opid']}" name="qty">
                                                            <?php
                                                            if($prod['qty']>0) {
                                                            for($i=0; $i<=$prod['qty']; $i++) {?>
                                                            <option value="{$i}">{$i}</option>
                                                            <?php } }  ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <?php } } ?>
                                    <div class="modal fade" id="delete-shop" tabindex="-1" role="dialog">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                    <h4 class="modal-title">商品退貨</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <h4>您確定要將選中商品退回嗎？</h4>
                                                    <h4>如果您確定退貨且Kiss-Silver確認收貨並同意後，請將物品寄回我們，我們收到貨後將安排退款</h4>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default waves-effect waves-light" data-dismiss="modal">取消</button>
                                                    <button type="button" class="btn btn-primary waves-effect waves-light" id="ConfirmReturn">確定退貨</button>
                                                </div>
                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                    </div><!-- /.modal -->
                                </div>

                            </div>


                            <div class="col-xs-12  col-sm-12 col-md-3 ">
                                <div class="cart-tj">
                                    <div class="cart-tjtitle">退貨原因</div>
                                    <div class="cart-tjbox ">
                                        <ul class="row">

                                            <li class="col-xs-12 col-md-12 text-right">
                                                <select class="form-control" name="return_type">
                                                    <option value="">請選擇退貨原因</option>
                                                    <?php
                                                    $returntypes = get_return_types();
                                                    if(!empty($returntypes)) {
                                                        foreach($returntypes as $k => $toname) {
                                                            ?>
                                                            <option value="{$toname}">{$toname}</option>
                                                        <?php } } ?>
                                                </select></li>
                                            <li class="col-xs-12 col-md-12 text-right">
                                                <textarea class="form-control" rows="5" style="height:100px;" name="remark" placeholder="退貨備註"></textarea></li>
                                        </ul>

                                    </div>

                                </div>
                                <div class="cart-tj">
                                    <div class="cart-send">
                                        <a href="javascript:void();" class="btn btn-default no-margin col-md-12 waves-effect waves-light" data-toggle="modal" data-target="#delete-shop" title="確定退貨">確定退貨 <i class="fa fa-long-arrow-right"></i></a>
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

<script>

$("#ConfirmReturn").click(function() {
    var products = [];
    $.each($(".ret_qty"), function() {
        if($(this).val()>0) {
            products.push({opid:$(this).attr("data-opid"), qty: $(this).val()})
        }
    })

    if(!products.length) {
        layer.msg("請選擇退貨商品");
        return false;
    }

    var data = {
        oid: "{$order['oid']}",
        return_type: $("select[name=return_type]").val(),
        remark: $("textarea[name=remark]").val(),
        products:products
    };

    $.ajax({
        url: "{:front_link('do_return_order')}",
        data: data,
        dataType: "json",
        type: "post",
        success: function (json) {
            if(json.code>0) {
                layer.msg(json.msg);
            } else {
                layer.msg(json.msg, {end: function() {
                    document.location.href = json.url;
                } });
            }
        }
    })
})
</script>
</body>
</html>