{include file="common/header" /}
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid.min.css">
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid-theme.min.css">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">銷售據點</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item active">銷售據點</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="card">
            <div class="card-body">
                <p><a href="{:admin_link('StoreNetwork/add')}" class="btn btn-primary bg-primary">新增</a></p>
                <div id="jsGrid1" class="jsgrid" style="position: relative; height: 100%; width: 100%;"><div class="jsgrid-grid-header jsgrid-header-scrollbar">
                        <table class="jsgrid-table">
                            <tr class="jsgrid-header-row">
                                <th class="jsgrid-header-cell">排序</th>
                                <th class="jsgrid-header-cell">據點編號</th>
                                <th class="jsgrid-header-cell">據點名稱</th>
                                <th class="jsgrid-header-cell">電話</th>
                                <th class="jsgrid-header-cell">地址</th>
                                <th class="jsgrid-header-cell">操作</th>
                            </tr>
                            <?php if(!empty($list)) {
                                foreach($list as $v) {
                            ?>
                            <tr class="jsgrid-row">
                                <td class="jsgrid-cell">{:$v['sortorder']}</td>
                                <td class="jsgrid-cell">{:$v['bcode']}</td>
                                <td class="jsgrid-cell">{:$v['title']}</td>
                                <td class="jsgrid-cell">{:$v['tel']}</td>
                                <td class="jsgrid-cell">{:$v['address']}</td>
                                <td class="jsgrid-cell">
                                    <a href="{:admin_link('edit', ['id' => $v['id']])}">編輯</a>
                                    <a class="AjaxTodo" href="{:admin_link('delete', ['id' => $v['id']])}">刪除</a>
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
