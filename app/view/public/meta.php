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
    <link rel="shortcut icon" type="image/png" href="/favicon.png" />
    <link rel="stylesheet" type="text/css" href="/static/front/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/static/front/font/iconfont.css">
    <link rel="stylesheet" type="text/css" href="/static/front/css/style.css?v=1.5.17">
    <link rel="stylesheet" type="text/css" href="/static/front/dist/assets/owl.carousel.css">
    <link rel="stylesheet" href="/static/front/css/all.min.css">
    <script src="https://kit.fontawesome.com/3df8baf8c9.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="/static/front/js/jquery-3.2.1.min.js"></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-180296843-1"></script>
    <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
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
<script>
  window.getAverageRGB = function(imgEl) {
    var blockSize = 5, // only visit every 5 pixels
        defaultRGB = {r:0,g:0,b:0}, // for non-supporting envs
        canvas = document.createElement('canvas'),
        context = canvas.getContext && canvas.getContext('2d'),
        data, width, height,
        i = -4,
        length,
        rgb = {r:0,g:0,b:0},
        count = 0;
        
    if (!context) {
        return defaultRGB;
    }
    
    height = canvas.height = imgEl.naturalHeight || imgEl.offsetHeight || imgEl.height;
    width = canvas.width = imgEl.naturalWidth || imgEl.offsetWidth || imgEl.width;
    
    context.drawImage(imgEl, 0, 0);
    
    try {
        data = context.getImageData(0, 0, width, height);
    } catch(e) {
        /* security error, img on diff domain */alert('x');
        return defaultRGB;
    }
    
    length = data.data.length;
    
    while ( (i += blockSize * 4) < length ) {
        ++count;
        rgb.r += data.data[i];
        rgb.g += data.data[i+1];
        rgb.b += data.data[i+2];
    }
    
    // ~~ used to floor values
    rgb.r = ~~(rgb.r/count);
    rgb.g = ~~(rgb.g/count);
    rgb.b = ~~(rgb.b/count);
    imgEl.closest('list-pic').style.backgroundColor = 'rgb('+rgb.r+','+rgb.g+','+rgb.b+')';
    // return rgb;
    
}
</script>


<body>