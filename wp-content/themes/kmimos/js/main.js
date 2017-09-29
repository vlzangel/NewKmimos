function playVideo(e) {
	var el = jQuery(e);
	var p = el.parent().parent().parent();
	jQuery('video', p).get(0).play();
	jQuery('.km-testimonial-text').css('display','none');
	jQuery('.img-testimoniales').css('display','none');
	jQuery('video').css('display','block');
}
function stopVideo(){
	jQuery.each( jQuery('video'), function(i, e){
		e.pause();
		e.currentTime = 0;
	});
	jQuery('.km-testimonial-text').css('display','block');
	jQuery('.img-testimoniales').css('display','block');
	jQuery('video').css('display','none');	
}

jQuery(document).on('click', '.control-video', function(e){
	stopVideo();
});

function menu(){
	var w = jQuery(window).width();
	if(jQuery(this).scrollTop() > 10) {
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
	}
}

function mapStatic( e ){
	var w = jQuery(e);
	if ( w.width() > 991 ) {
		var scrollTop = w.scrollTop();
		var mapPrin = jQuery(".km-caja-resultados");
		var mapElem = jQuery(".km-caja-resultados .km-columna-der");
		var offset = mapPrin.offset();
		var topPre = 41;

		if ( scrollTop > 290 ) {
			mapElem.addClass("mapAbsolute");
			var topSumar = scrollTop - offset.top + topPre;
			mapElem.css({
				top: topSumar
			});
		} else {
			mapElem.removeClass("mapAbsolute");
		}
	}
}

jQuery(window).resize(function() {
	menu();
});

