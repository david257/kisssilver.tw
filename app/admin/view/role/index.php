{include file="common/header" /}
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid.min.css">
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid-theme.min.css">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">許可權節點表</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item active">許可權節點表</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="card">
            <div class="card-body">
                <p><a href="{:admin_link('Role/add')}" class="DialogForm btn btn-primary bg-primary">新增</a></p>
                <div id="jsGrid1" class="jsgrid" style="position: relative; height: 100%; width: 100%;"><div class="jsgrid-grid-header jsgrid-header-scrollbar">
                        <table class="jsgrid-table">
                            <tr class="jsgrid-header-row">
                                <th>ID</th>
                                <th>角色名稱</th>
                                <th>有效性</th>
                                <th>創建日期</th>
                            </tr>
                            <?php
                            if(!empty($list)) {
                                foreach($list as $v) {
                                    ?>
                                    <tr class="jsgrid-row">
                                        <td class="jsgrid-cell"><?= $v["roleid"] ?></td>
                                        <td class="jsgrid-cell"><?= $v["title"] ?></td>
                                        <td class="jsgrid-cell"><?= $v["status"]?"Y":"N" ?></td>
                                        <td class="jsgrid-cell"><?= !empty($v["create_time"])?date("Y-m-d H:i:s", $v["create_time"]):"" ?></td>
                                        <td class="jsgrid-cell">
                                            <a class="DialogForm" href="{:admin_link('edit', ['roleid' => $v['roleid']])}">編輯</a>
                                            <a class="AjaxTodo" href="{:admin_link('delete', ['roleid' => $v['roleid']])}">刪除</a>
                                        </td>
                                    </tr>
                            <?php } } ?>
                        </table>
                    </div>
                    <p class="text-center">
                    <div class="row">
                        <div class="col-sm-12"><div class="dataTables_paginate paging_simple_numbers">{:$pages}</div></div>
                    </div>
                    </p>
                </div>
    </section>
</div>
{include file="common/footer" /}
