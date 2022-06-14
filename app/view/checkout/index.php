{include file="public/meta" /}
<link rel="stylesheet" type="text/css" href="/static/front/css/slider-pro.min.css" media="screen"/>
<link rel="stylesheet" type="text/css" href="/static/front/css/examples.css" media="screen"/>
<link rel="stylesheet" type="text/css" href="/static/front/css/list.css">
<link rel="stylesheet" href="/static/front/css/viewer.min.css">
<link rel="stylesheet" type="text/css" href="/static/front/css/user.css">
<style>
    #se_address {
        border: 1px solid #ddd;
        padding: 5px;
        background: beige;
        display: none;
    }
    .editaddress {
        color: blue;
        text-decoration: underline;
    }
</style>
{include file="public/kefu" /}
<div class="index-box">
    {include file="public/header" /}
    <section class="sub">
        <div class="container">
            <div class="sub-loation">
                <ol class="breadcrumb">
                    <li><a href="{:front_link('Index/index')}">首頁</a></li>
                    <li><a href="{:front_link('Cart/index')}">購物車</a></li>
                    <li class="active">訂單填寫</li>
                </ol>
            </div>
        </div>
    </section>
    <section class="member">
        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    <div class="user-key"><h1>訂購人地址<span class="text-danger text-small">*必填</span></h1></div>
                    <div class="member-form">
                        <div class="location-list">
                            <h4>以下為常用地址</h4>
                            <div class="table-set">
                                <?php
                                if(!empty($addresses)) {
                                    foreach($addresses as $address) {
                                    if($address["is_default"]) {
                                ?>
                                <div class="location-table row text-center no-gutter">
                                    <div class="col-xs-3 col-md-1">
                                        <dl>
                                            <dt>選擇</dt>
                                            <dd class="justify"><input type="radio" data="" name="addrid" value="{:$address['addrid']}"></dd>
                                        </dl>
                                    </div>
                                    <div class="col-xs-2 col-md-3">
                                        <dl>
                                            <dt>收件人</dt>
                                            <dd class="justify">{:$address['fullname']}</dd>
                                        </dl>
                                    </div>
                                    <div class="col-xs-2 col-md-3">
                                        <dl>
                                            <dt>電話</dt>
                                            <dd class="justify">{:$address['tel']}</dd>
                                        </dl></div>
                                    <div class="col-xs-5 col-md-5">
                                        <dl>
                                            <dt>地址</dt>
                                            <dd class="justify">{:GetCountryName($address['cityid'])}{:GetCountryName($address['areaid'])}{:$address['address']} <a class="editaddress" onclick="edit_address({:$address['addrid']})" href="javascript:void(0);">編輯</a></dd>
                                        </dl>
                                    </div>
                                </div>
                            <?php } else { ?>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="addrid" value="{:$address['addrid']}">
                                    <b>{:$address['fullname']}</b>{:GetCountryName($address['cityid'])}{:GetCountryName($address['areaid'])}{:$address['address']} 電話:{:$address['tel']} {:$address['address']} <a class="editaddress" onclick="edit_address({:$address['addrid']})" href="javascript:void(0);">編輯</a>
                                </label>
                            </div>
                            <?php } } } ?>
                            </div>
                        </div>
                        <div class="location-check-btn"><button class="btn btn-default news-loc waves-effect waves-light">使用新地址</button><button class="btn btn-default  old-loc waves-effect waves-light">使用常用地址</button></div>
                        <form class="AjaxForm form-horizontal news-loc-form row " method="post" action="{:front_link('Customer/saveAddress')}">
                            <input type="hidden" name="addrid" value="0"/>
                            <div class="form-group  col-md-6">
                                <label for="inputEmail3" class="col-sm-12 col-md-12 control-label"><span class="text-danger">*</span>收件人</label>
                                <div class="col-sm-12 col-md-12">
                                    <input type="text" class="form-control" name="fullname" placeholder="小白">

                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="tell" class="col-sm-12 col-md-12 control-label"><span class="text-danger">*</span> 聯絡市話</label>
                                <div class="col-sm-12 col-md-12">
                                    <input type="text" class="form-control" name="tel" placeholder="02-33336666">
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="tell" class="col-sm-12 col-md-12 control-label"><span class="text-danger">*</span> 手機號碼</label>
                                <div class="col-sm-12 col-md-12">
                                    <input type="text" class="form-control" name="mobile" placeholder="">
                                </div>
                            </div>
                            <div class="cityarea form-group col-md-12">
                                <label for="birthday" class="col-sm-12 col-md-12 control-label"><span class="text-danger">*</span>地址</label>

                                <div class="col-sm-6 col-md-6 pull-left ">
                                    <select class="form-control" name="provid" style="display: none;">
                                    </select>
                                    <select class="form-control" name="cityid">
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-6 pull-left no-padding-r">
                                    <select class="form-control" name="areaid">
                                    </select>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-sm-12 col-md-12">
                                    <input type="text" class="form-control" name="address" placeholder="仁愛路10巷10號10F">
                                </div>
                            </div>
                            <div class="cityarea form-group col-md-12">
                                <label for="birthday" class="col-sm-12 col-md-12 control-label"><span class="text-danger">*</span>郵遞區號</label>
                                <div class="col-xs-12 col-md-12">
                                    <input type="text"  class="form-control" id="postcode" name="postcode" placeholder="郵遞區號">
                                </div>
                            </div>
                            <div class="form-group  col-md-12">
                                <div class=" col-sm-12 col-md-12">
                                    <button type="submit" class="btn btn-default no-margin-l waves-effect waves-light">儲  存</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="user-key"><h1>取貨方式</h1></div>
                    <div class="member-form order-pei">
                        <dl>
                            <dt>配送方式</dt>
                            <dd>
                                <?php
                                $express_types = express_types();
                                if(!empty($express_types)) {
                                    foreach($express_types as $k => $v) {
                                        if($k==$LogisticsType) {
                                            $checked = "checked";
                                        } else {
                                            $checked  ="";
                                        }
                                        ?>
                                        <label>
                                            <input type="radio" <?php echo $checked;?> name="LogisticsType" value="<?php echo $k;?>">
                                            <?php echo $v;?>
                                        </label>
                                    <?php } } ?>
                            </dd>
                        </dl>
                        <dl>
                            <dt>物流子類型 </dt>
                            <dd>
                                <div class="select">
                                    <select id="changeLogisticsSubType" name="LogisticsSubType"></select>
                                    <div id="se_address"></div>
                                </div>
                                <span id="choose_mendian" style="display: none;"><a id="storename" onclick="return check_href(this)" target="_blank" href="" style="font-size:12px;color:blue;text-decoration: underline;">*選擇門市...</a></span>
                            </dd>
                        </dl>
                        <dl class="Express CVS" style="display: none;">
                            <dt>超商取貨<em>(必填)</em></dt>
                            <dd>
                                <div>門市名稱: <input type="text" name="CVSStoreName" readonly placeholder="請點擊選擇門市按鈕獲取" class="ipt-m4"/></div>
                                <div>門市地址: <input type="text" name="CVSAddress" readonly placeholder="請點擊選擇門市按鈕獲取" class="ipt-m4"/></div>
                                <input type="hidden" name="CVSStoreID"  value=""/>
                                <input type="hidden" name="CVSTelephone" value=""/>
                            </dd>

                        </dl>
                    </div>
                    <div class="user-key"><h1>付款方式</h1></div>

                    <div class="member-form order-pei">
						<div class="row">
                        <?php
                        $pay_types = get_pay_types();
                        if(!empty($pay_types)) {
                        foreach($pay_types as $k => $v) {
                        if($v["state"] && in_array($k, $pay_tpyes)) {
                        ?>
                        <div class="col-md-3 paytypes paytype<?php echo $k;?>">
                            <div class="radio">
                                <label class="diaplay-block">
                                    <input type="radio" name="pay_type" value="<?php echo $k;?>" >
                                    <?php echo $v["title"];?><?php echo $v["desc"];?>
                                </label>
                            </div>
                        </div>
                        <?php } } } ?>
