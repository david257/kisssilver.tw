{include file="common/header_meta" /}
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">新增/編輯新聞</h1>
                </div><!-- /.col -->
            </div>
        </div>
    </div>
    <section class="content">
        <div class="card">
        <form class="AjaxForm" action="<?= url("Node/save") ?>" method="post">
            <div class="searchBar">
                <table class="table table-bordered table-striped table-sm table-responsive-sm">
                    <tr>
                        <td style="text-align: right">
                            名稱：
                        </td>
                        <td>
                            <input type="text" size="50" name="title" class="form-control" value="<?= isset($node["title"])?$node["title"]:"" ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: right">
                            所屬上級：
                        </td>
                        <td>
                            <select name="pid" class="form-control">
                                <option value="0">無</option>
                            <?php
                            if(!empty($tree_nodes)) {
                                foreach($tree_nodes as $item){
                                    if(isset($node["pid"]) && $item["id"]==$node["pid"]) {
                                        $selected = "selected";
                                    } else {
                                        $selected = "";
                                    }
                                    echo '<option '.$selected.' value="'.$item["id"].'">';
                                    //根據所在的層次縮進
                                    echo str_repeat('......',$item['level']);
                                    echo $item['name'];
                                    echo '</option>';
                                }
                            }
                            ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: right">
                            URL：
                        </td>
                        <td>
                            <input type="text" size="50" name="url" class="form-control" value="<?= isset($node["url"])?$node["url"]:"" ?>" />
                            <br/>
                            模組/控制器/方法
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: right">
                            選單：
                        </td>
                        <td>
                            <input type="checkbox"  name="is_menu" <?= (isset($node["is_menu"]) && $node["is_menu"])?"checked":"" ?> value="1" />
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: right">
                            排序：
                        </td>
                        <td>
                            <input type="text" size="10" name="sortorder" class="form-control" value="<?= isset($node["sortorder"])?$node["sortorder"]:"999" ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: right">
                            是否顯示：
                        </td>
                        <td>
                            <input type="checkbox" name="status" <?= (isset($node["status"]) && $node["status"])?"checked":"" ?> value="1" />
                        </td>
                    </tr>
                </table>
                <input type="hidden" name="nodeid" value="<?= isset($node["nodeid"])?$node["nodeid"]:0 ?>" />
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">儲存</button>
                </div>
            </div>
        </form>
        </div>
    </section>
</div>
{include file="common/footer_js" /}
