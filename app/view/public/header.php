{include file="public/top_news" /}
<?php
$config_setting = get_setting();
$setting = $config_setting["setting"];
?>
<header>
    <nav class="navbar navbar-default">
    <?php if (!is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "mobile"))) { ?>
    <canvas id="world" style="position:fixed;left:0;top:40px;z-index:-1;width:100%;"></canvas>
    <script>
    (function() {
  var COLORS, Confetti, NUM_CONFETTI, PI_2, canvas, confetti, context, drawCircle, i, range, resizeWindow, xpos;

  NUM_CONFETTI = 50;

  COLORS = [[85, 71, 106], [174, 61, 99], [219, 56, 83], [244, 92, 68], [248, 182, 70]];

  PI_2 = 2 * Math.PI;

  canvas = document.getElementById("world");

  context = canvas.getContext("2d");

  window.w = 0;

  window.h = 0;

  resizeWindow = function() {
    window.w = canvas.width = window.innerWidth;
    return window.h = canvas.height = 137.63;
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
<?php } ?>
        <div class="nav-container">
            <div class="container-fluid">
                <div class="nav-top">
                    <div class="nav-top-x">
                        <?php if (!is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "mobile"))) { ?>
                        <ul class="nav navbar-nav  navbar-left">
                            <li><a href="{:front_link('StoreNetwork/index')}">
                                    <svg class="icon" aria-hidden="true">
                                        <use xlink:href="#icon-dizhi"></use>
                                    </svg>
                                    銷售據點</a></li>
                            <li><a href="{:isset($setting['common']['line'])?$setting['common']['line']:''}">
                                    <svg class="icon" aria-hidden="true">
                                        <use xlink:href="#icon-line"></use>
                                    </svg>
                                    Line@客服</a></li>
                            <li><a href="{:front_link('Lives/index')}">
                                    <svg class="icon" aria-hidden="true">
                                        <use xlink:href="#icon-tubiaozhizuomoban-"></use>
                                    </svg>
                                    直 播</a></li>
                        </ul>
                        <?php } ?>
                        <form class="navbar-form navbar-right search" action="{:front_link('Category/search')}">
                            <div class="form-group">
                                <input type="text" class="form-control" name="keyword"
                                    value="{:isset($keyword)?$keyword:''}" placeholder="Search">
                            </div>
                            <button type="submit" class="btn btn-default">
                                <svg class="icon" aria-hidden="true">
                                    <use xlink:href="#icon-search1"></use>
                                </svg>
                            </button>
                        </form>
                        <?php if (!is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "mobile"))) { ?>
                        <ul class="nav navbar-nav  navbar-right">
                            <li><a href="{:front_link('Customer/profile')}">
                                    <svg class="icon" aria-hidden="true">
                                        <use xlink:href="#icon-user"></use>
                                    </svg>
                                    會員中心</a></li>
                            <li><a href="{:front_link('Wishlist/index')}">
                                    <svg class="icon" aria-hidden="true">
                                        <use xlink:href="#icon-aixin"></use>
                                    </svg>
                                    <span class="">Love（{:wishlist_qtys()}）</span></a></li>
                            <li><a href="{:front_link('Cart/index')}">
                                    <svg class="icon" aria-hidden="true">
                                        <use xlink:href="#icon-gongzuoquyu-5"></use>
                                    </svg>
                                    <span class=" ">cart（{:cart_qtys()}）</span></a></li>
                        </ul>
                        <?php } ?>
                        <?php if (is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "mobile"))) { ?>
                        <!-- Brand and toggle get grouped for better mobile display -->
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                                data-target="#bs-example-navbar-collapse-1" aria-expanded="false"> <span
                                    class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span
                                    class="icon-bar"></span> <span class="icon-bar"></span> </button>
                            <a class="navbar-brand" href="{:front_link('Index/index')}"><img
                                    src="/static/front/images/logo.png" width="180" height="140" alt="" /></a>
                            <div class="navbar-icon">
                                <!-- <a href="{:front_link('Lives/index')}">
                                    <svg class="icon" aria-hidden="true">
                                        <use xlink:href="#icon-tubiaozhizuomoban-"></use>
                                    </svg>
                            </a>-->
                                <a href="javascript:void()" class="m-search-k">
                                    <svg class="icon" aria-hidden="true">
                                        <use xlink:href="#icon-search1"></use>
                                    </svg>
                                </a>
                                <a href="{:front_link('Customer/profile')}">
                                    <svg class="icon" aria-hidden="true">
                                        <use xlink:href="#icon-user"></use>
                                    </svg>
                                </a>
                                <a href="{:front_link('Cart/index')}" class="wap-cart-k">
                                    <em>{:cart_qtys()}</em>
                                    <svg class="icon" aria-hidden="true">
                                        <use xlink:href="#icon-gongzuoquyu-5"></use>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    <?php if (!is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "mobile"))) { ?>
                    <div class="web-logo">
                        <a href="{:front_link('Index/index')}">
                            <img src="/static/front/images/logo.png" alt="Kiss-Silver" />
                        </a>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <?php if (is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "mobile"))) { ?>
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#bs-example-navbar-collapse-1" aria-expanded="false"> <span class="sr-only">Toggle
                            navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span
                            class="icon-bar"></span> </button>
                    <a class="navbar-brand" href="#"><img src="/static/front/images/logo.png" width="180" height="140"
                            alt="" /></a>
                </div>
                <div class="caption">分類</div>
                <?php } ?>
                <ul class="top-nav text-center center-block" id="navss">
                    <?php
                    $headerCates = get_all_categories();
                    if(isset($headerCates[0]) && !empty($headerCates[0])) {
                        foreach($headerCates[0] as $cate) {
                    ?>
                    <li class="nav-item nav-drown-k dropdown">
                        <a class="nav-link" id="sub-drown{:$cate['catid']}"
                            href="{:front_link('Category/index', ['catid' => $cate['catid']])}" data-toggle="dropdown"
                            role="button" aria-haspopup="true" aria-expanded="false">
                            <span class="show-xs">
                                <?php if(!empty($cate['icon'])) { ?>
                                <svg class="icon" aria-hidden="true">
                                    <use xlink:href="{:$cate['icon']}"></use>
                                </svg>
                                <?php } ?>
                            </span>
                            {:$cate['catname']}
                        </a>
                        <?php
                        if(isset($headerCates[$cate["catid"]]) && !empty($headerCates[$cate["catid"]])) {
                        ?>
                        <div class="nav-drown sun-drown dropdown-menu" aria-labelledby="sub-drown{:$cate['catid']}">
                            <div class="wap-open">{:$cate['catname']} <button class="pull-right btn btn-default"
                                    type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                        class="iconfont icon-guanbi"></i></button></div>
                            <div class="row no-gutter">
                                <?php
                                foreach($headerCates[$cate["catid"]] as $sk => $scate) {
                                ?>
                                <dl class="col-xs-12 col-md-12">
                                    <dt><a
                                            href="{:front_link('Category/index', ['catid' => $scate['catid']])}">{:$scate['catname']}<i>·{:$scate['en_catname']}</i></a>
                                    </dt>
                                    <?php if(!empty($scate['cat_banner'])) {?>
                                    <?php } ?>
                                    <?php
                                    if(isset($headerCates[$scate["catid"]]) && !empty($headerCates[$scate["catid"]])) {
                                        foreach($headerCates[$scate["catid"]] as $ssk => $sscate) {
                                ?>
                                    <dd><a
                                            href="{:front_link('Category/index', ['catid' => $sscate['catid']])}">{:$sscate['catname']}<i>·{:$sscate['en_catname']}</i></a>
                                    </dd>
                                    <?php } } ?>
                                </dl>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>
                    </li>
                    <?php } } ?>
                </ul>
                <?php if (is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "mobile"))) { ?>
                <div class="caption">其他資訊</div>
                <ul class="nav navbar-nav">
                    <li><a href="{:front_link('StoreNetwork/index')}">
                            <svg class="icon" aria-hidden="true">
                                <use xlink:href="#icon-dizhi"></use>
                            </svg>
                            銷售據點</a></li>
                    <li><a href="{:isset($setting['common']['line'])?$setting['common']['line']:''}">
                            <svg class="icon" aria-hidden="true">
                                <use xlink:href="#icon-line"></use>
                            </svg>
                            Line@客服</a></li>
                    <li><a href="{:front_link('Lives/index')}">
                            <svg class="icon" aria-hidden="true">
                                <use xlink:href="#icon-tubiaozhizuomoban-"></use>
                            </svg>
                            直 播</a></li>
                    <li><a href="{:front_link('Customer/profile')}">
                            <svg class="icon" aria-hidden="true">
                                <use xlink:href="#icon-user"></use>
                            </svg>
                            會員中心</a></li>
                    <li><a href="{:front_link('Wishlist/index')}">
                            <svg class="icon" aria-hidden="true">
                                <use xlink:href="#icon-aixin"></use>
                            </svg>
                            <span>我的收藏({:wishlist_qtys()})</span></a></li>
                    <li><a href="{:front_link('Cart/index')}">
                            <svg class="icon" aria-hidden="true">
                                <use xlink:href="#icon-gongzuoquyu-5"></use>
                            </svg>
                            <span>購物車({:cart_qtys()})</span></a></li>
                </ul>
                <?php } ?>
            </div>
    </nav>
</header>