{include file="common/header" /}
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">新增/編輯據點</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item"><a href="{:admin_link('News/index')}">銷售據點</a></li>
                        <li class="breadcrumb-item active">新增/編輯據點</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
    <form class="AjaxForm" action="{:admin_link('save')}" method="post" role="form">
    <section class="content">
        <div class="card">

                <input type="hidden" name="id" value="{:isset($store['id'])?$store['id']:0}"/>
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">據點編號</label>
                        <div class="col-sm-10"><input type="text" name="bcode" value="{:isset($store['bcode'])?$store['bcode']:''}" class="form-control" placeholder="據點編號" maxlength="255">（與ERP一致）</div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">據點名稱</label>
                        <div class="col-sm-10"><input type="text" name="title" value="{:isset($store['title'])?$store['title']:''}" class="form-control" placeholder="據點名稱" maxlength="255"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">聯絡電話</label>
                        <div class="col-sm-10"><input type="text" name="tel" value="{:isset($store['tel'])?$store['tel']:''}" class="form-control" placeholder="聯絡電話" maxlength="255"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">據點地址</label>
                        <div class="col-sm-10"><input type="text" name="address" value="{:isset($store['address'])?$store['address']:''}" class="form-control" placeholder="據點地址" maxlength="255"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">地圖代碼</label>
                        <div class="col-sm-10"><textarea name="map_code" placeholder="地圖代碼" rows="5" class="form-control">{:isset($store['map_code'])?$store['map_code']:''}</textarea></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">據點介紹</label>
                        <div class="col-sm-10"><textarea id="editor" name="content" placeholder="據點介紹" rows="10" class="form-control">{:isset($store['content'])?$store['content']:''}</textarea></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">排序</label>
                        <div class="col-sm-10"><input type="number" name="sortorder" value="{:isset($store['sortorder'])?$store['sortorder']:0}" class="form-control" placeholder="排序" min="0" max="100000000"></div>
                    </div>
                </div>
        </div>
    </section>
        <section class="content">
            <div class="row">
                <div class="col-md-6">
                    <a class="btn btn-secondary text-left" href="{:admin_link('StoreNetwork/index')}">取消</a>
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
