{include file="public/meta" /}
<link rel="stylesheet" type="text/css" href="/static/front/dist/assets/owl.theme.default.css">
<link rel="stylesheet" type="text/css" href="/static/front/css/list.css">
<link rel="stylesheet" type="text/css" href="/static/front/css/user.css">
<link href="/static/front/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
{include file="public/kefu" /}
<div class="index-box">
    {include file="public/header" /}
    <section class="sub">
        <div class="container">
            <div class="sub-loation">
                <ol class="breadcrumb">
                    <li><a href="{:front_link('Index/index')}">首頁</a></li>
                    <li class="active">{:$page_detail['title']}</li>
                </ol>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-2">
                    <div class="list-group member-nav">
                        <div class="nav-collapse">{:$PageMenu['title']}<span class="pull-right"><i class="fa fa-bars"></i></span></div>
                        <nav>
                            <ul>
                                <?php
                                if(!empty($PageMenu['list'])) {
                                    foreach($PageMenu['list'] as $pageRow) {
                                    if($page_detail['pageid'] == $pageRow['pageid']) {
                                        $class = 'active';
                                    } else {
                                        $class = '';
                                    }
                                ?>
                                <li><a href="{:front_link('detail', ['pageid' => $pageRow['pageid']])}" class="list-group-item {$class}">{:$pageRow['title']}</a></li>
                                <?php } } ?>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="col-md-10">

                    <h1>{:$page_detail['title']}</h1>

                    <hr />
                    <p>{:$page_detail['content']}</p>


                </div>
            </div>
        </div>


    </section>
    {include file="public/footer" /}
</div>
</div>
<script src="/static/front/js/moment-with-locales.min.js"></script>
<script src="/static/front/js/bootstrap-datetimepicker.min.js"></script>
</body>
</html>