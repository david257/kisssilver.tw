<script>
    (function (factory) {
        if (typeof define === 'function' && define.amd) {
            // AMD. Register as anonymous module.
            define('ChineseDistricts', [], factory);
        } else {
            // Browser globals.
            factory();
        }
    })(function () {
        var ChineseDistricts;
        $.ajaxSettings.async = false;
        $.getJSON("{:url('Api/getCountries')}", function(json) {
            ChineseDistricts = json;
        });

        if (typeof window !== 'undefined') {
            window.ChineseDistricts = ChineseDistricts;
        }
        return ChineseDistricts;

    });
</script>
<script src="/static/js/dist/distpicker.js"></script>
<script>
    $(function() {
        $("select[name=provid]").val(1).change();
        setTimeout(function() {
            $(".country_dist").distpicker();
            var provid = {:isset($customer['provid'])?$customer['provid']:0};
            var cityid = {:isset($customer['cityid'])?$customer['cityid']:0};
            var areaid = {:isset($customer['areaid'])?$customer['areaid']:0};
            if(provid>0) {
                $("select[name=provid]").val(provid).change();
            }
            if(cityid>0) {
                $("select[name=cityid]").val(cityid).change();
            }
            if(areaid>0) {
                $("select[name=areaid]").val(areaid);
            }
        }, 200);
    })

    function getPostcode(id, postcodeId) {
        $.get("{:front_link('Api/getPostcodeByCountryId')}", {id:id}, function(postcode) {
            $("#"+postcodeId).val(postcode);
        })
    }

</script>