{include file="common/header" /}
<link rel="stylesheet" href="/static/admin/plugins/toastr/toastr.min.css">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">會員等級</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item active">會員等級</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="card">
            <div class="card-body">
                <div style="position: relative; height: 100%; width: 100%;">
                    <table id="groups" class="table table-bordered table-striped table-sm table-responsive-sm">
                        <tr>
                            <th class="text-center">等級名稱</th>
                            <th>折扣</th>
                            <th>年度訂購總額</th>
                        </tr>
                        <?php if(!empty($list)) {
                            foreach($list as $v) {
                                ?>
                                <tr>
                                    <td class="text-center"><?= $v["title"] ?></td>
                                    <td><input type="text" class="form-control" data-name="discount" data-groupid="{:$v['group_id']}" value="<?= $v["discount"] ?>"/></td>
                                    <td><input type="text" class="form-control" data-name="order_amount" data-groupid="{:$v['group_id']}" value="<?= $v["order_amount"] ?>"/></td>
                                </tr>
                            <?php } } ?>
                    </table>
                </div>
    </section>
</div>
{include file="common/footer" /}
<script>
    $("#groups input").blur(function() {
        var obj = $(this);
        $.ajax({
            url: "{:admin_link('Customer/updateGroup')}",
            type: "post",
            data: {field:obj.attr("data-name"), group_id: obj.attr("data-groupid"), val: obj.val()},
            dataType: "json",
            success: function(json) {
                layer.msg(json.msg);
            }
        })
    })
</script>
