{include file="public/meta" /}
<link rel="stylesheet" type="text/css" href="/static/front/css/slider-pro.min.css" media="screen"/>
<link rel="stylesheet" type="text/css" href="/static/front/css/examples.css" media="screen"/>
<link rel="stylesheet" type="text/css" href="/static/front/css/list.css">
<link rel="stylesheet" href="/static/front/css/viewer.min.css">
<link rel="stylesheet" type="text/css" href="/static/front/css/user.css">
{include file="public/kefu" /}
<div class="index-box">
    {include file="public/header" /}
    <section class="sub">
        <div class="container">
            <div class="sub-loation">
                <ol class="breadcrumb">
                    <li><a href="{:front_link('Index/index')}">首頁</a></li>
                    <li><a href="#">會員中心</a></li>
                    <li class="active">會員詳細資料</li>
                </ol>
            </div>



        </div>
    </section>
    <section class="member">
        <div class="container">
            <div class="row">
                <div class="col-md-2">
                    <div class="list-group member-nav">
                        <div class="nav-collapse">會員中心<span class="pull-right"><i class="iconfont icon-duiqi04"></i></span></div>
                        {include file="public/customer_menu" /}
                    </div>
                </div>
                <div class="col-md-10">

                    <div class="user-key"><h1>我的追蹤商品<span class="pull-right">共 <span class="text-danger">{:$totalItems}</span> 件</span></h1></div>
                    <section class="sub-list">

                        <div class="row no-gutters">
                            <?php
                            if(!empty($list)) {
                            foreach($list as $prod) {
                                $image = isset($images[$prod['prodid']])?showfile($images[$prod['prodid']]['image_thumb']):'';
                                $image2 = isset($images2[$prod['prodid']])?showfile($images2[$prod['prodid']]['image_thumb']):$image;
                            ?>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <div class="list-list">


                                    <div class="ps-wrap">
                                        <div class="ps-pic"><a href="{:front_link('Product/detail', ['prodid' => $prod['prodid']])}"><img src="{$image}"  onMouseOver="javascript:this.src='{$image2}'" onMouseOut="javascript:this.src='{$image}'"alt=""></a></div>

                                        <div class="ps-love"><a href="#"><i class="fa fa-heart text-danger"></i></a></div>
                                    </div>

                                    <div class="ps-content">
                                        <div class="ps-title-small">{:$prod['prodname']}</div>
                                        <div class="ps-title-lg"><a href="{:front_link('Product/detail', ['prodid' => $prod['prodid']])}">{:$prod['prod_features']}</a></div>
                                        <div class="ps-price">{:price_label($prod)}</div>
                                        <div class="ps-colors">{:isset($vos[$prod['void']])?$vos[$prod['void']]:0} colors</div>
                                        <div class="ps-price"><a class="AjaxTodo" href="{:front_link('Wishlist/remove', ['prodid' => $prod['prodid']])}">移除</a></div>
                                    </div>
                                </div>

                            </div>
                            <?php } } ?>

                        </div>
                    </section>
                    <div class="container">
                        {:$pages}

                    </div>
                </div>
            </div>
        </div>
    </section>
    {include file="public/footer" /}
</div>
</div>
</body>
</html>