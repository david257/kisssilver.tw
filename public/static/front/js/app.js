$(function(){
	var bodyw=$('body').width();
	var bwidth=$('body').width();
	var newsproducw=$(".news-product .list-one").width();
$(".news-product .list-one .list-pic img").css({"width" : newsproducw});
$(".news-product .list-one .list-pic video").css({"width" : newsproducw});

var hotproductw=$(".hot-product .list-one").width();
$(".hot-product .list-one .list-pic img").css({"width" : hotproductw});
$(".hot-product .list-one .list-pic video").css({"width" : hotproductw});

var saleproductw=$(".sale-product .list-one").width();
$(".sale-product .list-one .list-pic img").css({"width" : saleproductw});
$(".sale-product .list-one .list-pic video").css({"width" : saleproductw});
		if(bodyw > 768){
			var locationbg=$(".hide-xs .location-bg").width()*600 / 1920;
		$(".hide-xs .location-bg").css({"height" : locationbg});
			}
			if(bodyw < 768){
			var locationbg1=$(".show-xs .location-bg").width()*5 / 3 ;
		$(".show-xs .location-bg").css({"height" : locationbg1});
			}
	$('.dropdown-toggle').dropdown();
	
	if($(".index-banner .sp-slide").length > 1){
	$( '.index-banner' ).sliderPro({
		width: 1920,
		height: 895,
		arrows: false,
		buttons: false,
		fade:true,
		autoplayDelay:2000, 
		fadeDuration:1000,
		waitForLayers: true,
		slideDistance:0,
		thumbnailPointer: true,
		autoplay: true,
		autoScaleLayers: false,
		reachVideoAction: 'playVideo',
		leaveVideoAction:'stopVideo',
		playVideoAction:'none',
		pauseVideoAction:'none',
		endVideoAction:'startAutoplay',
		breakpoints: {
			800:{
				width: 900,
		height: 1500,
	arrows: false,
		buttons: false,
		fade:false,
		autoplayDelay:5000, 
		fadeDuration:1000,
		waitForLayers: true,
		slideDistance:0,
		thumbnailPointer: false,
		autoplay: true,
		autoScaleLayers: false,
		touchSwipe:false,
		loop:false,
			}
		}
	});
	}
	if($(".index-banner .sp-slide").length < 2){
	$( '.index-banner' ).sliderPro({
		width: 1920,
		height: 895,
		arrows: false,
		buttons: false,
		fade:true,
		touchSwipe:false,
		autoplayDelay:2000, 
		fadeDuration:1000,
		waitForLayers: true,
		slideDistance:0,
		thumbnailPointer: true,
		autoplay: false,
		autoScaleLayers: false,
		breakpoints: {
			800:{
				width: 900,
		height: 1500,
				thumbnailWidth: 120,
				thumbnailHeight: 50,
				buttons: false,
				autoplay: false,
			}
		}
	});
	}
	if($(".top-tips .owl-carousel .item").length > 0){
	 $('.top-tips .owl-carousel').owlCarousel({
      stagePadding: 0,
    loop:false,
    margin:10,
	items:1,
    nav:false,
    responsiveClass:true,
	navigation:false,
	autoplay:true,
    responsive:{
        0:{
            items:1,
            nav:false
        },
        600:{
            items:1,
            nav:false
        },
        1000:{
            items:1,
            nav:false,
            loop:true
        }
    }
		}); 
		

	}
	if($(".news-owl .owl-carousel .item").length > 0){
	 $('.news-owl .owl-carousel').owlCarousel({
      stagePadding: 0,
    loop:false,
    margin:10,
	items:4,
    nav:true,
    responsiveClass:true,
	navigation:true,
	autoplay:true,
	arrows: true,
    buttons: false,
    responsive:{
        0:{
            items:2,
            nav:false,
			margin:2,
			navigation:false,
			arrows: false,
        },
        600:{
            items:2,
            nav:false,
			margin:2,
			navigation:false,
			arrows: false,
        },
        1000:{
            items:4,
            nav:true,
            loop:false
        }
    }
		}); 
		
		if(bodyw > 600){
	var newsowl1=$(".news-owl .owl-carousel").width()/4 - 20;
$(".news-owl .owl-carousel .item img").css({"height" : newsowl1});
$(".news-owl .owl-carousel .item video").css({"height" : newsowl1});
$(".news-owl .owl-carousel .item video").css({"width" : newsowl1});
	}
	if(bodyw <= 600){
	var newsowl1=$(".news-owl .owl-carousel").width()/2 - 20;
$(".news-owl .owl-carousel .item img").css({"height" : newsowl1});
$(".news-owl .owl-carousel .item video").css({"height" : newsowl1});
$(".news-owl .owl-carousel .item video").css({"width" : newsowl1});
	}
	}
	if($(".hot-owl .owl-carousel .item").length > 0){
	 $('.hot-owl .owl-carousel').owlCarousel({
      stagePadding: 0,
    loop:true,
    margin:20,
	items:4,
    nav:true,
    responsiveClass:true,
	navigation:true,
	autoplay:true,
	arrows: true,
    buttons: false,
    responsive:{
         0:{
            items:2,
            nav:false,
			margin:2,
			navigation:false,
			arrows: false,
        },
        600:{
            items:2,
            nav:false,
			margin:10,
			navigation:false,
			arrows: false,
        },
        1000:{
            items:4,
            nav:true,
            loop:false
        }
    }
		}); 
		if(bodyw > 600){
	var hotowl1=$(".hot-owl .owl-carousel").width()/4 - 20;
$(".hot-owl .owl-carousel .item img").css({"height" : hotowl1});
$(".hot-owl .owl-carousel .item video").css({"height" : hotowl1});
$(".hot-owl .owl-carousel .item video").css({"width" : hotowl1});
	}
	if(bodyw <= 600){
	var hotowl1=$(".hot-owl .owl-carousel").width()/2 - 20;
$(".hot-owl .owl-carousel .item img").css({"height" : hotowl1});
$(".hot-owl .owl-carousel .item video").css({"height" : hotowl1});
$(".hot-owl .owl-carousel .item video").css({"width" : hotowl1});
	}
	}
	
if($(".sub-list .owl-carousel .item").length > 0){
    $('.owl-carousel').owlCarousel({
      stagePadding: 10,
    loop:true,
    margin:10,
    nav:true,
    responsiveClass:true,
	navigation:true,
    responsive:{
        0:{
            items:4,
            nav:false,
			navigation:false,
			arrows: false,
        },
        600:{
            items:4,
            nav:false,
			navigation:false,
			arrows: false,
        },
        1000:{
            items:4,
            nav:true,
            loop:false
        }
    }
		}); 
		if(bodyw > 600){
	var sublist1=$(".sub-list .owl-carousel").width()/4 - 20;
$(".sub-list .owl-carousel .item img").css({"height" : sublist1});
$(".sub-list .owl-carousel .item video").css({"height" : sublist1});
$(".sub-list .owl-carousel .item video").css({"width" : sublist1});
	}
	if(bodyw <= 600){
	var sublist1=$(".sub-list .owl-carousel").width()/2 - 20;
$(".sub-list .owl-carousel .item img").css({"height" : sublist1});
$(".sub-list .owl-carousel .item video").css({"height" : sublist1});
$(".sub-list .owl-carousel .item video").css({"width" : sublist1});
	}
}
if($(".detail-tj .owl-carousel .item").length > 0){
 $('.detail-tj .owl-carousel').owlCarousel({
      stagePadding: 0,
    loop:true,
    margin:10,
	items:5,
    nav:true,
    responsiveClass:true,
	navigation:true,
    responsive:{
        0:{
            items:2,
            nav:false,
			navigation:false,
			arrows: false,
        },
        600:{
            items:3,
            nav:false,
			navigation:false,
			arrows: false,
        },
        1000:{
            items:5,
		}
	}
});
	}
	if($(".index-four-gg .owl-carousel .item").length > 0){
 $('.index-four-gg .owl-carousel').owlCarousel({
      stagePadding: 0,
    loop:false,
    margin:10,
	items:4,
    nav:false,
	navigation:false,
	autoplay:true,
    responsiveClass:true,
    responsive:{
        0:{
            items:2,
            nav:false,
			margin:0,
			navigation:false,
			arrows: false,
        },
        600:{
            items:2,
            nav:false,
			  margin:0,
			navigation:false,
			arrows: false,
        },
        1000:{
            items:4,
		}
	}
});
	}
	if($(".index-three-gg .owl-carousel .item").length > 0){
 $('.index-three-gg .owl-carousel').owlCarousel({
      stagePadding: 0,
    loop:false,
    margin:10,
	items:3,
    nav:false,
	navigation:false,
	autoplay:true,
    responsiveClass:true,
    responsive:{
        0:{
            items:2,
            nav:false,
			margin:0,
			navigation:false,
			arrows: false,
        },
        600:{
            items:2,
            nav:false,
			margin:0,
			navigation:false,
			arrows: false,
        },
        1000:{
            items:3,
		}
	}
});
	}
	if($(".index-two-gg .owl-carousel .item").length > 0){
 $('.index-two-gg .owl-carousel').owlCarousel({
      stagePadding: 0,
    loop:false,
    margin:10,
	items:2,
    nav:false,
	navigation:false,
	autoplay:true,
    responsiveClass:true,
    responsive:{
        0:{
            items:2,
            nav:false,
			margin:0,
			navigation:false,
			arrows: false,
        },
        600:{
            items:2,
            nav:false,
			margin:0,
			navigation:false,
			arrows: false,
        },
        1000:{
            items:2,
		}
	}
});
	}
	if($(".index-one-gg .owl-carousel .item").length > 0){
 $('.index-one-gg .owl-carousel').owlCarousel({
      stagePadding: 0,
    loop:false,
    margin:0,
	items:1,
    nav:false,
	navigation:false,
	autoplay:true,
    responsiveClass:true,
    responsive:{
        0:{
            items:1,
            nav:false,
			margin:0,
			navigation:false,
			arrows: false,
        },
        600:{
            items:1,
            nav:false,
			margin:0,
			navigation:false,
			arrows: false,
        },
        1000:{
            items:1,
		}
	}
});
	}
$("#two-fq").click(function(){
	$(".three-fq").hide();
$(".two-fq").show();
});
$(".m-search-k").click(function(){
	$(".search").show();
	$("header").css({"height" : 115});
});

		if(bodyw > 600){
	var detailtj1=$(".detail-tj .owl-carousel").width()/5 - 20;
$(".detail-tj .owl-carousel .item video").css({"height" : detailtj1});
$(".detail-tj .owl-carousel .item video").css({"width" : detailtj1});
	}
	if(bodyw <= 600){
	var detailtj1=$(".detail-tj .owl-carousel").width()/2 - 20;
$(".detail-tj .owl-carousel .item video").css({"height" : detailtj1});
$(".detail-tj .owl-carousel .item video").css({"width" : detailtj1});
	}



$(".add-loc").click(function(){$(".location-form").toggle()});
$(".news-loc").click(function(){$(".news-loc-form").show();
$(".location-list").hide();$(".news-loc").hide();
$(".old-loc").show()});
$(".old-loc").click(function(){$(".news-loc-form").hide();
$(".location-list").show();$(".news-loc").show();
$(".old-loc").hide();});
var bwidth=$("body").width();
if(bwidth<=768){
	$(".member-nav .nav-collapse").click(function(){
		$(".member-nav nav").toggle();})}
		$(".wap-nav-open").click(function(){
			$(".wap-nav-togger").show();});
			$(".wap-nav-togger .wap-open button").click(function(){
				$(".wap-nav-togger").hide()});$("#three-fq").click(function(){$(".three-fq").show();$(".two-fq").hide()});$("#two-fq").click(function(){$(".three-fq").hide();$(".two-fq").show()})});
