function menu(){
	var w = jQuery(window).width();

	if(jQuery(this).scrollTop() > 10) {

		jQuery('.bg-transparent').addClass('bg-white');

	} else {

		jQuery('.bg-transparent').removeClass('bg-white');
		
	}

}

jQuery(window).resize(function() {
	menu();
});

jQuery(window).scroll(function() {
	menu();
});

var fecha = new Date();
jQuery(document).ready(function(){
	menu();

	jQuery(document).on("focus", "input.input-label-placeholder", function(){
		jQuery(this).parent().addClass("focus");
	}).on("blur", "input.input-label-placeholder", function(){
		let i = jQuery(this);
		if ( i.val() !== "" ) jQuery(this).parent().addClass("focused");
		else jQuery(this).parent().removeClass("focused");

		jQuery(this).parent().removeClass("focus");
	});

});
