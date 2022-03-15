{include file="public/meta" /}
<link rel="stylesheet" type="text/css" href="/static/front/css/slider-pro.min.css" media="screen" />
<link rel="stylesheet" type="text/css" href="/static/front/css/examples.css" media="screen" />
<link rel="stylesheet" type="text/css" href="/static/front/css/list.css" media="screen" />
<link rel="stylesheet" href="/static/front/css/viewer.min.css">
<style type="text/css">
#attrimage {
    text-align: center;
    vertical-align: middle;
}

.disenabled {
    display: none;
}
</style>
{include file="public/kefu" /}
<div class="index-box">
    {include file="public/header" /}
    <section class="detail">

        <div class="sub-loation">
            <ol class="breadcrumb">
                <li><a href="{:front_link('Index/index')}">首頁</a></li>
                <?php
                if(!empty($breadcrumbs)) {
                    foreach($breadcrumbs as $bv) {
                    ?>
                <li><a href="{:front_link('Category/index', ['catid' => $bv['catid']])}">{:$bv['catname']}</a></li>
                <?php  } } ?>
                <li class="active">{:$product['prodname']}</li>
            </ol>
        </div>

        <div class="detail-top row no-gutters ">

            <div class="col-xs-12 col-md-8 grey">
                <div class="detail-pic ">
                    <div id="detail" class="slider-pro detail-p">
                        <div class="sp-slides" id="original">
                            <?php
                            if(isset($product["video"]) && !empty($product["video"])) {
                            ?>
                            <div class="sp-slide">
                                <video class="lazy" controls onmouseover="this.play()"
                                    poster="{:showfile($product['video_image'])}" onmouseout="this.pause()">
                                    <source data-src="{:showfile($product['video'])}" type="video/mp4">
                                </video>
                            </div>
                            <?php } ?>
                            <?php
                            if(!empty($images)) {
                                foreach($images as $image) {
                            ?>
                            <div class="sp-slide">
                                <img class="sp-image" src="/static/front/css/images/blank.gif"
                                    data-original="{:showfile($image['image_std'])}"
                                    data-src="{:showfile($image['image_std'])}"
                                    data-retina="{:showfile($image['image_file'])}" />
                            </div>
                            <?php } } ?>
                            <div id="attrimage" style="display: none"></div>
                        </div>
                        <div class="sp-thumbnails">
                            <?php
                            if(isset($product["video"]) && !empty($product["video"])) {
                                if(!empty($product["video_image"])) {
                            ?>
                            <div class="sp-thumbnail"><img src="{:showfile($product['video_image'])}" /></div>
                            <?php } else { ?>
                            <div class="sp-thumbnail"><img src="/static/front/images/video.png" /></div>
                            <?php } } ?>
                            <?php
                            if(!empty($images)) {
                            foreach($images as $image) {
                            ?>
                            <div class="sp-thumbnail"> <img src="{:showfile($image['image_tiny'])}" alt="" /> </div>
                            <?php } } ?>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-xs-12 col-md-4 white">
                <div class="detail-content">
                    <div class="ps-title-small" onClick="javascript:sbslider();">{:$product['prodcode']}</div>
                    <div class="ps-title-lg">{:$product['prodname']}</div>
                    <div class="ps-price"><span class="ps-price-red">特價 <i
                                id="PriceShow">{:price_label($product)}</i></span><span
                            class="ps-price-up">{:price_label_list($product)}</span></div>
                    <div class="ps-price"><span class="ps-price-red">實際優惠價格顯示於購物車</span></div>

                    <div class="ps-state">{:$product['prod_features']}</div>
                    <div id="panel_sel"></div>
                    <div class="ps-select row no-gutter">
                        <div class="col-xs-12 col-md-12 no-gutter">
                            <div class="detail-number">
                                <input type="text" id="BuyQty" class="spinnerExample" />
                            </div>
                        </div>
                        <div class="ps-btn row col-xs-12 col-md-12 no-gutter">
                            <div class="col-xs-9 col-md-9">
                                <div class="detail-cart"><button onClick="javascript:addToCart();" id="AddtoCart"
                                        class="detail-cart-btn">加入購物車<svg class="icon" aria-hidden="true">
                                            <use xlink:href="#icon-ziyuan"></use>
                                        </svg></button></div>
                            </div>
                            <div class="col-xs-3 col-md-3">
                                <div href="{:front_link('Wishlist/add', ['prodid' => $product['prodid']])}"
                                    class="AjaxTodo detail-love"><button
                                        class="detail-love-btn {:in_array($product['prodid'], $wishlists)?'active':''}"><svg
                                            class="icon" aria-hidden="true">
                                            <use xlink:href="#icon-aixin"></use>
                                        </svg></button></div>
                            </div>
                        </div>
                        <?php
                        if(!empty($product['page_ids'])) {
                        $sp_pages = get_pages_byids($product['page_ids']);
                        if(!empty($sp_pages)) {
                            foreach($sp_pages as $page) {
                        ?>
                        <div class="ps-size"><a href="javascript:void" class="size-btn" data-toggle="modal"
                                data-target="#myModal{$page['pageid']}"><i class="iconfont icon-gouwuche1"></i>
                                {$page['title']}</a></div>
                        <div class="modal fade modal-detail" id="myModal{$page['pageid']}" tabindex="-1" role="dialog"
                            aria-labelledby="myModalLabel{$page['pageid']}">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel{$page['pageid']}">{$page['title']}</h4>
                                    </div>
                                    <div class="modal-body">

                                        {:$page['content']}

                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } } } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="detail-tabs">
        <div class="container-small">

            <!-- tab標籤 -->
            <ul id="myTab" class="nav nav-tabs" role="tablist">
                <li role="presentation">
                    <a href="#home" ria-controls="home" role="tab" data-toggle="tab">
                        商品詳細規格
                    </a>
                </li>



            </ul>
            <!-- 每個tab頁對應的内容 -->
            <div id="myTabContent" class="tab-content">
                <div class="tab-pane in active" id="home">
                    {:$product['prod_desc']}
                    <div class="row care">

                        <div class="clearfix"></div>
                    </div>
                </div>


            </div>

        </div>
    </section>
    <?php if(!empty($related_items)) { ?>
    <section class="detail-tj">
        <div class="container-small">
            <h3>相關商品</h3>
            <div class="owl-carousel">
                <?php
                    foreach($related_items as $prod) {
                        if(!isset($prod["prodid"])) continue;
                        $image = isset($prod['image'])?$prod['image']:'';
                        $image2 = isset($prod['image2'])?$prod['image2']:$image;
                        $video = isset($prod["video"])?$prod["video"]:'';
                        ?>
                <div class="item">
                    <div class="list-one detail">
                        <div class="list-pic"><a href="{:front_link('Product/detail', ['prodid' => $prod['prodid']])}">
                                <?php
                                        if(!empty($video)) {
                                            ?>
                                <video class="lazy" controls onmouseover="this.play()"
                                    poster="{:showfile($prod['video_image'])}" onmouseout="this.pause()">
                                    <source data-src="{:showfile($video)}" type="video/mp4">
                                </video>
                                <?php } else { ?>
                                <img class="img lazy" data-src="{$image}" onMouseOvers="javascript:this.src='{$image2}'"
                                    onMouseOutw="javascript:this.src='{$image}'" alt="" />
                                <?php } ?>
                            </a></div>
                        <div class="list-content"> <a
                                href="{:front_link('Product/detail', ['prodid' => $prod['prodid']])}">
                                <div class="name">{:$prod['prodname']}</div>
                                <!-- <h4>{:$prod['prod_features']}</h4> -->
                                <div class="price">
                                    <span class="current-price">{:price_label($prod)}</span>
                                    <div class="list-icon"><a
                                            href="{:front_link('Wishlist/add', ['prodid' => $prod['prodid']])}"
                                            class="AjaxTodo">
                                            <svg class="icon " aria-hidden="true">
                                                <use
                                                    xlink:href="#icon-aixin{:in_array($prod['prodid'], $wishlists)?'-active':''}">
                                                </use>
                                            </svg>
                                        </a> <a href="{:front_link('Product/detail', ['prodid' => $prod['prodid']])}">
                                            <svg class="icon" aria-hidden="true">
                                                <use xlink:href="#icon-gouwu1"></use>
                                            </svg>
                                        </a> </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <?php }  ?>

            </div>
        </div>
    </section>
    <?php } ?>
    <?php if(!empty($viewed_items)) { ?>
    <section class="detail-tj">
        <div class="container-small">
            <h3>你可能喜歡</h3>
            <div class="owl-carousel">
                <?php
                foreach($viewed_items as $prod) {
                    if(!isset($prod["prodid"])) continue;
                    $image = isset($prod['image'])?$prod['image']:'';
                    $image2 = isset($prod['image2'])?$prod['image2']:$image;
                    $video = isset($prod["video"])?$prod["video"]:'';
                ?>
                <div class="item">
                    <div class="list-one detail">
                        <div class="list-pic"><a href="{:front_link('Product/detail', ['prodid' => $prod['prodid']])}">
                                <?php
                                if(!empty($video)) {
                                    ?>
                                <video class="lazy" poster="{:showfile($prod['video_image'])}" controls
                                    onmouseover="this.play()" onmouseout="this.pause()">
                                    <source data-src="{:showfile($video)}" type="video/mp4">
                                </video>
                                <?php } else { ?>
                                <img class="img lazy" data-src="{$image}" onMouseOvers="javascript:this.src='{$image2}'"
                                    onMouseOutw="javascript:this.src='{$image}'" alt="" />
                                <?php } ?>
                            </a></div>
                        <div class="list-content">
                            <a href="{:front_link('Product/detail', ['prodid' => $prod['prodid']])}">
                                <div class="name">{:$prod['prodname']}</div>
                                <!-- <h4>{:$prod['prod_features']}</h4> -->
                                <div class="price">
                                    <span class="current-price">{:price_label($prod)}</span>
                                    <div class="list-icon"><a
                                            href="{:front_link('Wishlist/add', ['prodid' => $prod['prodid']])}"
                                            class="AjaxTodo">
                                            <svg class="icon " aria-hidden="true">
                                                <use
                                                    xlink:href="#icon-aixin{:in_array($prod['prodid'], $wishlists)?'-active':''}">
                                                </use>
                                            </svg>
                                        </a> <a href="{:front_link('Product/detail', ['prodid' => $prod['prodid']])}">
                                            <svg class="icon" aria-hidden="true">
                                                <use xlink:href="#icon-gouwu1"></use>
                                            </svg>
                                        </a> </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <?php } ?>

            </div>
        </div>
    </section>
    <?php } ?>
    {include file="public/footer" /}
