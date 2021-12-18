{include file="common/header" /}
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">新增/編輯新聞</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item"><a href="{:admin_link('News/index')}">新聞列表</a></li>
                        <li class="breadcrumb-item active">新增/編輯新聞</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="card">
            <form class="AjaxForm" action="{:admin_link('save')}" method="post" role="form">
                <input type="hidden" name="newsid" value="{:isset($news['newsid'])?$news['newsid']:0}"/>
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">標題</label>
                        <div class="col-sm-10"><input type="text" name="title" value="{:isset($news['title'])?$news['title']:''}" class="form-control" placeholder="標題" maxlength="255"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">縮圖</label>
                        <div class="col-sm-10">
                            <?php
                            if(isset($news['thumb_image']) && !empty($news['thumb_image'])) {
                                $style = "display:block";
                                $src = showfile($news['thumb_image']);
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
                        <label class="col-sm-2 col-form-label">URL</label>
                        <div class="col-sm-10"><input type="text" name="url" value="{:isset($news['url'])?$news['url']:''}" class="form-control" placeholder="請填寫全路徑,填寫後點擊直接跳到此URL" maxlength="255"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">內容</label>
                        <div class="col-sm-10"><textarea id="editor" name="content" placeholder="內容" rows="30">{:isset($news['content'])?$news['content']:''}</textarea></div>
                    </div>
                    <!--<div class="form-group row">
                        <label class="col-sm-2 col-form-label">排序</label>
                        <div class="col-sm-10"><input type="number" name="sortorder" value="{:isset($news['sortorder'])?$news['sortorder']:0}" class="form-control" placeholder="排序" min="0" max="100000000"></div>
                    </div>-->
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">啟用</label>
                        <div class="col-sm-10">
                            <div class="form-check">
                                <input class="form-check-input" name="state" <?php echo (isset($news['state']) && $news['state'])?'checked':'';?> value="1" type="checkbox">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">SEO標題</label>
                        <div class="col-sm-10"><input type="text" name="seo_title" value="{:isset($news['seo_title'])?$news['seo_title']:''}" class="form-control" placeholder="SEO標題" maxlength="255"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">SEO關鍵字</label>
                        <div class="col-sm-10"><input type="text" name="seo_keywords" value="{:isset($news['seo_keywords'])?$news['seo_keywords']:''}" class="form-control" placeholder="SEO關鍵字" maxlength="255"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">SEO描述</label>
                        <div class="col-sm-10"><input type="text" name="seo_desc" value="{:isset($news['seo_desc'])?$news['seo_desc']:''}" class="form-control" placeholder="SEO描述" maxlength="255"></div>
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
