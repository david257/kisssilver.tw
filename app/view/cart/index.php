{include file="public/meta" /}
<link rel="stylesheet" type="text/css" href="/static/front/css/slider-pro.min.css" media="screen" />
<link rel="stylesheet" type="text/css" href="/static/front/css/examples.css" media="screen" />
<link rel="stylesheet" type="text/css" href="/static/front/css/list.css">
<link rel="stylesheet" href="/static/front/css/viewer.min.css">
<link rel="stylesheet" type="text/css" href="/static/front/css/user.css">

{include file="public/kefu" /}
<div class="index-box">
    {include file="public/header" /}
    <section class="member">
        <div class="container-fluid">
            <div class="sub-loation">
                <ol class="breadcrumb">
                    <li><a href="{:front_link('Index/index')}">首頁</a></li>
                    <li class="active"><a href="#">購物車</a></li>
                </ol>
            </div>
            <div class="row">

                <div class="col-xs-12 col-sm-12 col-md-12 cart-container">

                    <div class="user-key">
                        <h1 class="pull-left">您的購物車<i>共 {:$totalItems} 件</i></h1><a href="#" onclick="history.back(-1)"
                            class="pull-right">繼續購物</a>
                        <div class="clearfix"></div>
                    </div>
                    <div class="cart-tab">

                        <div class="cart-box clearfix">

                            <?php
                            if(!empty($carts)) {
                                foreach($carts as $cartId => $prod) {
                            ?>
                            <div class="cart-list row">
                                <div class="cart-pic col-xs-3 col-sm-3 col-md-2">
                                    <a href="{:front_link('Product/detail', ['prodid' => $prod['prodid']])}"><img
                                            src="{:showfile($prod['prodimage'])}" alt="{:$prod['prodname']}" /></a>
                                </div>
                                <div class="col-xs-8 col-sm-8 col-md-10">
                                    <div class="row">
                                        <div class="cart-content col-xs-12 col-sm-8 col-md-8 row no-gutter">
                                            <p><a
                                                    href="{:front_link('Product/detail', ['prodid' => $prod['prodid']])}">{:$prod['prodname']}</a>
                                            </p>
                                            <p class="col-xs-12 red"> 單價：<i>$ {:$prod['prodprice']}</i></p>
                                            <?php
                                            if(!empty($prod['voptions'])) {
                                            foreach($prod['voptions'] as $k => $voption) {
                                            ?>
                                            <p class="col-xs-12">{:$voption['attrname']}：<i>{:$voption['valuename']}</i>
                                            </p>
                                            <?php } } ?>

                                        </div>
                                        <div
                                            class="cart-num justify-lg justify-md text-md-right text-sm-right col-xs-12  col-sm-4 col-md-4">

                                            <div class="cart-number pull-right">
                                                <input type="text" data-cartid="{$cartId}" value="{:$prod['qty']}"
                                                    class="cartqty spinnerExample value" />
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-12 cart-set">
                                            <ul>
                                                <li><a class="AjaxTodo"
                                                        href="{:front_link('Cart/delete', ['cartId' => $cartId])}"
                                                        data-toggle="modal" data-target="#delete-shop"><svg class="icon"
                                                            aria-hidden="true">
                                                            <use xlink:href="#icon-icon_huabanfuben"></use>
                                                        </svg>刪除</a></li>
                                                <li><a href="javascript:void();"
                                                        onclick="get_prod_data('{$cartId}', {$prod['prodid']})"
                                                        data-toggle="modal" data-target="#edit-shop"><svg class="icon"
                                                            aria-hidden="true">
                                                            <use xlink:href="#icon-xiugai"></use>
                                                        </svg>編輯</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr />
                            <?php } } ?>
                            <div class="cart-send">
                                <a href="javascript:void(0)" id="CheckoutBtn"
                                    class="btn btn-default no-margin pull-right col-xs-12 col-md-3" title="下一步">下一步<i
                                        class="fa fa-long-arrow-right"></i></a>
                            </div>
                            <div class="modal fade" id="delete-shop" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">刪除商品</h4>
                                        </div>
                                        <div class="modal-body">
                                            <h4>您確定要將本商品從購物車中刪除嗎？</h4>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default"
                                                data-dismiss="modal">取消</button>
                                            <button type="button" class="btn btn-primary">確定</button>
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->
                        </div>
                    </div>




                    <div class="modal fade" id="edit-shop" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div id="editProd" class="modal-content">

                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                </div>
                <!-- Modal -->
                <div class="modal fade modal-detail" id="size" tabindex="-1" role="dialog"
                    aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">尺碼表</h4>
                            </div>
                            <div class="modal-body">
                                <div class="container" data-auto-id="size-chart-container">
                                    <section data-auto-id="sizechart-sizing" class="gl-vspacing-l">
                                        <header>
                                            <h4 class="title___za8ye">尺碼表</h4>
                                            <h5>找到合適您的尺碼</h5>
                                        </header>
                                        <div class="gl-table gl-table--horizontal">
                                            <div class="gl-table__container">
                                                <div class="gl-table__fixed-columns">
                                                    <table class="gl-table__side-labels">
                                                        <tbody>
                                                            <tr>
                                                                <td class="gl-table__label"><span>Product label</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="gl-table__label"><span>Chest</span></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="gl-table__label"><span>Waist</span></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="gl-table__label"><span>Hip</span></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="gl-table__scrollable-columns">
                                                    <table class="gl-table gl-table--scrollable">
                                                        <thead>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="1"><span class="gl-table__content">XS 30/32
                                                                        | 32/34</span></td>
                                                                <td colspan="1"><span class="gl-table__content">S 34/36
                                                                        | 36/38</span></td>
                                                                <td colspan="1"><span class="gl-table__content">M 38/40
                                                                        | 40/42</span></td>
                                                                <td colspan="1"><span class="gl-table__content">L 42/44
                                                                        | 44/46</span></td>
                                                                <td colspan="1"><span class="gl-table__content">XL 46/48
                                                                        | 48/50</span></td>
                                                                <td colspan="1"><span class="gl-table__content">2XL
                                                                        50/52 | 52/54</span></td>
                                                                <td colspan="1"><span class="gl-table__content">3XL
                                                                        54/56 | 56/58</span></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="1"><span class="gl-table__content">31 -
                                                                        33"</span></td>
                                                                <td colspan="1"><span class="gl-table__content">34 -
                                                                        37"</span></td>
                                                                <td colspan="1"><span class="gl-table__content">37 -
                                                                        40"</span></td>
                                                                <td colspan="1"><span class="gl-table__content">40 -
                                                                        44"</span></td>
                                                                <td colspan="1"><span class="gl-table__content">44 -
                                                                        48"</span></td>
                                                                <td colspan="1"><span class="gl-table__content">48 -
                                                                        52"</span></td>
                                                                <td colspan="1"><span class="gl-table__content">53 -
                                                                        58"</span></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="1"><span class="gl-table__content">27 -
                                                                        29"</span></td>
                                                                <td colspan="1"><span class="gl-table__content">30 -
                                                                        32"</span></td>
                                                                <td colspan="1"><span class="gl-table__content">32 -
                                                                        35"</span></td>
                                                                <td colspan="1"><span class="gl-table__content">35 -
                                                                        39"</span></td>
                                                                <td colspan="1"><span class="gl-table__content">39 -
                                                                        43"</span></td>
                                                                <td colspan="1"><span class="gl-table__content">43 -
                                                                        47"</span></td>
                                                                <td colspan="1"><span class="gl-table__content">48 -
                                                                        53"</span></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="1"><span class="gl-table__content">32 -
                                                                        34"</span></td>
                                                                <td colspan="1"><span class="gl-table__content">35 -
                                                                        37"</span></td>
                                                                <td colspan="1"><span class="gl-table__content">37 -
                                                                        40"</span></td>
                                                                <td colspan="1"><span class="gl-table__content">40 -
                                                                        44"</span></td>
                                                                <td colspan="1"><span class="gl-table__content">44 -
                                                                        48"</span></td>
                                                                <td colspan="1"><span class="gl-table__content">48 -
                                                                        51"</span></td>
                                                                <td colspan="1"><span class="gl-table__content">51 -
                                                                        56"</span></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </section>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="detail-tj">
        <div class="container-fluid">
            <h3>你可能喜歡</h3>
            <div class="owl-carousel">
                <?php
                if(!empty($viewed_items)) {
                    foreach($viewed_items as $prod) {
                        $image = isset($prod['image'])?$prod['image']:'';
                        $image2 = isset($prod['image2'])?$prod['image2']:$image;
                        ?>
                <div class="item">
                    <div class="list-one detail">
                        <div class="list-pic"><a
                                href="{:front_link('Product/detail', ['prodid' => $prod['prodid']])}"><img
                                    src="{$image}" onMouseOver="javascript:this.src='{$image2}'"
                                    onMouseOut="javascript:this.src='{$image}'" alt="" /></a></div>
                        <div class="list-content">
                            <a href="{:front_link('Product/detail', ['prodid' => $prod['prodid']])}">
                                <div class="name">{:$prod['prodname']}</div>
                                <!-- <h4>{:$prod['prod_features']}</h4> -->
                                <div class="price">
                                    <span class="current-price">{:price_label($prod)}</span>
                                    <div class="list-icon"><a
                                            href="{:front_link('Wishlist/add', ['prodid' => $prod['prodid']])}"
                                            class="AjaxTodo">
                                            <svg class="icon" aria-hidden="true">
                                                <use xlink:href="#icon-aixin"></use>
                                            </svg>
                                        </a> <a href="#">
                                            <svg class="icon" aria-hidden="true">
                                                <use xlink:href="#icon-gouwu1"></use>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <?php } } ?>

            </div>
        </div>
    </section>
    {include file="public/footer" /}
