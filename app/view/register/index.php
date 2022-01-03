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
        <div class="container-fluid">
            <div class="sub-loation">
                <ol class="breadcrumb">
                    <li><a href="#">首頁</a></li>
                    <li class="active">會員註冊</li>
                </ol>
            </div>



        </div>
    </section>
    <section class="login">
        <div class="container">
            <div class="login-box">
                <div class="products-key"><h1>會員註冊</h1></div>
                <div class="login-form">
                    <form class="form-horizontal AjaxForm" method="post" action="{:front_link('index')}">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="email" maxlength="255" class="form-control" id="inputEmail3" name="custconemail" placeholder="請輸入帳號(Email )">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-8">
                                <input type="number" maxlength="255" class="form-control" id="inputEmail3" name="mobile" placeholder="手機號碼">
                            </div>
                            <div class="col-sm-4">
                                <a class="btn btn-success" id="get_mobile_code" href="">獲取手機驗證碼</a>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="number" maxlength="255" class="form-control" id="inputEmail3" name="code" placeholder="請輸入驗證碼">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" maxlength="100" class="form-control" id="inputEmail3" name="fullname" placeholder="請輸入姓名">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="password" class="form-control" id="inputPassword3" name="custpassword" placeholder="請輸入密碼">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="password" class="form-control" id="inputPassword3" name="custpassword2" placeholder="請再次輸入密碼">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12 no-gutters">
                                <button type="submit" class="btn btn-default btn-lg col-md-12 no-margin">註  冊</button>
                            </div>
                        </div>
                        <hr />
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-blue btn-lg col-md-12 no-margin"><i class="fa fa-facebook-official"></i> 使用facebook帳號登入</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-red btn-lg col-md-12 no-margin"><i class="fa fa-google"></i> 使用google帳號登入</button>
                            </div>
                        </div>
                    </form>
                </div>
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

    var wait_secs = {$wait_secs};

    sms_btn();
    var t;
    function sms_btn() {
        if(wait_secs>0) {
            $("#get_mobile_code").addClass("disabled");
           t = setInterval(function () {
                if(wait_secs<=0) {
                    $("#get_mobile_code").text("獲取手機驗證碼");
                    clearInterval(t);
                    $("#get_mobile_code").removeClass("disabled");
                } else {
                    $("#get_mobile_code").text("請在(" + (wait_secs) + ")秒後重新獲取");
                    wait_secs--;
                }
            }, 1000);
        }
    }

    $("#get_mobile_code").click(function() {
        if($("input[name=mobile]").val() == "") {
            layer.msg("請輸入手機號碼");
            return false;
        }
        $.ajax({
            url: '<?php echo url('send_sms_code');?>',
            data: {mobile: $("input[name=mobile]").val()},
            method: "post",
            dataType: "json",
            success: function(json) {
                json = JSON.parse(json);
                layer.msg(json.msg);
                if(json.code===0) {
                    wait_secs = 120;
                    sms_btn();
                    $("#get_mobile_code").addClass("disabled");
                }
            },
            error: function() {
                layer.msg("獲取手機驗證碼失敗");
            }
        })
        return false;
    })
</script>
</body>
</html>