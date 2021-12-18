<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>登錄</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/static/admin/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="/static/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="/static/admin/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg"><img src="/static/admin/images/logo.png" style="max-width: 100%"/> </p>
            <form id="loginform" action="" method="post" class="AjaxForm">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="用戶名" name="username" maxlength="50">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="密碼" name="passwd" maxlength="50">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!--<div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember">
                            <label for="remember">
                                記住我
                            </label>
                        </div>
                    </div>-->
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">登入</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="/static/admin/plugins/jquery/jquery.min.js"></script>
<script src="/static/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/static/admin/dist/js/adminlte.min.js"></script>
{include file="common/js" /}
</body>
</html>
