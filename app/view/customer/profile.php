{include file="public/meta" /}
<link rel="stylesheet" type="text/css" href="/static/front/css/slider-pro.min.css" media="screen"/>
<link rel="stylesheet" type="text/css" href="/static/front/css/examples.css" media="screen"/>
<link rel="stylesheet" type="text/css" href="/static/front/css/list.css">
<link rel="stylesheet" href="/static/front/css/viewer.min.css">
<link rel="stylesheet" type="text/css" href="/static/front/css/user.css">
{include file="public/kefu" /}
<div class="index-box">
    {include file="public/header" /}
    <section class="sub">
        <div class="container">
            <div class="sub-loation">
                <ol class="breadcrumb">
                    <li><a href="{:front_link('Index/index')}">首頁</a></li>
                    <li><a href="#">會員中心</a></li>
                    <li class="active">會員詳細資料</li>
                </ol>
            </div>



        </div>
    </section>
    <section class="member">
        <div class="container">
            <div class="row">
                <div class="col-md-2">
                    <div class="list-group member-nav">
                        <div class="nav-collapse">會員中心<span class="pull-right"><i class="iconfont icon-duiqi04"></i></span></div>
                        {include file="public/customer_menu" /}
                    </div>
                </div>
                <div class="col-md-10">

                    <div class="user-key"><h1>個人詳細資料</h1></div>
                    <div class="member-form ">

                        <form class="AjaxForm form-horizontal row" method="post" action="{:front_link('edit')}">

                            <div class="form-group row col-md-6 clearfix">
                                <label for="inputEmail3" class="col-xs-12 col-md-12 control-label">會員帳號<span class="text-danger">「帳號無法變更」</span></label>
                                <div class="col-xs-12 col-md-12">
                                    <input type="email" disabled class="form-control" id="inputEmail3" placeholder="{:isset($customer['custconemail'])?$customer['custconemail']:''}">

                                </div>
                            </div>
                            <div class="form-group  row col-md-6 clearfix">
                                <label for="inputEmail3" class="col-xs-12 col-md-12 control-label">會員編號<span class="text-danger">「會員編號無法變更」</span></label>
                                <div class="col-xs-12 col-md-12">
                                    <input type="email" disabled class="form-control" id="inputEmail3" placeholder="{:$customer['vipcode']}">

                                </div>
                            </div>
                            <div class="form-group row col-md-6 clearfix">
                                <label for="inputEmail3" class="col-xs-12 col-md-12 control-label">姓名</label>
                                <div class="col-xs-12 col-md-12">
                                    <input type="text" class="form-control" id="inputEmail3" placeholder="{:$customer['fullname']}" name="fullname" value="{:$customer['fullname']}">

                                </div>
                            </div>

                            <div class="form-group row  col-md-6">
                                <label for="birthday" class="col-xs-12 col-md-12 control-label cart-num justify-lg justify-md text-md-right text-sm-right col-xs-12 "><span class="text-danger">*</span>生日</label>
                                <div class="col-xs-4 col-md-4">
                                <select class="form-control" name="birth_year">
                                    <option>出生年</option>
                                    <?php
                                    $years = date("Y");
                                    for($i=$years; $i>=1950; $i--) {
                                        if($i==$customer['birth_year']) {
                                            $selected = 'selected';
                                        } else {
                                            $selected = '';
                                        }
                                    ?>
                                    <option {$selected} value="{$i}">{$i}</option>
                                    <?php } ?>
                                  </select>
                                </div>
                                 <div class="col-xs-4 col-md-4">
                                <select class="form-control" name="birth_month">
                                    <option>出生月</option>
                                    <?php
                                    for($i=1; $i<=12; $i++) {
                                        if($i==$customer['birth_month']) {
                                            $selected = 'selected';
                                        } else {
                                            $selected = '';
                                        }
                                        ?>
                                        <option {$selected} value="{$i}">{$i}</option>
                                    <?php } ?>
                                  </select>
                                </div>
                                 <div class="col-xs-4 col-md-4">
                                <select class="form-control" name="birth_day">
                                    <option>出生日</option>
                                    <?php
                                    for($i=1; $i<=31; $i++) {
                                        if($i==$customer['birth_day']) {
                                            $selected = 'selected';
                                        } else {
                                            $selected = '';
                                        }
                                        ?>
                                        <option {$selected} value="{$i}">{$i}</option>
                                    <?php } ?>
                                  </select>
                                </div>
                            </div>
                            <div class="form-group row col-md-6">
                                <label for="birthday" class="col-xs-12 col-md-12 control-label"><span class="text-danger">*</span>性別</label>
                                <div class="col-xs-12 col-md-12">
                                    <label class="radio-inline">
                                        <input type="radio" name="sex" id="inlineRadio1" value="1" <?php echo $customer['sex']==1?'checked':'';?>> 男
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="sex" id="inlineRadio2" value="2" <?php echo $customer['sex']==2?'checked':'';?>> 女
                                    </label>


                                </div>
                            </div>
                            <div class="form-group row col-md-12 clearfix"></div>
                            <div class="form-group row col-md-6 clearfix">
                                <label for="inputEmail3" class="col-xs-12 col-md-12 control-label">手機載具<span class="text-danger">「範例: /ABC+123」</span></label>
                                <div class="col-xs-12 col-md-12">
                                    <input type="text" class="form-control" id="inputEmail3" placeholder="例如：/N94.F1S" name="invoice_code" value="{:$customer['invoice_code']}">

                                </div>
                            </div>
                            <div class="form-group row col-md-6 clearfix">
                                <label for="inputEmail3" class="col-xs-12 col-md-12 control-label">自然人憑證<span class="text-danger">「共16碼，前2碼為英文，後14碼為數字」</span></label>
                                <div class="col-xs-12 col-md-12">
                                    <input type="text" class="form-control" id="inputEmail3" placeholder="例如：TP03000001234565" name="pc_code" value="{:$customer['pc_code']}">

                                </div>
                            </div>
                            <div class="form-group row col-md-6">
                                <label for="tell" class="col-xs-12 col-md-12 control-label"> 聯絡市話</label>
                                <div class="col-xs-12 col-md-12">
                                    <input type="text"  class="form-control" id="tell" placeholder="例如：02-33336666" name="tel" value="{:$customer['tel']}">
                                </div>
                            </div>
                            <div class="form-group row col-md-6">
                                <label for="phone" class="col-xs-12 col-md-12 control-label">手機號碼</label>
                                <div class="col-xs-12 col-md-12">
                                    <input type="text"  class="form-control" id="phone" placeholder="{:empty($customer['mobile'])?'例如：0922666888':$customer['mobile']}" name="mobile" value="{$customer['mobile']}"><!--<button type="button" onclick="timer(120);" class="btn btn-default btn-small no-margin-l" id="fyz">發送驗證碼<span style="display:none"></span></button>-->
                                </div>
                            </div>

                            <div class="form-group row clearfix col-md-6"></div>
                            <div class="country_dist">
                            <label for="birthday" class="col-xs-12 col-md-12 control-label"><span class="text-danger">*</span>地址</label>
                            <div class="form-group row   col-xs-6 col-md-4  ">
                                <div class="col-xs-12 col-md-12">
                                    <select class="form-control" name="provid">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row    col-xs-6 col-md-4  ">
                                <div class="col-xs-12 col-md-12">
                                    <select class="form-control" name="cityid">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row    col-xs-6 col-md-4">
                                <div class="col-xs-12 col-md-12">
                                    <select class="form-control" name="areaid">
                                    </select>
                                </div>
                            </div>
                            </div>
                            <div class="form-group row   col-xs-6 col-md-4 ">
                                <div class="col-xs-12 col-md-12">
                                    <input type="text"  class="form-control" id="postcode" name="postcode" value="{:$customer['postcode']}" placeholder="郵遞區號">
                                </div>
                            </div>

                            <div class="form-group row   col-xs-12 col-md-8 ">
                                <div class="col-xs-12 col-md-12">
                                    <input type="text"  class="form-control" id="birthday" name="address" value="{:$customer['address']}" placeholder="例如：仁愛路10巷10號10F">
                                </div>
                            </div>

                            <div class="form-group row col-md-12" style="clear:both;">
                                <div class=" col-xs-12 col-md-12">
                                    <button type="submit" class="btn btn-default no-margin-l">儲存變更</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="user-key"><h1>常用收件地址</h1></div>
                    <div class="member-form">


                        <div class="clearfix">  <button type="submit" class="btn btn-default no-margin-l add-loc">新增收件地址</button></div>
                        <div class="location-form">
                            <form class="AjaxForm form-horizontal row" method="post" action="{:front_link('saveAddress')}">
                                <input type="hidden" id="addr_addrid" name="addrid" value="0">
                                <div class="form-group row  col-md-6">
                                    <label for="inputEmail3" class="col-xs-12 col-md-12 control-label"><span class="text-danger">*</span>收件人</label>
                                    <div class="col-xs-12 col-md-12">
                                        <input type="text"  class="form-control" id="addr_fullname" name="fullname" placeholder="例如：小白">

                                    </div>
                                </div>
                                <div class="form-group row  col-md-6">
                                    <label for="tell" class="col-xs-12 col-md-12 control-label"><span class="text-danger">*</span> 聯絡市話</label>
                                    <div class="col-xs-12 col-md-12">
                                        <input type="text"  class="form-control" id="addr_tel" name="tel" placeholder="例如：02-33336666">
                                    </div>
                                </div>
                                <div class="form-group row  col-md-6">
                                    <label for="tell" class="col-xs-12 col-md-12 control-label"><span class="text-danger">*</span> 手機號碼</label>
                                    <div class="col-xs-12 col-md-12">
                                        <input type="text"  class="form-control" id="addr_mobile" name="mobile" placeholder="">
                                    </div>
                                </div>
                                <div class="cityarea form-group row  col-md-6">
                                    <label for="birthday" class="col-xs-12 col-md-12 control-label"><span class="text-danger">*</span>地址</label>
                                    <div class="col-xs-6 col-md-4 pull-left ">
                                        <select class="form-control" id="addr_provid" name="provid">
                                        </select>
                                    </div>
                                    <div class="col-xs-6 col-md-4 pull-left ">
                                        <select class="form-control" id="addr_cityid" name="cityid">
                                        </select>
                                    </div>
                                    <div class="col-xs-6 col-md-4 pull-left">
                                        <select class="form-control" id="addr_areaid" name="areaid">
                                        </select>
                                    </div>



                                </div>
                                <div class="form-group row col-xs-12 col-md-6">
                                    <label for="loc" class="col-xs-12 col-md-12 control-label"><span class="text-danger">*</span> 詳細地址</label>
                                    <div class="col-xs-12 col-md-12">
                                        <input type="text"  class="form-control" id="addr_address" name="address" placeholder="例如：仁愛路10巷10號10F">
                                    </div>
                                </div>
                                <div class="form-group row col-xs-12 col-md-6">
                                    <label for="loc" class="col-xs-12 col-md-12 control-label">郵遞區號</label>
                                    <div class="col-xs-12 col-md-12">
                                        <input type="text"  class="form-control" id="addr_postcode" name="postcode" placeholder="郵遞區號">
                                    </div>
                                </div>

                                <div class="form-group  col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-md-12">
                                        <input type="checkbox" name="is_default" value="1"> 設為常用地址
                                    </div>
                                </div>
                                <div class="form-group  col-xs-12 col-md-12">

                                    <button type="submit" class="btn btn-default no-margin-l">儲存變更</button>

                                </div>

                            </form>
                        </div>
                        <div class="table-set">

                            <?php
                            $jsonAddress = [];
                            if(!empty($customer_addresses["list"])) {
                                foreach($customer_addresses["list"] as $info) {
                                    $address = '';
                                    $address .= isset($countryName[$info["provid"]])?$countryName[$info["provid"]]:'';
                                    $address .= isset($countryName[$info["cityid"]])?$countryName[$info["cityid"]]:'';
                                    $address .= isset($countryName[$info["areaid"]])?$countryName[$info["areaid"]]:'';
                                    $address .= $info["address"];
                                    $jsonAddress[$info["addrid"]] = $info;
                            ?>
                            <div class="location-table row text-center no-gutter">
                                <div class="col-xs-2 col-md-2">
                                    <dl>
                                        <dt>收件人</dt>
                                        <dd class="justify">{:$info['fullname']}</dd>
                                    </dl>
                                </div>
                                <div class="col-xs-2 col-md-2">
                                    <dl>
                                        <dt>電話</dt>
                                        <dd class="justify">{:$info['tel']}</dd>
                                    </dl></div>
                                <div class="col-xs-5 col-md-5">
                                    <dl>
                                        <dt>地址</dt>
                                        <dd class="justify">
                                            {:$info['is_default']?'【常用】':''}{:$address}
                                        </dd>
                                    </dl>
                                </div>
                                <div class="col-xs-3 col-md-3">
                                    <dl>
                                        <dt>功能</dt>
                                        <dd class="justify"><button onclick="edit_shipping_adress({$info['addrid']})" class="btn btn-blue btn-sm">修改</button><button href="{:front_link('removeAddress', ['addrid' => $info['addrid']])}" class="AjaxTodo btn btn-danger btn-sm">刪除</button></dd>
                                    </dl>
                                </div>
                            </div>
                            <?php } } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {include file="public/footer" /}
