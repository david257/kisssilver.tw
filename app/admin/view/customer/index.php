{include file="common/header" /}
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid.min.css">
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid-theme.min.css">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">會員列表</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item active">會員列表</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="card">
            <div class="card-body">
                <div class="pageHeader">
                    <form action="<?php echo url('Customer/index'); ?>" method="post">
                        <table class="table table-bordered table-striped table-sm table-responsive-sm">
                            <tr>
                                <td>
                                    關鍵字：
                                    <input type="text" class="form-control" placeholder="用戶帳號,Email,手機" size="30" name="keyword" value="<?php echo $keyword;?>" />
                                </td>
                                <td>
                                    會員等級：
                                    <select name="group_id" class="form-control">
                                        <option value="0">全部</option>
                                        <?php
                                        $groups = getCustomerGroups();
                                        if(!empty($groups)) {
                                        foreach($groups as $k => $grp) {
                                            if($group_id == $grp["group_id"]) {
                                                $selected = "selected";
                                            } else {
                                                $selected = "";
                                            }
                                            ?>
                                            <option <?php echo $selected;?> value="<?php echo $grp['group_id'];?>"><?php echo $grp["title"];?></option>
                                        <?php } } ?>
                                    </select>
                                </td>
                                <td>
                                    註冊日期：
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
                <div style="position: relative; height: 100%; width: 100%;">
                    <table class="table table-bordered table-striped table-sm table-responsive-sm">
                        <tr>
                            <th>會員帳號</th>
                            <th>客戶姓名</th>
                            <th>註冊郵箱</th>
                            <th>手機</th>
                            <th>開通狀態</th>
                            <th>會員等級</th>
                            <th>剩餘紅利點數</th>
                            <th>創建日期</th>
                            <th>操作</th>
                        </tr>
                        <?php if(!empty($list)) {
                            foreach($list as $v) {
                                ?>
                                <tr>
                                    <td><?= $v["vipcode"] ?></td>
                                    <td><?= $v["fullname"] ?></td>
                                    <td><?= $v["custconemail"] ?></td>
                                    <td><?= $v["mobile"] ?></td>
                                    <td><?= $v["state"]?'已開通':'未開通' ?></td>
                                    <td><?= isset($groups[$v["group_id"]])?$groups[$v["group_id"]]['title']:'' ?></td>
                                    <td><?= $v["credits"] ?></td>
                                    <td><?= !empty($v["create_at"]) ? date("Y-m-d H:i:s", $v["create_at"]) : "" ?></td>
                                    <td class="jsgrid-cell">
                                        <a class="DialogForm btn btn-secondary btn-sm" href="{:admin_link('Customer/detail', ['customerid' => $v['customerid']])}">明細</a>
                                        <a class="btn btn-primary btn-sm" href="{:admin_link('changePasswd', ['customerid' => $v['customerid']])}">變更密碼</a>
                                        <a class="btn btn-success btn-sm" href="{:admin_link('chargeCredits', ['customerid' => $v['customerid']])}">儲值紅利點數</a>
                                        <a class="DialogForm btn btn-success btn-sm" href="{:admin_link('clogs', ['customerid' => $v['customerid']])}">查看紅利點數歷史</a>
                                        <a class="AjaxTodo btn btn-danger btn-sm" href="{:admin_link('delete', ['customerid' => $v['customerid']])}">刪除</a>
                                    </td>
                                </tr>
                            <?php } } ?>
                    </table>
                    <p class="text-center">
                    <div class="row">
                        <div class="col-sm-4">會員總數: {$totals} </div> <div class="col-sm-8"><div class="dataTables_paginate paging_simple_numbers">{:$pages}</div></div>
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
