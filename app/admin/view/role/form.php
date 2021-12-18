{include file="common/header_meta" /}
<link rel="stylesheet" href="/static/js/ztree/css/zTreeStyle/zTreeStyle.css" type="text/css">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">新增/編輯角色</h1>
                </div><!-- /.col -->
            </div>
        </div>
    </div>
    <section class="content">
        <div class="card">
<form class="AjaxForm" action="<?= url("Role/save") ?>" method="post">
    <div class="searchBar">
        <table layoutH="38" width="100%">
            <tr>
                <td style="text-align: right">
                    角色名稱：
                </td>
                <td>
                    <input type="text" size="30" name="title" value="<?= isset($role["title"]) ? $role["title"] : "" ?>" />
                </td>
            </tr>
            <tr>
                <td style="text-align: right">
                    許可權：
                </td>
                <td>
                    <div style=" float:left; display:block; overflow:auto; width:300px; height:300px; overflow:auto; border:solid 1px #CCC; line-height:21px; background:#FFF;">
                        <ul id="tree" class="ztree"></ul>
                    </div>    
                </td>
            </tr>
            <tr>
                <td style="text-align: right">
                    是否顯示：
                </td>
                <td>
                    <input type="checkbox" name="status" <?= (isset($role["status"]) && $role["status"]) ? "checked" : "" ?> value="1" />
                </td>
            </tr>
        </table>
        <input type="hidden" name="roleid" value="<?= isset($role["roleid"]) ? $role["roleid"] : 0 ?>" />
        <input type="hidden" name="limits"/>
        <div class="card-footer text-right">
            <button type="submit" id="submitBtn" class="btn btn-primary">儲存</button>
        </div>
    </div>
</form>
        </div>
    </section>
</div>
{include file="common/footer_js" /}
<script type="text/javascript" src="/static/js/ztree/js/jquery.ztree.core.js"></script>
<script type="text/javascript" src="/static/js/ztree/js/jquery.ztree.excheck.js"></script>
<script>
    var setting = {
        check: {
            enable: true
        },
        data: {
            simpleData: {
                enable: true
            }
        }
    };
    var zNodes = {:toJSon($tree_nodes, true)};
    $(document).ready(function() {
        zTreeObj = $.fn.zTree.init($("#tree"), setting, zNodes);
        $("#submitBtn").click(function() {
            var zTreeObj = $.fn.zTree.getZTreeObj("tree");
            var checkedNodes = zTreeObj.getCheckedNodes();
            if(checkedNodes.length) {
                var checkCatIds = [];
                $.each(checkedNodes, function(i, obj) {
                    checkCatIds.push(obj.id);
                })
                $("input[name=limits]").val(checkCatIds.join(","));
            }
        })
    })
</script>
