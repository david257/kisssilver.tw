{include file="common/header" /}
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid.min.css">
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid-theme.min.css">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">門市結帳報表</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item active">門市結帳報表</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="card">
            <div class="card-body">
                <div class="pageHeader">
                    <div class="alert alert-info">產生了結帳記錄的門市才會被統計到這裡</div>
                    <form action="<?php echo admin_link('StoreOrder/report'); ?>" method="post">
                        <table class="table table-bordered table-striped table-sm table-responsive-sm">
                            <tr>
                                <td>
                                    關鍵字：
                                    <input type="text" class="form-control" placeholder="門市帳號名稱，用戶名" size="30" name="keyword" value="<?php echo $keyword;?>" />
                                </td>
                                <td>
                                    結帳日期起：
                                    <div class="input-group">
                                        <input type="text" class="form-control datetime float-right" autocomplete="off" name="start_date" value="{$start_date}">
                                    </div>
                                </td>
                                <td>
                                    結帳日期止：
                                    <div class="input-group">
                                        <input type="text" class="form-control datetime float-right" autocomplete="off" name="end_date" value="{$end_date}">
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
                <div>
                        <table class="table table-bordered table-striped table-sm table-responsive-sm">
                            <thead>
                            <tr>
                                <th>門市帳號</th>
                                <th>結帳筆數</th>
                                <th>訂購總額</th>
                                <th>折價券總額</th>
                                <th>贈送總點數</th>
                                <th>實際支付總額</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(!empty($list)) {
                                foreach($list as $v) {
                                    ?>
                                    <tr>
                                        <td><?= $v["fullname"] ?></td>
                                        <td><?= $v["total"] ?></td>
                                        <td><?= $v["totalAmount"] ?></td>
                                        <td><?= $v["totalCouponAmount"] ?></td>
                                        <td><?= $v["totalCredits"] ?></td>
                                        <td><?= $v["totalPaidAmount"] ?></td>
                                        <td><a class="DialogForm" data-width="1000px" href="{:admin_link('index', ['userid' => $v['userid'], 'start_date' => $start_date, 'end_date' => $end_date])}">明細</a></td>
                                    </tr>
                                <?php } } ?>
                            </tbody>
                        </table>
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
