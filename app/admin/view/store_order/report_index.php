{include file="common/header_meta" /}
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid.min.css">
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid-theme.min.css">
<div class="">
    <section class="content">
        <div class="card">
            <div class="card-body">
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
