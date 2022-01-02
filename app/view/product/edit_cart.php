{include file="public/meta" /}
<link rel="stylesheet" type="text/css" href="/static/front/css/slider-pro.min.css" media="screen"/>
<link rel="stylesheet" type="text/css" href="/static/front/css/examples.css" media="screen"/>
<link rel="stylesheet" type="text/css" href="/static/front/css/list.css" media="screen"/>
<link rel="stylesheet" href="/static/front/css/viewer.min.css">
<style type="text/css">

</style>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">編輯商品</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-4"><img src="{:showfile($images[0]['image_thumb'])}" /></div>
        <div class="col-md-8">
            <p>{:$product['prodname']}</p>
            <h5>{:price_label($product)}</h5>
            <hr />
            <div class="ps-size"><a href="javascript:void"  data-toggle="modal" data-target="#size"><i class="fa fa-scissors"></i> 尺碼表</a></div>
            <!-- Large modal -->
            <div id="panel_sel"></div>
            <div class="ps-select row">
                <div class="col-xs-6 col-md-6">
                </div>
                <div class="col-xs-6 col-md-6">
                    <div class="detail-number">
                        <input type="text" class="spinnerExample" id="BuyQty" value="1"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button id="AddtoCart" type="button" class="btn btn-primary">儲存更改</button>
</div>
{include file="public/js" /}
<script type="text/javascript" src="/static/front/js/jquery.spinner1.js"></script>
<script type="text/javascript">
    $('.spinnerExample').spinner({});
    $('#myTab a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
    })

</script>
<script>
    var keys = <?php echo isset($voptions['attrvalues'])?toJSON($voptions['attrvalues'], true):'{}';?>;

    var sku_list=<?php echo !empty($variation_combins)?toJSON($variation_combins, true):'{}';?>;

    var voptionIds = [];

    function show_attr_item(){
        var html='';
        for(k in keys){
            html+='<div class="goods_attr" > <div class="ps-colors">'+k+'</div>';
            html+='<ul>'
            for(k2 in keys[k]){
                attri_values=keys[k][k2];
                $.each(attri_values, function(_attr_id, data) {console.log(data)
                    voptionIds.push(_attr_id);
                    html+='<li class="text disabled" val="'+_attr_id+'" >';
                    if(data.image !='') {
                        html+='<span><div class="image"><img title="'+data.title+'" src="'+data.image+'"/></div></span>';
                    } else if(data.color != '') {
                        html+='<span><div title="'+data.title+'" class="color" style="background:'+data.color+'"></div></span>';
                    } else {
                        html+='<span>'+data.title+'</span>';
                    }
                    html+='<s></s>';
                    html+='</li>';
                })
            }
            html+='</ul>';
            html+='</div>';
        }
        $('#panel_sel').html(html);

        checkStock(voptionIds);
    }

    show_attr_item();
    function filterProduct(ids){
        var result=[];
        $(sku_list).each(function(k,v){
            _attr='|'+v['attrs']+'|';
            _all_ids_in=true;
            for( k in ids){
                if(_attr.indexOf('|'+ids[k]+'|')==-1){
                    _all_ids_in=false;
                    break;
                }
            }
            if(_all_ids_in){
                result.push(v);
            }
        });
        return result;
    }

    function filterAttrs(ids){
        var products=filterProduct(ids);
        //console.log(products);
        var result=[];
        $(products).each(function(k,v){
            result=result.concat(v['attrs'].split('|'));
        });
        return result;
    }

    function _getSelAttrId(){
        var list=[];
        $('.goods_attr li.active').each(function(){
            list.push($(this).attr('val'));
        });
        return list;
    }

    $('.goods_attr li').click(function(){
        if($(this).hasClass('disabled')){
            return ;
        }
        if($(this).hasClass('active')){
            $(this).removeClass('active');
        }else{
            $(this).siblings().removeClass('active');
            $(this).addClass('active');
        }
        var select_ids=_getSelAttrId();

        var ids=filterAttrs(select_ids);

        var attrIndex = $(this).parent().parent().index();

        $(".goods_attr:gt("+attrIndex+")").find("li").removeClass("active");
        $(".goods_attr:gt("+attrIndex+")").find("li").addClass("disabled");

        if(!$('.goods_attr li.active').length) {
            checkStock(voptionIds);
        } else {
            var leftOptions = [];
            $.each($('.goods_attr:gt('+attrIndex+') li'), function() {
                leftOptions.push($(this).attr('val'));
            })
            checkedAndLeftOptions(leftOptions);
        }

    });


    function checkedAndLeftOptions(ids)
    {
        var checkedOp = [];
        $.each($('.goods_attr li.active'), function() {
            activeId = $(this).attr('val');
            checkedOp.push(activeId);
        })

        selectedActive = '|'+checkedOp.join('|')+'|';

        $.each(sku_list, function(sli, slv) {
            attris = '|'+slv.attrs+'|';
            if(attris.indexOf(selectedActive) !== -1) {
                $.each(ids, function(i, k) {
                    if(attris.indexOf('|'+k+'|') !== -1 && slv.stock>0) {
                        $(".goods_attr li[val="+k+"]").removeClass('disabled');
                    }
                })
            }
        })
    }

    function checkStock(voptions) {
        $.each(voptions, function(i, k) {
            $.each(sku_list, function(sli, slv) {
                attris = '|'+slv.attrs+'|';
                if(slv.stock>0 && attris.indexOf('|'+k+'|') !== -1) {
                    $(".goods_attr li[val="+k+"]").removeClass('disabled');
                }
            })
        })
    }

    $("#AddtoCart").click(function() {
        var totalAttris = $(".goods_attr").length;
        var checkedAttris = $(".goods_attr li.active").length;
        if(checkedAttris<totalAttris) {
            layer.msg("請選擇規格");
            return false;
        }

        var selectedAttrs = [];
        if(totalAttris>0) {
            $.each($(".goods_attr li.active"), function () {
                selectedAttrs.push($(this).attr("val"))
            })
        }

        var cartData = {
            prodid: {:$product['prodid']},
            qty: $("#BuyQty").val(),
            cartId: '{$cartId}',
            voption:selectedAttrs.join(",")
        };

        if(cartData.qty<=0) {
            layer.msg("請選擇購買數量");
            return false;
        }

        $.ajax({
            url: '{:front_link("Cart/save")}',
            data: cartData,
            type: "POST",
            dataType: "JSON",
            success: function(json) {
                if(json.code>0) {
                    layer.alert(json.msg, {icon: 1});
                } else {
                    layer.alert(json.msg, {icon: 1, end: function() {
                        location.href = json.url;
                     } })
                }
            }
        })
    })
</script>
</body>
</html>