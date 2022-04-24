{include file="public/meta" /}
<!-- <link rel="stylesheet" type="text/css" href="/static/front/css/slider-pro.min.css" media="screen"/> -->
<!-- <link rel="stylesheet" type="text/css" href="/static/front/css/examples.css" media="screen"/> -->
<canvas id="confetti" style="position:absolute;left:0;top:0;z-index:-1;"></canvas>
    <script>
    (function() {
  var COLORS, Confetti, NUM_CONFETTI, PI_2, canvas, confetti, context, drawCircle, i, range, resizeWindow, xpos;

  NUM_CONFETTI = 350;

  COLORS = [[85, 71, 106], [174, 61, 99], [219, 56, 83], [244, 92, 68], [248, 182, 70]];

  PI_2 = 2 * Math.PI;

  canvas = document.getElementById("confetti");

  context = canvas.getContext("2d");

  window.w = 0;

  window.h = 0;

  resizeWindow = function() {
    var body = document.body,
    html = document.documentElement;

    var height = Math.max( body.scrollHeight, body.offsetHeight, 
                       html.clientHeight, html.scrollHeight, html.offsetHeight );
    window.w = canvas.width = window.innerWidth;
    return window.h = canvas.height = height;
  };

  window.addEventListener('resize', resizeWindow, false);

  window.onload = function() {
    return setTimeout(resizeWindow, 0);
  };

  range = function(a, b) {
    return (b - a) * Math.random() + a;
  };

  drawCircle = function(x, y, r, style) {
    context.beginPath();
    context.arc(x, y, r, 0, PI_2, false);
    context.fillStyle = style;
    return context.fill();
  };

  xpos = 0.5;

  document.onmousemove = function(e) {
    return xpos = e.pageX / w;
  };

  window.requestAnimationFrame = (function() {
    return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame || function(callback) {
      return window.setTimeout(callback, 1000 / 60);
    };
  })();

  Confetti = (function() {
    function Confetti() {
      this.style = COLORS[~~range(0, 5)];
      this.rgb = "rgba(" + this.style[0] + "," + this.style[1] + "," + this.style[2];
      this.r = ~~range(2, 6);
      this.r2 = 2 * this.r;
      this.replace();
    }

    Confetti.prototype.replace = function() {
      this.opacity = 0;
      this.dop = 0.03 * range(1, 4);
      this.x = range(-this.r2, w - this.r2);
      this.y = range(-20, h - this.r2);
      this.xmax = w - this.r;
      this.ymax = h - this.r;
      this.vx = range(0, 2) + 8 * xpos - 5;
      return this.vy = 0.7 * this.r + range(-1, 1);
    };

    Confetti.prototype.draw = function() {
      var _ref;
      this.x += this.vx;
      this.y += this.vy;
      this.opacity += this.dop;
      if (this.opacity > 1) {
        this.opacity = 1;
        this.dop *= -1;
      }
      if (this.opacity < 0 || this.y > this.ymax) {
        this.replace();
      }
      if (!((0 < (_ref = this.x) && _ref < this.xmax))) {
        this.x = (this.x + this.xmax) % this.xmax;
      }
      return drawCircle(~~this.x, ~~this.y, this.r, this.rgb + "," + this.opacity + ")");
    };

    return Confetti;

  })();

  confetti = (function() {
    var _i, _results;
    _results = [];
    for (i = _i = 1; 1 <= NUM_CONFETTI ? _i <= NUM_CONFETTI : _i >= NUM_CONFETTI; i = 1 <= NUM_CONFETTI ? ++_i : --_i) {
      _results.push(new Confetti);
    }
    return _results;
  })();

  window.step = function() {
    var c, _i, _len, _results;
    requestAnimationFrame(step);
    context.clearRect(0, 0, w, h);
    _results = [];
    for (_i = 0, _len = confetti.length; _i < _len; _i++) {
      c = confetti[_i];
      _results.push(c.draw());
    }
    return _results;
  };

  step();

}).call(this);
</script>
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
                    <video class="lazy" controls>
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
                    <video class="lazy" controls>
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
                    <video class="lazy" controls>
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
                    <video class="lazy" controls>
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
                    <video class="lazy" controls>
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
                    <video class="lazy" controls>
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
                    <video class="lazy" controls>
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
                        <video class="lazy" controls>
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
                            <video class="hidden-xs lazy" controls>
                                <source data-src="{:showfile($middle_one_banner[0]['imagefile'])}" type="video/mp4">
                            </video>
                            <video class="hidden show-xs lazy" controls>
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
                            <video class="hidden-xs lazy" controls>
                                <source data-src="{:showfile($middle_two_banner[0]['imagefile'])}" type="video/mp4">
                            </video>
                            <video class="hidden show-xs lazy" controls>
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