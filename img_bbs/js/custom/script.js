// JavaScript Document

$(function(){

	$(window).bind("scroll", function() {
		if ($(this).scrollTop() > $(window).height() * 0.2) { 
			$(".gotoTopBtn").fadeIn();
		} else {
			$(".gotoTopBtn").fadeOut();
		}
	});

});