</div>

                    </div>
                    <div class="user-key"><h1>發票</h1></div>

                    <div class="member-form">

                        <div class="fp-box row">
                                <div class="form-group  col-md-6">
                                    <label for="birthday" class="col-sm-12 col-md-12 control-label"><span class="text-danger">*</span>發票類型</label>
                                    <div class="col-sm-12 col-md-12">
                                        <label class="radio-inline" id="two-fq">
                                            <input type="radio" name="invoice_type" checked value="2"> 二聯式發票
                                        </label>
                                        <label class="radio-inline" id="three-fq">
                                            <input type="radio" name="invoice_type" value="3"> 三聯式發票
                                        </label>


                                    </div>
                                </div>
                                <div class="form-group  col-md-6">
                                    <label for="inputEmail3" class="col-xs-12 col-md-12 control-label">電子發票Email通知</label>
                                    <div class="col-xs-12 col-md-12">
                                        <input type="text" class="form-control col-md-12" name="invoice_email" value="{$customer['custconemail']}" placeholder="Email">

                                    </div>
                                </div>
                                <div class="two-fq">
                                    <div class="row col-md-12">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-xs-12 col-md-12 control-label">發票載具</label>
                                                <div class="col-xs-12 col-md-12">
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" name="invoice_zaiju" value="invoice_code">
                                                            手機條碼載具
                                                        </label>
                                                    </div>
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" name="invoice_zaiju" value="pc_code">
                                                            自然人憑證載具
                                                        </label>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="tell" class="col-xs-12 col-md-12 control-label">發票捐贈</label>
                                                <div class="col-xs-12 col-md-12">

                                                    <select class="form-control  col-md-12" id="donate_to" name="donate_to">
                                                        <option value="0">捐贈單位</option>
                                                        <?php
                                                        $invoiceto = get_invoice_to();
                                                        if(!empty($invoiceto)) {
                                                            foreach($invoiceto as $k => $toname) {
                                                        ?>
                                                        <option value="{$k}">{$toname}</option>
                                                        <?php } } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="three-fq">
                                    <div class="row col-md-12">
                                        <div class="form-group  col-md-6">
                                            <label for="inputEmail3" class="col-xs-12 col-md-12 control-label"><span class="text-danger">*</span>發票抬頭</label>
                                            <div class="col-xs-12 col-md-12">
                                                <input type="text" class="form-control col-md-12" name="invoice_header" placeholder="發票抬頭">

                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="tell" class="col-xs-12 col-md-12 control-label"><span class="text-danger">*</span> 統一編號</label>
                                            <div class="col-xs-12 col-md-12">
                                                <input type="text" class="form-control col-md-12" name="invoice_no" placeholder="統一編號">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>

                </div>
                <!-- Modal -->

                <div class="col-md-3">
                    <div class="cart-tj">
                        <div class="cart-tjtitle">訂單摘要<i>共 {$totalItems} 件</i></div>
                        <div class="cart-tjbox ">
                            <?php
                            if(!empty($carts)) {
                            foreach($carts as $cartId => $prod) {
                            ?>
                            <div class="cart-list row no-gutter">

                                <div class="cart-pic col-xs-6 col-md-4">
                                    <a href="{:front_link('Product/detail', ['prodid' => $prod['prodid']])}"><img src="{:showfile($prod['prodimage'])}" alt="{:$prod['prodname']}"></a>
                                </div>

                                <div class="cart-content col-xs-6 col-md-8">
                                    <p><a href="{:front_link('Product/detail', ['prodid' => $prod['prodid']])}">{:$prod['prodname']}</a></p>
                                    <?php
                                    if(!empty($prod['voptions'])) {
                                        foreach($prod['voptions'] as $k => $voption) {
                                            ?>
                                            <p>{:$voption['attrname']}：<b>{:$voption['valuename']}</b></p>
                                        <?php } } ?>
                                    <div class="text-right">
                                        <p>$ {:$prod['prodprice']} x {:$prod['qty']}</p>
                                        <p><b>$ <?php echo $prod['prodprice']*$prod['qty'];?></b></p>
                                    </div>
                                </div>


                            </div>
                            <hr>
                            <?php } } ?>



                            <div class="order-sales">
                                <h5>使用優惠券</h5>
                                <p>整筆訂單為Kiss-Silver非特價商品時才可以使用優惠券。</p>

                                <select class="form-control" name="coupon_code">
                                    <option value="0">請選擇您的優惠券</option>
                                    <?php
                                    if(!empty($coupons)) {
                                        foreach($coupons as $coupon) {
                                    ?>
                                    <option value="{:$coupon['code']}">{:$coupon['code']}&nbsp;&nbsp;&nbsp;&nbsp;{:$coupon['amount']}元</option>
                                    <?php } } ?>
                                </select>

                            </div>
                            <hr>
                            <div class="order-sales">
                                <h5>使用紅利點數<b>餘額：{$customer['credits']} 點</b></h5>
                                <p>100紅利點數抵1元。</p>

                                <div><p><label>使用 <input type="text" min="100" step="100" max="{$customer['credits']}" class="form-control " name="credits" placeholder="">點紅利點數</label></p></div>

                            </div>
                            <hr>
                            <div class="order-sales">
                                <div><p>
                                            折扣碼： <input type="text" class="form-control " autocomplete="off" name="code" placeholder="請輸入折扣碼">
                                        <div class="discount-code-tip"></div>
                                    </p></div>
                            </div>
                            <hr>
                            <ul class="row">
                                <li class="col-sx-5 col-md-5 text-left">商品金額</li>
                                <li class="col-sx-7 col-md-7 text-right"><b>$ {$subTotal}</b></li>
                            </ul>
                            <ul class="row">
                                <li class="col-sx-5 col-md-5 text-left">運費</li>
                                <li class="col-sx-7 col-md-7 text-right"><b>$ {$total_shipping_fee}</b></li>
                            </ul>
                            <!--<ul class="row">
                                <li class="col-sx-5 col-md-5 text-left">優惠金額</li>
                                <li class="col-sx-7 col-md-7 text-right"><b class="text-danger">- $ 100</b></li>
                              </ul>-->
                            <span id="promotionlist">
                              <?php if(!empty($promotion_rules)) {
                    foreach($promotion_rules as $k => $promotion) {
                    ?>
             
                    <ul class="row">
                                <li class="col-sx-5 col-md-5 text-left">{$promotion['title']}</li>
                                <li class="col-sx-7 col-md-7 text-right"><b class="text-danger">- {:format_price($promotion['amount'])}</b></li>
                              </ul>
                     
                        <?php
                        if(!empty($promotion["gifts"])) {
                        ?>
            
                            <?php
                            $totalgift = count($promotion["gifts"]);
                            foreach ($promotion["gifts"] as $giftid => $gift) {
                            ?>
                    
                            <div class="cart-list row no-gutter">
								<div class="cart-pic col-xs-2 col-md-2">
                                <input type="radio" class="giftid" name="giftid" <?php echo $totalgift==1?'checked':"";?> value="{$giftid}"/>
                                </div>					
                                <div class="cart-pic col-xs-4 col-md-4">
                                    <img src="{:showfile($gift['thumb_image'])}" alt="{:$prod['prodname']}"></a>
                                </div>

                                <div class="cart-content col-xs-6 col-md-6">
                                    <p>{$gift['prodname']}</p>
                                    <div class="text-right">
                                        <p><b>$ 0</b></p>
                                    </div>
                                </div>


                            </div>
                            <?php } ?>
                        <hr>
                        <?php } ?>
                  
                    <?php }} ?>
                            </span>
                            <ul class="row">
                                <li class="col-sx-5 col-md-5 text-left">紅利點數抵扣</li>
                                <li class="col-sx-7 col-md-7 text-right"><b class="text-danger">- $ <i id="credits">0</i></b></li>
                            </ul>
                            <ul class="row">
                                <li class="col-sx-5 col-md-5 text-left"><h4>商品總計</h4></li>
                                <li class="col-sx-7 col-md-7 text-right"><h4><b>$ <i id="payAmount">0</i></b></h4></li>
                            </ul>
                            <ul class="row">
                                <li class="col-sx-5 col-md-5 text-left">獲得紅利點數</li>
                                <li class="col-sx-7 col-md-7 text-right"><b class="text-danger"><i id="getcredits">0</i> 點</b></li>
                            </ul>
                        </span>

                    </div>
                    
                    
                    
                    
                </div>
            </div>
			<div class="user-key"><p>
				<textarea name="ordnote" id="ordnote" class="form-control col-md-12" rows="3" placeholder="訂單備註..." maxlength="1000"></textarea>
			</p></div>
            <div class="user-key"><p><label><input id="iagreen" class="" type="checkbox"> 我接受Kiss-Silver<a href="clause.html">服務條款</a>和<a href="clause.html">隱私權政策</a></label></p></div>
            <div class="order-btn"><button type="button" id="make_order" class="btn btn-default waves-effect waves-light">送出訂單  <i class="fa fa-long-arrow-right"></i></button></div>
        </div>
  

