<h1>產品庫存變動通知</h1>
<table class="table" cellspacing="0" width="100%">
	<tr>
		<th>產品編號</th>
		<th>產品名稱</th>
        <th>歷史庫存</th>
        <th>現有庫存</th>
	</tr>
	<?php
	if(!empty($products)) {
		foreach($products as $sku => $v) {
			if(isset($v['name'])) {
	?>
	<tr>
		<td>{$sku}</td>
		<td>{$v['name']}</td>
        <td style="text-align: center">{$v['sync_before_qty']}</td>
        <td style="text-align: center">{$v['sync_after_qty']}</td>
	</tr>
	<?php } } } ?>
</table>