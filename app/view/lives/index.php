{include file="public/meta" /}
<link rel="stylesheet" type="text/css" href="/static/front/dist/assets/owl.theme.default.css">
<link rel="stylesheet" type="text/css" href="/static/front/css/list.css">
<link rel="stylesheet" type="text/css" href="/static/front/css/user.css">
<link href="/static/front/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
{include file="public/kefu" /}
<div class="index-box">
    {include file="public/header" /}
    <section class="live">
        <div class="live-index no-gutters">
            <div class="tn-fix-icon"><a href="#"><img src="images/tnicon.png" alt=""></a></div>
            {:$live['live_code']}
            <div class="clearfix"></div>
        </div>

    </section>
    <section class="detail-tj">
        <div class="live-pro">
            <div class="container-small">
                <h3>商品專區</h3>
                <hr />
                <div class="owl-carousel">
                    <?php
                    if(!empty($products)) {
                    foreach($products as $prod) {
                        $image = isset($prod['image'])?$prod['image']:'';
                        $image2 = isset($prod['image2'])?$prod['image2']:$image;
                        $video = isset($prod["video"])?$prod["video"]:'';
                    ?>
                    <div class="item">
                        <div class="list-list">
                            <div class="ps-wrap">
                                <div class="ps-pic"><a target="_blank" href="{:front_link('Product/detail', ['prodid' => $prod['prodid']])}">
                                        <?php
                                        if(!empty($video)) {
                                            ?>
                                            <video controls onmouseover="this.play()" onmouseout="this.pause()"><source src="{:showfile($video)}" type="video/mp4"></video>
                                        <?php } else { ?>
                                            <img src="{$image}" onMouseOvers="javascript:this.src='{$image2}'" onMouseOutw="javascript:this.src='{$image}'"   alt=""/>
                                        <?php } ?>
                                    </a></div>
                                <div class="ps-tag">New</div>
                                <div class="ps-love"><a href="#"><i class="fa fa-heart-o"></i></a></div>
                            </div>

                            <div class="ps-content">
                                <div class="ps-title-small">{:$prod['prodname']}</div>
                                <div class="ps-title-lg"><a href="{:front_link('Product/detail', ['prodid' => $prod['prodid']])}">{:$prod['prod_features']}</a></div>
                                <div class="ps-price">{:price_label($prod)}</div>
                            </div>
                        </div>
                    </div>
                    <?php } } ?>
                </div>
            </div>
        </div>
    </section>
    <?php if($live['jiangpin_qty']>0) { ?>
    <section class="detail-tj">
        <div class="cj-fix-icon"><a href="#"><img src="images/cjicon.png" alt=""/></a></div>

        <div class="live-pro">
            <div class="container-small">
                <h3 id="chouj" name="chouj">抽獎專區</h3>
                <hr />
                <div class="chou-content">
                    <p>
                        本期抽獎活動共計獎品<b>{:$live['jiangpin_qty']}</b>件，可中獎會員<b>{:$live['jiangpin_qty']}</b>位。目前共計參與抽獎會員<b>{:$totalapply}</b>人次，中獎結果將於活動結束後24小時內公布。
                        {:$live['content']}
                    </p>
                </div>
                <div class="chou-content">
                    <h3 class="text-center">抽獎開始時間<span class="text-danger">{:date('Y/m/d H:i', $live['start_date'])}</span>，抽獎截止時間<span class="text-danger">{:date('Y/m/d H:i', $live['end_date'])}</span> ,距離抽獎結束還有</h3>
                </div>
                <div class="chou-content">
                    <div class=" text-center margins">
                        <div class="live-time">

                            <div class="data-show-box h1" id="dateShow1">
                                <span id="countd" class="date-tiem-span d">00</span>:
                                <span id="counth" class="date-tiem-span h">00</span>:
                                <span id="countm" class="date-tiem-span m">00</span>:
                                <span id="counts" class="date-s-span s">00</span>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="chou-btn text-center">
                    <?php if($live['end_date']<time()) { ?>
                        <button class="btn btn-lg" style="background: gay">抽獎已結束</button>
                    <?php } elseif($live['start_date']>time()) { ?>
                        <button class="btn btn-lg" style="background: gay">抽獎還未開始</button>
                    <?php } else { ?>
                    <button class="AjaxTodo btn btn-lg btn-yellow1" data-tip="確定要參與抽獎嗎" href="{:front_link('lottery', ['liveid' => $live['liveid']])}">我要抽獎</button>
                    <?php } ?>
                </div>

                <h3>中獎名單</h3>
                <hr />
                <div class="chou-table">
                    <table width="100%" border="0">
                        <tbody>
                        <tr>
                            <th colspan="3" scope="col" height="60">中獎名單</th>
                        </tr>
                        <tr>
                            <th width="50" height="50" align="center" scope="col">#</th>
                            <th height="50" align="center" scope="col">會員編號</th>
                            <th height="50" align="center" scope="col">會員等級</th>
                        </tr>
                        <?php
                        if(!empty($lucylist)) {
                            foreach($lucylist as $v) {
                        ?>
                        <tr>
                            <td width="50" height="50" align="center">1</td>
                            <td height="50" align="center">{:$v['vipcode']}</td>
                            <td height="50" align="center">{:$v['title']}</td>
                        </tr>
                        <?php } } else { ?>
                            <tr>
                                <td colspan="3" scope="col" height="60">等待開獎</td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </section>
    <?php } ?>
    {include file="public/footer" /}
</div>
</div>
<script>
    var starttime = new Date("{:date('Y/m/d H:i', $live['end_date'])}");
    setInterval(function () {
        var nowtime = new Date();
        var time = starttime - nowtime;
        if(time<=0) {
            day = '00';
            hour = '00';
            minute = '00';
            seconds = '00';
        } else {
            var day = parseInt(time / 1000 / 60 / 60 / 24);
            if(day<10) {
                day = '0'+day;
            }
            var hour = parseInt(time / 1000 / 60 / 60 % 24);
            if(hour<10) {
                hour = '0'+hour;
            }
            var minute = parseInt(time / 1000 / 60 % 60);
            if(minute<10) {
                minute = '0'+minute;
            }
            var seconds = parseInt(time / 1000 % 60);
            if(seconds<10) {
                seconds = '0'+seconds;
            }
        }
        $("#countd").html(day);
        $("#counth").html(hour);
        $("#countm").html(minute);
        $("#counts").html(seconds);
    }, 1000);
</script>

</body>
</html>