</div>  
</section>
</div>
<input id="seaddress" type="hidden" value="{$se_address}">
{include file="public/footer" /}
<script src="/static/front/dist/owl.carousel.js"></script>
<script type="text/javascript" src="/static/front/js/jquery.spinner.js"></script>
<script type="text/javascript" src="/static/front/js/jquery.sliderPro.min.js"></script>
{include file="public/city" /}
<script>
    $("input[name=credits]").blur(function() {
        var obj = $(this);
        var credits = $(this).val();
        $.getJSON('{:front_link("Checkout/getCreditMoney")}', {credits:credits}, function(json) {
            $("#credits").text(0);

            if(json.code>0) {
                obj.val(0);
                layer.msg(json.msg);return false;
            }
            $("#credits").text(json.money);
            calcPayAmount();
        })
    })

    $("select[name=coupon_code]").change(function() {
        calcPayAmount();
    })

    $("input[name=pay_type]").click(function() {
        calcPayAmount();
    })

    function calcPayAmount()
    {
        credits = $("input[name=credits]").val();
        coupon_code = $("select[name=coupon_code]").val();
        pay_type = $("input[name=pay_type]:checked").val();
        _code = $("input[name=code]").val();
        LogisticsType = $("input[name=LogisticsType]:checked").val();
        $.getJSON('{:front_link("Checkout/getPayAmount")}', {code:_code,logisticstype:LogisticsType,pay_type:pay_type, credits:credits, coupon_code:coupon_code}, function(json) {
            $("#payAmount").text(json.payAmount);
            $("#getcredits").text(json.getCredits);
            $("#promotionlist").html(json.promotionList);
        })
    }
    $(function() {
        calcPayAmount();
    })

    $("input[name=LogisticsType]").click(function() {
        calcPayAmount();
    })

	$("input[name=addrid]").click(function() {
		localStorage.setItem('addrid', $(this).val());
	})
