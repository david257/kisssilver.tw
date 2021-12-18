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
                    <li class="active">News</li>
                </ol>
            </div>
        </div>
    </section>
    <section class="news-list">
        <div class="container">
            <div class="row">
                <div class='col-xs-8 col-sm-3'>
                    <div class="timer-box">
                        <label>
                            <input type='text' class="form-control" name="date" id='datetime' value="{:$date}" placeholder="選擇日期" />
                            <i class="fa fa-calendar-o"></i>
                        </label>
                    </div>
                </div>
                <div class='col-xs-4 col-sm-3'>
                    <button id="submit_btn" class="btn btn-default">篩選 <i class="fa fa-long-arrow-right"></i></button>
                </div>
            </div>

        </div>
        <div class="container">
            <div class="row">
                <?php
                if(!empty($list)) {
                    foreach($list as $v) {
						if(!empty($v['url'])) {
							$url = $v['url'];
						} else {
							$url = front_link('detail', ['newsid' => $v['newsid']]);
						}
                ?>
                <div class="col-xs-12 col-md-12 ">
                    <div class="news-l row no-gutter">
                        <div class="col-xs-12 col-md-4">
                            <a href="{:$url}" title="{:$v['title']}"><img src="{:showfile($v['thumb_image'])}" alt="{:$v['title']}" /></a>
                        </div>
                        <div class="col-xs-12 col-md-8">
                            <div class="news-content">
                                <a href="{$url}" title="{:$v['title']}">
                                    <h3>{:$v['title']}</h3></a>
                                <h5><i class="fa fa-clock-o"></i> {:format_date($v['create_at'])}</h5>
                                <p><?php echo wordscut($v['content'], 200);?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } } ?>

            </div>
        </div>
        <div class="container">
            {:$pages}
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