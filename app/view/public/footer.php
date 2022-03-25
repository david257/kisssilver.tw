<footer class="page-footer center-on-small-only mt-0">
    <div class="container-fluid">
        <div class="row">
            <div class="fb-container">
                {:isset($setting['common']['fbfans'])?$setting['common']['fbfans']:''}
                 <div style="clear:both"></div>
            </div>
            <div class="">
                <?php
                $pages = get_pages();
                if(isset($pages[0]) && !empty($pages[0])) {
                    foreach($pages[0] as $page) {
                ?>
                <dl class="pull-left col-md-4">
                    <dt>{:$page['title']}</dt>
                    <?php
                    if(isset($pages[$page['pageid']]) && !empty($pages[$page['pageid']])) {
                    foreach($pages[$page['pageid']] as $subPage) {
                    ?>
                    <dd><a href="{:front_link('Page/detail', ['pageid' => $subPage['pageid']])}">{:$subPage['title']}</a></dd>
                    <?php } } ?>
                </dl>
                <?php } } ?>
                <div style="clear:both"></div>
            </div>
            <div class="" style="padding:0 30px;">
                <form id="footer_subscribe" class="AjaxForm" action="{:front_link('Subscribe/send')}" method="post">
                <dl class="pull-left">
                    <dt style="margin-bottom:5px;">Kiss-Silver最新消息</dt>
                    <dd>搶先了解激動人心的新設計、特別活動、新店開張等更多消息。 </dd>
                    <dt style="margin-bottom:5px; margin-top:10px;">客服時間</dt>
                    <dd>周一至周日:15:00~23:00</dd>
                    
                    <dd>
                        <input type="text" name="email" placeholder="電子郵件地址" class="input-f">
                    </dd>
                    <dd>
                        <button onclick="$('#footer_subscribe').submit()" type="button" class="btn btn-white">訂 閱</button>
                    </dd>
                </dl>
                </form>
                 <div style="clear:both"></div>
            </div>
        </div>
    </div>
    <div class="footer-copyright">
        <div class="container-fluid">
            <p>&copy; {:date('Y')} Kiss-Silver ALLRIGHTS RESERVED.</p>
        </div>
    </div>
</footer>
{include file="public/js" /}