{include file="public/meta" /}
<!-- <link rel="stylesheet" type="text/css" href="/static/front/css/slider-pro.min.css" media="screen"/> -->
<!-- <link rel="stylesheet" type="text/css" href="/static/front/css/examples.css" media="screen"/> -->
{include file="public/kefu" /}
<div class="index-box">
    {include file="public/header" /}
    <?php if(sizeof($header_banners) > 0) { ?>
    <section class="banner">
        <?php if (!is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "mobile"))) { ?>
        <?php foreach ($header_banners as $banner) { ?>
        <a href="{:$banner['url']}">
            <img alt="{:$banner['title']}" src="{:showfile($banner['imagefile'])}" />
        </a>
        <?php } ?>
        <?php } else { ?>
        <?php foreach ($header_banners as $banner) { ?>
        <a href="{:$banner['url']}">
            <img alt="{:$banner['title']}" src="{:showfile($banner['min_imagefile'])}" />
        </a>
        <?php } ?>
        <?php } ?>
    </section>
    <?php } ?>
    <?php if(sizeof($header_three_banners) > 0) { ?>
    <?php if (!is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "mobile"))) { ?>
    <section class="index-three-gg">
        <?php foreach ($header_three_banners as $banner) { ?>
        <div class="item">
            <?php
                        if(strpos($banner['imagefile'], '.mp4') !== false) {
                            ?>
            <div class="four-ggpic"> <a href="{:$banner['url']}">
                    <video  autoplay muted loop 
x5-video-player-fullscreen="true"
x5-playsinline
playsinline
webkit-playsinline onmouseover="this.play()"  onmouseout="this.pause()" class="lazy" controls>
                        <source data-src="{:showfile($banner['imagefile'])}" type="video/mp4">
                    </video>

                </a> </div>
            <?php } else { ?>
            <div class="four-ggpic"> <a href="{:$banner['url']}"><img onload="fadeIn(this)"
                        style="display:none;" src="{:showfile($banner['imagefile'])}" alt="{:$banner['title']}" />
                </a> </div>
            <?php } ?>
        </div>
        <?php } ?>
    </section>
    <?php } else { ?>
    <section>
        <?php foreach ($header_three_banners as $banner) { ?>
        <div class="item">
            <?php
                        if(strpos($banner['imagefile'], '.mp4') !== false) {
                            ?>
            <div class="four-ggpic"> <a href="{:$banner['url']}">
                    <video  autoplay muted loop 
x5-video-player-fullscreen="true"
x5-playsinline
playsinline
webkit-playsinline onmouseover="this.play()"  onmouseout="this.pause()" class="lazy" controls>
                        <source data-src="{:showfile($banner['min_imagefile'])}" type="video/mp4">
                    </video>
                </a> </div>
            <?php } else { ?>
            <div class="four-ggpic"> <a href="{:$banner['url']}">
                    <img onload="fadeIn(this)" style="display:none;" src="{:showfile($banner['min_imagefile'])}"
                        alt="{:$banner['title']}" /></a> </div>
            <?php } ?>
        </div>
        <?php } ?>
    </section>
    <?php } ?>
    <?php } ?>
    <?php if(sizeof($header_one_banners) > 0) { ?>
    <?php if (!is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "mobile"))) { ?>
    <section class="index-one-gg">
        <?php foreach ($header_one_banners as $banner) { ?>
        <div class="item">
            <?php
                        if(strpos($banner['imagefile'], '.mp4') !== false) {
                            ?>
            <div class="four-ggpic"> <a href="{:$banner['url']}">
                    <video  autoplay muted loop 
x5-video-player-fullscreen="true"
x5-playsinline
playsinline
webkit-playsinline onmouseover="this.play()"  onmouseout="this.pause()" class="lazy" controls>
                        <source data-src="{:showfile($banner['imagefile'])}" type="video/mp4">
                    </video>
                </a> </div>
            <?php } else { ?>
            <div class="four-ggpic"> <a href="{:$banner['url']}"><img onload="fadeIn(this)"
                        style="display:none;" src="{:showfile($banner['imagefile'])}" alt="{:$banner['title']}" />
                </a> </div>
            <?php } ?>
        </div>
        <?php } ?>
    </section>
    <?php } else { ?>
    <section>
        <?php foreach ($header_one_banners as $banner) { ?>
        <div class="item">
            <?php
                        if(strpos($banner['imagefile'], '.mp4') !== false) {
                            ?>
            <div class="four-ggpic"> <a href="{:$banner['url']}">
                    <video  autoplay muted loop 
x5-video-player-fullscreen="true"
x5-playsinline
playsinline
webkit-playsinline onmouseover="this.play()"  onmouseout="this.pause()" class="lazy" controls>
                        <source data-src="{:showfile($banner['min_imagefile'])}" type="video/mp4">
                    </video>
                </a> </div>
            <?php } else { ?>
            <div class="four-ggpic"> <a href="{:$banner['url']}">
                    <img onload="fadeIn(this)" style="display:none;" src="{:showfile($banner['min_imagefile'])}"
                        alt="{:$banner['title']}" /></a> </div>
            <?php } ?>
        </div>
        <?php } ?>
    </section>
    <?php } ?>
    <?php } ?>
    <?php if(sizeof($header_two_banners) > 0) { ?>
    <?php if (!is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "mobile"))) { ?>
    <section class="index-two-gg">
        <?php foreach ($header_two_banners as $banner) { ?>
        <div class="item">
            <?php
                        if(strpos($banner['imagefile'], '.mp4') !== false) {
                            ?>
            <div class="four-ggpic"> <a href="{:$banner['url']}">
                    <video  autoplay muted loop 
x5-video-player-fullscreen="true"
x5-playsinline
playsinline
webkit-playsinline onmouseover="this.play()"  onmouseout="this.pause()" class="lazy" controls>
                        <source data-src="{:showfile($banner['imagefile'])}" type="video/mp4">
                    </video>
                </a> </div>
            <?php } else { ?>
            <div class="four-ggpic"> <a href="{:$banner['url']}"><img onload="fadeIn(this)"
                        style="display:none;" src="{:showfile($banner['imagefile'])}" alt="{:$banner['title']}" />
                </a> </div>
            <?php } ?>
        </div>
        <?php } ?>
    </section>
    <?php } else { ?>
    <section>
        <?php foreach ($header_two_banners as $banner) { ?>
        <div class="item">
            <?php
                    if(strpos($banner['imagefile'], '.mp4') !== false) {
                        ?>
            <div class="four-ggpic"> <a href="{:$banner['url']}">
                    <video  autoplay muted loop 
x5-video-player-fullscreen="true"
x5-playsinline
playsinline
webkit-playsinline onmouseover="this.play()"  onmouseout="this.pause()" class="lazy" controls>
                        <source data-src="{:showfile($banner['min_imagefile'])}" type="video/mp4">
                    </video>
                </a> </div>
            <?php } else { ?>
            <div class="four-ggpic"> <a href="{:$banner['url']}">
                    <img onload="fadeIn(this)" style="display:none;" src="{:showfile($banner['min_imagefile'])}"
                        alt="{:$banner['title']}" /></a> </div>
            <?php } ?>
        </div>
        <?php } ?>
    </section>
    <?php } ?>
    <?php } ?>
    <?php if(sizeof($header_four_banners) > 0) { ?>
    <?php if (!is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "mobile"))) { ?>
    <section class="index-four-gg">
        <?php foreach ($header_four_banners as $banner) { ?>
        <div class="item">
            <?php if(strpos($banner['imagefile'], '.mp4') !== false) {?>
            <div class="four-ggpic">
                <a href="{:$banner['url']}">
                    <video  autoplay muted loop 
x5-video-player-fullscreen="true"
x5-playsinline
playsinline
webkit-playsinline onmouseover="this.play()"  onmouseout="this.pause()" class="lazy" controls>
                        <source data-src="{:showfile($banner['imagefile'])}" type="video/mp4">
                    </video>
                </a>
            </div>
            <?php } else { ?>
            <div class="four-ggpic"> <a href="{:$banner['url']}"><img onload="fadeIn(this)"
                        style="display:none;" src="{:showfile($banner['imagefile'])}" alt="{:$banner['title']}" /></a>
            </div>
            <?php } ?>
        </div>
        <?php } ?>
    </section>
    <?php } else { ?>
    <section>
        <?php foreach ($header_four_banners as $banner) { ?>
            <div class="item">
                <?php
                            if(strpos($banner['imagefile'], '.mp4') !== false) {
                                ?>
                <div class="four-ggpic"> <a href="{:$banner['url']}">
                        <video  autoplay muted loop 
x5-video-player-fullscreen="true"
x5-playsinline
playsinline
webkit-playsinline onmouseover="this.play()"  onmouseout="this.pause()" class="lazy" controls>
                            <source data-src="{:showfile($banner['min_imagefile'])}" type="video/mp4">
                        </video>
                    </a> </div>
                <?php } else { ?>
                <div class="four-ggpic"> <a href="{:$banner['url']}">
                        <img onload="fadeIn(this)" style="display:none;" src="{:showfile($banner['min_imagefile'])}"
                            alt="{:$banner['title']}" /></a> </div>
                <?php } ?>
            </div>
        <?php } ?>
    </section>
    <?php } ?>
    <?php } ?>
    <section class="news-product">
        <div class="container-fluid">
            <div class="i-title">
                <h3>NEW ARRIVAL</h3>
            </div>
            <div class="row mno-gutter">
                <?php
                if(!empty($new_products)) {
                    foreach($new_products as $prod) {
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
                                <video class="lazy"  autoplay muted loop 
x5-video-player-fullscreen="true"
x5-playsinline
playsinline
webkit-playsinline onmouseover="this.play()"  onmouseout="this.pause()"
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
                                                <use xlink:href="#icon-gongzuoquyu-5"></use>
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
        <div class="text-center list-more"> <a href="{:front_link('Category/search')}" class="btn btn-white">瀏覽全部商品</a>
        </div>

    </section>

    <?php if(!empty($middle_one_banner)) { ?>
    <section class="index-gg">
        <div class="index-ggp">
            <div class="index-ggp-p">
                <a href="{$middle_one_banner[0]['url']}">
                    <?php
                    if(strpos($middle_one_banner[0]['imagefile'], '.mp4') !== false) {
                        ?>
                    <div class="four-ggpic"> <a href="{:$banner['url']}">
                            <video  autoplay muted loop 
x5-video-player-fullscreen="true"
x5-playsinline
playsinline
webkit-playsinline onmouseover="this.play()"  onmouseout="this.pause()" class="hidden-xs lazy" controls>
                                <source data-src="{:showfile($middle_one_banner[0]['imagefile'])}" type="video/mp4">
                            </video>
                            <video  autoplay muted loop 
x5-video-player-fullscreen="true"
x5-playsinline
playsinline
webkit-playsinline onmouseover="this.play()"  onmouseout="this.pause()" class="hidden show-xs lazy" controls>
                                <source data-src="{:showfile($middle_one_banner[0]['min_imagefile'])}" type="video/mp4">
                            </video>
                        </a> </div>
                    <?php } else { ?>
                    <?php if (!is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "mobile"))) { ?>
                    <img data-src="{:showfile($middle_one_banner[0]['imagefile'])}" alt="" class="img lazy hidden-xs" />
                    <?php } else { ?>
                    <img data-src="{:showfile($middle_one_banner[0]['min_imagefile'])}" alt=""
                        class="img lazy hidden show-xs" />
                    <?php } ?>
                    <?php } ?>
                </a>
            </div>
            <div class="index-gg-c col-md-5">
                <!--<h1>{$middle_one_banner[0]['title']}</h1>
                <p>{$middle_one_banner[0]['content']}</p>
                <a target="_blank" href="{$middle_one_banner[0]['url']}">了解更多訊息 ></a>-->
            </div>
        </div>
    </section>
    <?php } ?>
    <section class="hot-product">
        <div class="container-fluid">
            <div class="i-title">
                <h3>HOT SALES</h3>
            </div>
            <div class="row mno-gutter">
                <?php
                if(!empty($hot_products)) {
                    foreach($hot_products as $prod) {
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
                                <video  autoplay muted loop 
x5-video-player-fullscreen="true"
x5-playsinline
playsinline
webkit-playsinline onmouseover="this.play()"  onmouseout="this.pause()" class="lazy" autoplay muted loop x5-video-player-fullscreen="true" x5-playsinline
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
                                                <use xlink:href="#icon-gongzuoquyu-5"></use>
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
        <div class="text-center"> <a href="{:front_link('Category/search', ['tag' => 'is_hot'])}"
                class="btn btn-white">瀏覽全部商品</a> </div>

    </section>
    <?php if(!empty($middle_two_banner)) { ?>
    <section class="index-gg">
        <div class="index-ggp">
            <div class="index-ggp-p">
                <a href="{$middle_two_banner[0]['url']}">
                    <?php
                        if(strpos($middle_two_banner[0]['imagefile'], '.mp4') !== false) {
                            ?>
                    <div class="four-ggpic"> <a href="{:$banner['url']}">
                            <video  autoplay muted loop 
x5-video-player-fullscreen="true"
x5-playsinline
playsinline
webkit-playsinline onmouseover="this.play()"  onmouseout="this.pause()" class="hidden-xs lazy" controls>
                                <source data-src="{:showfile($middle_two_banner[0]['imagefile'])}" type="video/mp4">
                            </video>
                            <video  autoplay muted loop 
x5-video-player-fullscreen="true"
x5-playsinline
playsinline
webkit-playsinline onmouseover="this.play()"  onmouseout="this.pause()" class="hidden show-xs lazy" controls>
                                <source data-src="{:showfile($middle_two_banner[0]['min_imagefile'])}" type="video/mp4">
                            </video>
                        </a> </div>
                    <?php } else { ?>
                    <img data-src="{:showfile($middle_two_banner[0]['imagefile'])}" alt="" class="img lazy hidden-xs" />
                    <img data-src="{:showfile($middle_two_banner[0]['min_imagefile'])}" alt=""
                        class="img lazy hidden show-xs" />
                    <?php } ?>
                </a>
            </div>
            <div class="index-gg-c col-md-5">
                <!--<h1>{$middle_two_banner[0]['title']}</h1>
                    <p>{$middle_two_banner[0]['content']}</p>
                    <a target="_blank" href="{$middle_two_banner[0]['url']}">了解更多訊息 ></a>-->
            </div>
        </div>
    </section>
    <?php } ?>
    <section class="sale-product">
        <div class="container-fluid ">
            <div class="i-title">
                <h3>ON SALE</h3>
            </div>
            <div class="row mno-gutter">
                <?php
                if(!empty($sale_products)) {
                foreach($sale_products as $prod) {
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
                                <video  autoplay muted loop 
x5-video-player-fullscreen="true"
x5-playsinline
playsinline
webkit-playsinline onmouseover="this.play()"  onmouseout="this.pause()" class="lazy" autoplay muted loop x5-video-player-fullscreen="true" x5-playsinline
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
                                                <use xlink:href="#icon-gongzuoquyu-5"></use>
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
        <div class="text-center"> <a href="{:front_link('Category/search', ['tag' => 'is_sale'])}"
                class="btn btn-white">瀏覽全部商品</a> </div>
    </section>
    {include file="public/footer" /}
</div>
</div>
</body>

</html>