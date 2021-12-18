{include file="common/header" /}
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid.min.css">
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid-theme.min.css">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">訂單清單</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item active">訂單清單</li>
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
	.order_list .green {
		font-weight: bold;
		color:blue;
	}

</style>
<div class="pageHeader">
    <form action="<?php echo admin_link('Orders/index'); ?>" method="post">
        <div class="searchBar">
            <div class="alert alert-info">由於綠界付款只能支持不重複的訂單號,故發送到綠界的訂單號會在現有訂單後加上V後再加上付款發起次數，避免重新支付產生的訂單號重複問題，在綠界後台廠商訂單編號可以直接複製到這裡檢索</div>
            <table class="table table-bordered table-striped table-sm table-responsive-sm">
                <tr>
                    <td>
                        <input type="text" placeholder="訂單號, 姓名, 電話, 折扣碼" size="50" name="keyword" value="<?php echo $keyword; ?>" />
                    </td>
                    <td>
                        訂單日期: <input type="text" size="10" class="date datetime" autocomplete="off" name="start_date" value="<?php echo $start_date; ?>" /> 至
                        <input type="text" size="10" class="date datetime" autocomplete="off" name="end_date" value="<?php echo $end_date; ?>" />
                    </td>
                    <td>
                        訂單金額: <input type="text" size="10" class="number" name="min_price" value="<?php echo $min_price; ?>" /> 至 
                        <input type="text" size="10" class="number" name="max_price" value="<?php echo $max_price; ?>" />
                    </td>
                    <td>
                        訂單狀態:
                        <select name="order_status">
                            <?php
                            $order_statuses = get_order_states();
                            foreach ($order_statuses as $k => $v) {
                                if ($k == $order_status) {
                                    $selected = 'selected';
                                } else {
                                    $selected = '';
                                }
                                ?>
                                <option <?php echo $selected; ?> value="<?php echo $k; ?>"><?php echo $v; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td><input type="submit" class="btn btn-xs btn-primary" value="檢索"/></td>
                </tr>
            </table>
        </div>
    </form>
</div>
<div class="pageContent">
    <table class="list order_list table table-bordered table-striped table-sm table-responsive-sm">
        <thead>
            <tr>
                <th>狀態</th>
                <th>庫存<br/>同步</th>
                <th>訂單號</th>
                <th>會員<br/>姓名</th>
                <th>訂單<br/>金額</th>
                <th>是否<br/>付款</th>
                <th>付款<br/>方式</th>
                <th>收貨人<br/>姓名</th>
                <th>收貨人<br/>手機</th>
                <!--<th>收貨人地址</th>-->
                <th>物流號</th>
                <th>發票號碼</th>
                <th>開票日期</th>
                <th>發票<br/>通知</th>
                <th>會員<br/>咨詢</th>
                <th>創建日期</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $expresses = express_coms();
            $paytypes = get_pay_types();
            foreach ($list as $k => $row) {
                ?>
                <tr target="oid" rel="<?php echo $row['oid']; ?>">
                    <td>
                        <?php 
                        $state = isset($order_statuses[$row['order_status']])?$order_statuses[$row['order_status']]:"";
                        echo $state;
                        ?>
                    </td>
                    <td>
                        <?php
                        if(isset($stockPushLogs[$row["oid"]])) {
                            if($stockPushLogs[$row["oid"]]["ret_code"] != "Success") {
                                $tip = $stockPushLogs[$row["oid"]]["err_msg"];
                                $bade = '<span class=\'badge bg-danger\'>'.$pushStateMsg[$stockPushLogs[$row["oid"]]["ret_code"]].'</span>';
                            } else {
                                $tip = '';
                                $bade = $pushStateMsg[$stockPushLogs[$row["oid"]]["ret_code"]];
                            }
                            echo $pushStates[$stockPushLogs[$row["oid"]]["push_state"]].":<a onclick='alert($(this).attr(\"title\"));' title='".$tip."'>".$bade."</a>";

                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $row['oid'];
                        if(!empty($row["snid"])) {
                            echo "<small>(門市售出)</small>";
                        }
                        ?>
                    </td>
                    <td><a class="DialogForm" href="{:admin_link('Customer/detail', ['customerid' => $row['customerid']])}"><?php echo $row['fullname']; ?></a></td>
                    <td><?php echo $row['total_amount']; ?></td>
                    <td><?php echo $row['pay_status']?"已付款":"N/A"; ?></td>
                    <td><?php echo isset($paytypes[$row['pay_type']])?$paytypes[$row['pay_type']]["title"]:"N/A"; ?></td>
                    <td><?php echo $row['shipping_name']; ?></td>
                    <td><?php echo $row['shipping_mobile']; ?></td>
                    <!--<td>
                        <?php if($row["LogisticsType"]=="HOME") { ?>
                        <?php echo $row['shipping_city']."-".$row['shipping_area']."-".$row['shipping_address']; ?>
                        <?php } else {?>
                        <?php echo $row['CVSAddress']."(".$row['CVSStoreName'].")";?>
                        <?php } ?>
                    </td>-->
                    <td><?php echo $row['AllPayLogisticsID']; ?></td>
                    <td><?php echo $row['InvoiceNumber']; ?></td>
                    <td><?php echo $row['InvoiceDate']; ?></td>
                    <td class="text-center"><?php echo $row['invoice_notify_times']; ?>次</td>
                    <td class="text-center">
                        <a class="DialogForm" href="{:admin_link('messages', ['oid' => $row['oid']])}"><?php echo isset($msgCount[$row['oid']])?$msgCount[$row['oid']]:0; ?></a>
                    </td>
                    <td><?php echo date('Y-m-d H:i:s', $row['create_date']); ?></td>
                    <td>
                        <a class="btn btn-xs btn-success" href="{:admin_link('admin/orders/printer', ["oid" => $row["oid"]])}" target="_blank">列印</a>
                        <?php if(!$row['snid']) { ?>
                        <div class="btn-group btn-xs">
                            <button type="button" class="btn btn-info">動作</button>
                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon btn-xs" data-toggle="dropdown" aria-expanded="false">
                                <div class="dropdown-menu"  style="">
                                    <a class="dropdown-item DialogForm" href="<?php echo admin_link('Orders/edit', ['oid' => $row["oid"]]);?>">變更狀態</a>
                                    <a class="dropdown-item AjaxTodo" href="<?php echo admin_link('Orders/delete', ['oid' => $row["oid"]]);?>">刪除</a>
                                    <?php if(!empty($row['AllPayLogisticsID'])) {?>
                                    <a class="dropdown-item DialogForm" href="<?php echo admin_link('Orders/PrintTradeDoc', ['oid' => $row["oid"]]);?>">列印貨運單</a>
                                    <a class="dropdown-item DialogForm" href="<?php echo admin_link('Orders/QueryLogisticsInfo', ['AllPayLogisticsID' => $row["AllPayLogisticsID"]]);?>">查看物流</a>
                                    <?php } else { ?>
                                        <?php if(in_array($row["pay_type"], [6,100])) { ?>
                                        <a class="dropdown-item AjaxTodo" href="<?php echo admin_link('Orders/create_express_order', ['oid' => $row["oid"]]);?>">產生物流單</a>
                                        <?php } ?>
                                    <?php } ?>
                                    <?php if(empty($row['InvoiceNumber']) && $row["pay_status"]) { ?>
                                    <a class="dropdown-item AjaxTodo" href="<?php echo admin_link('Orders/makeInvoice', ['oid' => $row["oid"]]);?>">開立發票</a>
                                    <?php } ?>
                                    <?php if(!empty($row['InvoiceNumber'])) { ?>
                                        <a class="dropdown-item AjaxTodo" href="<?php echo admin_link('Orders/notifyInvoice', ['oid' => $row["oid"]]);?>">發送發票通知</a>
                                    <?php } ?>
                                    <?php
                                    if(isset($stockPushLogs[$row["oid"]]) && $stockPushLogs[$row["oid"]]["ret_code"] != "Success") {
                                        ?>
                                        <a class="dropdown-item AjaxTodo" href="<?php echo admin_link('Orders/rePushStock', ['oid' => $row["oid"]]);?>">重新執行庫存同步</a>
                                    <?php } ?>
                                </div>
                            </button>
                        </div>
                        <?php } else { ?>
                        <?php } ?>
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
