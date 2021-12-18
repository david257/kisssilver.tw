{include file="common/header" /}
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid.min.css">
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid-theme.min.css">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">優惠券列表</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item active">優惠券列表</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="card">
            <div class="card-body">
                <p>
                    <a href="{:admin_link('Coupon/add')}" class="btn btn-primary">發放</a>
                    <a href="{:admin_link('Coupon/import')}" class="btn btn-secondary">匯入</a>
                </p>
                <div class="pageHeader">
                    <form action="<?php echo url('coupon/index'); ?>" method="post">
                            <table class="table table-bordered table-striped table-sm table-responsive-sm">
                                <tr>
                                    <td>
                                        關鍵字：
                                        <input type="text" class="form-control" alt="優惠券號碼, 用戶帳號" size="30" name="keyword" value="<?php echo $keyword;?>" />
                                    </td>
                                    <td>
                                        使用狀態：
                                        <select name="has_used" class="form-control">
                                            <option value="All">全部</option>
                                            <?php
                                            $has_useds = [
                                                "0" => "未使用",
                                                "1" => "已使用"
                                            ];
                                            foreach($has_useds as $k => $v) {
                                                $selected = (is_numeric($has_used) && $k==$has_used)?"selected":"";
                                                ?>
                                                <option <?php echo $selected;?> value="<?php echo $k;?>"><?php echo $v;?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td>
                                        使用日期：
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                              <span class="input-group-text">
                                                <i class="far fa-calendar-alt"></i>
                                              </span>
                                            </div>
                                            <input type="text" class="form-control float-right datetime" autocomplete="off" name="used_date" value="{$used_date}">
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
                <div style="position: relative; height: 100%; width: 100%;">
                        <table class="table table-bordered table-striped table-sm table-responsive-sm">
                            <tr>
                                <th>用戶帳號</th>
                                <th>優惠券號碼</th>
                                <th>標題</th>
                                <th>類型</th>
                                <th>有效期</th>
                                <th>滿足金額</th>
                                <th>折扣金額</th>
                                <th>是否已使用</th>
                                <th>使用日期</th>
                                <th>創建日期</th>
                                <th>操作</th>
                            </tr>
                            <?php if(!empty($list)) {
                                $coupontypes = coupon_types();
                                foreach($list as $v) {
                                    ?>
                                    <tr class="jsgrid-row">
                                        <td><?= $v["vipcode"] ?></td>
                                        <td><?= $v["code"] ?></td>
                                        <td><?= $v["title"] ?></td>
                                        <td><?= $coupontypes[$v["coupon_type"]]['title']??'' ?></td>
                                        <td><?= date("Y-m-d", $v["start_time"])."/".date("Y-m-d", $v["end_time"]) ?></td>
                                        <td><?= $v["min_amount"] ?></td>
                                        <td><?= $v["amount"] ?></td>
                                        <td><?= $v["has_used"] ? "Y" : "N" ?></td>
                                        <td><?= !empty($v["used_date"]) ? date("Y-m-d H:i:s", $v["used_date"]) : "" ?></td>
                                        <td><?= !empty($v["create_time"]) ? date("Y-m-d H:i:s", $v["create_time"]) : "" ?></td>
                                        <td class="jsgrid-cell">
                                            <a class="AjaxTodo btn btn-danger btn-sm" href="{:admin_link('delete', ['cpid' => $v['cpid']])}">刪除</a>
                                        </td>
                                    </tr>
                                <?php } } ?>
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
