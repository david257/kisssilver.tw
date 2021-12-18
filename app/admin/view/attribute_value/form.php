{include file="common/header_meta" /}
<link rel="stylesheet" href="/static/admin/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">新增/編輯商品屬性/規格</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <form class="AjaxForm" action="{:admin_link('save')}" method="post">
        <div class="card">
            <input type="hidden" name="valueid" value="{:isset($value['valueid'])?$value['valueid']:0}"/>
            <input type="hidden" name="attrid" value="{$attrid}"/>
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">規格選項</label>
                    <div class="col-sm-10"><input type="text" name="name" value="{:isset($value['name'])?$value['name']:''}" class="form-control" placeholder="選項名稱" maxlength="255"></div>
                </div>
                <?php if($attrtype=="color") {?>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">顏色代碼</label>
                    <div class="col-sm-10"><input type="text" name="bgcolor" value="{:isset($value['bgcolor'])?$value['bgcolor']:''}" readonly class="form-control my-colorpicker colorpicker-element" placeholder="背景顏色" maxlength="7"></div>
                </div>
                <?php } ?>
                <?php if($attrtype=="image") {?>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">圖像</label>
                        <div class="col-sm-10">
                            <?php
                            if(isset($value['imagefile']) && !empty($value['imagefile'])) {
                                $style = "display:block";
                                $src = showfile($value['imagefile']);
                                $imagefile = $value['imagefile'];
                            } else {
                                $style = "display:none";
                                $src = '';
                                $imagefile = '';
                            }
                            ?>
                            <img src="{:$src}" style="max-height: 100px;{:$style}" />
                            <br/>
                            <input type="file" class="image_upload" />
                            <input type="hidden" class="image_path" name="imagefile" value="{:$imagefile}">
                        </div>
                    </div>
                <?php } ?>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">排序</label>
                    <div class="col-sm-10"><input type="number" name="sortorder" value="{:isset($value['sortorder'])?$value['sortorder']:0}" class="form-control" placeholder="排序" min="0" max="100000000"></div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">啟用</label>
                    <div class="col-sm-10">
                        <div class="form-check">
                            <input class="form-check-input" name="state" <?php echo (isset($value['state']) && $value['state'])?'checked':'';?> value="1" type="checkbox">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-right">
            <button type="submit" class="btn btn-primary">儲存</button>
        </div>
        </form>
    </section>

</div>
{include file="common/footer_js" /}
<script src="/static/admin/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<script>
    $('.my-colorpicker').colorpicker()
</script>
