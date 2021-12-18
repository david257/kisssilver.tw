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
                    <li class="active">變更密碼</li>
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

                    <div class="user-key"><h1>變更密碼</h1></div>
                    <div class="member-form">

                        <form class="form-horizontal AjaxForm" method="post" action="{:front_link('updatePasswd')}">

                            <div class="form-group row">

                                <div class="col-sm-12 col-md-12">
                                    <input type="password"  class="form-control" id="tell" name="old_passwd" placeholder="請輸入舊密碼">
                                </div>
                            </div>
                            <div class="form-group row">

                                <div class="col-sm-12 col-md-12">
                                    <input type="password"  class="form-control" id="tell" name="new_passwd" placeholder="請輸入新密碼">
                                </div>
                            </div>
                            <div class="form-group row">

                                <div class="col-sm-12 col-md-12">
                                    <input type="password"  class="form-control" id="tell" name="new_passwd2" placeholder="請再次輸入新密碼">
                                </div>
                            </div>


                            <div class="form-group row">
                                <div class=" col-sm-12 col-md-12">
                                    <button type="submit" class="btn btn-default no-margin-l">儲存變更</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {include file="public/footer" /}
</div>
</div>
</body>
</html>