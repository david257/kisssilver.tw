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
<div class="index-box"> {include file="public/header" /}
    <section class="location hide-xs">
        <div class="location-bg"
            style="background-image:url({:empty($category['cat_banner'])?'/static/front/images/PLP-CAMELIA-2880x900.jpg':showfile($category['cat_banner'])});">
            <div class="container-fluid">
                <div class="location-txt"> <a href="{:front_link('Index/index')}">首頁</a>
                    <?php
                    if(!empty($breadcrumbs)) {
                        foreach($breadcrumbs as $bv) {
                            if($bv['catid'] == $category['catid']) {
                    ?>
                    .<a
                        href="{:front_link('Category/index', ['catid' => $category['catid']])}">{:$category['catname']}</a>
                    <?php } else { ?>
                    .<a href="{:front_link('Category/index', ['catid' => $bv['catid']])}">{:$bv['catname']}</a>
                    <?php } } } ?>
                </div>
            </div>
        </div>
    </section>
    <!--下邊的location是手機版，背景圖調用手機版 的圖片就可以了-->
    <section class="location show-xs">
        <div class="location-bg"
            style="background-image:url({:empty($category['cat_banner_xs'])?'/static/front/images/PLP-CAMELIA-2880x900.jpg':showfile($category['cat_banner_xs'])});">
            <div class="container-fluid">
                <div class="location-txt"> <a href="{:front_link('Index/index')}">首頁</a>
                    <?php
                    if(!empty($breadcrumbs)) {
                        foreach($breadcrumbs as $bv) {
                            if($bv['catid'] == $category['catid']) {
                    ?>
                    .<a
                        href="{:front_link('Category/index', ['catid' => $category['catid']])}">{:$category['catname']}</a>
                    <?php } else { ?>
                    .<a href="{:front_link('Category/index', ['catid' => $bv['catid']])}">{:$bv['catname']}</a>
                    <?php } } } ?>
                </div>
            </div>
        </div>
    </section>
    <section class="sub">
        <div class="container-fluid">
            <div class="products-key">
                <h1>{:$category['catname']}</h1>
                <i>{:$totalItems} 件商品</i>
            </div>
            <div class="sx-open" type="button" data-toggle="collapse" data-target="#products-nav"
                aria-controls="sub-drown" aria-expanded="false" aria-label="Toggle navigation">商品篩選 <span
                    class="pull-right"><i class="fa fa-bars"></i></span></div>
            <div class="products-nav " id="products-nav">
                <div class="wap-open">商品篩選
                    <button class="pull-right btn btn-default" type="button" data-toggle="collapse"
                        data-target="#products-nav" aria-controls="sub-drown" aria-expanded="false"
                        aria-label="Toggle navigation"><i class="iconfont icon-guanbi"></i></button>
                </div>
                <div class="products-nav-top"> <span class="pull-left"><a>商品篩選</a></span><span class="pull-right"><a
                            href="{:front_link('Category/index', ['catid' => $category['catid']])}">清除所有</a><a
                            href="javascript:void();"><i class="fa fa-close"></i></a></span> </div>
                <ul class="list-nav">
                    <li class="list-dropdown"> <a class="list-dropdown-toggle"> 商品排序 <span
                                class="fa fa-angle-down"></span> </a>
                        <ul class="list-dropdown-menu">
                            <div class="link-check">
                                <ul>
                                    <?php
                                    $_filter = $filter?implode("-", $filter):'';
                                    ?>
                                    <li><a
                                            href="{:front_link('Category/index', ['catid' => $category['catid'], 'filter' => $_filter, 'sortby' => 'create_at.desc'])}">最新</a>
                                    </li>
                                    <li><a
                                            href="{:front_link('Category/index', ['catid' => $category['catid'], 'filter' => $_filter, 'sortby' => 'prod_price.desc'])}">上架時間<i>新-舊</i></a>
                                    </li>
                                    <li><a
                                            href="{:front_link('Category/index', ['catid' => $category['catid'], 'filter' => $_filter, 'sortby' => 'prod_price.desc'])}">上架時間<i>舊-新</i></a>
                                    </li>
                                    <li><a
                                            href="{:front_link('Category/index', ['catid' => $category['catid'], 'filter' => $_filter, 'sortby' => 'prod_price.desc'])}">價格<i>高-低</i></a>
                                    </li>
                                    <li><a
                                            href="{:front_link('Category/index', ['catid' => $category['catid'], 'filter' => $_filter, 'sortby' => 'prod_price.asc'])}">價格<i>低-高</i></a>
                                    </li>
                                    <li><a
                                            href="{:front_link('Category/index', ['catid' => $category['catid'], 'filter' => $_filter, 'sortby' => 'sold_qty.desc'])}">銷售量<i>高-低</i></a>
                                    </li>
                                </ul>
                            </div>
                        </ul>
                    </li>
                    <li class="list-dropdown"> <a class="list-dropdown-toggle"> 產品分類 <span
                                class="fa fa-angle-down"></span> </a>
                        <ul class="list-dropdown-menu">
                            <div class="link-check">
                                <ul>
                                    <?php
                                    if(!empty($sub_cates)) {
                                        foreach($sub_cates as $subcate) {

                                    ?>
                                    <li><a
                                            href="{:front_link('Category/index', ['catid' => $subcate['catid']])}">{:$subcate['catname']}<i>[{:isset($catItems[$subcate['catid']])?$catItems[$subcate['catid']]:0}]</i></a>
                                    </li>
                                    <?php } } ?>
                                </ul>
                            </div>
                        </ul>
                    </li>
                    <?php
                    if(!empty($filterAttries["attries"])) {
                        foreach($filterAttries["attries"] as $attrid => $attrInfo) {
                    ?>
                    <li class="list-dropdown"> <a class="list-dropdown-toggle"> {:$attrInfo['title']} <span
                                class="fa fa-angle-down"></span> </a>
                        <?php if(isset($filterAttries["voptions"][$attrid]) && !empty($filterAttries["voptions"][$attrid])) { ?>
                        <ul class="list-dropdown-menu">
                            <div class="link-check">
                                <ul>
                                    <?php foreach($filterAttries["voptions"][$attrid] as $voption) {?>
                                    <li>
                                        <?php
                                        $currentFilter = $filter;
                                        if(empty($filter) || !in_array($voption["valueid"], $filter)) {
                                            $currentFilter[] = $voption["valueid"];
                                            $class = 'glyphicon-unchecked';
                                        } else {
                                            $key = array_search($voption["valueid"], $currentFilter);
                                            unset($currentFilter[$key]);
                                            $class = 'glyphicon-check';
                                        }
                                        $_filter = $currentFilter?implode("-", $currentFilter):'';
                                        ?>
                                        <a
                                            href="{:front_link('Category/index', ['catid' => $category['catid'], 'filter' => $_filter])}"><i
                                                class="glyphicon {$class}"></i> {:$voption['name']}
                                            <i>{:isset($attrItems[$voption["valueid"]])?$attrItems[$voption["valueid"]]:0}</i></a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </ul>
                        <?php } ?>
                    </li>
                    <?php } } ?>
                </ul>
            </div>
            <div class="list-tag">
                <?php
                if(!empty($checkedAttris)) {
                    foreach ($checkedAttris as $k => $v) {
                ?>
                <span class="label label-default">{$v} <a href="#"><i class="fa fa-times"></i></a></span>
                <?php }  ?>
                <a href="{:front_link('Category/index', ['catid' => $category['catid']])}">清除所有</a>
                <?php } ?>
            </div>
        </div>
    </section>
    <section class="hot-product">
        <div class="container-fluid">
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
                        <div class="list-pic">
                            <?php
                                if(!empty($video)) {
                                ?>
                            <video autoplay muted loop x5-video-player-fullscreen="true" x5-playsinline playsinline
                                webkit-playsinline onmouseover="this.play()" onmouseout="this.pause()">
                                <source src="{:showfile($video)}" type="video/mp4">
                            </video>
                            <?php } else { ?><a href="{:front_link('Product/detail', ['prodid' => $prod['prodid']])}">
                                <img class="img lazy" data-src="{$image}" onMouseOvers="javascript:this.src='{$image2}'"
                                    onMouseOutw="javascript:this.src='{$image}'" alt="" /></a>
                            <?php } ?>
                        </div>
                        <div class="list-content">
                            <a href="{:front_link('Product/detail', ['prodid' => $prod['prodid']])}">
                                <div class="name">{:$prod['prodname']}</div>
                                <!-- <h4>{:$prod['prod_features']}</h4> -->
                                <div class="price">
                                    <div class="price-tag">
                                        <?php if($prod['prod_list_price']>$prod['prod_price']) { ?>
                                        <span class="old-price">{:price_label_list($prod)}</span>
                                        <?php } ?>
                                        <span class="current-price">{:price_label($prod)}</span>
                                    </div>
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
        <div class="container"> {:$pages} </div>
    </section>
    {include file="public/footer" /}
    <script src="/static/js/layui/layui.js"></script>
</div>
</div>
<script>
/*
    layui.use('flow', function(){
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
					var saleproductw=$(".sale-product .list-one").width();
$(".sale-product .list-one .list-pic img").css({"width" : saleproductw});
$(".sale-product .list-one .list-pic img").css({"height" : saleproductw});
$(".sale-product .list-one .list-pic").css({"width" : saleproductw});
$(".sale-product .list-one .list-pic").css({"height" : saleproductw});
                })

            }
        });
    })*/
</script>
</body>

</html>