</div>
</div>
<script type="text/javascript" src="/static/front/js/jquery.spinner.js"></script>
<script type="text/javascript" src="/static/front/js/jquery.sliderPro.min.js"></script>
<script type="text/javascript">
$('.spinnerExample').spinner({});
$('#myTab a').click(function(e) {
    e.preventDefault()
    $(this).tab('show')
})
</script>
<script>
$(function() {
    $('.owl-carousel').owlCarousel({
        stagePadding: 0,
        loop: true,
        margin: 10,
        items: 4,
        nav: true,
        responsiveClass: true,
        navigation: true,
        responsive: {
            0: {
                items: 2,
                nav: true
            },
            600: {
                items: 3,
                nav: false
            },
            1000: {
                items: 4,
                nav: true,
                loop: false
            }
        }
    });

    $('.detail-p').sliderPro({
        width: 720,
        height: 720,
        fade: true,
        loop: false,
        arrows: true,
        buttons: false,
        autoplay: false,
        imageScaleMode: 'contain',
        thumbnailPointer: true,
        thumbnailWidth: 80,
        thumbnailHeight: 80,
        thumbnailArrows: false,
        thumbnailsPosition: 'left',
        breakpoints: {
            800: {
                thumbnailsPosition: 'bottom',
                thumbnailWidth: 80,
                thumbnailHeight: 80

            },
            500: {
                thumbnailsPosition: 'bottom',
                thumbnailWidth: 80,
                thumbnailHeight: 80

            }
        }
    });

});

