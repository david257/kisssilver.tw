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
                    <li class="active">紅利點數</li>
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

                    <div class="user-key"><h1>紅利點數明細<span class="pull-right">餘額 <span class="text-danger">{:$customer['credits']}</span> 點</span></h1></div>
                    <div class="member-form">
                        <div class="clearfix">
                            <div class="table-set">
                                <?php
                                if(!empty($list)) {
                                    foreach($list as $v) {

                                ?>
                                <div class="location-table row text-center no-gutter">
                                    <div class="col-xs-6 col-md-4">
                                        <dl>
                                            <dt>日期</dt>
                                            <dd class="justify">{:date('Y/m/d H:i:s', $v['create_at'])}</dd>
                                        </dl>
                                    </div>
                                    <div class="col-xs-6 col-md-4">
                                        <dl>
                                            <dt>訂單編號</dt>
                                            <dd class="justify">{:$v['ordno']} {:$v['msg']}</dd>
                                        </dl></div>
                                    <div class="col-xs-6 col-md-2">
                                        <dl>
                                            <dt>紅利點數</dt>
                                            <dd class="justify">{:$v['change_amount']>0?'+'.$v['change_amount']:$v['change_amount']} 點</dd>
                                        </dl>
                                    </div>
                                    <div class="col-xs-6 col-md-2">
                                        <dl>
                                            <dt>餘額</dt>
                                            <dd class="justify">{:$v['balance_amount']} 點</dd>
                                        </dl>
                                    </div>
                                </div>
                                <?php } } ?>
                            </div>
                        </div>
                    </div>
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