</script>
<script>
    var coverbox;
    $(".cityarea").distpicker();
    $("#make_order").click(function() {
        if(!$("#iagreen").is(":checked")) {
            layer.msg("請接受Kiss-Silver服務條款和隱私權政策")
            return false;
        }

        var addrid = $("input[name=addrid]:checked").val();
        var LogisticsType = $("input[name=LogisticsType]:checked").val();
        var LogisticsSubType = $("#changeLogisticsSubType").val();

        var CVSStoreName = $("input[name=CVSStoreName]").val();
        var CVSAddress = $("input[name=CVSAddress]").val();
        var CVSStoreID = $("input[name=CVSStoreID]").val();
        var CVSTelephone = $("input[name=CVSTelephone]").val();

        var pay_type = $("input[name=pay_type]:checked").val();
        var invoice_type = $("input[name=invoice_type]:checked").val();
        var invoice_header = $("input[name=invoice_header]").val();
        var invoice_zaiju = $("input[name=invoice_zaiju]:checked").val();
        var invoice_email = $("input[name=invoice_email]").val();
        var invoice_no = $("input[name=invoice_no]").val();
        var donate_to  =$("#donate_to").val();

        var coupon_code = $("select[name=coupon_code]").val();
        var code = $("input[name=code]").val();
        var credits = $("input[name=credits]").val();
        var giftid = $(".giftid:checked").val();

		var ordnote = $("#ordnote").val();

        if($(".giftid").length && (giftid===undefined || giftid===null)) {
            layer.msg("請選擇贈品");
            return false;
        }

        if(credits%100 != 0) {
            layer.msg("紅利點數請輸入100的整數倍");
            return  false;
        }

        var jsonParams = {
            addrid:addrid===undefined?0:addrid,
            LogisticsType:LogisticsType===undefined?'':LogisticsType,
            LogisticsSubType:(LogisticsSubType===undefined || LogisticsSubType===0)?'':LogisticsSubType,
            CVSStoreName:CVSStoreName===undefined?'':CVSStoreName,
            CVSAddress:CVSAddress===undefined?'':CVSAddress,
            CVSStoreID:CVSStoreID===undefined?'':CVSStoreID,
            CVSTelephone:CVSTelephone===undefined?'':CVSTelephone,
            pay_type:pay_type===undefined?'':pay_type,
            invoice_type:invoice_type===undefined?'':invoice_type,
            invoice_header:invoice_header===undefined?'':invoice_header,
            invoice_zaiju:invoice_zaiju===undefined?'':invoice_zaiju,
            invoice_email:invoice_email===undefined?'':invoice_email,
            invoice_no:invoice_no===undefined?'':invoice_no,
            donate_to:donate_to===undefined?'':donate_to,
            coupon_code:coupon_code===undefined?'':coupon_code,
            code:code===undefined?'':code,
            credits:credits===undefined?'':credits,
            giftid:giftid===undefined?0:giftid,
            ordnote:ordnote===undefined?'':ordnote
        };

        //set shipping type
        $.ajax({
            url: "<?php echo front_link('Checkout/makeOrder');?>",
            type: "post",
            dataType: "json",
            data: jsonParams,
            beforeSend: function() {
                coverbox = layer.load(0, {
                    shade: [0.1,'#fff'] //0.1透明度的白色背景
                });
            },
            success: function (json) {
                if(json.code) {
                    layer.msg(json.msg);
                    return false;
                } else {
                    layer.msg("等待跳轉中...");
                    window.location.href = json.url;
                }

            },
            complete: function() {
                layer.close(coverbox)
            }
        })
    })