function sbslider() {
    $("#detail").removeClass("detail-p");
    $("#detail").addClass("detail-p2");
    $('.detail-p2').sliderPro({
        width: 320,
        height: 320,
        fade: true,
        loop: false,
        arrows: true,
        buttons: false,
        autoplay: false,
        imageScaleMode: 'contain',
        thumbnailPointer: true,
        thumbnailWidth: 80,
        thumbnailHeight: 80,
        thumbnailArrows: false,
        thumbnailsPosition: 'left',
        breakpoints: {
            800: {
                thumbnailsPosition: 'bottom',
                thumbnailWidth: 80,
                thumbnailHeight: 80

            },
            500: {
                thumbnailsPosition: 'bottom',
                thumbnailWidth: 80,
                thumbnailHeight: 80

            }
        }
    });
}

function addToCart() {
    var totalAttris = $(".goods_attr").length;
    var checkedAttris = $(".goods_attr li.active").length;
    if (checkedAttris < totalAttris) {
        layer.msg("請選擇規格");
        return false;
    }

    var selectedAttrs = [];
    if (totalAttris > 0) {
        $.each($(".goods_attr li.active"), function() {
            selectedAttrs.push($(this).attr("val"))
        })
    }

    var cartData = {
        prodid: {
            : $product['prodid']
        },
        qty: $("#BuyQty").val(),
        voption: selectedAttrs.join(",")
    };

    if (cartData.qty <= 0) {
        layer.msg("請選擇購買數量");
        return false;
    }

    $.ajax({
        url: '{:front_link("Cart/save")}',
        data: cartData,
        type: "POST",
        dataType: "JSON",
        success: function(json) {
            if (json.code > 0) {
                layer.alert(json.msg, {
                    icon: 2,
                    btn: ['確定']
                });
            } else {
                layer.alert(json.msg, {
                    icon: 1,
                    btn: ['確定'],
                    end: function() {
                        location.reload();
                    }
                })
            }
        }
    })
}
</script>
<script src="/static/front/js/viewer.min.js"></script>
<script>
var viewer = new Viewer(document.getElementById('original'), {
    url: 'data-original',
    navbar: false,
    zoomRatio: 0.4,
    minZoomRatio: 0.1, //最小縮放比例
    maxZoomRatio: 1.5, //最大縮放比例
    button: true,
    title: false,
    keyboard: true,
    movable: true,
    rotatable: false,
    scalable: false,
    fullscreen: false,
    toolbar: false,


});
</script>
<script>
var keys = <?php echo isset($voptions['attrvalues'])?toJSON($voptions['attrvalues'], true):'{}';?>;

