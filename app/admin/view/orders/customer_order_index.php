{include file="common/header_meta" /}
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid.min.css">
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid-theme.min.css">
<div class="">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">訂單清單</h1>
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
<div class="pageContent">
    <table class="list order_list table table-bordered table-striped table-sm table-responsive-sm">
        <thead>
            <tr>
                <th>訂單號</th>
                <th>會員姓名</th>
                <th>訂單金額</th>
                <th>是否付款</th>
                <th>收貨人姓名</th>
                <th>收貨人手機</th>
                <th>創建日期</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $expresses = express_coms();
            foreach ($list as $k => $row) {
                ?>
                <tr target="oid" rel="<?php echo $row['oid']; ?>">
                    <td><?php echo $row['oid']; ?></td>
                    <td><?php echo $row['fullname']; ?></td>
                    <td><?php echo $row['total_amount']; ?></td>
                    <td><?php echo $row['pay_status']?"已付款":"N/A"; ?></td>
                    <td><?php echo $row['shipping_name']; ?></td>
                    <td><?php echo $row['shipping_mobile']; ?></td>
                    <td><?php echo date('Y-m-d H:i:s', $row['create_date']); ?></td>
                    <td>
                        <a class="DialogForm" href="<?php echo admin_link('Orders/edit', ['oid' => $row["oid"]]);?>">訂購明細</a>
                    </td>
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
