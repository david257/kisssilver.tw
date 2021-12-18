{include file="common/header" /}
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid.min.css">
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid-theme.min.css">
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">商品規格</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item active">商品規格</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <p><a href="{:admin_link('add')}"  class="DialogForm btn bg-primary btn-primary btn-sm">新增商品規格</a></p>
            <form action="{:admin_link('index')}" method="post">
                <table>
                    <tr>
                        <td><input type="text" class="form-control" placeholder="產品編號" name="keywords" value="{$keywords}"></td>
                        <td><input type="submit" class="btn btn-primary" value="檢索"/> </td>
                    </tr>
                </table>
            </form>
            <table class="table table-sm">
                <thead>
                <tr>
                    <th>產品編號</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if(!empty($list)) {
                    foreach($list as $v) {
                        ?>
                        <tr>
                            <td>{$v['vname']}</td>
                            <td>
                                <a href="{:admin_link('Attribute/index', ['void' => $v['void']])}" class="DialogForm">設置規格</a>
                                <a href="{:admin_link('edit', ['void' => $v['void']])}" class="DialogForm">編輯</a>
                                <a href="{:admin_link('delete', ['void' => $v['void']])}" class="AjaxTodo">刪除</a>
                            </td>
                        </tr>
                    <?php } } ?>
                </tbody>
            </table>
        </div>
        <p>
        <div class="row">
            <div class="col-sm-12"><div class="dataTables_paginate paging_simple_numbers">{:$pages}</div></div>
        </div>
        </p>
    </section>
</div>
{include file="common/footer" /}