</div>
</div>
<script src="/static/front/dist/owl.carousel.js"></script>
<script type="text/javascript" src="/static/front/js/jquery.spinner1.js"></script>
<script type="text/javascript" src="/static/front/js/jquery.sliderPro.min.js"></script>
<script type="text/javascript">
$('.spinnerExample').spinner({});
</script>
<script>
$(".decrease,.increase").click(function() {
    qty = $(this).parent().find(".cartqty").val();
    cartId = $(this).parent().find(".cartqty").attr("data-cartid");
    $.ajax({
        url: '{:front_link("Cart/updateQty")}',
        data: {
            cartId: cartId,
            qty: qty
        },
        type: "POST",
        dataType: "JSON",
        success: function(json) {
            if (json.code > 0) {
                layer.alert(json.msg, {
                    icon: 1
                });
            }
        }
    })
})
</script>
<script>
function get_prod_data(cartId, prodid) {
    $.getJSON('{:front_link("Product/editCart")}', {
        prodid: prodid,
        cartId: cartId
    }, function(json) {
        if (json.code > 0) {
            layer.msg(json.msg);
        } else {
            $("#editProd").html(json.html);
        }
    })
}
</script>
<script>
$("#CheckoutBtn").click(function() {
    document.location.href = "{:front_link('Checkout/index')}";
})
</script>
</body>

</html>