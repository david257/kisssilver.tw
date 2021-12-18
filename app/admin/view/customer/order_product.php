{include file="common/header_meta" /}
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid.min.css">
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid-theme.min.css">
<div class="">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">會員消費統計</h1>
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
                            <th>姓名</th>
                            <th>會員等級</th>
                            <th>訂購次數</th>
                            <th>訂購總額</th>
                            <th>操作</th>
                        </tr>
                        <?php if(!empty($list)) {
                            foreach($list as $v) {
                                ?>
                                <tr>
                                    <td><?= $v["vipcode"] ?></td>
                                    <td><?= $v["custconemail"] ?></td>
                                    <td><?= $v["fullname"] ?></td>
                                    <td><?= isset($groups[$v["group_id"]])?$groups[$v["group_id"]]['title']:'' ?></td>
                                    <td><?= $v["total"] ?></td>
                                    <td><?= $v["totalAmount"] ?></td>
                                    <td class="jsgrid-cell">
                                        <a class="DialogForm btn btn-secondary btn-sm" href="{:admin_link('Orders/index', ['dialog' => 1, 'customerid' => $v['customerid'], 'start_date' => $start_date, 'end_date' => $end_date])}">明細</a>
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
