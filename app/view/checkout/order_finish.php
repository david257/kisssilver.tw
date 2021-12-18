{include file="public/meta" /}
<link rel="stylesheet" type="text/css" href="/static/front/css/slider-pro.min.css" media="screen"/>
<link rel="stylesheet" type="text/css" href="/static/front/css/examples.css" media="screen"/>
<link rel="stylesheet" type="text/css" href="/static/front/css/list.css">
<link rel="stylesheet" href="/static/front/css/viewer.min.css">
<link rel="stylesheet" type="text/css" href="/static/front/css/user.css">

{include file="public/kefu" /}
<div class="index-box">
    {include file="public/header" /}
    <section class="member">
        <div class="container">
            <h2 class="text-center">訂單付款通知</h2>
            <hr>
            <table class="table table-responsive-sm table-hover table-striped table-bordered">
                <tr><td>訂單編號：</td><td>{$order['oid']}</td></tr>
                <tr><td>訂單金額：</td><td>{:format_price($order['total_amount'])}</td></tr>
                <tr><td>付款方式：</td><td>{:isset($pay_types[$order['pay_type']])?$pay_types[$order['pay_type']]['title']:'N/A'}</td></tr>
                <tr><td>付款狀態：</td><td>{:$order['pay_status']?"已付款":"N/A"}</td></tr>
                <tr><td>訂單明細：</td><td><a href="{:front_link('Order/detail', ['oid' => $order['oid']])}">查看>></a></td></tr>
            </table>
        </div>
    </section>
</body>
</html>