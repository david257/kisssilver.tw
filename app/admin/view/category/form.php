{include file="common/header" /}
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">新增/編輯分類</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item"><a href="{:admin_link('Category/index')}">分類列表</a></li>
                        <li class="breadcrumb-item active">新增/編輯分類</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="card">
            <form class="AjaxForm" action="{:admin_link('save')}" method="post" role="form">
                <input type="hidden" name="catid" value="{:isset($cate['catid'])?$cate['catid']:0}"/>
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">所屬分類</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="parentid">
                                <option value="0">--無--</option>
                                {:$selectOptions}
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">分類名稱</label>
                        <div class="col-sm-10"><input type="text" name="catname" value="{:isset($cate['catname'])?$cate['catname']:''}" class="form-control" placeholder="分類名稱" maxlength="100"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">英文分類名稱</label>
                        <div class="col-sm-10"><input type="text" name="en_catname" value="{:isset($cate['en_catname'])?$cate['en_catname']:''}" class="form-control" placeholder="英文分類名稱" maxlength="100"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">分類圖片（電腦端）</label>
                        <div class="col-sm-10">
                            <?php
                            if(isset($cate['cat_banner']) && !empty($cate['cat_banner'])) {
                                $style = "display:block";
                                $src = showfile($cate['cat_banner']);
                            } else {
                                $style = "display:none";
                                $src = '';
                            }
                            ?>
                            <img src="{:$src}" style="max-height: 100px;{:$style}" />
                            <br/>
                            <input type="file" class="image_upload" />
                            <input type="hidden" class="image_path" name="cat_banner" value="">
                            （圖片尺寸：1920*600）
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">分類圖片（手機端）</label>
                        <div class="col-sm-10">
                            <?php
                            if(isset($cate['cat_banner_xs']) && !empty($cate['cat_banner_xs'])) {
                                $style = "display:block";
                                $src = showfile($cate['cat_banner_xs']);
                            } else {
                                $style = "display:none";
                                $src = '';
                            }
                            ?>
                            <img src="{:$src}" style="max-height: 100px;{:$style}" />
                            <br/>
                            <input type="file" class="image_upload" />
                            <input type="hidden" class="image_path" name="cat_banner_xs" value="">
                            （圖片尺寸：800*465）
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">圖示名稱</label>
                        <div class="col-sm-10"><input type="text" name="icon" value="{:isset($cate['icon'])?$cate['icon']:''}" class="form-control" placeholder="圖示完整名字" maxlength="100"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">排序</label>
                        <div class="col-sm-10"><input type="number" name="sortorder" value="{:isset($cate['sortorder'])?$cate['sortorder']:0}" class="form-control" placeholder="排序" min="0" max="100000000"></div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">啟用</label>
                        <div class="col-sm-10">
                            <div class="form-check">
                                <input class="form-check-input" name="state" <?php echo (isset($cate['state']) && $cate['state'])?'checked':'';?> value="1" type="checkbox">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">SEO標題</label>
                        <div class="col-sm-10"><input type="text" name="seo_title" value="{:isset($cate['seo_title'])?$cate['seo_title']:''}" class="form-control" placeholder="SEO標題" maxlength="255"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">SEO關鍵字</label>
                        <div class="col-sm-10"><input type="text" name="seo_keywords" value="{:isset($cate['seo_keywords'])?$cate['seo_keywords']:''}" class="form-control" placeholder="SEO關鍵字" maxlength="255"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">SEO描述</label>
                        <div class="col-sm-10"><input type="text" name="seo_desc" value="{:isset($cate['seo_desc'])?$cate['seo_desc']:''}" class="form-control" placeholder="SEO描述" maxlength="255"></div>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">儲存</button>
                </div>
            </form>
        </div>
    </section>
</div>
{include file="common/footer" /}