</script>
<script>
    <?php
        $wuliuTypes = get_wuliu_paytypes();
    ?>
    var wuliuTypes = <?php echo json_encode($wuliuTypes, JSON_UNESCAPED_UNICODE);?>;
    var LogisticsType = "{:$LogisticsType}";
    $(function() {
        getSubTypes(LogisticsType);

		<?php if($ref == 'ecpay_map') { ?>
			var addrid = localStorage.getItem('addrid');
			$("input[name=addrid][value="+addrid+"]").click();
			$("input[name=LogisticsType][value=CVS]").click();
		<?php } ?>
    })

    $("input[name=LogisticsType]").click(function() {
        LogisticsType = $(this).val();
        getSubTypes(LogisticsType);

        $(".paytypes").hide();
        $.each(wuliuTypes[LogisticsType], function(i, type) {
            $(".paytype"+type).show();
        })
    })

    var initLogisticsType = $("input[name=LogisticsType]:checked").val();
    if(initLogisticsType=="HOME" || initLogisticsType=="CVS" || initLogisticsType=="SE") {
        $(".paytypes").hide();
        $.each(wuliuTypes[initLogisticsType], function(i, type) {
            $(".paytype"+type).show();
        })
    }

    function getSubTypes(LogisticsType) {
        $(".Express").hide();
        $("#popwindow").hide();
        $("."+LogisticsType).show();
        $.get("<?php echo url('Cart/get_logistics_subtype');?>", {LogisticsType:LogisticsType}, function(data) {
            $("select[name=LogisticsSubType]").html(data);
            $("#choose_mendian").hide();
            if(LogisticsType=="CVS") {
				$("#changeLogisticsSubType").val(localStorage.getItem('LogisticsSubType')).change();
				setCSVData();
                $("#choose_mendian").show();
            }
        })

		localStorage.setItem('LogisticsType', LogisticsType);
        if(LogisticsType=="SE") {
            $("#se_address").html($("#seaddress").val()).show();
            $("#changeLogisticsSubType").hide();
        } else {
            $("#se_address").html('').hide();
            $("#changeLogisticsSubType").show();
        }
    }

    $("#choose_mendian input").click(function() {
        changeLogisticsSubType();
    })

    function changeLogisticsSubType() {
        var subtype = $("#changeLogisticsSubType").val();
        if(subtype === "") {
            alert("請選擇物流子類型");
            return false;
        }
        var new_win = $("#changeLogisticsSubType").find("option:selected").attr("rel");
        if(new_win==1) {
            show_stores(subtype);
        }
    }

    function show_stores(stype) {
        //var url = '<?php echo url("express/map");?>?LogisticsSubType='+stype;
        //$("#storename").attr("href", url);
        //window.open('<?php echo url("express/map");?>?LogisticsSubType='+stype,'_blank','width='+(window.screen.availWidth)/2+',height='+(window.screen.availHeight)/2+',top='+(window.screen.availHeight)/4+', left='+(window.screen.availWidth)/4+'menubar=no,toolbar=no,status=no,scrollbars=yes')
    }
    var openedPage;
    function check_href(obj) {
        if($(obj).attr("href")==="") {
            alert("請選擇物流子類型");
            return false;
        }
        localStorage.setItem('close_command', '');
        openedPage = window.open($(obj).attr("href"));
        return false;
    }

    var tt = setInterval(function(){
        close_command = localStorage.getItem('close_command');
        if(close_command == "close") {
            openedPage.close();
            clearInterval(tt);
        }
    }, 200);

    $("#changeLogisticsSubType").change(function() {
        <?php if($ref != 'ecpay_map') { ?>
		localStorage.clear();
		<?php } ?>
		localStorage.setItem('LogisticsSubType', $(this).val());
        var url = '<?php echo front_link("Express/map");?>?LogisticsSubType='+$(this).val();
        $("#storename").attr("href", url);
    });

    $("#choose_mendian").click(function() {
        setCSVData();
    });

    function setCSVData() {
		var t = setInterval(function setStoreAddress() {
            var jsonStr = localStorage.getItem('csv_data');
            if(jsonStr != null) {
                var json = JSON.parse(jsonStr);
                $("input[name=CVSStoreID]").val(json.CVSStoreID);
                $("input[name=CVSStoreName]").val(json.CVSStoreName);
                $("input[name=CVSTelephone]").val(json.CVSTelephone);
                $("input[name=CVSAddress]").val(json.CVSAddress);
            }
        }, 500);
	}
