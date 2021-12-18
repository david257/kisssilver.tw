<?php
$sideMenus = [
    "訂單記錄" => front_link("Order/index"),
    "退貨記錄" => front_link("Order/returns"),
    "紅利點數" => front_link("Customer/credits"),
    "我的追蹤商品" => front_link("Wishlist/index"),
    "我的折價券" => front_link("Customer/coupons"),
    "個人詳細資料" => front_link("Customer/profile"),
    "變更密碼" => front_link("Customer/updatePasswd"),
];
$nowUrl = request()->url();
?>
<meta name="google-signin-client_id" content="{:isset($setting['google']['clientid'])?$setting['google']['clientid']:""}">
<nav>
 <div class="nav-collapse hide show-xs">會員中心<span class="pull-right"><i class="iconfont icon-duiqi04"></i></span></div>
    <ul>
        <?php foreach($sideMenus as $title => $url) { ?>
        <li><a href="{:$url}" class="list-group-item {:$nowUrl==$url?'active':''}">{:$title}</a></li>
        <?php } ?>
        <li><a href="{:front_link("Login/logout")}" class="list-group-item">登出</a></li>
    </ul>
</nav>