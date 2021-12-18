{include file="common/header" /}
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">新增/編輯贈品</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item"><a href="{:admin_link('Gift/index')}">贈品列表</a></li>
                        <li class="breadcrumb-item active">新增/編輯贈品</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="card">
            <form class="AjaxForm" action="{:admin_link('save')}" method="post" role="form">
                <input type="hidden" name="giftid" value="{:isset($gift['giftid'])?$gift['giftid']:0}"/>
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">贈品名稱</label>
                        <div class="col-sm-10"><input type="text" name="prodname" value="{:isset($gift['prodname'])?$gift['prodname']:''}" class="form-control" placeholder="名稱" maxlength="255"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">縮圖</label>
                        <div class="col-sm-10">
                            <?php
                            if(isset($gift['thumb_image']) && !empty($gift['thumb_image'])) {
                                $style = "display:block";
                                $src = showfile($gift['thumb_image']);
                            } else {
                                $style = "display:none";
                                $src = '';
                            }
                            ?>
                            <img src="{:$src}" style="max-height: 100px;{:$style}" />
                            <br/>
                            <input type="file" class="image_upload" />
                            <input type="hidden" class="image_path" name="thumb_image" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">庫存</label>
                        <div class="col-sm-10"><input type="number" name="stock" value="{:isset($gift['stock'])?$gift['stock']:0}" class="form-control" placeholder="庫存" min="0" max="10000000"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">啟用</label>
                        <div class="col-sm-10">
                            <div class="form-check">
                                <input class="form-check-input" name="state" <?php echo (isset($gift['state']) && $gift['state'])?'checked':'';?> value="1" type="checkbox">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">儲存</button>
                </div>
            </form>
        </div>
    </section>
</div>
{include file="common/footer" /}
