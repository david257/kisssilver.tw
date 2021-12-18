{include file="common/header" /}
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid.min.css">
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid-theme.min.css">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">商品列表</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item active">商品列表</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <p>
            <a href="{:admin_link('add')}" class="btn btn-primary">新增</a>
            <a href="{:admin_link('import')}" class="DialogForm btn btn-success">匯入</a>
            <a href="{:admin_link('export')}" target="_blank" class="btn btn-warning">匯出品項建檔</a>
        </p>
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
                            標籤：
                            <select name="fieldname" class="form-control">
                                <option value="0">全部</option>
                                <?php
                                $tags = [
                                    "is_hot" => "熱銷",
                                    "is_sale" => "促銷",
                                    "is_live" => "直播推薦"
                                ];
                                foreach($tags as $k => $v) {
                                    $selected = $k==$fieldname?"selected":"";
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
                            縮圖
                        </th>
                        <th class="text-center">
                            熱銷
                        </th>
                        <th class="text-center">
                            促銷
                        </th>
                        <th class="text-center">影片</th>
                        <th class="text-center">
                            直播推薦
                        </th>
                        <th class="text-center">
                            庫存日期
                        </th>
                        <th style="width: 8%" class="text-center">
                            狀態
                        </th>
                        <th style="width: 20%">
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
                            <br>
                            <small>
                                發佈於 {:date('Y-m-d', $prod['create_at'])}
                            </small>
                        </td>
                        <td class="text-center">{$prod['prodcode']}</td>
                        <td class="text-center">
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <?php if(isset($images[$prod['prodid']]) && !empty($images[$prod['prodid']])) { ?>
                                    <img alt="Avatar" style="max-width: 30px" class="table-avatar" src="{:showfile($images[$prod['prodid']])}">
                                    <?php } ?>
                                </li>
                            </ul>
                        </td>
                        <td class="text-center">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" {:$prod['is_hot']?'checked':''} class="custom-control-input featured_switch" id="ishot{:$prod['prodid']}" data-prodid="{:$prod['prodid']}" data-field="is_hot" value="1">
                                <label class="custom-control-label" for="ishot{:$prod['prodid']}"></label>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" {:$prod['is_sale']?'checked':''} class="custom-control-input featured_switch" id="issaler{:$prod['prodid']}" data-prodid="{:$prod['prodid']}" data-field="is_sale" value="1">
                                <label class="custom-control-label" for="issaler{:$prod['prodid']}"></label>
                            </div>
                        </td>
                        <td class="text-center">
                            <?php echo isset($product_videos[$prod['prodid']])?'已傳':'';?>
                        </td>
                        <td class="text-center">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" {:$prod['is_live']?'checked':''} class="custom-control-input featured_switch" id="islive{:$prod['prodid']}" data-prodid="{:$prod['prodid']}" data-field="is_live" value="1">
                                <label class="custom-control-label" for="islive{:$prod['prodid']}"></label>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="custom-control custom-switch">
                                {:$prod['stock_sync_date']?date('Y-m-d H:i:s', $prod['stock_sync_date']):''}
                            </div>
                        </td>
                        <td class="project-state text-center">
                            <?php if($prod['state']) { ?>
                            <span class="badge badge-success">發佈中</span>
                            <?php } else { ?>
                                <span class="badge badge-danger">未發佈</span>
                            <?php } ?>
                        </td>
                        <td class="project-actions text-right">
                            <a class="btn btn-primary btn-sm" target="_blank" href="{:front_link('Product/detail', ['token' => $token, 'prodid' => $prod['prodid']])}">
                                <i class="fas fa-folder">
                                </i>
                                預覽
                            </a>
                            <a class="btn btn-info btn-sm" href="{:admin_link('edit', ['prodid' => $prod['prodid']])}">
                                <i class="fas fa-pencil-alt">
                                </i>
                                編輯
                            </a>
                            <a class="AjaxTodo btn btn-danger btn-sm" href="{:admin_link('delete', ['prodid' => $prod['prodid']])}">
                                <i class="fas fa-trash">
                                </i>
                                刪除
                            </a>
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
<script>
    $(".featured_switch").change(function() {
        var data_state;
        if($(this).is(":checked")) {
            data_state = 1;
        } else {
            data_state = 0;
        }
        var data = {prodid:$(this).attr('data-prodid'),data_name:$(this).attr("data-field"), data_state:data_state}
        $.getJSON('{:admin_link("update_state")}', data, function(json) {
            if(json.code) {
                layer.msg(json.msg);
                return false;
            }
        })
    })
</script>
