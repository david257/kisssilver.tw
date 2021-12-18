{include file="common/header" /}
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid.min.css">
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid-theme.min.css">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">促銷列表</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item active">促銷列表</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="card">
            <div class="card-body">
                <p><a href="{:admin_link('Promotion/add')}" class="DialogForm btn btn-primary bg-primary">新增</a></p>
                <p class="alert alert-danger">同時設置多個促銷，如果同時滿足條件則會出現同時打折，請設置時注意，避免誤操作，同一時期請只設置一種促銷類型</p>
                <div id="jsGrid1" class="jsgrid" style="position: relative; height: 100%; width: 100%;">
                    <table class="table table-bordered table-striped table-sm table-responsive-sm" >
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>促銷名稱</th>
                            <th>開始日期</th>
                            <th>截止日期</th>
                            <th>有效性</th>
                            <th>創建日期</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (!empty($list)) {
                            foreach ($list as $v) {
                                ?>
                                <tr target="ptid" rel="<?= $v["ptid"] ?>">
                                    <td><?= $v["ptid"] ?></td>
                                    <td><?= $v["title"] ?></td>
                                    <td><?= date("Y-m-d", $v["start_date"]) ?></td>
                                    <td><?= date("Y-m-d", $v["end_date"]) ?></td>
                                    <td><?= $v["state"] ? "Y" : "N" ?></td>
                                    <td><?= !empty($v["create_time"]) ? date("Y-m-d H:i:s", $v["create_time"]) : "" ?></td>
                                    <td>
                                        <a class="DialogForm btn btn-primary btn-xs" href="{:admin_link('Promotion/edit', ['ptid' => $v['ptid']])}">編輯</a>
                                        <a class="AjaxTodo btn btn-danger btn-xs" href="{:admin_link('Promotion/delete', ['ptid' => $v['ptid']])}">刪除</a>
                                    </td>
                                </tr>
                            <?php }
                        } ?>
                        </tbody>
                    </table>
                </div>
    </section>
</div>
{include file="common/footer" /}
