<h1>產品補貨通知</h1>
<table class="table" cellspacing="0" width="100%">
	<tr>
		<th>產品編號</th>
		<th>產品名稱</th>
        <th>安全庫存</th>
        <th>現有庫存</th>
		<th>規格</th>
	</tr>
	<?php
	if(!empty($nooption_list)) {
		foreach($nooption_list as $v) {
	?>
	<tr>
		<td>{$v['prodcode']}</td>
		<td>{$v['prodname']}</td>
        <td style="text-align: center">{$v['stock_warning']}</td>
        <td style="text-align: center">{$v['stock']}</td>
		<td style="text-align: center">無</td>
	</tr>
	<?php } } ?>
	<?php
	if(!empty($product_options)) {
		foreach($product_options as $prodid => $options) {
	?>
	<tr>
		<td>{$products[$prodid]['prodcode']}</td>
		<td>{$products[$prodid]['prodname']}</td>
        <td style="text-align: center">見規格欄</td>
        <td style="text-align: center">見規格欄</td>
		<td style="text-align: center">
            <table class="table" width="100%" cellspacing="0">
                <tr><th>子件編號</th><th>規格名稱</th><th>安全庫存</th><th>現有庫存</th></tr>
                <?php
                $voptions = [];
                if(!empty($options)) {
                    foreach($options as $k => $op) {
                ?>
                 <tr>
                     <td>{:$op['vcsku']}</td>
                     <td><?php echo implode(",", $op["voptions"]);?></td>
                     <td style="text-align: center">{:$op['vstock_warning']}</td>
                     <td style="text-align: center">{:$op['vcstock']}</td>
                 </tr>
                <?php
                    }
                }
                ?>
            </table>

		</td>
	</tr>
	<?php } } else { ?>
        <tr><td colspan="5" align="center">暫無補貨商品</td></tr>
    <?php } ?>
</table>