{include file="common/header" /}
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid.min.css">
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid-theme.min.css">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">變更會員密碼</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item"><a href="{:admin_link('Customer/index')}">會員列表</a></li>
                        <li class="breadcrumb-item active">變更會員密碼</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <form enctype="multipart/form-data" class="AjaxForm" action="<?= admin_link("changePasswd") ?>" method="post">
            <input type="hidden" name="customerid" value="<?= $customerid ?>" />
            <section class="content">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-sm table-responsive-sm">
                            <tr>
                                <td class="text-right" style="width: 120px;">
                                    新密碼：
                                </td>
                                <td>
                                    <input type="password" size="10" name="passwd" class="form-control" value="" />
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right" style="width: 120px;">
                                    確認密碼：
                                </td>
                                <td>
                                    <input type="password" size="10" name="passwd2" class="form-control" value="" />
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-md-6">
                        <a class="btn btn-secondary text-left" href="{:admin_link('Customer/index')}">取消</a>
                    </div>
                    <div class="col-md-6 text-right">
                        <input type="submit" id="submitBtn" class="btn btn-primary" value="儲存">
                    </div>
                </div>
            </section>
        </form>
    </section>
</div>
{include file="common/footer" /}
