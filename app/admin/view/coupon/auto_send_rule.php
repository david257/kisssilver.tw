{include file="common/header" /}
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">優惠券自動發放設置</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item active">優惠券自動發放設置</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="card">
            <div class="card-body">
                    <div class="card card-primary card-outline">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-7 col-sm-9">
                                    <div class="tab-content" id="vert-tabs-right-tabContent">
                                        <div class="tab-pane fade active show" id="vert-tabs-right-base" role="tabpanel" aria-labelledby="vert-tabs-right-base-tab">
                                            <div class="alert alert-info">會員註冊開通時自動發送設定的新人禮券</div>
                                            <form class="AjaxForm" action="<?= url("Coupon/save_auto_rule") ?>" method="post">
                                                <table  width="100%" layoutH="108">
                                                    <tr>
                                                        <td style="text-align: right;">
                                                            標題：
                                                        </td>
                                                        <td>
                                                            <div class="row">
                                                            <input type="text" size="50" name="title" class="form-control" value="<?php echo isset($rules["register"])?$rules["register"]["title"]:"";?>" />
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: right">
                                                            消費金額：
                                                        </td>
                                                        <td>
                                                            <div class="row">
                                                            <input type="number" size="10" name="min_amount" class="form-control" value="<?php echo isset($rules["register"])?$rules["register"]["min_amount"]:"0";?>" />
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: right">
                                                            折價金額：
                                                        </td>
                                                        <td>
                                                            <div class="row">
                                                            <input type="number" size="10" name="amount" class="form-control" value="<?php echo isset($rules["register"])?$rules["register"]["amount"]:"0";?>" />
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: right">
                                                            有效期：
                                                        </td>
                                                        <td>
                                                            <div class="row">
                                                                <input type="number" size="10" name="expired_days" class="form-control col-2" value="<?php echo isset($rules["register"])?$rules["register"]["expired_days"]:"0";?>" />
                                                                <i class="col-6">天(發放日開始計算)</i>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: right">
                                                            是否啟用：
                                                        </td>
                                                        <td>
                                                            <div class="row">
                                                            <input type="checkbox" name="state" <?php echo (isset($rules["register"]) && $rules["register"]["state"])?"checked":"";?> value="1" />
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <input type="hidden" name="auto_type" value="register" />
                                                <div class="card-footer text-right">
                                                    <button type="submit" class="btn btn-primary">儲存</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="tab-pane fade" id="vert-tabs-right-home" role="tabpanel" aria-labelledby="vert-tabs-right-home-tab">
                                            <div class="alert alert-info">自動給30天內即將過生日的會員發送生日禮券</div>
                                            <form class="AjaxForm" action="<?= url("Coupon/save_auto_rule") ?>" method="post">
                                                <table  width="100%" layoutH="108">
                                                    <tr>
                                                        <td style="text-align: right;">
                                                            標題：
                                                        </td>
                                                        <td>
                                                            <div class="row">
                                                                <input type="text" size="50" name="title" class="form-control" value="<?php echo isset($rules["birthday"])?$rules["birthday"]["title"]:"";?>" />
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: right">
                                                            消費金額：
                                                        </td>
                                                        <td>
                                                            <div class="row">
                                                                <input type="number" size="10" name="min_amount" class="form-control" value="<?php echo isset($rules["birthday"])?$rules["birthday"]["min_amount"]:"0";?>" />
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: right">
                                                            折價金額：
                                                        </td>
                                                        <td>
                                                            <div class="row">
                                                                <input type="number" size="10" name="amount" class="form-control" value="<?php echo isset($rules["birthday"])?$rules["birthday"]["amount"]:"0";?>" />
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: right">
                                                            有效期：
                                                        </td>
                                                        <td>
                                                            <div class="row">
                                                                <input type="number" size="10" name="expired_days" class="form-control col-2" value="<?php echo isset($rules["birthday"])?$rules["birthday"]["expired_days"]:"0";?>" />
                                                                <i class="col-6">天(發放日開始計算)</i>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: right">
                                                            是否啟用：
                                                        </td>
                                                        <td>
                                                            <div class="row">
                                                                <input type="checkbox" name="state" <?php echo (isset($rules["birthday"]) && $rules["birthday"]["state"])?"checked":"";?> value="1" />
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <input type="hidden" name="auto_type" value="birthday" />
                                                <div class="card-footer text-right">
                                                    <button type="submit" class="btn btn-primary">儲存</button>
                                                </div>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-5 col-sm-3">
                                    <div class="nav flex-column nav-tabs nav-tabs-right h-100" id="vert-tabs-right-tab" role="tablist" aria-orientation="vertical">
                                        <a class="nav-link active" id="vert-tabs-right-base-tab" data-toggle="pill" href="#vert-tabs-right-base" role="tab" aria-controls="vert-tabs-right-base" aria-selected="true">新人禮券</a>
                                        <a class="nav-link" id="vert-tabs-right-home-tab" data-toggle="pill" href="#vert-tabs-right-home" role="tab" aria-controls="vert-tabs-right-home" aria-selected="true">生日禮券</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
            </div>
    </section>
</div>
{include file="common/footer" /}

