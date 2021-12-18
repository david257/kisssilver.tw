{include file="common/header" /}
<link rel="stylesheet" href="/static/js/layui/css/layui.css"  media="all">
<link rel="stylesheet" href="/static/js/ztree/css/zTreeStyle/zTreeStyle.css" type="text/css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .layui-upload-img { width: 90px; height: 90px; margin: 0; }
    .pic-more { width:100%; left; margin: 10px 0px 0px 0px;}
    .pic-more li { width:90px; float: left; margin-right: 5px;}
    .pic-more li .layui-input { display: initial; }
    .pic-more li a { position: absolute; top: 0; display: block; }
    .pic-more li a i { font-size: 24px; background-color: #008800; }
    #slide-pc-priview .item_img img{ width: 90px; height: 90px;}
    #slide-pc-priview li{position: relative;}
    #slide-pc-priview li .operate{ color: #000; display: none;}
    #slide-pc-priview li .toleft{ position: absolute;top: 40px; left: 1px; cursor:pointer;}
    #slide-pc-priview li .toright{ position: absolute;top: 40px; right: 1px;cursor:pointer;}
    #slide-pc-priview li .close{position: absolute;top: 5px; right: 5px;cursor:pointer;}
    #slide-pc-priview li:hover .operate{ display: block;}
    #attrlist .row {
        margin: 3px 0;
    }

