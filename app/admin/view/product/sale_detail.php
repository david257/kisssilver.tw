{include file="common/header_meta" /}
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid.min.css">
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid-theme.min.css">
<div class="">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">商品銷量明細</h1>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="card">

            <div class="card-body p-0">
                <table class="table table-bordered table-striped table-sm table-responsive-sm">
                    <thead>
                    <tr>
                        <th style="width: 1%">
                            訂單ID
                        </th>
                        <th style="width: 20%">
                            產品名稱
                        </th>
                        <th class="text-center">
                            編號
                        </th>
                        <th class="text-center">
                            規格
                        </th>
                        <th class="text-center">
                            售出數量
                        </th>
                        <th class="text-center">
                            售出總額
                        </th>
                        <th class="text-center">
                            訂購日期
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(!empty($list)) {
                        foreach($list as $prod) {
                    ?>
                    <tr>
                        <td>
                            {$prod['oid']}
                        </td>
                        <td>
                            <a>
                                {$prod['prodname']}
                            </a>
                        </td>
                        <td class="text-center">{$prod['sku']}</td>
                        <td class="text-left">
                            <?php
                            if(!empty($prod["options"])) {
                                $json_options = json_decode($prod["options"], true);
                                foreach($json_options as $vname => $options) {
                                    ?>
                                    <i style="font-size: 12px;font-weight: 100;"><?php echo $options["attrname"];?>: <em style="color:red;"><?php echo $options["valuename"];?></em></i>
                                    <?php
                                }
                            }
                            ?>
                        </td>
                        <td class="text-center">{$prod['qty']}</td>
                        <td class="text-center">{$prod['total_amount']}</td>
                        <td class="text-center">{:date('Y-m-d', $prod['create_date'])}</td>
                    </tr>
                    <?php } } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <p>
        <div class="row">
            <div class="col-sm-12"><div class="dataTables_paginate paging_simple_numbers">{:$pages}</div></div>
        </div>
        </p>
    </section>
</div>
{include file="common/footer" /}
