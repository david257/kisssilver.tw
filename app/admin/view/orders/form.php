{include file="common/header_meta" /}
<style>
    .title td {
        font-size: 14px;
        font-weight: bold;
        background: #7cc5e5;
        color: #fff;
    }
    .title_name {
        text-align: right;
    }
</style>
<div class="pageContent">
    <form method="post" action="<?php echo admin_link('Orders/save'); ?>" class="AjaxForm">
        <div>
            <input type="hidden" name="oid" value="<?php echo $order['oid']; ?>"/>    
            <table class="table table-bordered table-striped table-sm table-responsive-sm">
                <tr class="title">
                    <td>訂單資訊</td>
                    <td>收貨人資訊</td>
                </tr>
                <tr>
                    <td valign="top">
                        <table width="100%">
                            <tr>
                                <td class="title_name">訂單日期:</td><td><?php echo date('Y-m-d H:i:s', $order['create_date']); ?></td>
                            </tr>
                            <tr>
                                <td class="title_name">訂單編號:</td><td><?php echo $order['oid']; ?></td>
                            </tr>
                            <tr>
                                <td class="title_name">總金額:</td><td><?php echo format_price($order['total_amount']); ?></td>
                            </tr>
                            <tr>
                                <td class="title_name">配送方式:</td>
                                <td>
                                    <?php
                                    $express_types = express_types();
                                    $LogisticsHomeSubTypes = LogisticsHomeSubTypes();
                                    $LogisticsCVSSubTypes = LogisticsCVSSubTypes();
                                    echo $express_types[$order['LogisticsType']];
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
                                    echo $pay_types[$order['pay_type']]["title"];
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="title_name">交易流水號:</td><td><?php echo $order['TradeNo']; ?></td>
                            </tr>
                            <tr>
                                <td class="title_name">運費:</td><td><?php echo format_price($order['shipping_fee']); ?></td>
                            </tr>
                            <tr>
                                <td class="title_name">優惠券:</td><td><?php echo $order['coupon_code']; ?></td>
                            </tr>
                            <tr>
                                <td class="title_name">優惠金額:</td><td><?php echo format_price($order['coupon_amount']); ?></td>
                            </tr>
                            <tr>
                                <td class="title_name">促銷模組:</td><td>
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
                                <td class="title_name">促銷金額:</td><td><?php echo $order['promotion_amount']; ?></td>
                            </tr>
							<tr>
								<td class="title_name">訂單備註:</td><td><?php echo $order['ordnote']; ?></td>
							</tr>
                            <tr class="title"><td colspan="2">發票Invoice</td></tr>
         
                                        <tr><td class="title_name">發票類型:</td><td><?php echo $order['invoice_type'];?></td></tr>
                                        <tr><td class="title_name">發票捐贈:</td><td><?php echo $order['donate_to']?(isset($donate_company[$order['donate_to']])?$donate_company[$order['donate_to']]:''):'不捐贈';?></td></tr>
                                        <tr><td class="title_name">公司抬頭:</td><td><?php echo $order['invoice_header'];?></td></tr>
                                        <tr><td class="title_name">統一編號:</td><td><?php echo $order['invoice_no'];?></td></tr>
                                        <tr><td class="title_name">發票載具:</td>
                                            <td>
                                                <?php
                                                $invoice_zaiju = invoice_zaiju();
                                                ?>
                                                {:isset($invoice_zaiju[$order['invoice_zaiju']])?$invoice_zaiju[$order['invoice_zaiju']]:''}
                                            </td>
                                        </tr>
                        </table>
                    </td>
                    <td valign="top">
                        <table width="100%">
                            <tr>
                                <td class="title_name">姓名:</td><td><?php echo $order['shipping_name']; ?></td>
                            </tr>
                            <tr>
                                <td class="title_name">行動電話:</td><td><?php echo $order['shipping_mobile']; ?></td>
                            </tr>
                            <tr>
                                <td class="title_name">收貨地址:</td>
                                <td>
                                    <?php
                                    if($order["LogisticsType"]=="HOME") {
                                    ?>
                                    <?php echo $order['shipping_city']; ?>-<?php echo $order['shipping_area']; ?>-<?php echo $order['shipping_address']; ?>
                                    <?php } else { ?>
                                    <?php echo $order['CVSAddress']; ?>(<?php echo $order['CVSStoreName']; ?>)
                                    <?php } ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr class="title">
                    <td colspan="2">訂購商品</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table width="100%">
                        <thead>
                            <tr>
                                <th>編號</th>
                                <th>商品名稱</th>
                                <th>訂購數量</th>
                                <th>售價</th>
                                <th>總金額</th>
                                <th>更新日期</th>
                                <th>創建日期</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $qty = $total_amount = $total_points = 0;
                            if(!empty($items)) {
                            foreach ($items as $k => $row) {
                                $qty += $row["qty"];
                                $total_amount += $row["total_amount"];
                                ?>
                                <tr target="oid" rel="<?php echo $row['oid']; ?>">
                                    <td><?php echo $row['sku']; ?></td>
                                    <td>
                                        <?php echo $row['prodname']; ?>
                                        <?php 
                                        if(!empty($row["options"])) {
                                            $json_options = json_decode($row["options"], true);
                                            foreach($json_options as $vname => $options) {
                                        ?>
                                        <div style="font-size: 12px;font-weight: 100;"><?php echo $options["attrname"];?>: <em style="color:red;"><?php echo $options["valuename"];?></em></div>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo $row['qty']; ?></td>
                                    <td><?php echo $row['prod_price']; ?></td>
                                    <td><?php echo $row['total_amount']; ?></td>
                                    <td><?php echo empty($row['update_date']) ? '' : date('Y-m-d H:i:s', $row['update_date']); ?></td>
                                    <td><?php echo date('Y-m-d H:i:s', $row['create_date']); ?></td>
                                </tr>
                            <?php } } ?>
                            <?php if(!empty($gift)) { ?>
                                <tr>
                                    <td><?php echo $gift['sku']; ?></td>
                                    <td>
                                        <?php echo $gift['prodname']; ?>
                                        <em style="color:red;">贈品</em>
                                    </td>
                                    <td>1</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right;">訂單狀態：</td>
                    <td>
                        <select name="order_status">
                            <?php
                            $order_status = get_order_states();
                            foreach ($order_status as $k => $v) {
                                if ($order['order_status'] == $k) {
                                    $select = "selected";
                                } else {
                                    $select = "";
                                }
                                ?>
                                <option <?php echo $select; ?> value="<?php echo $k; ?>"><?php echo $v; ?></option>
                            <?php } ?>
                        </select>
                        <br/>
                        <br/>
                        <button type="submit" class="btn btn-primary btn-xs">儲存</button>
                    </td>
                </tr>
            </table>
            <div class="text-center"><i class="alert alert-danger">溫馨提醒:訂單變更為完成狀態，會把購物返點發給會員， 請不要重複執行訂單完成狀態</i></div>
            <br/>
            <br/>
        </div>

    </form>
</div>
{include file="common/footer_js" /}
