{include file="common/header" /}
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid.min.css">
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid-theme.min.css">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">管理員新增/編輯</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item"><a href="{:admin_link('User/index')}">管理員列表</a></li>
                        <li class="breadcrumb-item active">管理員新增/編輯</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <form class="AjaxForm" action="<?= url("save") ?>" method="post">
    <section class="content">
        <div class="card">

	<div class="searchBar">
		<table class="table table-bordered table-striped table-sm table-responsive-sm">
            <tr>
				<td style="text-align: right; width: 120px;">
					用戶名：
				</td>
                <td>
                    <input type="text" size="50" class="form-control" name="username" value="<?= isset($user["username"])?$user["username"]:"" ?>" />
                </td>
            </tr>
            <tr>
				<td style="text-align: right">
					姓名：
				</td>
                <td>
                    <input type="text" size="50" class="form-control" name="fullname" value="<?= isset($user["fullname"])?$user["fullname"]:"" ?>" />
                </td>
            </tr>
            <tr>
				<td style="text-align: right">
					登入密碼：
				</td>
                <td>
                    <input type="text" size="50" class="form-control" name="userpass" value="" />
                </td>
            </tr>
            <tr>
                <td style="text-align: right">
                    所屬門店：
                </td>
                <td>
                    <select name="snid" class="form-control">
                        <option value="0">選擇</option>
                        <?php
                        if(!empty($stores)) {
                            foreach($stores as $snid => $storetitle){
                                if(isset($user["snid"]) && $snid==$user["snid"]) {
                                    $selected = "selected";
                                } else {
                                    $selected = "";
                                }
                                echo '<option '.$selected.' value="'.$snid.'">';
                                //根據所在的層次縮進
                                echo $storetitle;
                                echo '</option>';
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
				<td style="text-align: right">
					角色：
				</td>
                <td>
                    <select name="roleid" class="form-control">
                        <option value="0">選擇</option>
                    <?php
                    if(!empty($roles)) {
                        foreach($roles as $role){
                            if(isset($user["roleid"]) && $role["roleid"]==$user["roleid"]) {
                                $selected = "selected";
                            } else {
                                $selected = "";
                            }
                            echo '<option '.$selected.' value="'.$role["roleid"].'">';
                            //根據所在的層次縮進
                            echo $role["title"];
                            echo '</option>';
                        }
                    }
                    ?>
                    </select>
                </td>
            </tr>
            <tr>
				<td style="text-align: right">
					是否顯示：
				</td>
                <td>
                    <input type="checkbox" name="status" <?= (isset($user["state"]) && $user["state"])?"checked":"" ?> value="1" />
                </td>
			</tr>
		</table>
        <input type="hidden" name="userid" value="<?= isset($user["userid"])?$user["userid"]:0 ?>" />
    </div>
        </div>
    </section>
            <section class="content">
                <div class="row">
                    <div class="col-md-6">
                        <a class="btn btn-secondary text-left" href="{:admin_link('User/index')}">取消</a>
                    </div>
                    <div class="col-md-6 text-right">
                        <input type="submit" id="submitBtn" class="btn btn-primary" value="儲存">
                    </div>
                </div>
            </section>
        </form>
    </section>
</div>
{include file="common/footer" /}
