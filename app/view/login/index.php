{include file="public/meta" /}
<style>
	.abcRioButtonLightBlue {
    background-color: #d32f2f !important;
    color: #fff !important;
	 font-size: 18px !important;
    padding: 15px 2.4rem !important;
	border-radius:0;
	height:auto !important;
	width:100% !important;
}
.abcRioButtonLightBlue:after{content:"使用google帳號登入"; display:block; clear:both; width:100% !important; text-align:center;}
.abcRioButtonContents{font-size: 18px !important;}
.abcRioButtonContents{display:none}
.abcRioButtonIcon{ display:none}

.abcRioButton {
    -webkit-border-radius: 1px;
    border-radius: 1px;
    -webkit-box-shadow 0 2px 4px 0px rgba(0,0,0,.25): ;
    box-shadow: 0 2px 4px 0 rgba(0,0,0,.25);
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    -webkit-transition: background-color .218s,border-color .218s,box-shadow .218s;
    transition: background-color .218s,border-color .218s,box-shadow .218s;
    -webkit-user-select: none;
    -webkit-appearance: none;
    background-color: #fff;
    background-image: none;
    color: #262626;
    cursor: pointer;
    outline: none;
    overflow: hidden;
    position: relative;
    text-align: center;
    vertical-align: middle;
    white-space: nowrap;
    width: auto;
}
</style>
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
                    <li class="active">會員登入</li>
                </ol>
            </div>



        </div>
    </section>
    <section class="login">
        <div class="container-fluid">
            <div class="login-box">
                <div class="products-key"><h1>會員登入</h1></div>
                <div class="login-form">
                    <form action="{:front_link('index')}" method="post" class="AjaxForm form-horizontal">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="email" name="custconemail" class="form-control" id="inputEmail3" placeholder="請輸入帳號(Email)">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="password" name="custpassword" class="form-control" id="inputPassword3" placeholder="請輸入密碼">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class=" col-sm-12 text-right">
                                <a href="{:front_link('Register/index')}" title="註冊帳號" class="pull-left">註冊帳號</a>
                                <a href="{:front_link('Login/forgot_pass')}" title="忘記密碼"  class="pull-right">忘記密碼?</a>
                                <div class="clearfix"></div>
                            </div>

                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-default btn-lg col-xs-12 col-md-12  no-margin">登  入</button>
                            </div>
                        </div>
                        <hr />
                        <!--<div class="form-group">
                            <div class="col-sm-12">
                                <a href="{:url('Oauth/login',['type'=>'facebook'])}" class="btn btn-blue btn-lg col-xs-12 col-md-12 no-margin"><i class="fa fa-facebook-official"></i> 使用facebook帳號登入</a>
                            </div>
                        </div>-->
                        <div class="form-group">
                            <div class="col-sm-12">
                                <a href="{:url('Oauth/login',['type'=>'google'])}" class="btn abcRioButtonLightBlue"></a>
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