</div>
</div>
<script src="/static/front/js/bootstrap-datetimepicker.min.js"></script>
{include file="public/city" /}
<script>
    $("select[name=areaid]").change(function() {
        var id = $(this).val();
        getPostcode(id, 'postcode');
    })

    $("#addr_areaid").change(function() {
        var id = $(this).val();
        getPostcode(id, 'addr_postcode');
    })

</script>
<script>
    var jsonAddress = {:toJSON($jsonAddress, true)};
    $(".cityarea").distpicker();
    function edit_shipping_adress(addrid) {
            json = jsonAddress[addrid];
            $("#addr_addrid").val(json.addrid);
            $("#addr_fullname").val(json.fullname);
            $("#addr_tel").val(json.tel);
            $("#addr_mobile").val(json.mobile);
            $("#addr_provid").val(json.provid).change();
            $("#addr_cityid").val(json.cityid).change();
            $("#addr_areaid").val(json.areaid);
            $("#addr_postcode").val(json.postcode);
            $("#addr_address").val(json.address);
            if(json.is_default) {
                $("input[name=is_default]").attr("checked", true);
            }
            $(".location-form").show();

    }
</script>
<script>

    $('#txt_birthday').datetimepicker({
        format: 'yyyy-mm-dd',
        showTodayButton:true,
        language: 'zh-TW',
        viewMode: 'days',
        minView: "month",
        autoclose : 'true',
    });
