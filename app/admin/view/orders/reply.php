{include file="common/header_meta" /}
<div class="content-wrapper">
    <section class="content">
    <form action="{:admin_link('reply')}" method="post" class="AjaxForm">
        <section class="content">
        <input type="hidden" name="id" value="{$msg['id']}" />
        <table class="list order_list table table-bordered table-striped table-sm table-responsive-sm">
            <tr>
                <td>回覆內容:</td>
                <td>
                    <textarea name="answer" class="form-control" rows="5">{$msg['answer']}</textarea>
                </td>
            </tr>
        </table>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-6">
                    <a class="btn btn-secondary text-left" href="{:admin_link('messages', ['oid' => $msg['oid']])}">取消</a>
                </div>
                <div class="col-md-6 text-right">
                    <input type="submit" id="submitBtn" class="btn btn-primary" value="儲存">
                </div>
            </div>
        </section>
    </form>
    </section>
</div>
{include file="common/footer_js" /}
