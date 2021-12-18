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
                    <li><a href="{:front_link('News/index')}">News</a></li>
                    <li class="active">{:$news_detail['title']}</li>
                </ol>
            </div>



        </div>
    </section>
    <section class="help">

        <div class="help-content">
            <div class="container">
                <h1>{:$news_detail['title']}</h1>
                <div class="bt-gray">發佈時間：{:format_date($news_detail['create_at'], '/')}</div>
                <hr />
                {:$news_detail['content']}
            </div>
            <div class="container">
                <nav aria-label="Page navigation">
                    <ul class="pager">
                        <?php
                        if(!empty($prev_link)) {
                        ?>
                            <li class="previous pull-left"><a href="{:front_link('detail', ['newsid' => $prev_link['newsid']])}">上一篇</a></li>
                        <?php } else { ?>
                            <li class="previous disabled pull-left"><a href="#">上一篇</a></li>
                        <?php } ?>
                        <?php
                        if(!empty($next_link)) {
                            ?>
                            <li class="next pull-right"><a href="{:front_link('detail', ['newsid' => $next_link['newsid']])}">下一篇</a></li>
                        <?php } else { ?>
                            <li class="next disabled pull-right"><a href="#">下一篇</a></li>
                        <?php } ?>
                    </ul>
                </nav>

            </div>
        </div>

    </section>
    {include file="public/footer" /}
</div>
</div>
<script src="/static/front/js/moment-with-locales.min.js"></script>
<script src="/static/front/js/bootstrap-datetimepicker.min.js"></script>
<script>
    var now_url = "{:front_link('News/index')}";
    $('#datetime').datetimepicker({
        format: 'yyyy-mm-dd',
        showTodayButton:true,
        language: 'zh-TW',
        viewMode: 'days',
        minView: "month",
        autoclose : 'true',
    });

    $("#submit_btn").click(function() {
        var goto_url;
        if(now_url.indexOf("?") !== -1) {
            goto_url = now_url+"&date="+$("#datetime").val();
        } else {
            goto_url = now_url+"?date="+$("#datetime").val();
        }
        document.location.href=goto_url;
    })
</script>
</body>
</html>