</script>
<script>
    function timer(time) {
        var btn = $("#fyz");
        $.getJSON('{:front_link("sendSmsCode")}', {phone:$("#phone").val()}, function(json) {
            if(json.code>0) {
                layer.msg(json.msg);
                btn.attr("disabled", false);
            } else {
                btn.attr("disabled", true); //按鈕禁止點擊
                btn.val(time <= 0 ? "發送驗證碼" : ("" + (time) + ""));
                var hander = setInterval(function() {
                    if (time <= 0) {
                        clearInterval(hander); //清除倒數計時
                        btn.val("發送驗證碼");
                        btn.attr("disabled", false);
                        btn.find("span").hide();
                        return false;
                    }else {
                        btn.find("span").show();
                        btn.find("span").html("(" + (time--) + ")");
                    }
                }, 1000);

                layer.prompt({title: '請輸入手機簡訊驗證碼', formType: 3}, function(smscode, index){

                    $.getJSON('{:front_link("updatePhone")}', {phone:$("#phone").val(), smscode:smscode}, function(json) {
                        if(json.code>0) {
                            layer.msg(json.msg);
                        } else {
                            layer.msg(json.msg, {end: function () {
                                    layer.close(index);
                            }});
                        }
                    })
                });
            }
        })
    }
    //調用方法
    //timer(30);
</script>
</body>
</html>