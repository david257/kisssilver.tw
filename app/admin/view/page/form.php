{include file="common/header" /}
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">新增/編輯單頁</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item"><a href="{:admin_link('Page/index')}">單頁列表</a></li>
                        <li class="breadcrumb-item active">新增/編輯單頁</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="card">
            <form class="AjaxForm" action="{:admin_link('save')}" method="post" role="form">
                <input type="hidden" name="pageid" value="{:isset($page['pageid'])?$page['pageid']:0}"/>
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">父級</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="parentid">
                                <option value="0">--無--</option>
                                <?php
                                if(!empty($cates)) {
                                    foreach($cates as $pageid => $title) {
                                        if(isset($page['parentid']) && $page['parentid']==$pageid) {
                                            $selected = 'selected';
                                        } else {
                                            $selected = '';
                                        }
                                ?>
                                <option {$selected} value="{$pageid}">{$title}</option>
                                <?php } } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">標題</label>
                        <div class="col-sm-10"><input type="text" name="title" value="{:isset($page['title'])?$page['title']:''}" class="form-control" placeholder="標題" maxlength="255"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">內容</label>
                        <div class="col-sm-10"><textarea id="editor" name="content" placeholder="內容" rows="30">{:isset($page['content'])?$page['content']:''}</textarea></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">同步顯示到</label>
                        <div class="col-sm-10">
                            <?php
                            $pagetypes = pagetypes();
                            foreach($pagetypes as $k => $v) {
                                if(isset($page['pagetype']) && stripos(','.$page['pagetype'].',', ','.$k.',') !== false) {
                                    $checked = 'checked';
                                } else {
                                    $checked = '';
                                }
                            ?>
                                <div><input type="checkbox" {$checked} name="pagetype[]" value="{$k}" /> {$v}</div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">排序</label>
                        <div class="col-sm-10"><input type="number" name="sortorder" value="{:isset($page['sortorder'])?$page['sortorder']:0}" class="form-control" placeholder="排序" min="0" max="100000000"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">啟用</label>
                        <div class="col-sm-10">
                            <div class="form-check">
                                <input class="form-check-input" name="state" <?php echo (isset($page['state']) && $page['state'])?'checked':'';?> value="1" type="checkbox">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">SEO標題</label>
                        <div class="col-sm-10"><input type="text" name="seo_title" value="{:isset($page['seo_title'])?$page['seo_title']:''}" class="form-control" placeholder="SEO標題" maxlength="255"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">SEO關鍵字</label>
                        <div class="col-sm-10"><input type="text" name="seo_keywords" value="{:isset($page['seo_keywords'])?$page['seo_keywords']:''}" class="form-control" placeholder="SEO關鍵字" maxlength="255"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">SEO描述</label>
                        <div class="col-sm-10"><input type="text" name="seo_desc" value="{:isset($page['seo_desc'])?$page['seo_desc']:''}" class="form-control" placeholder="SEO描述" maxlength="255"></div>
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
