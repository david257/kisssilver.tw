<table class="table">
    <tr>
        <th>商品編號</th>
        <th>商品名稱</th>
        <th>商品規格</th>
        <th>購買數量</th>
    </tr>
    <?php
    foreach($products as $product) {
    ?>
    <tr>
        <td>{$product['prodcode']}</td>
        <td>{$product['prodname']}</td>
        <td>
            <?php if(isset($options[$product["prodid"]])) { ?>
            <table>
                <?php
                foreach($options[$product["prodid"]] as $attrid => $op) {
                    ?>
                    <tr>
                        <td>{$op['name']}：</td>
                        <td>
                            <select name="valueids[{$product["prodid"]}][{$attrid}]" class="form-control">
                            <?php
                            foreach($op["values"] as $k => $value) {
                                ?>
                                <option value="{$value['valueid']}">{$value['name']}</option>
                            <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <?php } else { ?>
                無
            <?php } ?>
        </td>
        <td>
            <select class="form-control" name="qty[{$product["prodid"]}]">
                <?php for($i=1; $i<=200; $i++) { ?>
                <option value="{$i}">{$i}</option>
                    <?php } ?>
            </select>
        </td>
    </tr>
    <?php
    }
    ?>
</table>