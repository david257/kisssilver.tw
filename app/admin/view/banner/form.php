{include file="common/header" /}
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">新增/編輯廣告圖</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item"><a href="{:admin_link('Banner/index')}">廣告圖列表</a></li>
                        <li class="breadcrumb-item active">新增/編輯廣告圖</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="card">
            <form class="AjaxForm" action="{:admin_link('save')}" method="post" role="form">
                <input type="hidden" name="bannerid" value="{:isset($banner['bannerid'])?$banner['bannerid']:0}"/>
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">標題</label>
                        <div class="col-sm-10"><input type="text" name="title" value="{:isset($banner['title'])?$banner['title']:''}" class="form-control" placeholder="廣告標題" maxlength="255"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">廣告圖小圖/影片</label>
                        <div class="col-sm-10">
                            <?php
                            if(isset($banner['min_imagefile']) && !empty($banner['min_imagefile'])) {
                                $style = "display:block";
                                $src = showfile($banner['min_imagefile']);
                                $min_imagefile = $banner['min_imagefile'];
                            } else {
                                $style = "display:none";
                                $src = '';
                                $min_imagefile = '';
                            }
                            ?>
                            <?php
                            if(strpos($src, ".mp4") !== false) {
                            ?>
                                <video controls><source src="{$src}" type="video/mp4"></video>
                            <?php } else { ?>
                            <img src="{:$src}" style="max-height: 100px;{:$style}" />
                            <?php } ?>
                            <br/>
                            <input type="file" class="image_upload" />
                            <input type="hidden" class="image_path" name="min_imagefile" value="{:$min_imagefile}">
                            （圖片尺寸：800*465）
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">廣告圖大圖/影片</label>
                        <div class="col-sm-10">
                            <?php
                            if(isset($banner['imagefile']) && !empty($banner['imagefile'])) {
                                $style = "display:block";
                                $src = showfile($banner['imagefile']);
                                $imagefile = $banner['imagefile'];
                            } else {
                                $style = "display:none";
                                $src = '';
                                $imagefile = '';
                            }
                            ?>
                            <?php
                            if(strpos($src, ".mp4") !== false) {
                                ?>
                                <video controls><source src="{$src}" type="video/mp4"></video>
                            <?php } else { ?>
                                <video controls style="display: none;"></video>
                                <img src="{:$src}" style="max-height: 100px;{:$style}" />
                            <?php } ?>
                            <br/>
                            <input type="file" class="image_upload" />
                            <input type="hidden" class="image_path" name="imagefile" value="{:$imagefile}">
                            （圖片尺寸：1920*895）
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">內容</label>
                        <div class="col-sm-10"><textarea name="content" class="form-control" placeholder="內容不能超過1000字元" maxlength="1000">{:isset($banner['content'])?$banner['content']:''}</textarea></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">顯示頁面</label>
                        <div class="col-sm-10">
                            <select name="page" class="form-control">
                                <?php
                                if(!empty($pages)) {
                                    foreach($pages as $page => $title) {
                                        if(isset($banner['page']) && $banner['page'] == $page) {
                                            $selected = 'selected';
                                        } else {
                                            $selected = '';
                                        }
                                ?>
                                <option {$selected} value="{$page}">{$title}</option>
                                <?php } } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">頁面位置</label>
                        <div class="col-sm-10">
                            <select name="location" class="form-control">
                                <?php
                                if(!empty($locations)) {
                                    foreach($locations as $location => $title) {
                                        if(isset($banner['location']) && $banner['location'] == $location) {
                                            $selected = 'selected';
                                        } else {
                                            $selected = '';
                                        }
                                        ?>
                                        <option {$selected} value="{$location}">{$title}</option>
                                    <?php } } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">跳轉連結</label>
                        <div class="col-sm-10"><input type="text" name="url" value="{:isset($banner['url'])?$banner['url']:''}" class="form-control" placeholder="請輸入完整url, http(s)://www.exambple.com/" maxlength="500"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">排序</label>
                        <div class="col-sm-10"><input type="number" name="sortorder" value="{:isset($banner['sortorder'])?$banner['sortorder']:0}" class="form-control" placeholder="排序" min="0" max="100000000"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">啟用</label>
                        <div class="col-sm-10">
                            <div class="form-check">
                                <input class="form-check-input" name="state" <?php echo (isset($banner['state']) && $banner['state'])?'checked':'';?> value="1" type="checkbox">
                            </div>
                        </div>
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
