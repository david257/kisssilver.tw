{include file="common/header_meta" /}
<div class="">
    <div class="col-12 col-sm-12 padding10">
        <div class="alert alert-info">
            <div>批次匯入會自動跳過如下情形：商品編號存在或重複，商品編號為空，商品名稱為空</div>
            <div>商品匯入的數據欄位格式為(產品編號,名稱,產品簡介,市價, 銷售價,材質,顏色,	尺寸,長寬(mm),產品分類),欄位位置不能隨意修改</div>
            <div>為了提升匯入的效率，請分批次進行匯入,比如一個EXCEL文件 500筆以下或者200筆為一次</div>
        </div>
        <form class="AjaxForm" action="{:admin_link('import')}" enctype="multipart/form-data" method="post" role="form">
        <div class="card  card-tabs">
            <input type="file" name="product"/>
        </div>
        <div class="row">
            <div class="col-md-6 text-right">
                <input type="submit" id="submitBtn" class="btn btn-primary" value="執行匯入"/>
            </div>
        </div>
        </form>
    </div>
</div>
{include file="common/footer_js" /}
