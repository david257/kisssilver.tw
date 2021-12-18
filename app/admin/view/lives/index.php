{include file="common/header" /}
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid.min.css">
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid-theme.min.css">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">直播列表</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item active">直播列表</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="card">
            <div class="card-body">
                <p><a href="{:admin_link('Lives/add')}" class="btn btn-primary bg-primary">新增</a></p>
                <div id="jsGrid1" class="jsgrid" style="position: relative; height: 100%; width: 100%;">
                        <table class="table table-bordered table-striped table-sm table-responsive-sm">
                            <tr class="jsgrid-header-row">
                                <th class="jsgrid-header-cell">直播名稱</th>
                                <th class="jsgrid-header-cell">獎品數量</th>
                                <th class="jsgrid-header-cell">抽獎日期起</th>
                                <th class="jsgrid-header-cell">抽獎日期止</th>
                                <th class="jsgrid-header-cell">參與人數</th>
                                <th class="jsgrid-header-cell">中獎人數</th>
                                <th class="jsgrid-header-cell">是否已開獎</th>
                                <th class="jsgrid-header-cell">操作</th>
                            </tr>
                            <?php if(!empty($list)) {
                                foreach($list as $v) {
                            ?>
                            <tr class="jsgrid-row">
                                <td class="jsgrid-cell">{:$v['title']}</td>
                                <td class="jsgrid-cell">{:$v['jiangpin_qty']}</td>
                                <td class="jsgrid-cell">{:date("Y/m/d H:i", $v['start_date'])}</td>
                                <td class="jsgrid-cell">{:date("Y/m/d H:i", $v['end_date'])}</td>
                                <td class="jsgrid-cell"><a href="{:admin_link('getUsers', ['liveid' => $v['liveid']])}">{:isset($live_lucky[$v['liveid']])?$live_lucky[$v['liveid']]:0}</a></td>
                                <td class="jsgrid-cell"><a href="{:admin_link('getUsers', ['liveid' => $v['liveid'], 'is_luck' => 1])}">{:isset($lucy_users[$v['liveid']])?$live_lucky[$v['liveid']]:0}</a></td>
                                <td class="jsgrid-cell">{:$v['has_lottery']?'<i class="badge bg-success">已開獎</i>':'<i class="badge bg-danger">未開獎</i>'}</td>
                                <td class="jsgrid-cell">
                                    <?php if(!$v['has_lottery']) { ?>
                                    <a href="{:admin_link('edit', ['liveid' => $v['liveid']])}">編輯</a>
                                    <?php } else { ?>
                                    <a href="{:admin_link('detail', ['liveid' => $v['liveid']])}">查看</a>
                                    <?php } ?>
                                    <a class="AjaxTodo" data-tip="刪除將會同時刪除抽獎記錄,請確認是否要執行此操作" href="{:admin_link('delete', ['liveid' => $v['liveid']])}">刪除</a>
                                    <?php if(!$v['has_lottery'] && $v['end_date']<time()) { ?>
                                        <?php if(isset($live_lucky[$v['liveid']]) && $live_lucky[$v['liveid']]) { ?>
                                            <a class="AjaxTodo" data-tip="真的要開獎嗎,開獎後不能撤銷哦" href="{:admin_link('makeLuckys', ['liveid' => $v['liveid']])}">立即開獎</a>
                                        <?php } ?>
                                    <?php } ?>
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
