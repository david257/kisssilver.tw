{include file="common/header_meta" /}
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">密碼更改</h1>
                </div><!-- /.col -->
            </div>
        </div>
    </div>
    <section class="content">
        <div class="card">
<form class="AjaxForm" action="<?= url("User/do_change_pass") ?>" method="post">
	<div class="searchBar">
		<table width="100%">
            <tr>
				<td style="text-align: right">
					舊密碼：
				</td>
                <td>
                    <input type="password" name="old_pass" />
                </td>
            </tr>
			<tr>
				<td style="text-align: right">
					新密碼：
				</td>
                <td>
                    <input type="password" name="new_pass" />
                </td>
            </tr>
            <tr>
				<td style="text-align: right">
					確認密碼：
				</td>
                <td>
                    <input type="password" name="renew_pass" />
                </td>
			</tr>
		</table>
        <div class="card-footer text-right">
            <button type="submit" class="btn btn-primary">儲存</button>
        </div>
	</div>
</form>
        </div>
    </section>
</div>
{include file="common/footer_js" /}