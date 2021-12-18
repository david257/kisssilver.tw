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
                <div style="position: relative; height: 100%; width: 100%;">
                    <table class="table table-bordered table-striped table-sm table-responsive-sm">
                        <tr>
                            <th>會員帳號</th>
                            <th>註冊郵箱</th>
                            <th>手機</th>
                            <th>會員等級</th>
                            <th>創建日期</th>
                            <th>操作</th>
                        </tr>
                        <?php if(!empty($list)) {
                            foreach($list as $v) {
                                ?>
                                <tr>
                                    <td><?= $v["vipcode"] ?></td>
                                    <td><?= $v["custconemail"] ?></td>
                                    <td><?= $v["mobile"] ?></td>
                                    <td><?= isset($groups[$v["group_id"]])?$groups[$v["group_id"]]['title']:'' ?></td>
                                    <td><?= !empty($v["create_at"]) ? date("Y-m-d H:i:s", $v["create_at"]) : "" ?></td>
                                    <td class="jsgrid-cell">
                                        <a class="DialogForm btn btn-primary btn-sm" href="{:admin_link('Customer/detail', ['customerid' => $v['customerid']])}">明細</a>
                                    </td>
                                </tr>
                            <?php } } ?>
                    </table>
                    <p class="text-center">
                    <div class="row">
                        <div class="col-sm-12"><div class="dataTables_paginate paging_simple_numbers">{:$pages}</div></div>
                    </div>
                    </p>
                </div>
    </section>
</div>
{include file="common/footer" /}
