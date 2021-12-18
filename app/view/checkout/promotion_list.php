<?php
if(!empty($promotion_rules)) {
  foreach($promotion_rules as $promotion) {
?>
<ul class="row">
<li class="col-sx-5 col-md-5 text-left">{$promotion['title']}</li>
<li class="col-sx-7 col-md-7 text-right"><b class="text-danger">- {:format_price($promotion['amount'])}</b></li>
</ul>
<?php
if(!empty($promotion["gifts"])) {
  $totalgift = count($promotion["gifts"]);
  foreach ($promotion["gifts"] as $giftid => $gift) {
?>
<div class="cart-list row no-gutter">
<div class="cart-pic col-xs-2 col-md-2">
<input type="radio" class="giftid" name="giftid" <?php echo $totalgift==1?'checked':"";?> value="{$giftid}"/>
</div>
<div class="cart-pic col-xs-4 col-md-4">
    <img src="{:showfile($gift['thumb_image'])}" alt="{:$gift['prodname']}"></a>
</div>
<div class="cart-content col-xs-6 col-md-6">
    <p>{$gift['prodname']}</p>
    <div class="text-right">
        <p><b>$ 0</b></p>
    </div>
</div>
</div>
<?php } ?>
<hr>
<?php } } } ?>