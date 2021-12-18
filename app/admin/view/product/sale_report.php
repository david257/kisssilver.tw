{include file="common/header" /}
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid.min.css">
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid-theme.min.css">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">商品銷量榜</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item active">商品銷量榜</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="pageHeader">
            <form action="<?php echo url('saleReport'); ?>" method="post">
                <table class="table table-bordered table-striped table-sm table-responsive-sm">
                    <tr>
                        <td>
                            關鍵字：
                            <input type="text" class="form-control" placeholder="商品名稱，編號" size="30" name="keyword" value="<?php echo $keyword;?>" />
                        </td>
                        <td>
                            訂購日期：
                            <div class="row">
                                <div class="col-4"><input type="text" class="form-control datetime" autocomplete="off" placeholder="起始日期" name="start_date" value="<?php echo $start_date;?>"></div>
                                <div class="col-4"><input type="text" class="form-control datetime" autocomplete="off" placeholder="截止日期" name="end_date" value="<?php echo $end_date;?>"></div>
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
        <!-- Default box -->
        <div class="card">

            <div class="card-body p-0">
                <div class="alert alert-info">匯總數據：<br/>
                    售出總數量: <b>{$total['total']}</b>， 售出總金額：<b>{$total['totalAmount']}</b>
                </div>
                <table class="table table-bordered table-striped table-sm table-responsive-sm">
                    <thead>
                    <tr>
                        <th style="width: 1%">
                            #
                        </th>
                        <th style="width: 20%">
                            產品名稱
                        </th>
                        <th class="text-center">
                            編號
                        </th>
                        <th class="text-center">
                            售出數量
                        </th>
                        <th class="text-center">
                            售出總額
                        </th>
                        <th class="text-center">
                            明細
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(!empty($list)) {
                        foreach($list as $prod) {
                    ?>
                    <tr>
                        <td>
                            #
                        </td>
                        <td>
                            <a>
                                {$prod['prodname']}
                            </a>
                        </td>
                        <td class="text-center">{$prod['prodcode']}</td>
                        <td class="text-center">{$prod['total']}</td>
                        <td class="text-center">{$prod['totalAmount']}</td>
                        <td class="text-center"><a class="DialogForm" href="{:admin_link('saleDetail', ['start_date' =>$start_date, 'end_date' => $end_date, 'prodid' => $prod['prodid']])}">明細</a></td>
                    </tr>
                    <?php } } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <p>
        <div class="row">
            <div class="col-sm-12"><div class="dataTables_paginate paging_simple_numbers">{:$pages}</div></div>
        </div>
        </p>
    </section>
</div>
{include file="common/footer" /}
<script>
    $('.datetime').datetimepicker({
        format:"Y-m-d",
        timepicker:false
    });
</script>
