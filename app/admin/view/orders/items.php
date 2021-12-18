<style>
    table.list .product_details {
        display: block;
        width: 345px;
        margin:0;
    }
    table.list .product_details.title  li {
        color: #0000FF;
        border-top: 1px solid #ddd;
    }
    table.list .product_details li {
        border-left: 0;
        padding: 5px;
        border-bottom: 1px solid #ddd;
        background: lemonchiffon;
        height: 21px;
        overflow: hidden; 
        float:left;
    }
    table.list .product_details li.sku {
        width: 60px;
        border-left: 1px solid #ddd;
        border-right: 1px solid #ddd;
    }
    table.list .product_details li.name {
        width: 129px;
        border-right: 1px solid #ddd;
    }
    table.list .product_details li.qty {
        width: 39px;
        border-right: 1px solid #ddd;
    }
    table.list .product_details li.price {
        width: 70px;
        border-right: 1px solid #ddd;
    }
    table.list .product_details .title td {
        background: #f0c040;
    }
    .chuku {
        border-left: 1px solid #999;
        border-top: 1px solid #999;
    }
    .chuku th, .chuku td{
        border-right: 1px solid #999;
        border-bottom: 1px solid #999;
    }
</style>
<div class="pageContent">
    <table class="list order_list" width="100%" layoutH="25">
        <thead>
            <tr>
                <th>ID</th>
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
            $sps = get_suppliers();
            foreach ($items as $k => $row) {
                $qty += $row["qty"];
                $total_amount += $row["total_amount"];
                ?>
                <tr target="oid" rel="<?php echo $row['oid']; ?>">
                    <td><?php echo $row['prodid']; ?></td>
                    <td><?php echo $row['sku']; ?></td>
                    <td>
                        <?php echo $row['prodname']; ?>
                        <?php 
                        if(!empty($row["options"])) {
                            $json_options = json_decode($row["options"], true);
                            foreach($json_options as $vname => $options) {
                                foreach($options as $voname => $vodata) {
                        ?>
                        <div style="font-size: 12px;font-weight: 100;"><?php echo $vname;?>: <em style="color:red;"><?php echo $voname;?></em></div>
                        <?php
                                }
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
                <tr>
                    <td colspan="2" style="text-align:right; color:blue;">出庫設置:</td>
                    <td colspan="6" style="background: #c9e2b3">
                        <table class="chuku">
                            <tr>
                                <th>供應商名稱</th>
                                <th>商品名稱(庫存)</th>
                                <th>出庫數量</th>
                                <th>出庫日期</th>
                                <th></th>
                            </tr>
                            <tr>
                                <td>
                                    <select class="spid" onchange="change_supply(<?php echo $row['opid'];?>, this)">
                                        <option value="">選擇</option>
                                        <?php
                                        if(isset($supplier[$row["prodid"]]) && !empty($supplier[$row["prodid"]])) {
                                            foreach($supplier[$row["prodid"]] as $spid => $items) {
                                                if($out_spid>0 && $out_spid == $spid) {
                                                    $selected = "selected";
                                                } else {
                                                    $selected = "";
                                                }
                                        ?>
                                        <option <?php echo $selected;?> rel='<?php echo json_encode($items);?>' value="<?php echo $spid;?>"><?php echo $sps[$spid];?></option>
                                        <?php } }  ?>
                                    </select>
                                    
                                </td>
                                <td>
                                    <select class="itemid" onchange="set_qty(<?php echo $row['opid'];?>, this)"></select>
                                </td>
                                <td><input type="text" class="out_stock" value="0" /></td>
                                <td><input type="text" class="date out_date" value="<?php echo date("Y-m-d");?>" /></td>
                                <td>
                                    <input type="hidden" class="opid" value="<?php echo $row["opid"];?>" />
                                    <input type="button" class="save_button" onclick="save_chuhuo(this)" value="確認出貨" />
                                </td>
                            </tr>
                            
                        </table>
                    </td>
                </tr>
            <?php } ?>            
        </tbody>
    </table>
    <div class="panelBar" style="font-size:14px;line-height: 25px;">物品總數: <b><?php echo $qty;?></b>, 物品總金額: <b><?php echo $total_amount;?></b></div>
</div>
<script>
    
    var out_items = <?php echo json_encode($out_items);?>;
    function change_supply(opid, obj) {
        var items = JSON.parse($(obj).find("option:selected").attr("rel"));
        if(items !== "") {
            var options = "<option value='0'>選擇</option>";
            $.each(items, function(i, k) {
                var okey = opid+'_'+k.itemid;
                if(out_items.hasOwnProperty(okey) && out_items[okey] !== undefined) {
                    selected = "selected";
                } else {
                    selected = "";
                }
                options += '<option '+selected+' value="'+k.itemid+'">'+k.title+'('+k.stock+')'+'</option>';
            });
            $(obj).parent().parent().find(".itemid").html(options);
        }
    }
    
    function set_qty(opid, obj) {
        
        var itemid = $(obj).val();
        var okey = opid+'_'+itemid;
        if(out_items.hasOwnProperty(okey) && out_items[okey] !== undefined) {
            $(obj).parent().parent().find(".spid").attr("disabled", true);
            $(obj).parent().parent().find(".itemid").attr("disabled", true);
            $(obj).parent().parent().find(".out_stock").val(out_items[okey]["out_stock"]).attr("disabled", true);
            $(obj).parent().parent().find(".out_date").val(out_items[okey]["out_date"]).attr("disabled", true);
            $(obj).parent().parent().find(".save_button").val("已出貨").attr("disabled", true);
        }
    }
    
    function save_chuhuo(obj) {
        if(confirm("確認出貨將更新供應商的商品庫存數，重複操作此操作將多次扣除供應商庫存, 請謹慎操作！！！")) {
            var trObj = $(obj).parent().parent();
            var opid = trObj.find(".opid").val();
            var itemid = trObj.find(".itemid").val();
            var out_stock = trObj.find(".out_stock").val();
            var out_date = trObj.find(".out_date").val();
            $.ajax({
                async : false,
                url: '<?php echo url("Orders/item_out");?>',
                data: {oid:<?php echo $oid;?>, opid:opid, itemid:itemid, out_stock:out_stock, out_date:out_date},
                dataType: "json",
                method: "post",
                success: function(json) {
                    if(json.statusCode==200) {
                        alertMsg.correct(json.message);
                    } else {
                        alertMsg.error(json.message);
                    }
                }
            })
        }
    }
    
    $(function() {
        <?php if($out_spid>0) { ?>
        setTimeout(function() {
            $(".spid").change();
        }, 100);
        
        setTimeout(function() {
            $(".itemid").change();
        }, 300);
        
        <?php } ?>
    })
</script>