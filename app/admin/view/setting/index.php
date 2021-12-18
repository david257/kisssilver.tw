{include file="common/header" /}
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">參數設置</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item active">參數設置</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="card">
            <div class="card-body">
                <form method="post" action="<?php echo admin_link('setting/save'); ?>" class="AjaxForm">
                <div class="card card-primary card-outline">
                <div class="card-body">
                    <div class="row">
                        <div class="col-7 col-sm-9">
                            <div class="tab-content" id="vert-tabs-right-tabContent">
                                <div class="tab-pane fade active show" id="vert-tabs-right-base" role="tabpanel" aria-labelledby="vert-tabs-right-base-tab">
                                    <table class="table table-bordered table-striped table-sm table-responsive-sm">
                                        <tr>
                                            <td class="text-right" valign="top" style="width: 120px">網站名稱:</td>
                                            <td>
                                                <input type="text" size="30" class="form-control" name="setting[site][title]" value="<?php echo $setting["site"]["title"]??'';?>"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" valign="top" style="width: 120px">SEO關鍵詞:</td>
                                            <td>
                                                <input type="text" size="30" class="form-control" name="setting[site][meta_keywords]" value="<?php echo $setting["site"]["meta_keywords"]??'';?>"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" valign="top" style="width: 120px">SEO描述:</td>
                                            <td>
                                                <input type="text" size="30" class="form-control" name="setting[site][meta_desc]" value="<?php echo $setting["site"]["meta_desc"]??'';?>"/>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="vert-tabs-right-home" role="tabpanel" aria-labelledby="vert-tabs-right-home-tab">
                                    <table class="table table-bordered table-striped table-sm table-responsive-sm">
                                        <tr>
                                            <td class="text-right" valign="top" style="width: 120px">發貨人名稱:</td>
                                            <td>
                                                <input type="text" size="30" class="form-control" name="setting[express][name]" value="<?php echo $setting["express"]["name"]??'';?>"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" valign="top" style="width: 120px">市話:</td>
                                            <td>
                                                <input type="text" size="30" class="form-control" name="setting[express][tel]" value="<?php echo $setting["express"]["tel"]??'';?>"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" valign="top" style="width: 120px">手機:</td>
                                            <td>
                                                <input type="text" size="30" class="form-control" name="setting[express][mobile]" value="<?php echo $setting["express"]["mobile"]??'';?>"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" valign="top" style="width: 120px">郵遞區號:</td>
                                            <td>
                                                <input type="text" size="30" class="form-control" name="setting[express][zipcode]" value="<?php echo $setting["express"]["zipcode"]??'';?>"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" valign="top" style="width: 120px">發貨地址:</td>
                                            <td>
                                                <input type="text" size="30" class="form-control" name="setting[express][address]" value="<?php echo $setting["express"]["address"]??'';?>"/>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="tab-pane fade" id="vert-tabs-right-profile" role="tabpanel" aria-labelledby="vert-tabs-right-profile-tab">
                                    <h2>金流金鑰</h2>
                                    <table class="table table-bordered table-striped table-sm table-responsive-sm">
                                        <tr>
                                            <td class="text-right" style="width: 120px">正式環境：</td>
                                            <td class=""><input type="checkbox" name="setting[ecpay][isProd]" <?php echo (isset($setting["ecpay"]["isProd"]) && $setting["ecpay"]["isProd"])?"checked":"";?> value="1"/></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" style="width: 120px">API類型：</td>
                                            <td class="">
                                                <input type="radio" name="setting[ecpay][LogisticsCVSSubTypes]" <?php echo (isset($setting["ecpay"]["LogisticsCVSSubTypes"]) && $setting["ecpay"]["LogisticsCVSSubTypes"]=="B2C")?"checked":"";?> value="B2C"/>B2C
                                                <input type="radio" name="setting[ecpay][LogisticsCVSSubTypes]" <?php echo (isset($setting["ecpay"]["LogisticsCVSSubTypes"]) && $setting["ecpay"]["LogisticsCVSSubTypes"]=="C2C")?"checked":"";?> value="C2C"/>C2C
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" style="width: 120px">HashKey：</td><td><input type="text" size="30" class="form-control" name="setting[pay][hashkey]" value="<?php echo $setting["pay"]["hashkey"]??'';?>"/></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" style="width: 120px">HashIV：</td><td><input type="text" size="30" class="form-control" name="setting[pay][hashiv]" value="<?php echo $setting["pay"]["hashiv"]??'';?>"/></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" style="width: 120px">MerchantID：</td><td><input type="text" size="30" class="form-control" name="setting[pay][merchantid]" value="<?php echo $setting["pay"]["merchantid"]??'';?>"/></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" style="width: 120px">付款方式：</td><td>
                                                <table>
                                                    <tr>
                                                        <th>啟用</th><th>付款方式</th><th>啟用滿額免運費</th>
                                                    </tr>
                                                    <?php
                                                    $paytypes = get_pay_types();
                                                    if(!empty($paytypes)) {
                                                    foreach($paytypes as $type => $typeRow) {
                                                        if($typeRow["state"]) {
                                                            if(isset($setting["pay"]['types']) && in_array($type,$setting["pay"]['types'])) {
                                                                $checked = 'checked';
                                                            } else {
                                                                $checked = '';
                                                            }

                                                            if(isset($setting["paytype_freeshipping"][$type]) && $setting["paytype_freeshipping"][$type]) {
                                                                $tchecked = 'checked';
                                                            } else {
                                                                $tchecked = '';
                                                            }
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <input type="checkbox" {$checked} name="setting[pay][types][]" value="{$type}"/>
                                                            </td>
                                                            <td>
                                                                {$typeRow['title']}
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" {$tchecked} name="setting[paytype_freeshipping][{$type}]" value="1"/>
                                                            </td>
                                                        </tr>
                                                        <?php } } } ?>
                                                    </table>
                                            </td>
                                        </tr>
                                    </table>
                                    <h2>物流金鑰</h2>
                                    <table class="table table-bordered table-striped table-sm table-responsive-sm">
                                        <tr>
                                            <td class="text-right" style="width: 120px">正式環境：</td>
                                            <td class=""><input type="checkbox" name="setting[express][isProd]" <?php echo (isset($setting["express"]["isProd"]) && $setting["express"]["isProd"])?"checked":"";?> value="1"/></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" style="width: 120px">HashKey：</td><td><input type="text" size="30" class="form-control" name="setting[express][hashkey]" value="<?php echo isset($setting["express"])?$setting["express"]["hashkey"]:"";?>"/></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" style="width: 120px">HashIV：</td><td><input type="text" size="30" class="form-control" name="setting[express][hashiv]" value="<?php echo isset($setting["express"])?$setting["express"]["hashiv"]:"";?>"/></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" style="width: 120px">MerchantID：</td><td><input type="text" size="30" class="form-control" name="setting[express][merchantid]" value="<?php echo isset($setting["express"])?$setting["express"]["merchantid"]:"";?>"/></td>
                                        </tr>
                                    </table>
                                    <h2>電子發票</h2>
                                    <table class="table table-bordered table-striped table-sm table-responsive-sm">
                                        <tr>
                                            <td class="text-right" style="width: 120px">正式環境：</td>
                                            <td class=""><input type="checkbox" name="setting[invoice][isProd]" <?php echo (isset($setting["invoice"]["isProd"]) && $setting["invoice"]["isProd"])?"checked":"";?> value="1"/></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" style="width: 120px">HashKey：</td><td><input type="text" size="30" class="form-control" name="setting[invoice][hashkey]" value="<?php echo isset($setting["invoice"])?$setting["invoice"]["hashkey"]:"";?>"/></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" style="width: 120px">HashIV：</td><td><input type="text" size="30" class="form-control" name="setting[invoice][hashiv]" value="<?php echo isset($setting["invoice"])?$setting["invoice"]["hashiv"]:"";?>"/></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" style="width: 120px">MerchantID：</td><td><input type="text" size="30" class="form-control" name="setting[invoice][merchantid]" value="<?php echo isset($setting["invoice"])?$setting["invoice"]["merchantid"]:"";?>"/></td>
                                        </tr>

                                        <tr>
                                            <td class="text-right" style="width: 120px">課稅類別：</td>
                                            <td>
                                                <?php
                                                $taxtypes = get_tax_types();
                                                foreach($taxtypes as $taxtype => $info) {
                                                    if((isset($setting["invoice"]) && $setting["invoice"]["taxtype"]==$taxtype)) {
                                                        $checked  = 'checked';
                                                    } else {
                                                        $checked = '';
                                                    }
                                                ?>
                                                <input type="radio" {$checked} name="setting[invoice][taxtype]" value="{$taxtype}"/> {$info['title']}
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" valign="top" style="width: 120px">捐贈單位設定:</td>
                                            <td>
                                                <textarea placeholder="一家單位一行" name="setting[common][invoice_to]" class="form-control" cols="50" rows="10"><?php echo isset($setting["common"]["invoice_to"])?$setting["common"]["invoice_to"]:"";?></textarea>
                                                <div>一條記錄一行，記錄由兩部分組成：捐贈碼+名稱，捐贈碼與名稱之間用豎線分隔,格式：捐贈碼|機關或團體名稱，例如: 168001|OMG 關懷社會愛心基金會 )</div>
                                                <a target="_blank" href="https://www.einvoice.nat.gov.tw/APCONSUMER/BTC603W/">捐贈碼查詢</a>
                                            </td>
                                        </tr>

                                    </table>

                                </div>
                                <div class="tab-pane fade" id="vert-tabs-right-credit" role="tabpanel" aria-labelledby="vert-tabs-right-credit-tab">
                                    <table class="table table-bordered table-striped table-sm table-responsive-sm">
                                        <tr>
                                            <td class="text-right" style="width: 120px">金額轉紅利點數比例:</td><td><input type="text" size="30" class="form-control" name="setting[creditsRate][moneyToCredit]" value="<?php echo $setting["creditsRate"]["moneyToCredit"]??'';?>"/></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" style="width: 120px">紅利點數轉金額比例:</td><td><input type="text" size="30" class="form-control" name="setting[creditsRate][CreditToMoney]" value="<?php echo $setting["creditsRate"]["CreditToMoney"]??'';?>"/></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" style="width: 120px">下單購物贈送紅利點數比例:</td><td><input type="text" size="30" class="form-control" name="setting[creditsRate][orderToCredit]" value="<?php echo $setting["creditsRate"]["orderToCredit"]??'';?>"/></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="vert-tabs-right-returntype" role="tabpanel" aria-labelledby="vert-tabs-right-returntype-tab">
                                    <table class="table table-bordered table-striped table-sm table-responsive-sm">
                                        <tr>
                                            <td class="text-right" valign="top" style="width: 120px">退貨類型:</td>
                                            <td>
                                                <textarea placeholder="一種類型一行" name="setting[common][return_type]" class="form-control" cols="50" rows="10"><?php echo isset($setting["common"]["return_type"])?$setting["common"]["return_type"]:"";?></textarea>
                                                (一種類型一行)</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="vert-tabs-right-fbfans" role="tabpanel" aria-labelledby="vert-tabs-right-fbfans-tab">
                                    <table class="table table-bordered table-striped table-sm table-responsive-sm">
                                        <tr>
                                            <td class="text-right" valign="top" style="width: 120px">臉書粉絲團代碼:</td>
                                            <td>
                                                <textarea placeholder="貼入臉書的粉絲團代碼" name="setting[common][fbfans]" class="form-control" cols="50" rows="10"><?php echo isset($setting["common"]["fbfans"])?$setting["common"]["fbfans"]:"";?></textarea>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="vert-tabs-right-line" role="tabpanel" aria-labelledby="vert-tabs-right-line-tab">
                                    <table class="table table-bordered table-striped table-sm table-responsive-sm">
                                        <tr>
                                            <td class="text-right" valign="top" style="width: 120px">Line客服:</td>
                                            <td>
                                                <input type="text" placeholder="Line客服連接" name="setting[common][line]" value="<?php echo isset($setting["common"]["line"])?$setting["common"]["line"]:"";?>" class="form-control">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="vert-tabs-right-messages" role="tabpanel" aria-labelledby="vert-tabs-right-messages-tab">
                                    <table class="table table-bordered table-striped table-sm table-responsive-sm">
                                        <tr>
                                            <td class="text-right" style="width: 120px">App Id:</td><td><input type="text" size="30" class="form-control" name="setting[facebook][appid]" value="<?php echo $setting["facebook"]["appid"]??'';?>"/></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" style="width: 120px">密鑰:</td><td><input type="text" size="30" class="form-control" name="setting[facebook][secret]" value="<?php echo $setting["facebook"]["secret"]??'';?>"/></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="vert-tabs-right-google" role="tabpanel" aria-labelledby="vert-tabs-right-google-tab">
                                    <table class="table table-bordered table-striped table-sm table-responsive-sm">
                                        <tr>
                                            <td class="text-right" style="width: 120px">Client Id:</td><td><input type="text" size="30" class="form-control" name="setting[google][clientid]" value="<?php echo $setting["google"]["clientid"]??'';?>"/></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" style="width: 120px">Secret:</td><td><input type="text" size="30" class="form-control" name="setting[google][secret]" value="<?php echo $setting["google"]["secret"]??'';?>"/></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="vert-tabs-right-smtp" role="tabpanel" aria-labelledby="vert-tabs-right-smtp-tab">
                                    <table class="table table-bordered table-striped table-sm table-responsive-sm">
                                        <tr>
                                            <td class="text-right" valign="top" style="width: 120px">服務器地址:</td>
                                            <td><input type="text" size="30" class="form-control" name="setting[smtp][host]" value="<?php echo $setting["smtp"]["host"]??'';?>"/></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" valign="top" style="width: 120px">帳號:</td>
                                            <td><input type="text" size="30" class="form-control" name="setting[smtp][username]" value="<?php echo $setting["smtp"]["username"]??'';?>"/></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" valign="top" style="width: 120px">密碼:</td>
                                            <td><input type="password" size="30" class="form-control" name="setting[smtp][password]" value="<?php echo $setting["smtp"]["password"]??'';?>"/></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" valign="top" style="width: 120px">端口:</td>
                                            <td><input type="text" size="30" class="form-control" name="setting[smtp][port]" value="<?php echo $setting["smtp"]["port"]??'';?>"/></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" valign="top" style="width: 120px">加密協議:</td>
                                            <td><input type="text" size="30" class="form-control" name="setting[smtp][smtp_secure]" value="<?php echo $setting["smtp"]["smtp_secure"]??'';?>"/></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" valign="top" style="width: 120px">開啟安全校驗:</td>
                                            <td><input type="checkbox" name="setting[smtp][smtp_auth]" <?php echo (isset($setting["smtp"]["smtp_auth"])&&$setting["smtp"]["smtp_auth"])?'checked':'';?> value="1"/></td>
                                        </tr>
										<tr>
                                            <td class="text-right" valign="top" style="width: 120px">補貨通知郵箱:</td>
                                            <td>
                                                <input type="text" size="30" class="form-control" name="setting[notify][nostock]" value="<?php echo $setting["notify"]["nostock"]??'';?>"/>(多個郵箱請用英文逗號隔開)
                                                <br/>
                                                <input type="checkbox" name="setting[notify][nostock_state]" <?php echo (isset($setting["notify"]["nostock_state"]) && $setting["notify"]["nostock_state"])?'checked':'';?> value="1"/> 啟用
                                            </td>
                                        </tr>
										<tr>
                                            <td class="text-right" valign="top" style="width: 120px">訂單通知郵箱:</td>
                                            <td><input type="text" size="30" class="form-control" name="setting[notify][order]" value="<?php echo $setting["notify"]["order"]??'';?>"/>(多個郵箱請用英文逗號隔開)</td>
                                        </tr>
                                        <tr><td></td><td><a class="AjaxTodo btn btn-success" href="{:admin_link('Email/test_ping')}">測試連通性</a></td></tr>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="vert-tabs-right-sms" role="tabpanel" aria-labelledby="vert-tabs-right-sms-tab">
                                    <table class="table table-bordered table-striped table-sm table-responsive-sm">
                                        <tr>
                                            <td class="text-right" valign="top" style="width: 120px">帳號:</td>
                                            <td><input type="text" size="30" class="form-control" name="setting[sms][username]" value="<?php echo $setting["sms"]["username"]??'';?>"/></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" valign="top" style="width: 120px">密碼:</td>
                                            <td><input type="password" size="30" class="form-control" name="setting[sms][passwd]" value="<?php echo $setting["sms"]["passwd"]??'';?>"/></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="vert-tabs-right-shipfee" role="tabpanel" aria-labelledby="vert-tabs-right-shipfee-tab">
                                    <div class="alert alert-info">預設值一旦改變會自動變更所有產品的固定運費</div>
                                    <table class="table table-bordered table-striped table-sm table-responsive-sm">
                                        <tr>
                                            <td class="text-right" valign="top" style="width: 120px">運費預設值:</td>
                                            <td><input type="number" size="30" class="form-control" name="setting[shipping][fee]" value="<?php echo $setting["shipping"]["fee"]??'0';?>"/></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="vert-tabs-right-se" role="tabpanel" aria-labelledby="vert-tabs-right-se-tab">
                                    <table class="table table-bordered table-striped table-sm table-responsive-sm">
                                        <tr>
                                            <td class="text-right" valign="top" style="width: 120px">門市地址:</td>
                                            <td><textarea class="form-control" maxlength="300" name="setting[se][address]"><?php echo $setting["se"]["address"]??'';?></textarea></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="vert-tabs-right-homeblock" role="tabpanel" aria-labelledby="vert-tabs-right-homeblock-tab">
                                    <table class="table table-bordered table-striped table-sm table-responsive-sm">
                                        <tr>
                                            <td class="text-right" valign="top" style="width: 120px">新品推薦:</td>
                                            <td><input type="text" maxlength="255" class="form-control" name="setting[homeblock][new]" value="<?php echo $setting["homeblock"]["new"]??'';?>"/></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" valign="top" style="width: 120px">熱銷商品:</td>
                                            <td><input type="text" maxlength="255" class="form-control" name="setting[homeblock][hot]" value="<?php echo $setting["homeblock"]["hot"]??'';?>"/></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" valign="top" style="width: 120px">促銷商品:</td>
                                            <td><input type="text" maxlength="255" class="form-control" name="setting[homeblock][sale]" value="<?php echo $setting["homeblock"]["sale"]??'';?>"/></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="vert-tabs-right-stocksys" role="tabpanel" aria-labelledby="vert-tabs-right-stocksys-tab">
                                    <table class="table table-bordered table-striped table-sm table-responsive-sm">
                                        <tr>
                                            <td class="text-right" valign="top" style="width: 120px">訂單接收網址:</td>
                                            <td><input type="text" maxlength="255" class="form-control" name="setting[stocksys][url]" value="<?php echo $setting["stocksys"]["url"]??'';?>"/></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" valign="top" style="width: 120px">庫存查詢網址:</td>
                                            <td><input type="text" maxlength="255" class="form-control" name="setting[stocksys][stock_sync_url]" value="<?php echo $setting["stocksys"]["stock_sync_url"]??'';?>"/></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" valign="top" style="width: 120px">滿足推送的訂單狀態:</td>
                                            <td>
                                                <?php
                                                $orderStates = get_order_states();
                                                unset($orderStates[99]);
                                                if(!empty($orderStates)) {
                                                    foreach($orderStates as $k => $v) {
                                                        if($k>=0) {
                                                        if(isset($setting["stocksys"]['order_push_states']) && is_array($setting["stocksys"]['order_cancel_states']) && in_array($k,$setting["stocksys"]['order_push_states'])) {
                                                            $checked = 'checked';
                                                        } else {
                                                            $checked = '';
                                                        }
                                                ?>
                                                <input type="checkbox" {$checked} name="setting[stocksys][order_push_states][]" value="<?php echo $k;?>"/> <?php echo $v;?>
                                                <?php } } } ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" valign="top" style="width: 120px">滿足取消的訂單狀態:</td>
                                            <td>
                                                <?php
                                                if(!empty($orderStates)) {
                                                    foreach($orderStates as $k => $v) {
                                                        if($k<0) {
                                                        if(isset($setting["stocksys"]['order_cancel_states']) && is_array($setting["stocksys"]['order_cancel_states']) && in_array($k,$setting["stocksys"]['order_cancel_states'])) {
                                                            $checked = 'checked';
                                                        } else {
                                                            $checked = '';
                                                        }
                                                        ?>
                                                        <input type="checkbox" {$checked} name="setting[stocksys][order_cancel_states][]" value="<?php echo $k;?>"/> <?php echo $v;?>
                                                    <?php } } } ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-5 col-sm-3">
                            <div class="nav flex-column nav-tabs nav-tabs-right h-100" id="vert-tabs-right-tab" role="tablist" aria-orientation="vertical">
                                <a class="nav-link active" id="vert-tabs-right-base-tab" data-toggle="pill" href="#vert-tabs-right-base" role="tab" aria-controls="vert-tabs-right-base" aria-selected="true">基本信息</a>
                                <a class="nav-link" id="vert-tabs-right-home-tab" data-toggle="pill" href="#vert-tabs-right-home" role="tab" aria-controls="vert-tabs-right-home" aria-selected="true">物流發貨信息</a>
                                <a class="nav-link" id="vert-tabs-right-profile-tab" data-toggle="pill" href="#vert-tabs-right-profile" role="tab" aria-controls="vert-tabs-right-profile" aria-selected="false">綠界設置</a>
                                <a class="nav-link" id="vert-tabs-right-credit-tab" data-toggle="pill" href="#vert-tabs-right-credit" role="tab" aria-controls="vert-tabs-right-credit" aria-selected="false">購物點數轉換比例</a>
                                <a class="nav-link" id="vert-tabs-right-returntype-tab" data-toggle="pill" href="#vert-tabs-right-returntype" role="tab" aria-controls="vert-tabs-right-returntype" aria-selected="false">退貨類型</a>
                                <a class="nav-link" id="vert-tabs-right-fbfans-tab" data-toggle="pill" href="#vert-tabs-right-fbfans" role="tab" aria-controls="vert-tabs-right-fbfans" aria-selected="false">底部粉絲團</a>
                                <a class="nav-link" id="vert-tabs-right-line-tab" data-toggle="pill" href="#vert-tabs-right-line" role="tab" aria-controls="vert-tabs-right-line" aria-selected="false">Line客服</a>
                                <a class="nav-link" id="vert-tabs-right-messages-tab" data-toggle="pill" href="#vert-tabs-right-messages" role="tab" aria-controls="vert-tabs-right-messages" aria-selected="false">Facebook登錄</a>
                                <a class="nav-link" id="vert-tabs-right-google-tab" data-toggle="pill" href="#vert-tabs-right-google" role="tab" aria-controls="vert-tabs-right-google" aria-selected="false">GOOGLE登錄</a>
                                <a class="nav-link" id="vert-tabs-right-smtp-tab" data-toggle="pill" href="#vert-tabs-right-smtp" role="tab" aria-controls="vert-tabs-right-smtp" aria-selected="false">SMTP設置</a>
                                <a class="nav-link" id="vert-tabs-right-sms-tab" data-toggle="pill" href="#vert-tabs-right-sms" role="tab" aria-controls="vert-tabs-right-sms" aria-selected="false">簡訊帳號設置</a>
                                <a class="nav-link" id="vert-tabs-right-shipfee-tab" data-toggle="pill" href="#vert-tabs-right-shipfee" role="tab" aria-controls="vert-tabs-right-shipfee" aria-selected="false">運費預設值</a>
                                <a class="nav-link" id="vert-tabs-right-se-tab" data-toggle="pill" href="#vert-tabs-right-se" role="tab" aria-controls="vert-tabs-right-se" aria-selected="false">門市取貨地址</a>
                                <a class="nav-link" id="vert-tabs-right-homeblock-tab" data-toggle="pill" href="#vert-tabs-right-homeblock" role="tab" aria-controls="vert-tabs-right-homeblock" aria-selected="false">首頁分隔介紹</a>
                                <a class="nav-link" id="vert-tabs-right-stocksys-tab" data-toggle="pill" href="#vert-tabs-right-stocksys" role="tab" aria-controls="vert-tabs-right-stocksys" aria-selected="false">庫存管理同步設置</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">儲存</button>
                    </div>
                </form>
            </div>
    </section>
</div>
{include file="common/footer" /}
