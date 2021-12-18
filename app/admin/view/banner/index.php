{include file="common/header" /}
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid.min.css">
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid-theme.min.css">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">廣告圖</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item active">廣告圖</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="card">

            <div class="card-body">
                <p><a href="{:admin_link('Banner/add')}" class="btn btn-primary bg-primary">新增</a></p>
                <div id="jsGrid1" class="jsgrid" style="position: relative; height: 100%; width: 100%;"><div class="jsgrid-grid-header jsgrid-header-scrollbar">
                        <table class="jsgrid-table">
                            <tr class="jsgrid-header-row">
                                <th class="jsgrid-header-cell">排序</th>
                                <th class="jsgrid-header-cell">位置</th>
                                <th class="jsgrid-header-cell">廣告小圖</th>
                                <th class="jsgrid-header-cell">廣告大圖</th>
                                <th class="jsgrid-header-cell">廣告標題</th>
                                <th class="jsgrid-header-cell">跳轉連結</th>
                                <th class="jsgrid-header-cell">啟用</th>
                                <th class="jsgrid-header-cell">操作</th>
                            </tr>
                            <?php if(!empty($list)) {
                                foreach($list as $v) {
                            ?>
                            <tr class="jsgrid-row">
                                <td class="jsgrid-cell">{:$v['sortorder']}</td>
                                <td class="jsgrid-cell">{:$locations[$v['location']]}</td>
                                <td class="jsgrid-cell">
                                    <?php if(!empty($v['min_imagefile'])) {?>
                                        <?php if(strpos($v['min_imagefile'], '.mp4') !== false) { ?>
                                            <video controls style="max-height: 100px;"><source src="{:showfile($v['min_imagefile'])}" type="video/mp4"></video>
                                        <?php } else { ?>
                                        <img src="{:showfile($v['min_imagefile'])}" class="img-md" />
                                    <?php } } ?>
                                </td>
                                <td class="jsgrid-cell">
                                    <?php if(!empty($v['imagefile'])) {?>
                                        <?php if(strpos($v['imagefile'], '.mp4') !== false) { ?>
                                            <video controls style="max-height: 100px;"><source src="{:showfile($v['imagefile'])}" type="video/mp4"></video>
                                        <?php } else { ?>
                                        <img src="{:showfile($v['imagefile'])}" class="img-md" />
                                    <?php } } ?>
                                </td>
                                <td class="jsgrid-cell">{:$v['title']}</td>
                                <td class="jsgrid-cell">{:$v['url']}</td>
                                <td class="jsgrid-cell">{:get_states($v['state'])}</td>
                                <td class="jsgrid-cell">
                                    <a href="{:admin_link('edit', ['bannerid' => $v['bannerid']])}">編輯</a>
                                    <a class="AjaxTodo" href="{:admin_link('delete', ['bannerid' => $v['bannerid']])}">刪除</a>
                                </td>
                            </tr>
                            <?php } } ?>
                        </table>
                </div>
        </div>
    </section>
</div>
{include file="common/footer" /}