</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">新增/編輯商品</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{:admin_link('Index/index')}">首頁</a></li>
                        <li class="breadcrumb-item"><a href="{:admin_link('Product/index')}">商品列表</a></li>
                        <li class="breadcrumb-item active">新增/編輯商品</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-12 padding10">
        <form class="AjaxForm" action="{:admin_link('save')}" method="post" role="form">
        <div class="card  card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="false">基本資料</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="true">圖片</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-video-tab" data-toggle="pill" href="#custom-tabs-one-video" role="tab" aria-controls="custom-tabs-one-video" aria-selected="true">影片</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-messages-tab" data-toggle="pill" href="#custom-tabs-one-messages" role="tab" aria-controls="custom-tabs-one-messages" aria-selected="false">商品規格</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-shuxing-tab" data-toggle="pill" href="#custom-tabs-one-shuxing" role="tab" aria-controls="custom-tabs-shuxing" aria-selected="false">商品屬性</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-settings-tab" data-toggle="pill" href="#custom-tabs-one-settings" role="tab" aria-controls="custom-tabs-one-settings" aria-selected="false">SEO</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-pages-tab" data-toggle="pill" href="#custom-tabs-one-pages" role="tab" aria-controls="custom-tabs-one-pages" aria-selected="false">連接設置</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                    <input type="hidden" name="prodid" value="{:isset($prod['prodid'])?$prod['prodid']:0}">
                    <div class="tab-pane fade active show" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">產品分類</label>
                            <div class="col-sm-10">
                                <ul id="categoryObj" class="ztree" style="border:1px solid #ddd;height: 300px;overflow: scroll"></ul>
                                <input type="hidden" name="cateIds" value=""/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">產品名稱</label>
                            <div class="col-sm-10"><input type="text" name="prodname" value="{:isset($prod['prodname'])?$prod['prodname']:''}" class="form-control" placeholder="產品名稱" maxlength="255"></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">產品特性</label>
                            <div class="col-sm-10"><input type="text" name="prod_features" value="{:isset($prod['prod_features'])?$prod['prod_features']:''}" class="form-control" placeholder="產品特性" maxlength="1000"></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">產品編號</label>
                            <div class="col-sm-10"><input type="text" name="prodcode" value="{:isset($prod['prodcode'])?$prod['prodcode']:''}" class="form-control" placeholder="產品編號" maxlength="64"></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">銷售價</label>
                            <div class="col-sm-10"><input type="number" min="0.00" step="0.01" max="100000000" name="prod_price" value="{:isset($prod['prod_price'])?$prod['prod_price']:'0.00'}" class="form-control" placeholder="銷售價"></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">零售價</label>
                            <div class="col-sm-10"><input type="number" min="0.00" step="0.01" max="100000000" name="prod_list_price" value="{:isset($prod['prod_list_price'])?$prod['prod_list_price']:'0.00'}" class="form-control" placeholder="零售價"></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">詳細規格</label>
                            <div class="col-sm-10">
                                <textarea name="prod_desc" maxlength="65535" id="editor">{:isset($prod['prod_desc'])?$prod['prod_desc']:""}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">現有庫存</label>
                            <div class="col-sm-10"><input type="number" min="0" max="100000000" name="stock" value="{:isset($prod['stock'])?$prod['stock']:0}" class="form-control" placeholder="現有庫存"></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">安全庫存</label>
                            <div class="col-sm-10"><input type="number" min="0" max="100000000" name="stock_warning" value="{:isset($prod['stock_warning'])?$prod['stock_warning']:0}" class="form-control" placeholder="安全庫存數，庫存低於此數會觸發郵件通知"></div>
                        </div>
                        <div class="form-group row" style="display:none">
                            <label class="col-sm-2 col-form-label">運費類型</label>
                            <div class="col-sm-10">
                                <select name="shipping_fee_type" class="form-control">
                                    <?php
                                    $shipping_types = get_shipping_types();
                                    if(!empty($shipping_types)) {
                                        foreach($shipping_types as $shiptype => $row) {
                                        if(isset($prod['shipping_fee_type']) && $shiptype==$prod['shipping_fee_type']) {
                                            $selected = 'selected';
                                        } else {
                                            $selected = '';
                                        }
                                    ?>
                                     <option {$selected} value="{$shiptype}">{$row["title"]}</option>
                                    <?php } } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row" style="display:none">
                            <label class="col-sm-2 col-form-label">運費</label>
                            <div class="col-sm-10">
                                <input type="number" onblur="checkshippingfee(this.value)" min="0" step="0.01" max="9999999" name="fixed_shipping_fee" value="{:isset($prod['fixed_shipping_fee'])?$prod['fixed_shipping_fee']:$shippingfee}" class="form-control" placeholder="運費">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">排序</label>
                            <div class="col-sm-10"><input type="number" min="0" max="9999999" name="sortorder" value="{:isset($prod['sortorder'])?$prod['sortorder']:0}" class="form-control" placeholder="排序數字越小越前"></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">啟用</label>
                            <div class="col-sm-10">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" name="state" id="customCheckbox2" <?php echo (isset($prod['state']) && $prod['state'])?'checked':'';?> value="1">
                                    <label for="customCheckbox2" class="custom-control-label"></label>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                        <div class="layui-form-item" id="pics">
                                <div class="layui-upload">
                                    <button type="button" class="btn btn-primary" id="slide-pc">選擇多圖</button>
                                    <div class="pic-more">
                                        <ul class="pic-more-upload-list" id="slide-pc-priview">
                                            <?php
                                            if(!empty($imageList)) {
                                                foreach($imageList as $image) {
                                            ?>
                                            <li class="item_img"><div class="operate"><i class="toleft layui-icon">《</i><i class="toright layui-icon">》</i><i  class="close layui-icon layui-icon-close"></i></div><img src="{:showfile($image['image_thumb'])}" class="img" ><input type="hidden" name="images[]" value="{$image['image_file']}" /></li>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-one-video" role="tabpanel" aria-labelledby="custom-tabs-one-video-tab">
                        <div class="layui-form-item" id="pics">
                            <div class="layui-upload">
                                <div class="pic-more">
                                    <button type="button" class="btn btn-primary" id="video_image_upload">選擇影片封面</button>
                                    <div id="video_image_div">
                                        <?php
                                        if(isset($prod["video_image"]) && !empty($prod["video_image"])) {
                                            ?>
                                                <img src="{:showfile($prod['video_image'])}" />
                                            <p><a href="{:url('removeVideoImage', ['prodid' => $prod['prodid']])}" class="AjaxTodo btn btn-danger">刪除</a></p>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-upload">
                                <div class="pic-more">
                                    <button type="button" class="btn btn-primary" id="video_upload">選擇影片文件(.mp4)</button>
                                    <div id="video_div">
                                        <?php
                                        if(isset($prod["video"]) && !empty($prod["video"])) {
                                        ?>
                                        <video controls>
                                            <source src="{:showfile($prod['video'])}" type="video/mp4">
                                        </video>
                                        <p><a href="{:url('removeVideo', ['prodid' => $prod['prodid']])}" class="AjaxTodo btn btn-danger">刪除</a></p>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-one-messages" role="tabpanel" aria-labelledby="custom-tabs-one-messages-tab">
                        <div class="row">
                            <div class="alert-info alert">打上勾勾的記錄才會顯示在前台，不打勾則不顯示，如果你想讓打勾的顯示又不能訂購，則將庫存設為0即可</div>
                        </div>
                        <div class="row">
                            <label class="col-md-2">商品規格</label>
                            <div class="col-md-10">
                                <select id="prod_void" name="void" class="form-control select2" style="min-width: 300px;">
                                    <option value="0">--無--</option>
                                    <?php
                                    if(!empty($product_variations)) {
                                        foreach($product_variations as $pv) {
                                            if(isset($prod['void']) && $prod['void'] == $pv['void']) {
                                                $selected = 'selected';
                                            } else {
                                                $selected = '';
                                            }
                                    ?>
                                    <option {$selected} value="{$pv['void']}">{$pv['vname']}</option>
                                    <?php } } ?>
                                </select>
                            </div>
                        </div>
                        <hr/>
                        <div id="attrcombin"></div>
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-one-shuxing" role="tabpanel" aria-labelledby="custom-tabs-one-shuxing-tab">
                        <div class="row">
                            <?php
                            if(!empty($search_attris["attrList"])) {
                                foreach($search_attris["attrList"] as $attrid => $attrvalues) {
                                    $attrname = $search_attris["attrName"][$attrid];
                                    ?>
                                    <div class="attrrows col-sm-6">
                                        <!-- checkbox -->
                                        <div class="form-group">
                                            <label class="col-md-6" data-name="{$attrname}">{$attrname}</label>
                                            <?php
                                            if(!empty($attrvalues)) {
                                                foreach($attrvalues as $attrvalue) {
                                                    if(isset($search_options[$attrid]) && in_array($attrvalue["valueid"], $search_options[$attrid])) {
                                                        $checked = 'checked';
                                                    } else {
                                                        $checked = '';
                                                    }
                                                    ?>
                                                    <div class="form-check col-md-12">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <input class="form-check-input" type="checkbox" {$checked} name="search_attr_options[{$attrid}][]" value="{$attrvalue["valueid"]}">
                                                                <label class="form-check-label">{$attrvalue["name"]}</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                <?php } } ?>
                                        </div>
                                    </div>
                                <?php } } ?>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-one-settings" role="tabpanel" aria-labelledby="custom-tabs-one-settings-tab">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">SEO標題</label>
                            <div class="col-sm-10"><input type="text" name="seo_title" value="{:isset($prod['seo_title'])?$prod['seo_title']:''}" class="form-control" placeholder="SEO標題" maxlength="255"></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">SEO關鍵字</label>
                            <div class="col-sm-10"><input type="text" name="seo_keywords" value="{:isset($prod['seo_keywords'])?$prod['seo_keywords']:''}" class="form-control" placeholder="SEO關鍵字" maxlength="255"></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">SEO描述</label>
                            <div class="col-sm-10"><input type="text" name="seo_desc" value="{:isset($prod['seo_desc'])?$prod['seo_desc']:''}" class="form-control" placeholder="關鍵字描述" maxlength="255"></div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-one-pages" role="tabpanel" aria-labelledby="custom-tabs-one-pages-tab">
                        <?php
                        $sp_pages = get_pages_bytype("add_to_cart");
                        if(!empty($sp_pages)) {
                        foreach($sp_pages as $page) {
                            if(isset($prod['page_ids']) && stripos(','.$prod['page_ids'].',', ','.$page['pageid'].',') !== false) {
                                $checked = 'checked';
                            } else {
                                $checked = '';
                            }
                            ?>
                            <div><input type="checkbox" {$checked} name="page_ids[]" value="{$page['pageid']}" /> {$page['title']}</div>
                        <?php } } ?>
                    </div>
                </div>
            </div>
            <!-- /.card -->

        </div>
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-secondary text-left" href="{:admin_link('index')}">取消</a>
            </div>
            <div class="col-md-6 text-right">
                <input type="submit" id="submitBtn" class="btn btn-primary" value="儲存"/>
            </div>
        </div>
        </form>
    </div>
</div>
{include file="common/footer" /}
<script src="/static/js/layui/layui.js" charset="utf-8"></script>
<script type="text/javascript">

    var combinData = {:isset($voptionvalues)?toJSON($voptionvalues, true):'"";'}
    //重點是這個組合方法



    var checkedAttris = [];
    var checkedAttrName = [];
    var checkedAttrValues = [];

    $("#prod_void").change(function() {
        var _void = $(this).val();
        if(_void<=0) {
            return false;
        }

        $.getJSON('{:admin_link("get_prod_vos")}', {void: _void}, function(json) {
            checkedAttris = [];
            checkedAttrName = [];
            if(json.code>0) {
                layer.msg(json.msg);
                return false;
            }
            $.each(json.data.attrList, function(k, rows) {
                var values = []
                $.each(rows, function(ik, kb) {
                        var valueid = kb.valueid;
                        var valuename = kb.name;
                        values.push(valueid);
                        checkedAttrValues[valueid]=valuename;
                })

                if(values.length) {
                    checkedAttris.push(values);
                    checkedAttrName.push(json.data.attrName[k])
                }
            })

            setTimeout(function() {
                makecombine();
            }, 1000);
        })
    })

    function makecombine() {
        var res = _combine(checkedAttris);
        var row = [];
        var rowspan = res.length;
        for (var n = checkedAttris.length - 1; n > -1; n--) {
            row[n] = parseInt(rowspan / checkedAttris[n].length);
            rowspan = row[n];

        }

        row.reverse();

        //table tr td
        var str = "";
        var len = res[0].length;
        var totalAttr = res.length;
        var allChecked = 0;
        for (var i = 0; i < res.length; i++) {

            var tmp = "";
            var combinValueIds = [];
            for (var j = 0; j < len; j++) {
                if (i % row[j] == 0 && row[j] > 1) {
                    tmp += "<td rowspan='" + row[j] + "'>" + checkedAttrValues[res[i][j]] + "</td>";
                } else if (row[j] == 1) {
                    tmp += "<td>" + checkedAttrValues[res[i][j]] + "</td>";
                }
                combinValueIds.push(res[i][j]);
            }
            combinName = combinValueIds.join(",");
            if(combinData[combinName] != undefined) {
                vstock = combinData[combinName]["vcstock"];
                vstock_warning = combinData[combinName]["vstock_warning"];
                vcprice = combinData[combinName]["vcprice"];
                vcsku = combinData[combinName]["vcsku"];
                vcenabled = combinData[combinName]["vcenabled"]?'checked':'';
            } else {
                vstock = 0;
                vstock_warning = 0;
                vcprice = 0;
                vcsku = '';
                vcenabled = 'checked';
            }

            if(vcenabled=="checked") {
                allChecked++;
            }

            if(vcprice<=0) {
                vcprice = $("input[name=prod_price]").val();
            }

            if(vcsku=="") {
                vcsku = $("input[name=prodcode]").val();
            }

            str += "<tr><td><input type='checkbox' "+vcenabled+" class='vcenabled' name='vcenabled["+combinName+"]' value='1'/></td>" + tmp + "<td><input class='form-control' type='number' name='vstock_warning["+combinName+"]' value='"+vstock_warning+"' /></td><td><input class='form-control' type='number' name='vstock["+combinName+"]' value='"+vstock+"' /></td>"
                + "<td><input class='form-control' type='text' name='vsku["+combinName+"]' value='"+vcsku+"' /></td>"
                + "<td><input class='form-control' type='number' name='vprice["+combinName+"]' value='"+vcprice+"' /></td>" + "</tr>";

        }

        var th = "";
        for (var k = 0; k < len; k++) {
            th += "<th>" + checkedAttrName[k] + "</th>";
        }

        if(allChecked==totalAttr) {
            allcheckedStr = 'checked';
        } else {
            allcheckedStr = '';
        }

        th = "<thead><th><input type='checkbox' "+allcheckedStr+" id='checkedall' onclick='checkstate(this)' value='1'> 啟用</th>" + th + "<th>安全庫存</th><th>現有庫存</th>" + "<th>編號</th>" + "<th>售價</th>" + "</thead>";
        str = "<table class='table table-bordered table-striped dataTable dtr-inline'>" + th + str + "</table>";
        $("#attrcombin").html(str);
    }

    function _combine(arr) {
        arr.reverse();
        var r = [];
        (function f(t, a, n) {
            if (n == 0) return r.push(t);
            for (var i = 0; i < a[n-1].length; i++) {
                f(t.concat(a[n-1][i]), a, n - 1);
            }
        })([], arr, arr.length);
        return r;
    }

    $(function() {
        $("#prod_void").change();
    })
</script>
<script>
    <!--
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

    var zNodes = {:$zNodes};

    $(document).ready(function(){
        $.fn.zTree.init($("#categoryObj"), setting, zNodes);

        $("#submitBtn").click(function() {
            var zTreeObj = $.fn.zTree.getZTreeObj("categoryObj");  //ztree的Id  zTreeId
            var checkedNodes = zTreeObj.getCheckedNodes();
            if(checkedNodes.length) {
                var checkCatIds = [];
                $.each(checkedNodes, function(i, obj) {
                    checkCatIds.push(obj.id);
                })
                $("input[name=cateIds]").val(checkCatIds.join(","));
            }
        })

    });
    //-->
</script>
<script>
    layui.use('upload', function(){
            var upload = layui.upload;
            upload.render({
                elem: '#slide-pc',
                url: '{:admin_link("Product/upload_image")}',
                size: 5000,
                exts: 'jpg|png|jpeg|gif',
                multiple: true,
                before: function(obj) {
                layer.msg('圖片上傳中...', {
                    icon: 16,
                    shade: 0.01,
                    time: 0
                })
            },
            done: function(res) {
                layer.close(layer.msg());//關閉上傳提示視窗
                if(res.code !== 0) {
                    return layer.msg(res.message);
                }
                $('#slide-pc-priview').append('<li class="item_img"><div class="operate"><i class="toleft layui-icon">《</i><i class="toright layui-icon">》</i><i  class="close layui-icon layui-icon-close"></i></div><img src="' + res.url + '" class="img" ><input type="hidden" name="images[]" value="' + res.saveName + '" /></li>');
            }
        });
        upload.render({
            elem: '.attrImage',
            url: '{:admin_link("upload")}',
            size: 5000,
            exts: 'jpg|png|jpeg|gif',
            multiple: true,
            before: function(obj) {
                layer.msg('圖片上傳中...', {
                    icon: 16,
                    shade: 0.01,
                    time: 0
                })
            },
            done: function(res) {
                layer.close(layer.msg());//關閉上傳提示視窗
                if(res.code !== 0) {
                    return layer.msg(res.message);
                }
                $('#slide-pc-priview').append('<li class="item_img"><div class="operate"><i class="toleft layui-icon">《</i><i class="toright layui-icon">》</i><i  class="close layui-icon layui-icon-close"></i></div><img src="' + res.url + '" class="img" ><input type="hidden" name="images[]" value="' + res.saveName + '" /></li>');
            }
        });
        upload.render({
            elem: '#video_upload',
            url: '{:admin_link("upload")}',
            size: 150000,
            exts: 'mp4',
            multiple: true,
            before: function(obj) {
                layer.msg('影片上傳中...', {
                    icon: 16,
                    shade: 0.01,
                    time: 0
                })
            },
            done: function(res) {
                layer.close(layer.msg());//關閉上傳提示視窗
                if(res.code !== 0) {
                    return layer.msg(res.message);
                }
                $('#video_div').html('<video controls><source src="' + res.url + '" type="video/mp4"></video><input type="hidden" name="video" value="' + res.saveName + '" />');
            }
        });
        upload.render({
            elem: '#video_image_upload',
            url: '{:admin_link("upload")}',
            size: 15000,
            exts: 'jpg|png|jpeg|gif',
            multiple: true,
            before: function(obj) {
                layer.msg('影片封面上傳中...', {
                    icon: 16,
                    shade: 0.01,
                    time: 0
                })
            },
            done: function(res) {
                layer.close(layer.msg());//關閉上傳提示視窗
                if(res.code !== 0) {
                    return layer.msg(res.message);
                }
                $('#video_image_div').html('<img src="' + res.url + '" /><input type="hidden" name="video_image" value="' + res.saveName + '" />');
            }
        });
    });
    //點擊多圖上傳的X,刪除當前的圖片
    $("body").on("click",".close",function(){
        $(this).closest("li").remove();
    });
    //多圖上傳點擊<>左右移動圖片
    $("body").on("click",".pic-more ul li .toleft",function(){
        var li_index=$(this).closest("li").index();
        if(li_index>=1){
            $(this).closest("li").insertBefore($(this).closest("ul").find("li").eq(Number(li_index)-1));
        }
    });
    $("body").on("click",".pic-more ul li .toright",function(){
        var li_index=$(this).closest("li").index();
        $(this).closest("li").insertAfter($(this).closest("ul").find("li").eq(Number(li_index)+1));
    });

    function checkshippingfee(shippingfee) {
        if(shippingfee<=0) {
            layer.confirm("請確認運費是否設置正確");
        }
    }

</script>
<script type="text/javascript" src="/static/js/ztree/js/jquery.ztree.core.js"></script>
<script type="text/javascript" src="/static/js/ztree/js/jquery.ztree.excheck.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
    $('#prod_void').select2({
        width: "300px"
    });

    function checkstate(obj) {
        if($(obj).is(":checked")) {
            $("#attrcombin").find(".vcenabled").attr("checked", true);
            $("#attrcombin").find(".vcenabled").prop("checked", true);
        } else {
            $("#attrcombin").find(".vcenabled").attr("checked", false);
            $("#attrcombin").find(".vcenabled").prop("checked", false);
        }
    }

</script>
