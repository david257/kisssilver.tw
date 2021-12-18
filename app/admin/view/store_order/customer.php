{include file="common/header" /}
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid.min.css">
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid-theme.min.css">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">會員列表</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item active">會員列表</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="card">
            <div class="card-body">
                <div class="pageHeader">
                    <div class="alert alert-info">在搜索框內輸入會員帳號進行搜索，點擊會員後面的結帳按鈕進行結帳</div>
                    <form action="<?php echo admin_link('StoreOrder/customer'); ?>" method="post">
                        <table class="table table-bordered table-striped table-sm table-responsive-sm">
                            <tr>
                                <td>
                                    關鍵字：
                                    <input type="text" class="form-control" placeholder="請輸入 用戶帳號, 姓名, Email, 手機檢索會員" size="30" name="keyword" value="<?php echo $keyword;?>" />
                                </td>
                                <td>
                                    <br/>
                                    <button type="submit" class="btn btn-primary">檢索</button>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
                <div>
                <table class="table table-bordered table-striped table-sm table-responsive-sm">
                    <thead>
                    <tr>
                        <th>會員帳號</th>
                        <th>會員姓名</th>
                        <th>手機</th>
                        <th>可用紅利點數</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(!empty($list)) {
                        foreach($list as $v) {
                            ?>
                            <tr>
                                <td><?= $v["vipcode"] ?></td>
                                <td><?= $v["fullname"] ?></td>
                                <td><?= $v["mobile"] ?></td>
                                <td><?= $v["credits"] ?></td>
                                <td><a class="btn btn-success btn-xs" href="<?= admin_link('checkout', ["customerid" => $v["customerid"]]) ?>">結帳</a></td>
                            </tr>
                    <?php } } else { ?>
                        <tr>
                            <td colspan="5">未找到會員記錄</td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                </div>
    </section>
</div>
{include file="common/footer" /}
