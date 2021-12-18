{include file="common/header_meta" /}
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid.min.css">
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid-theme.min.css">
<div class="content-wrapper">
    <section class="content">
        <h4>儲值記錄</h4>
        <table class="table table-bordered table-striped table-sm table-responsive-sm">
            <tr>
                <th>變動點數</th>
                <th>儲值日期</th>
                <th>備註</th>
                <th>操作人</th>
            </tr>
            <?php
            if(!empty($creditLogs)) {
            foreach($creditLogs as $v) {
            ?>
            <tr>
                <td>{$v['change_amount']}</td>
                <td>{:date('Y-m-d H:i:s', $v['create_at'])}</td>
                <td>{$v['msg']}</td>
                <td>{$v['fullname']}</td>
            </tr>
            <?php } } ?>
        </table>
        <p class="text-center">
        <div class="row">
            <div class="col-sm-12"><div class="dataTables_paginate paging_simple_numbers">{:$pages}</div></div>
        </div>
        </p>
    </section>

</div>
{include file="common/footer" /}
