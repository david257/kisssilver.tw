<script src="/static/js/jquery.form.js"></script>
<script src="/static/js/layer/layer.js"></script>
<script>
    $(".DialogForm").click(function(){
        var _width = parseInt($(this).attr("data-width"));
        var _height = parseInt($(this).attr("data-height"));
        var width = _width>0?_width:800;
        var height = _height>0?_height:600;

        var wheight = $(window).height();
        var wwidth = $(window).width();
        if(wheight<height) {
            height = wheight-20;
        }
        if(wwidth<width) {
            width = wwidth-20;
        }

        layer.open({
            type: 2,
            title: $(this).attr("rel"),
            shadeClose: false,
            shade: 0.3,
            maxmin: true, //開啟最大化最小化按鈕
            area: [width+'px', height+'px'],
            content: $(this).attr("href")
        });
        return false;
    })

    $(".AjaxTodo").click(function(){
        var url = $(this).attr("href");
        var tip_text = $(this).attr("data-tip");
        if(tip_text == '' || tip_text == null || tip_text==undefined) {
            tip_text = '確定要執行此操作嗎？';
        }
        layer.confirm(tip_text, {
            icon: 3,
            title: '系統提示',
            btn: ['確定','取消'] //按鈕
        }, function(){
            var index = layer.load(0, {
                shade: [0.1,'#fff'] //0.1透明度的白色背景
            });
            $.getJSON(url, function(json) {
                layer.close(index);
                if (json.code === 0) { //無錯誤
                    layer.alert(json.msg, {icon: 1, title: '系統提示',
                        btn: ['確定','取消'], end: function() {
                            document.location.reload();
                        } });
                } else {
                    layer.alert(json.msg, {icon: 2, title: '系統提示',
                        btn: ['確定','取消']});
                }
            })
        }, function(){
            layer.closeAll();
        });
        return false;
    })

    var isLock = false;
    $(".AjaxForm").submit(function() {
        //表單送出前確認
        var formId = $(this);

        var relUrl = ($(this).attr("rel"));
        var tip_text = $(this).attr("data-tip");
        if(tip_text == '' || tip_text == null || tip_text==undefined) {
            tip_text = '請確定資料無誤';
        }

        if(typeof updateEditorContent != 'undefined') {
            updateEditorContent();
        }
        var index;
        layer.confirm(tip_text, {
            icon: 3,
            title: '系統提示',
            btn: ['確定','取消'] //按鈕
        }, function() {

            var shadeBox = "";
            var options = {
                beforeSubmit: function () {
                    if (isLock) {
                        layer.alert("請不要重複送出", {icon: 2, title: '系統提示',
                            btn: ['確定','取消']});
                        return false;
                    }
                    isLock = true;

                    shadeBox = layer.open({
                        type: 3,
                        title: false,
                        shade: [0.8],
                        closeBtn: false,
                        shadeClose: false,
                    });

                    index = layer.load(0, {
                        shade: [0.1,'#fff'] //0.1透明度的白色背景
                    });

                },
                success: function (data) {
                    var json = $.parseJSON(data);
                    if (json.code === 0) { //無錯誤
                        layer.alert(json.msg, {icon: 1, title: '系統提示',
                            btn: ['確定','取消'], time: 1000, end: function() {
                            if(json.url !== undefined && json.url !== null && json.url !== '') {
                                document.location.href = json.url;
                            } else {
                                parent.layer.close(parent.layer.getFrameIndex(window.name));
                                parent.location.reload();
                            }
                        } });

                    } else {
                        layer.alert(json.msg, {icon: 2, title: '系統提示',
                            btn: ['確定','取消']});
                    }
                },
                error: function (xhr, status, err) {
                    layer.alert(err, {icon: 2, title: '系統提示',
                        btn: ['確定','取消']});
                },
                complete: function () {
                    layer.close(index);
                    $("#shadetips").hide();
                    setTimeout(function () {
                        isLock = false;
                        layer.close(shadeBox);
                    }, 1500)
                }
            };

            // pass options to ajaxForm
            formId.ajaxSubmit(options);
        }, function(){
            layer.closeAll();
        });
        return false;
    })
</script>
