{include file="common/header" /}
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid.min.css">
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid-theme.min.css">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">單頁列表</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item active">單頁列表</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="card">
            <div class="card-body">
                <p><a href="{:admin_link('Page/add')}" class="btn btn-primary bg-primary">新增</a></p>
                <div id="jsGrid1" class="jsgrid" style="position: relative; height: 100%; width: 100%;"><div class="jsgrid-grid-header jsgrid-header-scrollbar">
                        <table class="jsgrid-table">
                            <tr class="jsgrid-header-row">
                                <th class="jsgrid-header-cell">排序</th>
                                <th class="jsgrid-header-cell">父級</th>
                                <th class="jsgrid-header-cell">標題</th>
                                <th class="jsgrid-header-cell">同步顯示位置</th>
                                <th class="jsgrid-header-cell">啟用</th>
                                <th class="jsgrid-header-cell">操作</th>
                            </tr>
                            <?php if(!empty($list)) {
                                $pagetpyes = pagetypes();
                                foreach($list as $v) {
                            ?>
                            <tr class="jsgrid-row">
                                <td class="jsgrid-cell">{:$v['sortorder']}</td>
                                <td class="jsgrid-cell">{:isset($cates[$v['parentid']])?$cates[$v['parentid']]:'無'}</td>
                                <td class="jsgrid-cell">{:$v['title']}</td>
                                <td class="jsgrid-cell">
                                    <?php
                                    $_types = [];
                                    if(!empty($v['pagetype'])) {
                                        $types = explode(",", $v["pagetype"]);
                                        foreach($types as $kk => $vv) {
                                           $typetitle =  isset($pagetpyes[$vv])?$pagetpyes[$vv]:'';
                                           if(!empty($typetitle)) {
                                               $_types[] = $typetitle;
                                           }
                                        }
                                        echo implode(",", $_types);
                                    }
                                    ?>
                                </td>
                                <td class="jsgrid-cell">{:get_states($v['state'])}</td>
                                <td class="jsgrid-cell">
                                    <a href="{:admin_link('edit', ['pageid' => $v['pageid']])}">編輯</a>
                                    <a class="AjaxTodo" href="{:admin_link('delete', ['pageid' => $v['pageid']])}">刪除</a>
                                </td>
                            </tr>
                            <?php } } ?>
                        </table>

                </div>
        </div>
    </section>
</div>
{include file="common/footer" /}
