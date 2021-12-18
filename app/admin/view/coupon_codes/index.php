{include file="common/header" /}
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid.min.css">
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid-theme.min.css">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">折扣碼列表</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item active">折扣碼列表</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="card">
            <div class="card-body">
                <p><a href="{:admin_link('CouponCodes/add')}" class="DialogForm btn btn-primary bg-primary">新增折扣碼</a></p>
                <p class="alert alert-danger">會員下單時同時擁有多個折扣碼只能使用一個</p>
                <div class="pageHeader">
                    <form action="<?php echo url('CouponCodes/index'); ?>" method="post">
                        <table class="table table-bordered table-striped table-sm table-responsive-sm">
                            <tr>
                                <td>
                                    關鍵字：
                                    <input type="text" class="form-control" alt="折扣碼" size="30" name="keyword" value="<?php echo $keyword;?>" />
                                </td>
                                <td>
                                    <br/>
                                    <button type="submit" class="btn btn-primary">檢索</button>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
                <div id="jsGrid1" class="jsgrid" style="position: relative; height: 100%; width: 100%;">
                    <table class="table table-bordered table-striped table-sm table-responsive-sm" >
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>折扣碼</th>
                            <th>折扣碼名稱</th>
                            <th>開始日期</th>
                            <th>截止日期</th>
                            <th>可否重複訂購</th>
                            <th>訂單筆數</th>
                            <th>訂單總額</th>
                            <th>有效性</th>
                            <th>創建日期</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (!empty($list)) {
                            foreach ($list as $v) {
                                ?>
                                <tr target="ccid" rel="<?= $v["ccid"] ?>">
                                    <td><?= $v["ccid"] ?></td>
                                    <td><?= $v["code"] ?></td>
                                    <td><?= $v["title"] ?></td>
                                    <td><?= date("Y-m-d", $v["start_date"]) ?></td>
                                    <td><?= date("Y-m-d", $v["end_date"]) ?></td>
                                    <td><?= $v["is_reuse"] ? "Y" : "N" ?></td>
                                    <td><?= $v["qty"] ?></td>
                                    <td><?= $v["total_amount"] ?></td>
                                    <td><?= $v["state"] ? "Y" : "N" ?></td>
                                    <td><?= !empty($v["create_time"]) ? date("Y-m-d H:i:s", $v["create_time"]) : "" ?></td>
                                    <td>
                                        <a target="_blank" class="btn btn-success btn-xs" href="{:admin_link('Orders/index', ['keyword' => $v['code']])}">查看訂單</a>
                                        <a class="DialogForm btn btn-primary btn-xs" href="{:admin_link('CouponCodes/edit', ['ccid' => $v['ccid']])}">編輯</a>
                                        <a class="AjaxTodo btn btn-danger btn-xs" href="{:admin_link('CouponCodes/delete', ['ccid' => $v['ccid']])}">刪除</a>
                                    </td>
                                </tr>
                            <?php }
                        } ?>
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