jQuery(window).scroll(function() {

	if( pines != undefined ){
		if( pines.length > 1 ){
			mapStatic( this );
		}
	}
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

	jQuery(".datepick td").on("click", function(e){
		jQuery( this ).children("a").click();
	});

	function getCleanedString(cadena){
		var specialChars = "!@#jQuery^&%*()+=-[]\/{}|:<>?,.";
		for (var i = 0; i < specialChars.length; i++) {
			cadena= cadena.replace(new RegExp("\\" + specialChars[i], 'gi'), '');
		}   
		cadena = cadena.toLowerCase();
		cadena = cadena.replace(/ /g," ");
		cadena = cadena.replace(/á/gi,"a");
		cadena = cadena.replace(/é/gi,"e");
		cadena = cadena.replace(/í/gi,"i");
		cadena = cadena.replace(/ó/gi,"o");
		cadena = cadena.replace(/ú/gi,"u");
		cadena = cadena.replace(/ñ/gi,"n");
		return cadena;
	}

	jQuery("#ubicacion_txt").on("keyup", function ( e ) {		
		var buscar_1 = getCleanedString( String(jQuery("#ubicacion_txt").val()).toLowerCase() );

		jQuery("#ubicacion_list div").css("display", "none");
		jQuery("#ubicacion_list div").each(function( index ) {
			if( String(jQuery( this ).attr("data-value")).toLowerCase().search(buscar_1) != -1 ){
				jQuery( this ).css("display", "block");
				if( index == 0 ){
					/*jQuery("#ubicacion").val( jQuery( this ).html() );
					jQuery("#ubicacion").attr( "data-value", jQuery( this ).attr("data-value") );*/
				}
			}
		});
	});

	jQuery("#ubicacion_txt").on("focus", function ( e ) {		
		var buscar_1 = getCleanedString( String(jQuery("#ubicacion_txt").val()).toLowerCase() );

		jQuery("#ubicacion_list div").css("display", "none");
		jQuery("#ubicacion_list div").each(function( index ) {
			if( String(jQuery( this ).attr("data-value")).toLowerCase().search(buscar_1) != -1 ){
				jQuery( this ).css("display", "block");
			}
		});
	});

	jQuery("#ubicacion_txt").on("change", function ( e ) {		
		var txt = getCleanedString( String(jQuery("#ubicacion_txt").val()).toLowerCase() );
		if( txt == "" ){
			jQuery("#ubicacion").val( "" );
			jQuery("#ubicacion").attr( "data-value", "" );
		}
	});

	jQuery(window).scroll(function() {
		menu();
	});

	jQuery.post(
		HOME+"/procesos/busqueda/ubicacion.php",
		{},
		function(data){
			jQuery("#ubicacion_list").html(data);
			jQuery("#ubicacion_list div").on("click", function(e){
				jQuery("#ubicacion_txt").val( jQuery(this).html() );
				jQuery("#ubicacion").val( jQuery(this).attr("value") );
				jQuery("#ubicacion").attr( "data-value", jQuery(this).attr("data-value") );
			});
			jQuery("#ubicacion_txt").attr("readonly", false);
		}
	);

	jQuery('.bxslider').bxSlider({
	  buildPager: function(slideIndex){
		switch(slideIndex){
		  case 0:
			return '<img src="'+HOME+'images/new/km-testimoniales/thumbs/testimonial-1.jpg">';
		  case 1:
			return '<img src="'+HOME+'images/new/km-testimoniales/thumbs/testimonial-2.jpg">';
		  case 2:
			return '<img src="'+HOME+'images/new/km-testimoniales/thumbs/testimonial-3.jpg">';
		}
	  }
	});
	jQuery('.km-premium-slider').bxSlider({
	    slideWidth: 200,
	    minSlides: 1,
	    maxSlides: 3,
	    slideMargin: 10
	  });

	jQuery('.km-galeria-cuidador-slider').bxSlider({
	    slideWidth: 200,
	    minSlides: 1,
	    maxSlides: 3,
	    slideMargin: 10
	});

	jQuery(document).on("click", '.show-map-mobile', function ( e ) {
		e.preventDefault();
		jQuery(".km-map-content").addClass("showMap");
	});

	jQuery(document).on("click", '.km-map-content .km-map-close', function ( e ) {
		e.preventDefault();
		jQuery(".km-map-content").removeClass("showMap");
	});

	function initCheckin(date, actual){
		if(actual){
			jQuery('#checkout').datepick({
				dateFormat: 'dd/mm/yyyy',
				defaultDate: date,
				selectDefaultDate: true,
				minDate: date,
				onSelect: function(xdate) {
					if(typeof calcular === 'function') {
						calcular();
					}
				},
				yearRange: date.getFullYear()+':'+(parseInt(date.getFullYear())+1),
				firstDay: 1,
				onmonthsToShow: [1, 1]
			});
		}else{
			jQuery('#checkout').datepick({
				dateFormat: 'dd/mm/yyyy',
				minDate: date,
				onSelect: function(xdate) {
					if(typeof calcular === 'function') {
						calcular();
					}
				},
				yearRange: date.getFullYear()+':'+(parseInt(date.getFullYear())+1),
				firstDay: 1,
				onmonthsToShow: [1, 1]
			});
		}
	}

	jQuery('#checkin').datepick({
		dateFormat: 'dd/mm/yyyy',
		minDate: fecha,
		onSelect: function(date1) {
			var ini = jQuery('#checkin').datepick( "getDate" );
			var fin = jQuery('#checkout').datepick( "getDate" );
			if( fin.length > 0 ){
				var xini = ini[0].getTime();
				var xfin = fin[0].getTime();
				if( xini > xfin ){
	            	jQuery('#checkout').datepick('destroy');
					initCheckin(date1[0], true);
	            }else{
	            	jQuery('#checkout').datepick('destroy');
					initCheckin(date1[0], false);
	            }
			}else{
				jQuery('#checkout').datepick('destroy');
				initCheckin(date1[0], true);
			}
			if(typeof calcular === 'function') {
				calcular();
			}
			if(typeof validar_busqueda_home === 'function') {
				validar_busqueda_home();
			}
		},
		yearRange: fecha.getFullYear()+':'+(parseInt(fecha.getFullYear())+1),
		firstDay: 1,
		onmonthsToShow: [1, 1]
	});

	jQuery('#checkout').datepick({
		dateFormat: 'dd/mm/yyyy',
		minDate: fecha,
		onSelect: function(xdate) {
			if(typeof calcular === 'function') {
				calcular();
			}
		},
		yearRange: fecha.getFullYear()+':'+(parseInt(fecha.getFullYear())+1),
		firstDay: 1,
		onmonthsToShow: [1, 1]
	});

	jQuery("#buscar").on("click", function ( e ) {
		e.preventDefault();
		jQuery("#buscador").submit();
	});

	jQuery("#buscar_no").on("click", function ( e ) {
		e.preventDefault();
		jQuery("#buscador").submit();
	});


	jQuery('.km-servicio-opcion').on('click', function(e) {
		/*jQuery(this).toggleClass('km-servicio-opcionactivo');*/
	});

	jQuery("#form_cuidador").submit(function(e){
		if( jQuery("#checkin").val() == "" ){
			jQuery("#checkin").css("border", "solid 1px red");
			jQuery("#checkout").css("border", "solid 1px red");
			jQuery(".validacion_fechas").css("display", "block");

			jQuery(".validacion_fechas").css("display", "block");
			jQuery(".km-ficha-fechas").css("margin-bottom", "0px");
        	e.preventDefault();
		}
    });

});
