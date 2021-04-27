(function($) {
	'use strict';

	/*
	WOW slider
	=========================== */
	new WOW().init();
	
	/*
	Smooth scroll
	=========================== */
	$('ul.navbar-nav li a, .btn-scroll').smoothScroll();
	
	/*
	Tooltips
	=========================== */
	$('.tooltips').tooltip({
		 selector: "a[data-toggle='tooltip']"
	})
	
	/*
	Hover image
	=========================== */		
	$(".image-caption").css({'opacity':'0','filter':'alpha(opacity=0)'});
	$('.portfolio-wrapp').hover(
		function() {
			$(this).find('.image-caption').stop().fadeTo(800, 1);
			$(".image-link", this).stop().animate({top:'50%'},{queue:false,duration:200});
			$(".image-title", this).stop().animate({bottom:'50%'},{queue:false,duration:500});
		},
		function() {
			$(this).find('.image-caption').stop().fadeTo(800, 0);
			$(".image-link", this).stop().animate({top:'-10%'},{queue:false,duration:200});
			$(".image-title", this).stop().animate({bottom:'-40%'},{queue:false,duration:500});
		});

	/* Client logo hover
	=========================== */	
	$(".client-logo").css({'opacity':'0.85','filter':'alpha(opacity=0.85)'});
	$('.client-link').hover(function(){
				$(this).find('.client-logo').stop().fadeTo(900, 1);
	}, function() {
				$(this).find('.client-logo').stop().fadeTo(900, 0.85);
	});	

})(jQuery);