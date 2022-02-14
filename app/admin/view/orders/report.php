{include file="common/header" /}
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid.min.css">
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid-theme.min.css">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">訂單統計</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item active">訂單統計</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="card">
            <div class="card-body">
<style>
    table.list .product_details {
        display: block;
        width: 345px;
        margin:0;
    }
    table.list .product_details.title  li {
        color: #0000FF;
        border-top: 1px solid #ddd;
    }
    table.list .product_details li {
        border-left: 0;
        padding: 5px;
        border-bottom: 1px solid #ddd;
        background: lemonchiffon;
        height: 21px;
        overflow: hidden; 
        float:left;
    }
    table.list .product_details li.sku {
        width: 60px;
        border-left: 1px solid #ddd;
        border-right: 1px solid #ddd;
    }
    table.list .product_details li.name {
        width: 129px;
        border-right: 1px solid #ddd;
    }
    table.list .product_details li.qty {
        width: 39px;
        border-right: 1px solid #ddd;
    }
    table.list .product_details li.price {
        width: 70px;
        border-right: 1px solid #ddd;
    }
    table.list .product_details .title td {
        background: #f0c040;
    }

</style>
<div class="pageHeader">
    <form action="<?php echo admin_link('Orders/report'); ?>" method="post">
        <div class="searchBar">
            <table class="table table-bordered table-striped table-sm table-responsive-sm">
                <tr>
                    <td>
                        訂單日期: <input type="text" size="10" class="date datetime" autocomplete="off" name="start_date" value="<?php echo $start_date; ?>" /> 至
                        <input type="text" size="10" class="date datetime" autocomplete="off" name="end_date" value="<?php echo $end_date; ?>" />
                    </td>
                    <td><input type="submit" class="btn btn-xs btn-primary" value="檢索"/></td>
                </tr>
            </table>
        </div>
    </form>
</div>
<div class="pageContent">
    <div class="alert alert-info">匯總數據：<br/>
        訂單總金額：<b>{$total['totalAmount']}</b>, 訂購總筆數: <b>{$total['totalOrderNums']}</b>， 商品售出總件數: <b>{$totalQty['totalQty']}</b>， 訂購會員總人數：<b>{$total['totalCustomers']}</b>
    </div>
    <table class="list order_list table table-bordered table-striped table-sm table-responsive-sm">
        <thead>
            <tr>
                <th>商品編號</th>
                <th>商品名稱</th>
                <th>訂購總額</th>
                <th>訂購筆數</th>
                <th>商品售出數</th>
                <th>訂購會員數</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $expresses = express_coms();
            foreach ($list as $k => $row) {
                ?>
                <tr>
                    <td><?php echo $row['prodcode']; ?></td>
                    <td><?php echo $row['prodname']; ?></td>
                    <td><?php echo $row['totalAmount']; ?></td>
                    <td><a class="DialogForm" href="{:admin_link('Orders/index', ['dialog' => 1, 'start_date' => $start_date, 'end_date' => $end_date])}"><?php echo $row['totalOrderNums']; ?></a></td>
                    <td><a class="DialogForm" href="{:admin_link('Product/saleDetail', ['prodid' => $row['prodid'], 'start_date' => $start_date, 'end_date' => $end_date])}"><?php echo $row['totalQty']; ?></a></td>
                    <td><a class="DialogForm" href="{:admin_link('Customer/orders', ['prodid' => $row['prodid'], 'start_date' => $start_date, 'end_date' => $end_date])}"><?php echo $row['totalCustomers']; ?></a></td>
                </tr>
            <?php } ?>            
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
