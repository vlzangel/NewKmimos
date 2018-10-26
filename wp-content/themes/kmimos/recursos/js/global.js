jQuery( document ).ready(function() {
	fixedHeader();
	jQuery(window).on('scroll', function () {
	  	fixedHeader();
	});

    if(navigator.platform.substr(0, 2) == 'iP'){
        /*jQuery('html').addClass('iOS');*/
        jQuery(".label-placeholder").addClass("focus");
    } else {
        jQuery(document).on("focus", "input.input-label-placeholder", function(){
            jQuery(this).parent().addClass("focus");
        }).on("blur", "input.input-label-placeholder", function(){
            let i = jQuery(this);
            if ( i.val() !== "" ) jQuery(this).parent().addClass("focused");
            else jQuery(this).parent().removeClass("focused");

            jQuery(this).parent().removeClass("focus");
        });
    }
    
});

function fixedHeader() {
    var ajustar = true;
    if( jQuery('nav').hasClass("nav_busqueda") ){ ajustar = false; }
    if( parseInt( jQuery("body").width() ) < 768 && jQuery('nav').hasClass("nav_busqueda") ){ ajustar = false; }
    if( ajustar ){
        let ww = jQuery(window).scrollTop();
        if (ww > 0) {
            jQuery('nav').addClass('nav_white');
        } else {
            jQuery('nav').removeClass('nav_white');
        }
    }
}