{include file="common/header_meta" /}
<div class="content-wrapper">
    <section class="content">
        <form class="AjaxForm" action="<?= url("CouponCodes/save") ?>" method="post">
            <table class="table table-bordered table-striped table-sm table-responsive-sm">
                <tr>
                    <td style="text-align: right;">
                        標題：
                    </td>
                    <td>
                        <input type="text" class="required" size="50" name="title" value="<?= isset($ccodes["title"]) ? $ccodes["title"] : "" ?>" />
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right;">
                        折扣方式：
                    </td>
                    <td>
                        <select name="ptype">
                            <?php
                            $ptypes = get_couponcodes_ptypes();
                            if(!empty($ptypes)) {
                                foreach($ptypes as $k => $v) {
                                    if(isset($ccodes["ptype"]) && $ccodes["ptype"]==$k) {
                                        $selected = "selected";
                                    } else {
                                        $selected = "";
                                    }
                                    ?>
                                    <option <?php echo $selected;?> value="<?php echo $k;?>"><?php echo $v;?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right;">
                        最小訂購金額：
                    </td>
                    <td>
                        <input type="text" size="10" class="required digits" name="total" value="<?= isset($ccodes["total"]) ? $ccodes["total"] : "0" ?>" />
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right;">
                        減免金額或折數：
                    </td>
                    <td>
                        <input type="text" size="10" class="digits" name="sub_total" value="<?= isset($ccodes["sub_total"]) ? $ccodes["sub_total"] : "0" ?>" />滿X元打Y折請直接輸入打折數，比如打8折，則輸入80，打6折輸入60
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right;">
                        開始日期：
                    </td>
                    <td>
                        <input type="text" size="10" name="start_date" class="required date datetime" autocomplete="off" value="<?= isset($ccodes["start_date"]) ? date("Y-m-d", $ccodes["start_date"]) : "" ?>" />
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right;">
                        結束日期：
                    </td>
                    <td>
                        <input type="text" size="10" name="end_date" class="required date datetime" autocomplete="off" value="<?= isset($ccodes["end_date"]) ? date("Y-m-d", $ccodes["end_date"]) : "" ?>" />
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right">
                        可否重複訂購：
                    </td>
                    <td>
                        <input type="checkbox" name="is_reuse" <?= (isset($ccodes["is_reuse"]) && $ccodes["is_reuse"]) ? "checked" : "" ?> value="1" />
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right">
                        是否顯示：
                    </td>
                    <td>
                        <input type="checkbox" name="state" <?= (isset($ccodes["state"]) && $ccodes["state"]) ? "checked" : "" ?> value="1" />
                    </td>
                </tr>
            </table>
            <input type="hidden" name="ccid" value="<?= isset($ccodes["ccid"]) ? $ccodes["ccid"] : 0 ?>" />
            <div class="text-right">
                <button type="submit" class="btn btn-primary">儲存</button>
            </div>
        </form>
    </section>

</div>
{include file="common/footer_js" /}
<script>
    $('.datetime').datetimepicker({
        format:"Y-m-d",
        timepicker:false
    });
</script>