{include file="public/meta" /}
<link rel="stylesheet" type="text/css" href="/static/front/dist/assets/owl.theme.default.css">
<link rel="stylesheet" type="text/css" href="/static/front/css/list.css">
<link rel="stylesheet" type="text/css" href="/static/front/css/user.css">
<link href="/static/front/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
{include file="public/kefu" /}
<div class="index-box">
    {include file="public/header" /}
    <section class="sub">
        <div class="container">
            <div class="sub-loation">
                <ol class="breadcrumb">
                    <li><a href="{:front_link('Index/index')}">首頁</a></li>
                    <li><a href="{:front_link('StoreNetwork/index')}">銷售據點</a></li>
                    <li class="active">{:$store['title']}</li>
                </ol>
            </div>



        </div>
    </section>
    <section class="help">

        <div class="help-content">
            <div class="container">
                <h1>{:$store['title']}</h1>
                <div class="bt-gray">電話：{:$store['tel']} {:$store['address']}</div>
                <hr />
                {:$store['map_code']}
                {:$store['content']}

            </div>
        </div>

    </section>
    {include file="public/footer" /}
</div>
</div>
</body>
</html>