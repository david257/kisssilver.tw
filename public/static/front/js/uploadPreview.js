/*
 
*使用方法:
*介面構造(IMG標籤外必須擁有DIV 而且必須給予DIV控制項ID)
* <div id="imgdiv"><img id="imgShow" width="120" height="120" /></div>
* <input type="file" id="up_img" />
*調用代碼:
* new uploadPreview({ UpBtn: "up_img", DivShow: "imgdiv", ImgShow: "imgShow" });
*參數說明:
*UpBtn:選擇檔控制項ID;
*DivShow:DIV控制項ID;
*ImgShow:圖片控制項ID;
*Width:預覽寬度;
*Height:預覽高度;
*ImgType:支援檔案類型 格式:["jpg","png"];
*callback:選擇檔案後回檔案方法;

*版本:v1.4
更新內容如下:
1.修復回檔案.

*版本:v1.3
更新內容如下:
1.修復多層級框架獲取路徑BUG.
2.去除對jquery外掛程式的依賴.
*/

 
var uploadPreview = function(setting) {
    /*
    
    *work:this(當前物件)
    */
    var _self = this;
    /*
    
    *work:判斷為null或者空值
    */
    _self.IsNull = function(value) {
        if (typeof (value) == "function") { return false; }
        if (value == undefined || value == null || value == "" || value.length == 0) {
            return true;
        }
        return false;
    }
    /*
    
    *work:預設配置
    */
    _self.DefautlSetting = {
        UpBtn: "",
        DivShow: "",
        ImgShow: "",
        Width: 100,
        Height: 100,
        ImgType: ["gif", "jpeg", "jpg", "bmp", "png"],
        ErrMsg: "選擇文件錯誤,圖片類型必須是(gif,jpeg,jpg,bmp,png)中的一種",
        callback: function() { }
    };
    /*
    
    *work:讀取配置
    */
    _self.Setting = {
        UpBtn: _self.IsNull(setting.UpBtn) ? _self.DefautlSetting.UpBtn : setting.UpBtn,
        DivShow: _self.IsNull(setting.DivShow) ? _self.DefautlSetting.DivShow : setting.DivShow,
        ImgShow: _self.IsNull(setting.ImgShow) ? _self.DefautlSetting.ImgShow : setting.ImgShow,
        Width: _self.IsNull(setting.Width) ? _self.DefautlSetting.Width : setting.Width,
        Height: _self.IsNull(setting.Height) ? _self.DefautlSetting.Height : setting.Height,
        ImgType: _self.IsNull(setting.ImgType) ? _self.DefautlSetting.ImgType : setting.ImgType,
        ErrMsg: _self.IsNull(setting.ErrMsg) ? _self.DefautlSetting.ErrMsg : setting.ErrMsg,
        callback: _self.IsNull(setting.callback) ? _self.DefautlSetting.callback : setting.callback
    };
    /*
    
    *work:獲取文本控制項URL
    */
    _self.getObjectURL = function(file) {
        var url = null;
        if (window.createObjectURL != undefined) {
            url = window.createObjectURL(file);
        } else if (window.URL != undefined) {
            url = window.URL.createObjectURL(file);
        } else if (window.webkitURL != undefined) {
            url = window.webkitURL.createObjectURL(file);
        }
        return url;
    }
    /*
    *author:周祥
    
    *work:綁定事件
    */
    _self.Bind = function() {
        document.getElementById(_self.Setting.UpBtn).onchange = function() {
            if (this.value) {
                var max = testMaxSize(this.files[0], 1024 * 1 * 1024);
                if (!max) {
                    alert('請重新選擇檔案，僅支援小於1MB圖片類型之檔案上傳！');
                    this.value = "";
                    return false;
                }
                if (!RegExp("\.(" + _self.Setting.ImgType.join("|") + ")$", "i").test(this.value.toLowerCase())) {
                    alert(_self.Setting.ErrMsg);
                    this.value = "";
                    return false;
                }
                if (navigator.userAgent.indexOf("MSIE") > -1) {
                    try {
                        document.getElementById(_self.Setting.ImgShow).src = _self.getObjectURL(this.files[0]);
                    } catch (e) {
                        var div = document.getElementById(_self.Setting.DivShow);
                        this.select();
                        top.parent.document.body.focus();
                        var src = document.selection.createRange().text;
                        document.selection.empty();
                        document.getElementById(_self.Setting.ImgShow).style.display = "none";
                        div.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale)";
                        div.style.width = _self.Setting.Width + "px";
                        div.style.height = _self.Setting.Height + "px";
                        div.filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = src;
                    }
                } else {
                    document.getElementById(_self.Setting.ImgShow).src = _self.getObjectURL(this.files[0]);
                }
                _self.Setting.callback();
            }
        }
    }
    /*
    
    *work:執行綁定事件
    */
    _self.Bind();
}

function testMaxSize(fileData, Max_Size) {
    var isAllow = false;
    var size = fileData.size;
    isAllow = size <= Max_Size;
    if (!isAllow) {
        isAllow = false;
    }
    return isAllow;
}