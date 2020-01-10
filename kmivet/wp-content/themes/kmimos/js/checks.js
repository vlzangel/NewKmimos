jQuery( document ).ready(function() {
    jQuery(".vlz_pin_check").on("click", function(){
	    if( jQuery(this).hasClass("vlz_no_check") ){
	        jQuery("input", this).attr("value", "1");
	        jQuery(this).removeClass("vlz_no_check");
	        jQuery(this).addClass("vlz_check");
	    }else{
	        jQuery("input", this).attr("value", "0");
	        jQuery(this).removeClass("vlz_check");
	        jQuery(this).addClass("vlz_no_check");
	    }
	});
});