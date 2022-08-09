{include file="common/header" /}
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid.min.css">
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid-theme.min.css">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">門市消費</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item"><a href="{:admin_link('StoreOrder/index')}">門市消費記錄</a></li>
                        <li class="breadcrumb-item active">門市消費</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <form class="AjaxForm" action="<?= url("checkout") ?>" method="post">
    <section class="content">
        <div class="card">

	<div class="searchBar">
        <input type="hidden" id="customerid" name="customerid" value="{:$customer["customerid"]}" />
		<table id="checkoutform" class="table table-bordered table-striped table-sm table-responsive-sm">
            <tr>
				<td style="text-align: right; width: 120px;">
					用戶名：
				</td>
                <td>
                    {:$customer["vipcode"]} <a href="{:admin_link('Customer/detail', ['customerid' => $customer['customerid']])}" class="DialogForm">明細</a>
                </td>
            </tr>
            <tr>
				<td style="text-align: right">
					姓名：
				</td>
                <td>
                    {:$customer["fullname"]}
                </td>
            </tr>
            <tr>
                <td style="text-align: right">
                    紅利點數餘額：
                </td>
                <td id="leftcredits">
                    {:$customer["credits"]}
                </td>
            </tr>

            <tr>
                <td style="text-align: right">
                    消費金額：
                </td>
                <td>
                    <input type="number" size="50" class="form-control" id="total_amount" name="total_amount" value="" />
                </td>
            </tr>
            <tr>
                <td style="text-align: right">
                    優惠券號：
                </td>
                <td>
                    <input type="text" size="50" class="form-control" id="coupon_code" name="coupon_code" value="" />
                </td>
            </tr>
            <tr>
                <td style="text-align: right">
                    使用紅利點數：
                </td>
                <td>
                    <input type="number" min="0" step="100" size="50" class="form-control" id="credits" name="credits" value="0" />
                </td>
            </tr>
            <tr>
                <td style="text-align: right">
                    應付金額：
                </td>
                <td id="payamount">

                </td>
            </tr>
            <tr>
                <td style="text-align: right">
                    獲得紅利點數：
                </td>
                <td id="return_credits">

                </td>
            </tr>
            <tr>
                <td style="text-align: right">
                    關聯商品：
                </td>
                <td>
                    <table class="table">
                        <tr><td colspan="2">
                                <textarea id="sku" rows="5" placeholder="商品編號1&#10;商品編號2&#10;商品編號3&#10;商品編號..." class="form-control"></textarea>
                                <input type="button" id="findnow" value="搜尋"/> （請在輸入框中輸入商品的編號,多個商品請換號輸入，一行一個商品編號，然後點擊搜尋）
                            </td></tr>
                        <tr>
                            <td>
                                <fieldset>
                                    <legend>商品清單</legend>
                                    <div id="fproduct"></div>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
		</table>
    </div>
        </div>
    </section>
            <section class="content">
                <div class="row">
                    <div class="col-md-6">
                        <a class="btn btn-secondary text-center" href="{:admin_link('StoreOrder/customer')}">取消</a>
                    </div>
                    <div class="col-md-6 text-right">
                        <input type="submit" id="submitBtn" class="btn btn-primary" value="確認結帳">
                    </div>
                </div>
            </section>
        </form>
    </section>
</div>
{include file="common/footer" /}
<script>
    $("#checkoutform input").blur(function() {
        $.getJSON('{:url("calc")}', {coupon_code: $("#coupon_code").val(), credits:$("#credits").val(), customerid:$("#customerid").val(), total_amount: $("#total_amount").val()}, function(json) {
            if(json.code>0) {
                layer.msg(json.msg);
                return false;
            }
            $("#payamount").text(json.total_amount);
            $("#return_credits").text(json.return_credits);
        })
    })

	$('#credits').keyup(function() {
		if($(this).val()>$('#leftcredits').text()) {
			layer.msg("輸入的點數無效");
		}
	})

    $('#findnow').click(function() {
        $.get('{:url("get_product")}', {prodcode:$('#sku').val()}, function(data) {
            $("#fproduct").html(data);
        })
    })
</script>
