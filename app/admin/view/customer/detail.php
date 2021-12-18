{include file="common/header_meta" /}
<link rel="stylesheet" href="/static/admin/plugins/toastr/toastr.min.css">
<div class="content-wrapper">
    <section class="content">
        <div class="card">
            <div class="card-body">
                <div style="position: relative; height: 100%; width: 100%;">
                    <table id="groups" class="table table-bordered table-striped table-sm table-responsive-sm">

                        <tr>
                            <td class="text-right">會員帳號</td>
                            <td>{:$customer['custconemail']}</td>
                            <td class="text-right">會員編號</td>
                            <td>{:$customer['vipcode']}</td>
                            <td class="text-right">手機號碼</td>
                            <td>{:empty($customer['mobile'])?'':$customer['mobile']}</td>
                        </tr>
                        <tr>
                            <td class="text-right">姓名</td>
                            <td>{:$customer['fullname']}</td>
                            <td class="text-right">性別</td>
                            <td>{:$customer['sex']==1?'男':'女'}</td>
                            <td class="text-right">生日</td>
                            <td>{:$customer['birth_year']}-{:$customer['birth_month']}-{:$customer['birth_day']}</td>
                        </tr>
                        <tr>
                            <td class="text-right">手機載具</td>
                            <td>{:$customer['invoice_code']}</td>
                            <td class="text-right">自然人憑證</td>
                            <td>{:$customer['pc_code']}</td>
                            <td class="text-right">聯絡市話</td>
                            <td>{:$customer['tel']}</td>
                        </tr>
                        <tr>
                            <td class="text-right">地址</td>
                            <td colspan="5">
                                <?php
                                    echo isset($countries[$customer['cityid']])?$countries[$customer['cityid']]:'';
                                    echo isset($countries[$customer['areaid']])?$countries[$customer['areaid']]:'';
                                    echo $customer["address"];
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>
    </section>
</div>
{include file="common/footer_js" /}
