var gPageSize = 10;
var i = 1; //设置当前页数，全局变量
var finished = false;
var dataUrl = '';

$(function () {
    //根据页数读取数据
    function getData(pagenumber) {
        //console.log(i);
        $.get(dataUrl, { pagesize: gPageSize, p: pagenumber }, function (data) {
            //if (data.length > 0) {
                //var jsonObj = JSON.parse(data);
                jsonObj = data.info;
                insertDiv(jsonObj);
            //}
        });
        $.ajax({
            type: "post",
            url: dataUrl,
            data: { pagesize: gPageSize, p: pagenumber },
            dataType: "json",
            success: function (data) {
                $(".loading").hide();
                if (data.length > 0) {
                    //var jsonObj = JSON.parse(data);
                    jsonObj = data.info;
                    insertDiv(jsonObj);
                }
            },
            beforeSend: function () {
                $(".loading").show();
            },
            error: function () {
                $(".loading").hide();
            }
        });
        i++; //页码自动增加，保证下次调用时为新的一页。
    }

    //核心代码
    var winH = $(window).height(); //页面可视区域高度 
    var scrollHandler = function () {
        var pageH = $(document.body).height();
        var scrollT = $(window).scrollTop(); //滚动条top 
        var aa = (pageH - winH - scrollT) / winH;
        if (!finished && aa < 0.2) { //0.02是个参数
            if (i % 10 === 0) { //每10页做一次停顿
                getData(i);
                $(window).unbind('scroll');
                $("#btn_Page").show();
            } else {
                getData(i);
                finished = true;
                setTimeout(function(){finished = false;},500);
                $("#btn_Page").hide();
            }
        }
    }

    //初始化加载第一页数据
    getData(1);

    //定义鼠标滚动事件
    $(window).scroll(scrollHandler);

    //继续加载按钮事件
    $("#btn_Page").click(function () {
        getData(i);
        $(window).scroll(scrollHandler);
    });
});