var sku_list = <?php echo !empty($variation_combins)?toJSON($variation_combins, true):'{}';?>;

var voptionIds = [];

function show_attr_item() {
    var html = '';
    for (k in keys) {
        html += '<div class="goods_attr" > <div class="ps-colors">' + k + '</div>';
        html += '<ul>'
        for (k2 in keys[k]) {
            attri_values = keys[k][k2];
            $.each(attri_values, function(_attr_id, data) {
                voptionIds.push(_attr_id);
                html += '<li class="text disabled" val="' + _attr_id + '" >';
                if (data.image != '') {
                    html += '<span><div class="image"><img title="' + data.title + '" src="' + data.image +
                        '"/></div></span>';
                } else if (data.color != '') {
                    html += '<span><div title="' + data.title + '" class="color" style="background:' + data
                        .color + '"></div></span>';
                } else {
                    html += '<span>' + data.title + '</span>';
                }
                html += '<s></s>';
                html += '</li>';
            })
        }
        html += '</ul>';
        html += '</div>';
    }
    $('#panel_sel').html(html);

    checkStock(voptionIds);
}

show_attr_item();

function filterProduct(ids) {
    var result = [];
    $(sku_list).each(function(k, v) {
        _attr = '|' + v['attrs'] + '|';
        _all_ids_in = true;
        for (k in ids) {
            if (_attr.indexOf('|' + ids[k] + '|') == -1) {
                _all_ids_in = false;
                break;
            }
        }

        if (_all_ids_in) {
            result.push(v);
        }
    });
    return result;
}

function filterAttrs(ids) {
    var products = filterProduct(ids);
    var result = [];
    $(products).each(function(k, v) {
        result = result.concat(v['attrs'].split('|'));
    });
    return result;
}

function _getSelAttrId() {
    var list = [];
    $('.goods_attr li.active').each(function() {
        list.push($(this).attr('val'));
    });
    return list;
}

$('.goods_attr li').click(function() {
    if ($(this).hasClass('disabled')) {
        return; //被鎖定了
    }

    var cindex = $(this).parent().index();

    if ($(".goods_attr:lt(" + cindex + ")").find("li").find("img").length === 0) {
        $("#attrimage").html('');
        $("#attrimage").hide();
        $(".sp-slide").show();
    }

    if ($(this).hasClass('active')) {
        $(this).removeClass('active');

        if ($(this).parent().find("img").length > 0) {
            if ($(this).find("img").length > 0) {
                $("#attrimage").html('');
                $("#attrimage").hide();
                $(".sp-slide").show();
            }
        }
    } else {
        $(this).siblings().removeClass('active');
        $(this).addClass('active');
        if ($(this).parent().find("img").length > 0) {
            if ($(this).find("img").length > 0) {
                $("#attrimage").html('<img src="' + $(this).find("img").attr("src") + '" data-original="' + $(
                        this).find("img").attr("src") + '"  data-src="' + $(this).find("img").attr("src") +
                    '" data-retina="' + $(this).find("img").attr("src") + '"/>');
                $(".sp-slide").hide();
                var height = $(".sp-grab").height();
                $("#attrimage").height(height);
                $("#attrimage").css("line-height", height + 'px');
                $("#attrimage").show();
            } else {
                $("#attrimage").html('');
                $("#attrimage").hide();
                $(".sp-slide").show();
            }
        }
    }
    var select_ids = _getSelAttrId();

    var ids = filterAttrs(select_ids);

    var attrIndex = $(this).parent().parent().index();

    $(".goods_attr:gt(" + attrIndex + ")").find("li").removeClass("active");
    $(".goods_attr:gt(" + attrIndex + ")").find("li").addClass("disabled");
    $(".goods_attr:gt(" + attrIndex + ")").find("li").addClass("disenabled");

    if (!$('.goods_attr li.active').length) {
        checkStock(voptionIds);
    } else {
        var leftOptions = [];
        $.each($('.goods_attr:gt(' + attrIndex + ') li'), function() {
            leftOptions.push($(this).attr('val'));
        })
        checkedAndLeftOptions(leftOptions);
    }

    //更換價格顯示
    changePrice();

});

