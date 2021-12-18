{include file="common/header" /}
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid.min.css">
<link rel="stylesheet" href="/static/admin/plugins/jsgrid/jsgrid-theme.min.css">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">退貨物品明細</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item active">退貨物品明細</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="card">
            <div class="card-body">
                <div class="pageContent">
                    <table class="list order_list table table-bordered table-striped table-sm table-responsive-sm">
                        <thead>
                        <tr>
                            <th>圖片</th>
                            <th>名稱</th>
                            <th>規格</th>
                            <th>單價</th>
                            <th>數量</th>
                            <th>退款金額</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if(!empty($products)) {
                            foreach($products as $prod) {
                                ?>
                                <tr>
                                    <td>
                                        <img src="{:showfile($prod['prodimage'])}" alt="{$prod['prodname']}">
                                    </td>
                                    <td>{$prod['prodname']}</td>
                                    <td>
                                        <?php
                                        if(!empty($prod["options"])) {
                                            $json_options = json_decode($prod["options"], true);
                                            foreach($json_options as $vname => $options) {
                                                ?>
                                                <p class="col-xs-6"><?php echo $options["attrname"];?>: <i><?php echo $options["valuename"];?></i></p>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        $ {$prod['prod_price']}
                                    </td>
                                    <td>{$prod['qty']}</td>
                                    <td>$ {$prod['return_amount']}</td>
                                </tr>
                            <?php } } ?>
                        </tbody>
                        <tr>
                            <td colspan="6">退貨原因: {$order['return_type']}</td>
                        </tr>
                        <tr>
                            <td colspan="6">備註： {$order['remark']}</td>
                        </tr>
                    </table>
                </div>
                <div class="text-left">
                    <a class="btn btn-secondary" href="{:admin_link('Orders/returns')}">返回</a>
                </div>
    </section>

</div>
{include file="common/footer" /}