var isCheckAll=false;
$(".checkboxAll input").click(function(){if(isCheckAll){$("input[type='checkbox']").each(function(){this.checked=false});isCheckAll=false}else{$("input[type='checkbox']").each(function(){this.checked=true});isCheckAll=true}});$(".cart-box .checkbox input").click(function(){var allcheckbox=$(".checkbox").length;var allcheckboxs=$(".checkbox input[type='checkbox']:checked").length;if(allcheckboxs<allcheckbox){$(".checkboxAll input[type='checkbox']").attr("checked",false)}else{$(".checkboxAll input[type='checkbox']").attr("checked",true)}

});
$(window).resize(function(){
var bodyw=$("body").width();
var saleproductw=$(".sale-product .list-one").width();
$(".sale-product .list-one .list-pic img").css({"width" : saleproductw});
$(".sale-product .list-one .list-pic video").css({"width" : saleproductw});
$(".sale-product .list-one .list-pic video").css({"height" : saleproductw});
if(bodyw > 600){
	var detailtj1=$(".detail-tj .owl-carousel").width()/4 - 20;
$(".detail-tj .owl-carousel .item video").css({"height" : detailtj1});
$(".detail-tj .owl-carousel .item video").css({"width" : detailtj1});
	}
	if(bodyw <= 600){
var detailtj1=$(".detail-tj .owl-carousel").width()/2 - 20;
$(".detail-tj .owl-carousel .item video").css({"height" : detailtj1});
$(".detail-tj .owl-carousel .item video").css({"width" : detailtj1});
}
if(bodyw > 600){
	var newsowl1=$(".news-owl .owl-carousel").width()/4 - 20;
$(".news-owl .owl-carousel .item video").css({"height" : newsowl1});
$(".news-owl .owl-carousel .item video").css({"width" : newsowl1});
	}
	if(bodyw <= 600){
	var newsowl1=$(".news-owl .owl-carousel").width()/2 - 20;
$(".news-owl .owl-carousel .item video").css({"height" : newsowl1});
$(".news-owl .owl-carousel .item video").css({"width" : newsowl1});
	}
	if(bodyw > 600){
	var sublist1=$(".sub-list .owl-carousel").width()/4 - 20;
$(".sub-list .owl-carousel .item img").css({"height" : sublist1});
$(".sub-list .owl-carousel .item img").css({"width" : sublist1});
	}
	if(bodyw <= 600){
	var sublist1=$(".sub-list .owl-carousel").width()/2 - 20;
$(".sub-list .owl-carousel .item img").css({"height" : sublist1});
$(".sub-list .owl-carousel .item video").css({"height" : sublist1});
$(".sub-list .owl-carousel .item video").css({"width" : sublist1});
	}
	if(bodyw > 600){
	var hotowl1=$(".hot-owl .owl-carousel").width()/4 - 20;
$(".hot-owl .owl-carousel .item img").css({"height" : hotowl1});
$(".hot-owl .owl-carousel .item video").css({"height" : hotowl1});
$(".hot-owl .owl-carousel .item video").css({"width" : hotowl1});
	}
	if(bodyw <= 600){
	var hotowl1=$(".hot-owl .owl-carousel").width()/2 - 20;
$(".hot-owl .owl-carousel .item img").css({"height" : hotowl1});
$(".hot-owl .owl-carousel .item video").css({"height" : hotowl1});
$(".hot-owl .owl-carousel .item video").css({"width" : hotowl1});
	}
			if(bodyw > 768){
			var locationbg=$(".hide-xs .location-bg").width()*600 / 1920;
		$(".hide-xs .location-bg").css({"height" : locationbg});
			}
			if(bodyw < 768){
			var locationbg1=$(".show-xs .location-bg").width()*5 / 3 ;
		$(".show-xs .location-bg").css({"height" : locationbg1});
			}
	});