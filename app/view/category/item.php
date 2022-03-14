<?php
                if(!empty($list)) {
                foreach($list as $prod) {
                    $image = isset($prod['image'])?$prod['image']:'';
                    $image2 = isset($prod['image2'])?$prod['image2']:$image;
                    $video = isset($prod["video"])?$prod["video"]:'';
                ?>
<div class="col-xs-6 col-md-3">
    <div class="list-one">
        <div class="list-pic"><a href="{:front_link('Product/detail', ['prodid' => $prod['prodid']])}">
                <?php
                                if(!empty($video)) {
                                    ?>
                <video controls onmouseover="this.play()" onmouseout="this.pause()">
                    <source src="{:showfile($video)}" type="video/mp4">
                </video>
                <?php } else { ?>
                <img src="{$image}" onMouseOvers="javascript:this.src='{$image2}'"
                    onMouseOutw="javascript:this.src='{$image}'" alt="" />
                <?php } ?>
            </a></div>
        <div class="list-content"> <a href="{:front_link('Product/detail', ['prodid' => $prod['prodid']])}">
                <div class="name">{:$prod['prodname']}</div>
                <!-- <h4>{:$prod['prod_features']}</h4> -->
                <div class="price">
                    <?php if($prod['prod_list_price']>$prod['prod_price']) { ?>
                    <span class="old-price">{:price_label_list($prod)}</span>
                    <?php } ?>
                    <span class="current-price">{:price_label($prod)}</span>
                    <div class="list-icon"><a href="{:front_link('Wishlist/add', ['prodid' => $prod['prodid']])}"
                            class="AjaxTodo">
                            <svg class="icon" aria-hidden="true">
                                <use xlink:href="#icon-aixin{:in_array($prod['prodid'], $wishlists)?'-active':''}">
                                </use>
                            </svg>
                        </a> <a href="{:front_link('Product/detail', ['prodid' => $prod['prodid']])}">
                            <svg class="icon" aria-hidden="true">
                                <use xlink:href="#icon-gouwu1"></use>
                            </svg>
                        </a> </div>
                </div>
            </a> </div>
    </div>
</div>
<?php } } ?>

<script>
$(".AjaxTodo").on("click", function() {
    var obj = $(this);
    var url = $(this).attr("href");
    var tip_text = $(this).attr("data-tip");
    if (tip_text == '' || tip_text == null || tip_text == undefined) {
        tip_text = '確定要執行此操作嗎？';
    }
    layer.confirm(tip_text, {
        icon: 3,
        title: '系統提示',
        btn: ['確定', '取消'] //按鈕
    }, function() {
        $.getJSON(url, function(json) {
            if (json.code === 0) { //無錯誤
                layer.alert(json.msg, {
                    icon: 1,
                    end: function() {
                        obj.find("use").attr("href", "#icon-aixin" + json
                            .active_class);
                    }
                });
            } else {
                layer.alert(json.msg, {
                    icon: 2
                });
            }
        })
    }, function() {
        layer.closeAll();
    });
    return false;
})
</script>