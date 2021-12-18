{include file="common/header" /}
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid.min.css">
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid-theme.min.css">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">郵件範本</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item active">郵件範本</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-striped table-sm table-responsive-sm">
                    <thead>
                    <tr>
                        <th>模板標題</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $email_temps = order_state_actions();
                    if (!empty($email_temps)) {
                        foreach ($email_temps as $k => $v) {
                            ?>
                            <tr>
                                <td><?= $v["title"] ?></td>
                                <td><a href="{:admin_link('edit', ['tpid' => $k])}" class="btn btn-primary btn-xs">編輯</a></td>
                            </tr>
                        <?php }
                    } ?>
                    </tbody>
                </table>
    </section>
</div>
{include file="common/footer" /}
