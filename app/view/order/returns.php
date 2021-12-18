{include file="public/meta" /}
<link rel="stylesheet" type="text/css" href="/static/front/css/list.css">
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
                    <li><a href="{:front_link('Order/index')}">訂單記錄</a></li>
                    <li class="active">退貨列表</li>
                </ol>
            </div>
        </div>
    </section>
    <section class="member">
        <div class="container">

            <div class="row">
                <div class="col-sm-12 col-md-2">
                    <div class="list-group member-nav">
                        <div class="nav-collapse">會員中心<span class="pull-right"><i class="fa fa-bars"></i></span></div>
                        {include file="public/customer_menu" /}
                    </div>
                </div>
                <div class="col-sm-12 col-md-10">

                    <div class="user-key"><h1>退貨列表</h1></div>
                    <div class="member-form">

                        <div class="table-set">
                            <?php
                            if(!empty($list)) {
                                foreach($list as $v) {
                            ?>
                            <div class="location-table row text-center no-gutter">
                                <div class="col-xs-6 col-sm-6  col-md-2">
                                    <dl>
                                        <dt>訂單編號</dt>
                                        <dd class="justify">{:$v['oid']}</dd>
                                    </dl>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-2">
                                    <dl>
                                        <dt>購買日期</dt>
                                        <dd class="justify">{:substr($v['oid'],0,8)}</dd>
                                    </dl></div>
                                <div class="col-xs-6 col-sm-6 col-md-2">
                                    <dl>
                                        <dt>退貨狀態</dt>
                                        <dd class="justify">
                                            <?php
                                            $returnstates = get_return_states();
                                            echo isset($returnstates[$v['state']])?$returnstates[$v['state']]:'N/A';
                                            ?>
                                        </dd>
                                    </dl>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-2">
                                    <dl>
                                        <dt>退回金額</dt>
                                        <dd class="justify">$ {$v['total_amount']}</dd>
                                    </dl>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-2">
                                    <dl>
                                        <dt>退貨日期</dt>
                                        <dd class="justify">{:date('Ymd', $v['create_date'])}</dd>
                                    </dl>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-2">
                                    <dl>
                                        <dt>訂單明細</dt>
                                        <dd class="justify"><a href="{:front_link('return_detail', ['returnno' => $v['returnno']])}">查看明細</a></dd>
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
    </section>
    {include file="public/footer" /}
</div>
</div>
</body>
</html>