</script>
<script>
    $("input[name=code]").blur(function() {
        var obj = $(this);
        var _code = obj.val();
        if(_code=="") {
            $(".discount-code-tip").text("");
        }
        credits = $("input[name=credits]").val();
        coupon_code = $("select[name=coupon_code]").val();
        pay_type = $("input[name=pay_type]:checked").val();
        $.getJSON('{:url("checkCouponCode")}', {code:_code,credits:credits,coupon_code:coupon_code,pay_type}, function(json) {
            if (json.code>0) {
                obj.val("");
                $(".discount-code-tip").removeClass("text-success").addClass("text-danger").text(json.msg)
            } else {
                $(".discount-code-tip").removeClass("text-danger").addClass("text-success").text(json.msg)
            }
            calcPayAmount();
        })
    })
</script>
<script>
    $("select[name=areaid]").change(function() {
        var id = $(this).val();
        getPostcode(id, 'postcode');
    })

    $(".news-loc").click(function() {
        $("input[name=addrid]").val(0);
        $("input[name=fullname]").val('');
        $("input[name=tel]").val('');
        $("input[name=mobile]").val('');
        $("select[name=cityid]").val(0);
        $("select[name=cityid]").change();
        setTimeout(function() {
            $("select[name=areaid]").val(0);
        }, 200)
        $("input[name=postcode]").val('');
        $("input[name=address]").val('');
    });

    function edit_address(addrid) {

        $(".news-loc").click();
        $.getJSON('{:url('getAddressById')}', {addrid:addrid}, function(json) {
            $("input[name=addrid]").val(json.addrid);
            $("input[name=fullname]").val(json.fullname);
            $("input[name=tel]").val(json.tel);
            $("input[name=mobile]").val(json.mobile);
            $("select[name=cityid]").val(json.cityid);
            $("select[name=cityid]").change();
            setTimeout(function() {
                $("select[name=areaid]").val(json.areaid);
            }, 200)
            $("input[name=postcode]").val(json.postcode);
            $("input[name=address]").val(json.address);
        })
    }
</script>
</body>
</html>