<div class="kefu">
<a href="{:front_link('Cart/index')}" class="wap-cart-k">
<em>{:cart_qtys()}</em>
        <svg class="icon" aria-hidden="true">
            <use xlink:href="#icon-ziyuan"></use>
        </svg>
    </a> 
    <a target="_blank" href="https://www.facebook.com/KissSilver">
        <svg class="icon" aria-hidden="true">
            <use xlink:href="#icon-facebook"></use>
        </svg>
    </a>
    <div class="line-b">
    <a href="#">
        <svg class="icon" aria-hidden="true">
            <use xlink:href="#icon-line1"></use>
        </svg>
    </a> 
    <div class="line-s">
    	<img src="/static/front/images/ericon.png" width="100px" />
    </div>
    </div>
    
    <a href="https://www.instagram.com/kisssilver_qr/" >
        <svg class="icon" aria-hidden="true">
            <use xlink:href="#icon-ins"></use>
        </svg>
    </a>
    <a href="javascript:void(0)" onclick="window.scrollTo({ top: 0, behavior: 'smooth' })" id="toTop">
        <i class="fa-solid fa-arrow-up"></i>
    </a>
</div>
<script>
window.onscroll = function () {
    if (pageYOffset >= 200) {
        document.getElementById('toTop').style.visibility = "visible";
    } else {
 document.getElementById('toTop').style.visibility = "hidden";
    }
};
</script>
