{include file="common/header" /}
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid.min.css">
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid-theme.min.css">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">優惠券發放</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item"><a href="{:admin_link('Coupon/index')}">優惠券列表</a></li>
                        <li class="breadcrumb-item active">優惠券發放</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <form enctype="multipart/form-data" class="AjaxForm" action="<?= admin_link("Coupon/save") ?>" method="post">
        <section class="content">
            <div class="card">
                <div class="card-body">

                        <table class="table table-bordered table-striped table-sm table-responsive-sm">
                            <tr>
                                <td style=" text-align: right; width: 120px" >
                                    標題：
                                </td>
                                <td>
                                    <input type="text" name="title" class="form-control" maxlength="100">
                                </td>
                            </tr>
                            <tr>
                                <td style=" text-align: right; width: 100px" >
                                    優惠券類型：
                                </td>
                                <td>
                                    <select name="coupon_type" class="form-control">
                                        <?php
                                          $types =  coupon_types();
                                          unset($types['birth']);
                                          unset($types['reg']);
                                          if(!empty($types)) {
                                              foreach($types as $k => $v) {
                                        ?>
                                        <option value="<?php echo $k;?>"><?php echo $v['title'];?></option>
                                        <?php
                                              }
                                          }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right">
                                    訂購金額：
                                </td>
                                <td>
                                    <input type="text" size="10" name="min_amount" class="form-control digits" value="0" />
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right">
                                    折價金額：
                                </td>
                                <td>
                                    <input type="text" size="10" name="amount" class="form-control digits" value="0" />
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right" valign="top">
                                    有效期：
                                </td>
                                <td>
                                    <input type="text" size="10" name="start_date" class="form-control date datetime" autocomplete="off" value="" />
                                    至
                                    <input type="text" size="10" name="end_date" class="form-control date datetime" autocomplete="off" value="" />
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right;">發放人群：</td>
                                <td>
                                    <input type="radio" name="send_type" onclick="$('#user_ids').show();" value="by_user_ids"/> 特定用戶
                                    <input type="radio" name="send_type" onclick="$('#user_ids').hide();" value="send_to_all"/> 全部用戶
                                </td>
                            </tr>
                            <tr id="user_ids" style="display: none;">
                                <td style="text-align: right;">會員ID：</td>
                                <td>
                                    <div style="height: 300px; overflow-y: scroll;background: #fff;">
                                        <?php
                                        if(!empty($customers)) {
                                            foreach($customers as $customer) {
                                        ?>
                                        <p><input type="checkbox" name="customer_ids[]" value="<?php echo $customer["customerid"];?>" /><?php echo empty($customer["fullname"])?"":$customer["fullname"]."-";?><?php echo $customer["vipcode"];?></p>
                                        <?php } } ?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-6">
                    <a class="btn btn-secondary text-left" href="{:admin_link('Coupon/index')}">取消</a>
                </div>
                <div class="col-md-6 text-right">
                    <input type="submit" id="submitBtn" class="btn btn-primary" value="儲存">
                </div>
            </div>
        </section>
    </form>
</section>
</div>
{include file="common/footer" /}
<script>
    $('.datetime').datetimepicker({
        format:"Y-m-d",
        timepicker:false
    });
</script>
