{include file="common/header_meta" /}
<div class="content-wrapper">
    <section class="content">
    <table class="list order_list table table-bordered table-striped table-sm table-responsive-sm">
        <thead>
            <tr>
                <th>加入日期</th>
                <th>諮詢/回覆</th>
                <th>圖片</th>
                <th>處理人員</th>
                <th>諮詢/回覆內容</th>
                <th>回覆日期</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php if(!empty($messages)) {
        foreach($messages as $msg) {
            ?>
                <tr>
                    <td>
                        {:date('Y-m-d H:i:s', $msg['creat_at'])}
                    </td>
                    <td>{$msg['question']}</td>
                    <td>
                        <?php
                        if(!empty($msg['images'])) {
                            $images = explode(",", $msg["images"]);
                            foreach($images as $k => $img) {
                                ?>
                                <a target="_blank" href="{:showfile($img)}"><img src="{:showfile($img)}" alt="" width="50"></a>
                            <?php } } ?>
                    </td>
                    <td>{$msg['fullname']}</td>
                    <td>{$msg['answer']}</td>
                    <td>{:$msg['answer_date']>0?date('Y-m-d H:i:s', $msg['answer_date']):''}</td>
                    <td>
                        <a class="btn btn-xs btn-primary" href="{:admin_link('reply', ["id" => $msg["id"]])}">回覆</a>
                    </td>
                </tr>
                <?php } } ?>
        </tbody>
    </table>
    </section>
</div>
{include file="common/footer_js" /}
