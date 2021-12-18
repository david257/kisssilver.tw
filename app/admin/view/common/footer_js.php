
<!-- jQuery -->
<script src="/static/admin/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="/static/admin/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/static/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/static/admin/plugins/jquery-knob/jquery.knob.min.js"></script>
<script src="/static/admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="/static/admin/dist/js/adminlte.js"></script>
<script src="/static/js/ckeditor/ckeditor.js"></script>
<script src="/static/js/datetimepicker/build/jquery.datetimepicker.full.js"></script>
<script>
    function updateEditorContent() {
        for(instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
    }
</script>
<script>
    if ( CKEDITOR.env.ie && CKEDITOR.env.version < 9 )
        CKEDITOR.tools.enableHtml5Elements( document );

    // The trick to keep the editor in the sample quite small
    // unless user specified own height.
    CKEDITOR.config.width = 'auto';
    CKEDITOR.config.uploadUrl  = "{:admin_link('upload')}";
    //CKEDITOR.config.filebrowserBrowseUrl = "{:admin_link('upload')}";
    //CKEDITOR.config.filebrowserImageBrowseUrl = "{:admin_link('upload')}";
    CKEDITOR.config.filebrowserUploadUrl = "{:admin_link('upload')}";
    CKEDITOR.config.filebrowserImageUploadUrl = "{:admin_link('upload')}";
    CKEDITOR.config.toolbar = 'Full';
    CKEDITOR.config.fullPage = true;
    CKEDITOR.config.toolbarGroups = [
        { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
        { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
        { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
        { name: 'forms', groups: [ 'forms' ] },
        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
        { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
        { name: 'links', groups: [ 'links' ] },
        { name: 'insert', groups: [ 'insert' ] },
        { name: 'styles', groups: [ 'styles' ] },
        { name: 'colors', groups: [ 'colors' ] },
        { name: 'tools', groups: [ 'tools' ] },
        { name: 'others', groups: [ 'others' ] },
        { name: 'about', groups: [ 'about' ] }
    ];

    CKEDITOR.config.removeButtons = 'Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Language,SpecialChar,About,Flash,PageBreak,Scayt,Save,Print,Templates';

    var initEditor = ( function(elemId) {
        var wysiwygareaAvailable = isWysiwygareaAvailable(),
            isBBCodeBuiltIn = !!CKEDITOR.plugins.get( 'bbcode' );
        return function() {
            var editorElement = CKEDITOR.document.getById( elemId );
            // Depending on the wysiwygarea plugin availability initialize classic or inline editor.
            if ( wysiwygareaAvailable ) {
                CKEDITOR.replace( elemId );
            } else {
                editorElement.setAttribute( 'contenteditable', 'true' );
                CKEDITOR.inline( elemId );
                // TODO we can consider displaying some info box that
                // without wysiwygarea the classic editor may not work.
            }
        };

        function isWysiwygareaAvailable() {
            // If in development mode, then the wysiwygarea must be available.
            // Split REV into two strings so builder does not replace it :D.
            if ( CKEDITOR.revision == ( '%RE' + 'V%' ) ) {
                return true;
            }

            return !!CKEDITOR.plugins.get( 'wysiwygarea' );
        }
    } );

    $(function() {
        if($("#editor")[0]) {
            initEditor("editor")();
        }
    })

</script>
<script>
    $(".image_upload").on('change', function () {
        var obj = $(this);
        var formData=new FormData();
        var curFile = this.files;
        if(curFile[0]["size"]>51200000) {
            layer.msg("圖片大小不能超過50M");
            return false;
        }
        /*if (!/image\/\w+/.test(curFile[0]["type"])){
            layer.msg('必須要上傳圖片類型文件');
            return  false;
        }*/
        formData.append('upload',curFile[0]);
        $.ajax({
            type: 'post',
            url: '{:admin_link('upload')}',
            data:formData,
            dataType:'json',
            cache:false,
            processData:false,
            contentType:false,
            success:function (json) {
                if (json.code == 0){
                    if(json.url.indexOf(".mp4") !== -1) {
                        obj.parent().find("video").html('<source src="'+json.url+'" type="video/mp4">');
                        obj.parent().find("video").show();
                    } else {
                        obj.parent().find("img").attr("src", json.url);
                        obj.parent().find("img").show();
                    }
                    obj.parent().find(".image_path").val(json.saveName);
                }else{
                    layer.msg(json.msg);
                    return false;
                }
            },
            complete: function() {
                obj.val('');
            }
        });
    });
</script>
{include file="common/js" /}
<script>
    $(function() {
        var currentUrl = "{$currentUrl}";
        $.each($("#side_menu .nav-link"), function() {
            var obj = $(this);
            if(obj.attr("href") == currentUrl) {
                obj.addClass("active");
                if(obj.hasClass("subnode")) {
                    obj.parent().parent().parent().addClass("menu-open");
                    obj.parent().parent().parent().find(".parentnode").addClass("active");
                }
            }
        })
    })
</script>