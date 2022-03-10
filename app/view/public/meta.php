<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{:isset($MetaTitle)?$MetaTitle.'-':''}{$sitetitle}</title>
    <meta name="description" content="{:isset($MetaDesc)?$MetaDesc:$sitedesc}" />
    <meta name="keywords" content="{:isset($MetaKeywords)?$MetaKeywords:$sitekeywords}" />
    <meta property="og:title" content="{:isset($MetaTitle)?$MetaTitle.'-':''}{$sitetitle}" />
    <meta property="og:description" content="{:isset($MetaDesc)?$MetaDesc:$sitedesc}" />
    <?php
    if(isset($og_image) && !empty($og_image)) {
        $ogimage = $current_domain.$og_image;
    } else {
        $ogimage = $current_domain.'/static/front/images/logo.png';
    }
    ?>
    <meta property="og:image" content="{$ogimage}" />
    <link rel="shortcut icon" type="image/png" href="/favicon.png"/>
    <link rel="stylesheet" type="text/css" href="/static/front/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/static/front/font/iconfont.css">
    <link rel="stylesheet" type="text/css" href="/static/front/css/style.css?v=1.0.6">
    <link rel="stylesheet" type="text/css" href="/static/front/dist/assets/owl.carousel.css">
    <script type="text/javascript" src="/static/front/js/jquery-3.2.1.min.js"></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-180296843-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-180296843-1');
    </script>
    <script>
        // this function must be defined in the global scope
        window.fadeIn = function(obj) {
            $(obj).fadeIn(1000);
        }
    </script>
</head>
<body>