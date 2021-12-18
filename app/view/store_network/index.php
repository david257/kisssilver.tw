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
                    <li class="active">銷售據點</li>
                </ol>
            </div>



        </div>
    </section>
    <section class="news-list">

        <div class="container">
            <div class="row">
                <?php
                if(!empty($list)) {
                    foreach($list as $v) {
                ?>
                <div class="col-xs-12 col-md-12 ">
                    <div class="news-l row no-gutter">
                        <div class="col-xs-12 col-md-12">
                            {:$v['map_code']}
                        </div>
                        <div class="col-xs-12 col-md-12">
                            <div class="news-content">
                                <a href="{:front_link('detail', ['id' => $v['id']])}" title="{:$v['title']}">
                                    <h3>{:$v['title']}</h3></a>
                                <h5>電話：{:$v['tel']}</h5>
                                <p>{:$v['address']}</p>
								<p>{:$v['content']}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } } ?>
            </div>
        </div>
    </section>
    {include file="public/footer" /}
</div>
</div>
</body>
</html>