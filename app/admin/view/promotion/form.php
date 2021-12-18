{include file="common/header_meta" /}
<link rel="stylesheet" href="/static/js/ztree/css/zTreeStyle/zTreeStyle.css" type="text/css">
<div class="content-wrapper">
    <section class="content">
<form class="AjaxForm" action="<?= url("Promotion/save") ?>" method="post">
    <table class="table table-bordered table-striped table-sm table-responsive-sm">
        <tr>
            <td style="text-align: right;">
                標題：
            </td>
            <td>
                <input type="text" class="required" size="50" name="title" value="<?= isset($promotion["title"]) ? $promotion["title"] : "" ?>" />
            </td>
        </tr>
        <tr>
            <td style="text-align: right;">
                促銷方式：
            </td>
            <td>
                <select name="ptype">
                <?php
                $ptypes = get_ptypes();
                if(!empty($ptypes)) {
                    foreach($ptypes as $k => $v) {
                        if(isset($promotion["ptype"]) && $promotion["ptype"]==$k) {
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
                達標數量或金額：
            </td>
            <td>
                <input type="text" size="10" class="required digits" name="total" value="<?= isset($promotion["total"]) ? $promotion["total"] : "0" ?>" />
            </td>
        </tr>
        <tr>
            <td style="text-align: right;">
                減免金額：
            </td>
            <td>
                <input type="text" size="10" class="digits" name="sub_total" value="<?= isset($promotion["sub_total"]) ? $promotion["sub_total"] : "0" ?>" />免運費方式不用設置,滿X件打折請直接輸入打折數，比如打8折，則輸入80，打6折輸入60
            </td>
        </tr>
        <tr>
            <td style="text-align: right;">
                開始日期：
            </td>
            <td>
                <input type="text" size="10" name="start_date" class="required date datetime" autocomplete="off" value="<?= isset($promotion["start_date"]) ? date("Y-m-d", $promotion["start_date"]) : "" ?>" />
            </td>
        </tr>
        <tr>
            <td style="text-align: right;">
                結束日期：
            </td>
            <td>
                <input type="text" size="10" name="end_date" class="required date datetime" autocomplete="off" value="<?= isset($promotion["end_date"]) ? date("Y-m-d", $promotion["end_date"]) : "" ?>" />
            </td>
        </tr>
        <tr>
            <td style="text-align: right">
                是否顯示：
            </td>
            <td>
                <input type="checkbox" name="state" <?= (isset($promotion["state"]) && $promotion["state"]) ? "checked" : "" ?> value="1" />
            </td>
        </tr>
        <tr>
            <td style="text-align: right">
                適用範圍：
            </td>
            <td>
                <div class="alert alert-default-warning">不選擇分類表示全館有效，選擇分類則表示只適用於所選擇分類,千萬注意：同一分類不同被不同的促銷規則綁定</div>
                <ul id="categoryObj" class="ztree" style="border:1px solid #ddd;height: 300px;overflow: scroll"></ul>
                <input type="hidden" name="cateIds" value=""/>
            </td>
        </tr>
    </table>
    <input type="hidden" name="ptid" value="<?= isset($promotion["ptid"]) ? $promotion["ptid"] : 0 ?>" />
    <div class="text-right">
        <button type="submit" id="submitBtn" class="btn btn-primary">儲存</button>
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
<script>
    <!--
    var setting = {
        /*view: {
            selectedMulti: false
        },
        check: {
            enable: true,
            chkboxType: {"Y" : "", "N" : ""}
        },
        data: {
            simpleData: {
                enable: true
            }
        },
        callback: {
            beforeClick:function () {
                var e =  e ||window.event;
                e.stopPropagation();
                return false;
            },
            onCheck:function (e, treeId, treeNode) {
                var treeObj = $.fn.zTree.getZTreeObj(treeId);
                var status = treeNode. checked;
                treeObj.checkAllNodes(false);
                treeObj.checkNode(treeNode, status, false);
            }
        }*/
        check: {
            enable: true,
            chkboxType: {"Y" : "ps", "N" : "ps"}
        },
        data: {
            simpleData: {
                enable: true
            }
        }
    };

    var zNodes = {:$zNodes};
    $(document).ready(function(){
        $.fn.zTree.init($("#categoryObj"), setting, zNodes);

        $("#submitBtn").click(function() {
            var zTreeObj = $.fn.zTree.getZTreeObj("categoryObj");  //ztree的Id  zTreeId
            var checkedNodes = zTreeObj.getCheckedNodes();
            if(checkedNodes.length) {
                var checkCatIds = [];
                $.each(checkedNodes, function(i, obj) {
                    checkCatIds.push(obj.id);
                })
                $("input[name=cateIds]").val(checkCatIds.join(","));
            }
        })

    });
    //-->
</script>
<script type="text/javascript" src="/static/js/ztree/js/jquery.ztree.core.js"></script>
<script type="text/javascript" src="/static/js/ztree/js/jquery.ztree.excheck.js"></script>