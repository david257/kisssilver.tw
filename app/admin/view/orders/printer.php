{include file="common/header_meta" /}
<style>
    .title_name {
        text-align: right;
    }
    .title td {
        background: #C1C1C1;
        text-align: left;
    }
    .table {
        border: 1px solid #000;
        margin: 0 auto;
        width: 100%;
    }
    .table td {
        border-right: 1px solid #000;
        border-bottom: 1px solid #000;
        padding: 10px;
        font-weight: bold;
    }
</style>
<div style="float:left;"><img src="/static/front/images/logo.png" height="80" /></div>
<?php if(!$order["snid"]) { ?>
<div style="float:right">
<div>{$senderName}</div>
<div>宅配專線: {$senderPhone}</div>
<div>地 址：{$senderAddress}</div>
</div>
<div style="clear:both;"></div>
<h2>寄貨資料</h2>
<table class="table" border="0" cellspacing="0">
    <tr>
        <td class="title_name">姓名:</td><td><?php echo $order['shipping_name']; ?></td>
    </tr>
    <tr>
        <td class="title_name">行動電話:</td><td><?php echo $order['shipping_mobile']; ?></td>
    </tr>
    <tr>
        <td class="title_name">配送方式:</td>
        <td>
            <?php
            $express_types = express_types();
            $LogisticsHomeSubTypes = LogisticsHomeSubTypes();
            $LogisticsCVSSubTypes = LogisticsCVSSubTypes();
            echo $express_types[$order['LogisticsType']]??'';
            echo " - ";
            if($order['LogisticsType']=="HOME") {
                echo $LogisticsHomeSubTypes[$order['LogisticsSubType']]??'';
            } else {
                echo $LogisticsCVSSubTypes[$order['LogisticsSubType']]??'';
            }
            ?>
        </td>
    </tr>
    <tr>
        <td class="title_name">付款方式:</td>
        <td>
            <?php
            $pay_types = get_pay_types();
            echo $pay_types[$order['pay_type']]["title"]??'';
            ?>
        </td>
    </tr>

    <?php
    if($order["LogisticsType"]=="HOME") {
    ?>
    <tr>
        <td class="title_name">收貨地址:</td><td><?php echo $order['shipping_city']; ?>-<?php echo $order['shipping_area']; ?>-<?php echo $order['shipping_address']; ?></td>
    </tr>
    <tr>
        <td class="title_name">物流編號:</td><td><?php echo $order['AllPayLogisticsID']; ?></td>
    </tr>
    <?php } elseif($order['LogisticsType']=="SE") {?>
        <tr>
        <td class="title_name">門市取貨地址:</td><td><?php echo $se_address; ?></td>
    </tr>
    <?php } else { ?>
    <tr>
        <td class="title_name">收貨地址:</td><td><?php echo $order['CVSAddress']; ?>(<?php echo $order['CVSStoreName']; ?>)</td>
    </tr>
    <tr>
        <td class="title_name">物流編號:</td><td><?php echo $order['AllPayLogisticsID']; ?></td>
    </tr>
    <?php } ?>

	<tr>
        <td class="title_name">訂單備註:</td><td><?php echo $order['ordnote']; ?></td>
    </tr>
</table>
<hr style="border: 1px dashed #ddd; margin: 20px 0 " />
<h2>訂單明細 #<?php echo $order['oid']; ?> <i style="float: right;font-style: normal; font-size: 0.95em;">訂購日期：<?php echo date("Y/m/d", $order['create_date']); ?></i></h2>
<?php } ?>
<table class="table" cellspacing="0" border="0">
        <tr class="title">
            <td>編號</td>
            <td>商品名稱</td>
            <td>訂購數量</td>
            <td>售價</td>
            <td>總金額</td>
        </tr>
        <?php
        $qty = $total_amount = $total_points = 0;
        if (!empty($items)) {
            foreach ($items as $k => $row) {
                $qty += $row["qty"];
                $total_amount += $row["total_amount"];
                ?>
                <tr>
                    <td><?php echo $row['sku']; ?></td>
                    <td>
                        <?php echo $row['prodname']; ?>
                        <?php 
                        if(!empty($row["options"])) {
                            $json_options = json_decode($row["options"], true);
                            foreach($json_options as $vname => $options) {
                        ?>
                        <div style="font-size: 12px;font-weight: 100;"><?php echo $options["attrname"]??'';?>: <em style="color:red;"><?php echo $options["valuename"]??'';?></em></div>
                        <?php
                            }
                        }
                        ?>
                    </td>
                    <td><?php echo $row['qty']; ?></td>
                    <td><?php echo $row['prod_price']; ?></td>
                    <td><?php echo $row['total_amount']; ?></td>
                </tr>
            <?php } } ?>
            <?php if(empty($order["snid"]) && !empty($gift)) { ?>
                <tr>
                    <td><?php echo $gift['sku']; ?></td>
                    <td>
                        <?php echo $gift['prodname']; ?>
                        <em style="color:red;">贈品</em>
                    </td>
                    <td>1</td>
                    <td>0</td>
                    <td>0</td>
                </tr>
            <?php } ?>
    <?php if(empty($order["snid"])) { ?>
        <tr>
            <td colspan="4" class="title_name">付款方式:</td>
            <td>
                <?php
                $pay_types = get_pay_types();
                echo $pay_types[$order['pay_type']]["title"]??"";
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="4" class="title_name">運費:</td><td><?php echo format_price($order['shipping_fee']); ?></td>
        </tr>
        <tr>
            <td colspan="4" class="title_name">折價券金額:</td><td><?php echo format_price($order['coupon_amount']); ?></td>
        </tr>
        <tr>
            <td colspan="4" class="title_name">促銷模組:</td><td>
                <?php
                if(!empty($order["promotion_rules"])) {
                    ?>
                    <table>
                        <?php
                        $rules = json_decode($order["promotion_rules"], true);
                        if(!empty($rules)) {
                            foreach($rules as $rule) {
                                ?>
                                <tr><td><?php echo $rule["title"];?></td><td>-<?php echo format_price($rule["amount"]);?></td></tr>
                                <?php
                            } }
                        ?>
                    </table>
                    <?php
                }
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="4" class="title_name">紅利點數抵扣:</td><td><?php echo format_price($order['credit_money']); ?></td>
        </tr>
        <tr>
            <td colspan="4" class="title_name">總金額:</td><td><?php echo format_price($order['total_amount']); ?></td>
        </tr>
        <?php } ?>
</table>
{include file="common/footer_js" /}