jQuery( document ).ready(function() {
	fixedHeader();
	jQuery(window).on('scroll', function () {
	  	fixedHeader();
	});
});

function fixedHeader() {
    var ajustar = true;
    if( !jQuery('nav').hasClass("nav_busqueda") ){ ajustar = false; }
    if( parseInt( jQuery("body").width() ) < 768 ){ ajustar = true; }
    if( ajustar ){
        let ww = jQuery(window).scrollTop();
        if (ww > 0) {
            jQuery('nav').addClass('nav_white');
        } else {
            jQuery('nav').removeClass('nav_white');
        }
    }
}