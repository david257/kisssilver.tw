{include file="common/header" /}
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid.min.css">
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid-theme.min.css">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">商品庫存列表</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item active">商品庫存列表</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="pageHeader">
            <form action="<?php echo url('index'); ?>" method="post">
                <table class="table table-bordered table-striped table-sm table-responsive-sm">
                    <tr>
                        <td>
                            關鍵字：
                            <input type="text" class="form-control" placeholder="商品名稱，編號" size="30" name="keyword" value="<?php echo $keyword;?>" />
                        </td>
                        <td>
                            啟用狀態：
                            <select name="state" class="form-control">
                                <option value="All">全部</option>
                                <?php
                                $states = [
                                    "0" => "未發佈",
                                    "1" => "發佈中"
                                ];
                                foreach($states as $k => $v) {
                                    $selected = (is_numeric($state) && $k==$state)?"selected":"";
                                    ?>
                                    <option <?php echo $selected;?> value="<?php echo $k;?>"><?php echo $v;?></option>
                                <?php } ?>
                            </select>
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
                <table class="table table-bordered table-striped table-sm table-responsive-sm">
                    <thead>
                    <tr>
                        <th>
                            主件編號
                        </th>
                        <th>
                            子件編號
                        </th>
                        <th>
                            產品名稱
                        </th>
                        <th class="text-center">
                            庫存數量
                        </th>
                        <th class="text-center">
                            庫存日期
                        </th>
                        <th style="width: 8%" class="text-center">
                            上架狀態
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
                            <a target="_blank" href="{:admin_link('Product/edit', ['prodid' => $prod['prodid']])}">{$prod['prodcode']}</a>
                        </td>
                        <td>
                            {$prod['scode']}
                        </td>
                        <td>
                            <a target="_blank" href="{:front_link('Product/detail', ['prodid' => $prod['prodid']])}">
                                {$prod['prodname']}
                            </a>
                        </td>
                        <td class="text-center">{$prod['stock']}</td>
                        <td class="text-center">
                            <div class="custom-control custom-switch">
                                {:$prod['stock_sync_date']?date('Y-m-d H:i:s', $prod['stock_sync_date']):''}
                            </div>
                        </td>
                        <td class="project-state text-center">
                            <?php if($prod['state']&&$prod['vcenabled']) { ?>
                            <span class="badge badge-success">發佈中</span>
                            <?php } else { ?>
                                <span class="badge badge-danger">未發佈</span>
                            <?php } ?>
                        </td>
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
