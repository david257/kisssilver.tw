{include file="common/header_meta" /}
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
            <input type="hidden" name="attrid" value="{:isset($attr['attrid'])?$attr['attrid']:0}"/>
            <input type="hidden" name="void" value="{$void}"/>
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">商品屬性/規格名稱</label>
                    <div class="col-sm-10"><input type="text" name="name" value="{:isset($attr['name'])?$attr['name']:''}" class="form-control" placeholder="商品屬性/規格名稱" maxlength="255"></div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">屬性/規格類型</label>
                    <div class="col-sm-10">
                        <select name="attrtype" class="form-control">
                            <?php
                            if(!empty($attrtypes)) {
                                foreach($attrtypes as $atype => $atypename) {
                                if(isset($attr['attrtype']) && $atype == $attr['attrtype']) {
                                    $selected = 'selected';
                                } else {
                                    $selected = '';
                                }
                            ?>
                             <option {$selected} value="{$atype}">{$atypename}</option>
                            <?php } } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">排序</label>
                    <div class="col-sm-10"><input type="number" name="sortorder" value="{:isset($attr['sortorder'])?$attr['sortorder']:0}" class="form-control" placeholder="排序" min="0" max="100000000"></div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">啟用</label>
                    <div class="col-sm-10">
                        <div class="form-check">
                            <input class="form-check-input" name="state" <?php echo (isset($attr['state']) && $attr['state'])?'checked':'';?> value="1" type="checkbox">
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
