{include file="public/meta" /}
<link rel="stylesheet" type="text/css" href="/static/front/css/list.css" media="screen" />
<style>
.layui-flow-more {
    width: 100%;
    text-align: center;
    clear: both;
}
</style>
{include file="public/kefu" /}
<div class="index-box">
    {include file="public/header" /}
    <section class="sale-product">
        <div class="container-fluid no-padding">
            <div id="LAY_demo1" class="row mno-gutter">
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
                                <video class="lazy" autoplay muted loop x5-video-player-fullscreen="true" x5-playsinline
                                    playsinline webkit-playsinline onmouseover="this.play()" onmouseout="this.pause()"
                                    poster="{:showfile($prod['video_image'])}">
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
                                    <?php if($prod['prod_list_price']>$prod['prod_price']) { ?>
                                    <span class="old-price">{:price_label_list($prod)}</span>
                                    <?php } ?>
                                    <span class="current-price">{:price_label($prod)}</span>
                                    <div class="list-icon">
                                        <a href="{:front_link('Wishlist/add', ['prodid' => $prod['prodid']])}"
                                            class="AjaxTodo">
                                            <svg class="icon" aria-hidden="true">
                                                <use
                                                    xlink:href="#icon-aixin{:in_array($prod['prodid'], $wishlists)?'-active':''}">
                                                </use>
                                            </svg>
                                        </a>
                                        <a href="{:front_link('Product/detail', ['prodid' => $prod['prodid']])}">
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
        <div class="container">
            {:$pages}
        </div>
    </section>
    {include file="public/footer" /}
    <script src="/static/js/layui/layui.js"></script>
</div>
</div>
<script>
/*layui.use('flow', function(){
        var bottomD = $(".page-footer").height();
        var flow = layui.flow;
        var totalpages = {$totalpages};
        flow.load({
            elem: '#LAY_demo1'
            ,isAuto: true
            ,end: '<p style="color:red">我是有底線的哦</p>'
            ,scrollElem: ''
            ,mb: bottomD+500
            ,done: function(page, next){
                $.get('{:$nextPage}', {page:page}, function(items) {
                    next(items, page < totalpages);
                })

            }
        });
    })*/
</script>
</body>

</html>