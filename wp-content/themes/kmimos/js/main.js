function menu(){
	var w = jQuery(window).width();

	if(jQuery(this).scrollTop() > 10) {

		jQuery('.bg-transparent').addClass('bg-white');

	} else {

		jQuery('.bg-transparent').removeClass('bg-white');
		
	}

/*	if(jQuery(this).scrollTop() > 10) {
		jQuery('.bg-transparent').addClass('bg-white');
		jQuery('.navbar-brand img').attr('src', HOME+'images/new/km-logos/km-logo-negro.png');


		jQuery('.nav-sesion .km-avatar').attr('src', AVATAR);
		jQuery('.nav-sesion .dropdown-toggle img').css('width','60px');


		jQuery('.nav-sesion .dropdown-toggle').css('padding','0px');
		jQuery('.nav-sesion .dropdown-toggle').removeClass('pd-tb11');
		jQuery('.nav-login').addClass('dnone');
		jQuery('.navbar').css('padding-top', '7px');
		jQuery('.navbar').css('height', '77px');

		jQuery('.bg-white-secondary').css('height','75px');

		if( w < 768 ){
			jQuery('.nav li').css('padding','10px 15px');
			jQuery('.nav li a').css('padding','10px 15px');
		}
		if( w >= 768 ){
			jQuery('a.km-nav-link, .nav-login li a').css('color','black');
			jQuery('.bg-white-secondary a.km-nav-link, .bg-white-secondary .nav-login li a').css('color','black');
		}
	} else {

		jQuery('.bg-transparent').removeClass('bg-white');
		jQuery('.navbar-brand img').attr('src', HOME+'/images/new/km-logos/km-logo.png');
		
		jQuery('.nav-sesion .km-avatar').attr('src', AVATAR);

		jQuery('.navbar-brand img').css('height','60px');

		jQuery('.nav-login').removeClass('dnone');
		jQuery('.navbar').css('padding-top', '30px');
		jQuery('.navbar').css('height', '77px');

		jQuery('.bg-white-secondary').css('height','100px');
		jQuery('.bg-white-secondary .navbar-brand img').attr('src', HOME+'images/new/km-logos/km-logo-negro.png');

		if( w < 768 ){
			jQuery('.nav li').css('padding','10px 15px');
			jQuery('.nav li a').css('padding','10px 15px');
		}
		if( w >= 768 ){
 			jQuery('a.km-nav-link, .nav-login li a').css('color','white');
			jQuery('.bg-white-secondary a.km-nav-link, .bg-white-secondary .nav-login li a').css('color','black');
		}
	}*/

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
