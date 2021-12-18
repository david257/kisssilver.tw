{include file="public/meta" /}
<script>
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if(d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'))
</script>
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
                    <li><a href="{:front_link('Index/index')}">首頁</a></li>
                    <li class="active">忘記密碼</li>
                </ol>
            </div>
        </div>
    </section>
    <section class="login">
        <div class="container">
            <div class="login-box">
                <div class="products-key"><h1>忘記密碼</h1></div>
                <div class="login-form">
                    <form class="AjaxForm form-horizontal" action="{:front_link('forgot_pass')}" method="post">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="email" name="email" class="form-control" id="inputEmail3" placeholder="請輸入帳號(Email)">
                                <p>請您輸入註冊時的E-mail帳號，我們將寄送密碼至您的信箱。</p></div>

                        </div>
                        <div class="form-group">
                            <div class="col-sm-12 no-gutters">
                                <button type="submit" class="btn btn-default btn-lg col-md-12 no-margin">送  出</button>
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
</body>
</html>