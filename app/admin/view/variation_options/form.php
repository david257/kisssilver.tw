{include file="common/header_meta" /}
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">新增/編輯商品規格</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <form class="AjaxForm" action="{:admin_link('save')}" method="post">
        <div class="card">
            <input type="hidden" name="void" value="{:isset($vo['void'])?$vo['void']:0}"/>
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">商品編號</label>
                    <div class="col-sm-10"><input type="text" name="vname" value="{:isset($vo['vname'])?$vo['vname']:''}" class="form-control" placeholder="商品編號" maxlength="255"></div>
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
