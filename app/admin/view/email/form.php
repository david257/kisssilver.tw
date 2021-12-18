{include file="common/header" /}
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">新增/編輯郵件範本</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item"><a href="{:admin_link('Email/index')}">郵件範本</a></li>
                        <li class="breadcrumb-item active">新增/編輯郵件範本</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <form class="AjaxForm" action="{:admin_link('save')}" method="post" role="form">
        <section class="content">
            <div class="card">
            <input type="hidden" name="tpid" value="<?= isset($tpid) ? $tpid : 0 ?>" />
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">郵件主題：</label>
                    <div class="col-sm-10"><input type="text" name="subject" value="<?= isset($title) ? $title : "" ?>" maxlength="255"></div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">郵件正文：</label>
                    <div class="col-sm-10"><textarea id="editor" name="body" placeholder="正文" style="height: 300px;"><?= isset($content) ? str_replace('{domain}', $domain, $content) : "" ?></textarea></div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-6">
                    <a class="btn btn-secondary text-left" href="{:admin_link('index')}">取消</a>
                </div>
                <div class="col-md-6 text-right">
                    <input type="submit" id="submitBtn" class="btn btn-primary" value="儲存"/>
                </div>
            </div>
        </section>
        </form>
    </section>
</div>
{include file="common/footer" /}
