{include file="common/header" /}
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">新增/編輯直播</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item"><a href="{:admin_link('Lives/index')}">直播列表</a></li>
                        <li class="breadcrumb-item active">新增/編輯直播</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <form action="{:admin_link('save')}" method="post" class="AjaxForm">
        <section class="content">
            <div class="card">
                    <input type="hidden" name="liveid" value="{:isset($live['liveid'])?$live['liveid']:0}"/>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">直播標題</label>
                            <div class="col-sm-10"><input type="text" name="title" value="{:isset($live['title'])?$live['title']:''}" class="form-control" placeholder="直播標題" maxlength="255"></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">直播室代碼</label>
                            <div class="col-sm-10">
                                <textarea name="live_code" rows="5" class="form-control">{:isset($live['live_code'])?$live['live_code']:''}</textarea>
                                視頻高度設為235最佳
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">推薦產品</label>
                            <div class="col-sm-10">
                                請前往產品列表設置
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">內容</label>
                            <div class="col-sm-10"><textarea id="editor" name="content" placeholder="內容" rows="30">{:isset($live['content'])?$live['content']:''}</textarea></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">獎品數量</label>
                            <div class="col-sm-10"><input type="number" name="jiangpin_qty" value="{:isset($live['jiangpin_qty'])?$live['jiangpin_qty']:0}" class="form-control" placeholder="獎品數量" min="0" max="100000000"></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">抽獎開始日期</label>
                            <div class="col-sm-10"><input type="text" name="start_date" autocomplete="off" value="{:isset($live['start_date'])?date('Y/m/d H:i', $live['start_date']):''}" class="form-control datetime" placeholder="抽獎開始日期" maxlength="255"></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">抽獎截止日期</label>
                            <div class="col-sm-10"><input type="text" name="end_date" autocomplete="off" value="{:isset($live['end_date'])?date('Y/m/d H:i', $live['end_date']):''}" class="form-control datetime" placeholder="抽獎截止日期" maxlength="255"></div>
                        </div>
                    </div>


            </div>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-6">
                    <a class="btn btn-secondary text-left" href="{:admin_link('Lives/index')}">返回</a>
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
<script>
    $('.datetime').datetimepicker();
</script>
