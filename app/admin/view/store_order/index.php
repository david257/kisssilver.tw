{include file="common/header" /}
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid.min.css">
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid-theme.min.css">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">門市消費記錄</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item active">門市消費記錄</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="card">
            <div class="card-body">
                <div class="pageHeader">
                    <form action="<?php echo admin_link('StoreOrder/index'); ?>" method="post">
                        <table class="table table-bordered table-striped table-sm table-responsive-sm">
                            <tr>
                                <td>
                                    關鍵字：
                                    <input type="text" class="form-control" placeholder="優惠券號碼, 用戶帳號, 姓名" size="30" name="keyword" value="<?php echo $keyword;?>" />
                                </td>
                                <td>
                                    結帳日期起：
                                    <div class="input-group">
                                        <input type="text" class="form-control datetime float-right" autocomplete="off" name="start_date" value="{$start_date}">
                                    </div>
                                </td>
                                <td>
                                    結帳日期止：
                                    <div class="input-group">
                                        <input type="text" class="form-control datetime float-right" autocomplete="off" name="end_date" value="{$end_date}">
                                    </div>
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
                                <th>結帳日期</th>
                                <th>會員帳號</th>
                                <th>會員姓名</th>
                                <th>消費金額</th>
                                <th>優惠券號</th>
                                <th>優惠金額</th>
                                <th>實際支付金額</th>
                                <th>贈送紅利點數</th>
                                <th>門市帳號</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(!empty($list)) {
                                foreach($list as $v) {
                                    ?>
                                    <tr>
                                        <td><?= !empty($v["create_at"])?date("Y-m-d H:i:s", $v["create_at"]):"" ?></td>
                                        <td><?= $v["vipcode"] ?></td>
                                        <td><?= $v["fullname"] ?></td>
                                        <td><?= $v["total_amount"] ?></td>
                                        <td><?= $v["coupon_code"] ?></td>
                                        <td><?= $v["coupon_amount"] ?></td>
                                        <td><?= $v["paid_amount"] ?></td>
                                        <td><?= $v["credits"] ?></td>
                                        <td><?= $v["user_fullname"] ?></td>
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
<script>
    $('.datetime').datetimepicker({
        format:"Y-m-d",
        timepicker:false
    });
</script>
