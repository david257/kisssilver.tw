{include file="common/header" /}
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid.min.css">
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid-theme.min.css">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">許可權節點表</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item active">許可權節點表</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="card">
            <div class="card-body">
                <p><a href="{:admin_link('Node/add')}" class="DialogForm btn btn-primary bg-primary">新增</a></p>
                <div id="jsGrid1" class="jsgrid" style="position: relative; height: 100%; width: 100%;">
                        <table class="table table-bordered table-striped table-sm table-responsive-sm">
                            <tr class="jsgrid-header-row">
                                <th class="jsgrid-header-cell">節點名稱</th>
                                <th class="jsgrid-header-cell">URL</th>
                                <th class="jsgrid-header-cell">啟用</th>
                                <th class="jsgrid-header-cell">操作</th>
                            </tr>
                            <?php if(!empty($nodes)) {
                                foreach($nodes as $k => $v) {
                                    ?>
                                    <tr class="jsgrid-row">
                                        <td class="jsgrid-cell">{:str_repeat('--', $v['level']).$v['name']}</td>
                                        <td class="jsgrid-cell"><?php echo $v['url'];?></td>
                                        <td class="jsgrid-cell">{:get_states($v['status'])}</td>
                                        <td class="jsgrid-cell">
                                            <a class="DialogForm" href="{:admin_link('edit', ['nodeid' => $v['id']])}">編輯</a>
                                            <a class="AjaxTodo" href="{:admin_link('delete', ['nodeid' => $v['id']])}">刪除</a>
                                        </td>
                                    </tr>
                                <?php } } ?>
                        </table>
                </div>
    </section>
</div>
{include file="common/footer" /}
