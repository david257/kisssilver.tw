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
                    <li class="active">我的折價券</li>
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
                    <?php
                    if(!empty($list)) {
                        foreach($list as $v) {

                            ?>
                    <div class="member-form">
                        <div class="clearfix">
                            <div class="table-set">

                                <div class="card">

                                    <div class="card-body">
                                        <h4 class="card-title card-header">{:$v['title']}</h4>
                                        <p class="card-text">
                                            <div>類型：{:$v['coupon_type']=='online'?'線上發放':'線下發放'}</div>
                                            <div>券號：{:$v['code']}</div>
                                            <div>最小訂購金額：{:$v['min_amount']}</div>
                                            <div>折扣金額：{:$v['amount']}</div>
                                            <div>有效期：{:date('Y/m/d', $v['start_time'])}~{:date('Y/m/d', $v['end_time'])}</div>
                                            <div>是否已使用：{:$v['has_used']?'已用':'未用'}</div>
                                            <div>使用日期：{:$v['used_date']?date('Y/m/d', $v['used_date']):''}</div>
                                            <div>啟用狀態:{:$v['state']?'啟用':'未啟用'}</div>
                                            <div>發放日期:{:date('Y/m/d H:i:s', $v['create_time'])}</div>
                                        </p>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <?php } } ?>
                    <div class="container">{:$pages}</div>
                </div>
            </div>
        </div>

    </section>
    {include file="public/footer" /}
</div>
</div>
</body>
</html>