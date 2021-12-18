{include file="common/header" /}
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid.min.css">
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid-theme.min.css">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">退貨清單</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item active">退貨清單</li>
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
    <form action="<?php echo admin_link('Orders/returns'); ?>" method="post">
        <div class="searchBar">
            <table class="table table-bordered table-striped table-sm table-responsive-sm">
                <tr>
                    <td>
                        <input type="text" placeholder="訂單號" size="50" name="keyword" value="<?php echo $keyword; ?>" />
                    </td>
                    <td>
                        訂單狀態:
                        <select name="state">
                            <?php
                            $statuses = get_return_states();
                            foreach ($statuses as $k => $v) {
                                if ($k == $state) {
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
                <th>訂單編號</th>
                <th>購買日期</th>
                <th>退貨狀態</th>
                <th>退回金額</th>
                <th>退貨日期</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php
        if(!empty($list)) {
        foreach($list as $v) {
            ?>
                <tr>
                    <td>
                        {:$v['oid']}
                    </td>
                    <td>{:substr($v['oid'],0,8)}</td>
                    <td>
                        <?php
                        $returnstates = get_return_states();
                        echo isset($returnstates[$v['state']])?$returnstates[$v['state']]:'N/A';
                        ?>
                    </td>
                    <td>
                        $ {$v['total_amount']}
                    </td>
                    <td>{:date('Ymd', $v['create_date'])}</td>
                    <td>
                        <a href="{:front_link('return_detail', ['returnno' => $v['returnno']])}">查看明細</a>
                        <?php if(empty($v['RtnCode']) && $v["state"] == "confirm") {?>
                            <a class="AjaxTodo btn btn-xs btn-success" href="{:front_link('create_return_express_order', ['returnid' => $v['returnid']])}">產生逆物流單</a>
                        <?php } ?>
                        <div class="btn-group btn-xs">
                            <button type="button" class="btn btn-info">動作</button>
                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                <div class="dropdown-menu"  style="">
                                    <?php
                                    if(!empty($returnstates)) {
                                        foreach($returnstates as $state => $statetitle) {
                                    ?>
                                    <a class="dropdown-item AjaxTodo" href="<?php echo admin_link('changeReturnState', ['returnid' => $v["returnid"], 'state' => $state]);?>">{$statetitle}</a>
                                    <?php } } ?>
                                </div>
                            </button>
                        </div>
                    </td>
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
