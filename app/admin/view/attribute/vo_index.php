{include file="common/header_meta" /}
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid.min.css">
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid-theme.min.css">
<div>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">商品規格</h1>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <p><a href="{:admin_link('add', ['void' => $void])}" data-width="300"  class="DialogForm btn bg-primary btn-primary btn-sm">新增商品規格</a></p>
            <div class="row">
                    <?php
                    if(!empty($list)) {
                    foreach($list as $v) {
                    ?>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">{$v['name']}</h3>
                                </div>
                                <form role="form" id="quickForm" novalidate="novalidate">
                                    <div class="card-body">
                                        <table class="table table-sm">
                                            <thead>
                                            <tr>
                                                <th>排序</th>
                                                <th>選項名稱</th>
                                                <th>顯示效果</th>
                                                <th>狀態</th>
                                                <th>操作</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            if(isset($attrvalues[$v['attrid']]) && !empty($attrvalues[$v['attrid']])) {
                                                foreach($attrvalues[$v['attrid']] as $value) {
                                            ?>
                                            <tr>
                                                <td>{$value['sortorder']}</td>
                                                <td>{$value['name']}</td>
                                                <td>
                                                    <?php
                                                    if($v["attrtype"]=="image") {
                                                        if(!empty($value['imagefile'])) {
                                                            echo '<div class="img-md"><img src="' . showfile($value['imagefile']) . '" style="max-width: 100%"/></div>';
                                                        } else {
                                                            echo $value['name'];
                                                        }
                                                    } elseif($v["attrtype"] == "color") {
                                                        echo '<div style="width: 100px; height:20px; background-color: '.$value['bgcolor'].'"></div>';
                                                    } else {
                                                        echo $value['name'];
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    {:get_states($value['state'])}
                                                </td>
                                                <td>
                                                    <a href="{:admin_link('AttributeValue/edit', ['valueid' => $value['valueid']])}" class="DialogForm">編輯</a>
                                                    <a href="{:admin_link('AttributeValue/delete', ['valueid' => $value['valueid']])}" class="AjaxTodo">刪除</a>
                                                </td>
                                            </tr>
                                            <?php } } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="card-footer text-left">
                                        <a href="{:admin_link('AttributeValue/add', ['attrid' => $v['attrid']])}" class="DialogForm btn bg-success btn-primary btn-sm">新增選項</a>
                                        <a href="{:admin_link('edit', ['attrid' => $v['attrid']])}" class="DialogForm btn bg-secondary btn-secondary btn-sm">編輯規格</a>
                                        <a href="{:admin_link('delete', ['attrid' => $v['attrid']])}" class="AjaxTodo btn bg-danger btn-danger btn-sm">刪除規格</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php } } ?>
            </div>
        </div>
    </section>
</div>
{include file="common/footer_js" /}
