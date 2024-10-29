jQuery(document).ready(function(){
								jQuery('.thumbnail').matchHeight();
    jQuery('#list').click(function(event){event.preventDefault();jQuery('#products .item').addClass('list-group-item');});
    jQuery('#grid').click(function(event){event.preventDefault();jQuery('#products .item').removeClass('list-group-item');jQuery('#products .item').addClass('grid-group-item');});
});


jQuery(window).on('load', function() {
jQuery(document).ready(function() {
    jQuery(".mCustomScrollbar").mCustomScrollbar({axis:"x"});
});
});


jQuery(document).ready(function(){
 jQuery('.bee-featured-slider').bxSlider({
	auto: true,
	 captions: true,
	 responsive: true,
    slideWidth: 200,
    minSlides: 1,
    maxSlides: 12,
    startSlide: 0,
    slideMargin: 10
	
  });
});