function changePrice() {
    var voptions = [];
    $.each($(".goods_attr"), function() {
        var voptionId = $(this).find("li.active").attr("val");
        if (!isNaN(voptionId)) {
            voptions.push(voptionId);
        }
    })

    if (voptions.length == $(".goods_attr").length) {
        $(sku_list).each(function(i, attrObj) {
            var attrVos = attrObj.attrs.split("|");
            if (attrVos.sort().toString() == voptions.sort().toString()) {
                $("#PriceShow").text('NT.$' + attrObj.vcprice);
            }
        })
    }
}

function checkedAndLeftOptions(ids) {
    var checkedOp = [];
    $.each($('.goods_attr li.active'), function() {
        activeId = $(this).attr('val');
        checkedOp.push(activeId);
    })

    //selectedActive = '|'+checkedOp.sort().join('|')+'|';

    $.each(sku_list, function(sli, slv) {

        /*if(slv.attrs==checkedOp.sort().join('|')) {
            $(".ps-price-red i").text('NT.$'+slv.vcprice);
        }*/
        _attris = slv.attrs.split("|");
        console.log(_attris);
        if (isContained(_attris, checkedOp)) {
            $(".ps-price-red i").text('NT.$' + slv.vcprice);
        }

        attris = '|' + slv.attrs + '|';

        if (isContained(_attris, checkedOp)) {
            $.each(ids, function(i, k) {
                if (attris.indexOf('|' + k + '|') !== -1 && slv.stock > 0) {
                    $(".goods_attr li[val=" + k + "]").removeClass('disabled');
                }
                if (attris.indexOf('|' + k + '|') !== -1 && slv.vcenabled > 0) {
                    $(".goods_attr li[val=" + k + "]").removeClass('disenabled');
                }
            })
        }
    })
}

function isContained(aa, bb) {
    if (!(aa instanceof Array) || !(bb instanceof Array) || ((aa.length < bb.length))) {
        return false;
    }
    var aaStr = aa.toString();
    for (var i = 0; i < bb.length; i++) {
        if (aaStr.indexOf(bb[i]) < 0) return false;
    }
    return true;
}

function checkStock(voptions) {
    $.each(voptions, function(i, k) {
        $.each(sku_list, function(sli, slv) {
            attris = '|' + slv.attrs + '|';
            if (slv.stock > 0 && attris.indexOf('|' + k + '|') !== -1) {
                $(".goods_attr li[val=" + k + "]").removeClass('disabled');
            }
            if (slv.vcenabled > 0 && attris.indexOf('|' + k + '|') !== -1) {
                $(".goods_attr li[val=" + k + "]").removeClass('disenabled');
            }
        })
    })
}

// $("#AddtoCart").click(function() {
//     var totalAttris = $(".goods_attr").length;
//     var checkedAttris = $(".goods_attr li.active").length;
//     if (checkedAttris < totalAttris) {
//         layer.msg("請選擇規格");
//         return false;
//     }

//     var selectedAttrs = [];
//     if (totalAttris > 0) {
//         $.each($(".goods_attr li.active"), function() {
//             selectedAttrs.push($(this).attr("val"))
//         })
//     }

//     var cartData = {
//         prodid: {
//             : $product['prodid']
//         },
//         qty: $("#BuyQty").val(),
//         voption: selectedAttrs.join(",")
//     };

//     if (cartData.qty <= 0) {
//         layer.msg("請選擇購買數量");
//         return false;
//     }

//     $.ajax({
//         url: '{:front_link("Cart/save")}',
//         data: cartData,
//         type: "POST",
//         dataType: "JSON",
//         success: function(json) {
//             if (json.code > 0) {
//                 layer.alert(json.msg, {
//                     icon: 2,
//                     btn: ['確定']
//                 });
//             } else {
//                 layer.alert(json.msg, {
//                     icon: 1,
//                     btn: ['確定'],
//                     end: function() {
//                         location.reload();
//                     }
//                 })
//             }
//         }
//     })
// })
</script>
</body>

</html>