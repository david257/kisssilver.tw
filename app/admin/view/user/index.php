{include file="common/header" /}
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid.min.css">
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid-theme.min.css">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">管理員列表</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item active">管理員列表</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="card">
            <div class="card-body">
                <p><a href="{:admin_link('add')}" class="btn btn-primary bt-xs">新增</a></p>
                <div>
                        <table class="table table-bordered table-striped table-sm table-responsive-sm">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>用戶名</th>
                                <th>姓名</th>
                                <th>角色</th>
                                <th>所屬門店</th>
                                <th>有效性</th>
                                <th>創建日期</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(!empty($list)) {
                                foreach($list as $v) {
                                    ?>
                                    <tr>
                                        <td><?= $v["userid"] ?></td>
                                        <td><?= $v["username"] ?></td>
                                        <td><?= $v["fullname"] ?></td>
                                        <td><?= isset($roles[$v["roleid"]])?$roles[$v["roleid"]]:"" ?></td>
                                        <td><?= isset($stores[$v["snid"]])?$stores[$v["snid"]]:"" ?></td>
                                        <td><?= $v["state"]?"Y":"N" ?></td>
                                        <td><?= !empty($v["create_at"])?date("Y-m-d H:i:s", $v["create_at"]):"" ?></td>
                                        <td class="jsgrid-cell">
                                            <a class="btn btn-primary bt-xs" href="{:admin_link('edit', ['userid' => $v['userid']])}">編輯</a>
                                            <a class="AjaxTodo btn btn-danger bt-xs" href="{:admin_link('delete', ['userid' => $v['userid']])}">刪除</a>
                                        </td>
                                    </tr>
                                <?php } } ?>
                            </tbody>
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
