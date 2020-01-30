/*
// Proceso Openpay
var __FORM_PAGO__ = 'reserva_form';
var __CB_PAGO_OK__ = function(){
	jQuery("#btn_reservar").html("Procesando...");
	jQuery.post(
		HOME+'/procesos/medicos/RESERVA/pagar.php',
		jQuery("#"+__FORM_PAGO__).serialize(),
		function(res){
			debug(res);
			if( res.error == false ){
				location.href = RAIZ+"/finalizar/"+res.cid;
			}else{
				jQuery(".errores_box").html( res.msg );
				jQuery(".errores_box").css("display", "block");

				jQuery("#btn_reservar").html("Solicitar Consulta");
				jQuery("#btn_reservar").prop("disabled", false);
			}
		}, 
		'json'
	);
}

var __CB_PAGO_KO__ = function(){
	jQuery("#btn_reservar").html("Solicitar Consulta");
	jQuery("#btn_reservar").prop("disabled", false);
} 
*/

// // Proceso Conekta //
var __FORM_PAGO__ = 'reserva_form';
var __CB_PAGO_OK__ = function(token){
	console.log(token);
	jQuery("#cita_token").val( token.id );
	jQuery("#btn_reservar").html("Procesando...");
	jQuery.post(
		HOME+'/procesos/medicos/RESERVA/pagar.php',
		jQuery("#"+__FORM_PAGO__).serialize(),
		function(res){
			debug(res);
			if( res.error == false ){
				location.href = RAIZ+"/finalizar/"+res.cid;
			}else{
				jQuery(".errores_box").html( res.msg );
				jQuery(".errores_box").css("display", "block");

				jQuery("#btn_reservar").html("Solicitar Consulta");
				jQuery("#btn_reservar").prop("disabled", false);
			}
		}, 
		'json'
	);
}

var __CB_PAGO_KO__ = function(error){
	console.log(error);
	jQuery("#btn_reservar").html("Solicitar Consulta");
	jQuery("#btn_reservar").prop("disabled", false);
}

var geocoder;
var map;
function initialize() {
	geocoder = new google.maps.Geocoder();
	get_ubicacion();
}

function get_coordenadas() {
	var state = jQuery('[name="state"] option:selected').html();
	var provincia = jQuery('[name="provincia"] option:selected').html();
	var colonia = jQuery('[name="colonia"] option:selected').html();
	if( state != "" &&  provincia != "" ){
		var address = ( colonia != '' && colonia != 'Seleccione...' ) ? colonia+'+' : '';
		address += state+'+Mexico';
		geocoder.geocode( { 'address': address}, function(results, status) {
			if (status == 'OK') {
			} else {
				alert('Geocode was not successful for the following reason: '+status);
			}
		});
	}
}

function get_ubicacion(){
	navigator.geolocation.getCurrentPosition( function(pos) {
        crd = pos.coords;
        jQuery('[name="cita_latitud"]').val( crd.latitude );
        jQuery('[name="cita_longitud"]').val( crd.longitude );
        var geocoder = new google.maps.Geocoder();
        var latlng = {lat: parseFloat(crd.latitude), lng: parseFloat(crd.longitude)};
        geocoder.geocode({'location': latlng}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                jQuery('[name="cita_direccion"]').val( results[0]['formatted_address'] );
            }
        });
    }, 
    function error(err) {
        if( err.message == 'User denied Geolocation' ){
            alert("Estimado usuario, para poder acceder a esta función, es necesario desbloquear a kmivet en la configuración de ubicación de su dispositivo.");
        }else{
            alert(err.message);
        }
    },{
        enableHighAccuracy: true,
        timeout: 5000,
        maximumAge: 0
    });
}

jQuery( document ).ready(function() {

	/* Sin uso por los momentos */
		jQuery('[name="state"]').on("change", function(e){
			jQuery.post(
				HOME+'/procesos/medicos/provincias.php',
				{ state: jQuery('[name="state"]').val() },
				function(provincias){
					jQuery('[name="provincia"]').html(provincias);
				}
			);
		});

		jQuery('[name="provincia"]').on("change", function(e){
			jQuery.post(
				HOME+'/procesos/medicos/colonias.php',
				{
					state: jQuery('[name="state"]').val(),
					provincia: jQuery('[name="provincia"]').val()
				},
				function(colonias){
					jQuery('[name="colonia"]').html(colonias);
					get_coordenadas();
				}
			);
		});

		jQuery('[name="colonia"]').on("change", function(e){
			get_coordenadas();
		});

	/* ================================================================= */

	jQuery('#reservar_medico').on('hidden.bs.modal', function (e) {
		jQuery("#modal_step_1").css('display', 'grid');
		jQuery("#modal_step_2").css('display', 'none');
	});

	jQuery("#cita_tipo_pago").on('change', function(e){
		jQuery("#modal_final_metodo").html( jQuery(this).val() );
	});
	jQuery("#modal_final_metodo").html( jQuery("#tipo_tarjeta").val() );

	jQuery("#especialidad").on('change', function(e) {
		jQuery(".modal_img_container > span").html( jQuery("#especialidad option:selected").text() );
		buscar();
	});
	buscar();

	jQuery(".pre-btn").on('click', (e) => {
		if( jQuery(".pre").css("display") == "none" ){
			jQuery(".pre").css("display", "block");
		}else{
			jQuery(".pre").css("display", "none");
		}
	});

	jQuery("#tipo_tarjeta").on("change", (e) => {
		if( jQuery("#tipo_tarjeta").prop("checked") === true ){
			jQuery(".form_tarjeta").css("display", "grid");
		}
	});

	jQuery("#tipo_efectivo").on("change", (e) => {
		if( jQuery("#tipo_efectivo").prop("checked") === true ){
			jQuery(".form_tarjeta").css("display", "none");
		}
	});

	jQuery("nav").addClass("nav_white");

	jQuery(".medico_ficha_info_container > div > label").on('click', function(e){
		var parent = jQuery(this).parent();
		jQuery(".medico_ficha_info_container > div").removeClass('active');
		parent.addClass('active');
		jQuery(".medico_ficha_info_box").html( parent.find('div').html() );
	});

	jQuery("#medico_nombre").on("keyup", function(e){
		var txt = jQuery(this).val();
		jQuery(".medico_item").each(function(i, v){
			var slug = String(jQuery(this).data("slug")).trim();
			if( slug.search(txt) == -1 ){
				jQuery(this).css("display", "none");
			}else{
				jQuery(this).css("display", "flex");
			}
		});
	});

	jQuery(".atras_ficha").on('click', function(e){
		jQuery(".medicos_container").removeClass('medico_ficha_si_cargada');
		jQuery(".medicos_container").addClass('medico_ficha_no_cargada');
	});


	/*  Proceso pago */
		
		jQuery("#btn_reservar").on("click", function(e){
			var latitud = jQuery('[name="cita_latitud"]').val();
			var longitud = jQuery('[name="cita_longitud"]').val();
			var direccion = jQuery('[name="cita_direccion"]').val();
			if( latitud == "" || longitud == "" || direccion == "" ){
				alert("Para tener acceso al servicio es necesario poder obtener su ubicación actual, por favor pícale en Permitir.");
				get_ubicacion();
			}else{
				jQuery("#btn_reservar").html("Validando...");
				jQuery("#btn_reservar").prop("disabled", true);
				
				// OpenPay.token.extractFormAndCreate(__FORM_PAGO__, sucess_callbak, error_callbak);

				
				Conekta.setPublicKey( KEY_CONEKTA );
				Conekta.setLanguage("es");
				Conekta.Token.create(jQuery("#reserva_form"), __CB_PAGO_OK__, __CB_PAGO_KO__);
				
			}
		});

} );

function debug( txt ){
	console.log( txt );
}

function buscar( CB ){
	jQuery(".medicos_list").html('<span>Cargando...</span>');

	var lat = jQuery("#latitud").val();
	var lng = jQuery("#longitud").val();

	lat = ( lat == '' ) ? 19.44907719008248 : parseFloat(lat);	
	lng = ( lng == '' ) ? -99.21679135411978 : parseFloat(lng);

	jQuery.post(
		HOME+'/procesos/medicos/BUSQUEDA/buscar.php',
		{
			specialty: jQuery("#especialidad").val(),
			lat: lat,
			lng: lng
		},
		( data ) => {
			
			debug( data );

			data = data[0];

			var HTML = '';
			jQuery.each(data, (i, v) => {
				HTML += '<div class="medico_item" data-id="'+v.id+'" data-slug="'+v.slug+'">';
		    	HTML += '	<div class="medico_img_container"> <div class="medico_img" style="background-image: url( '+v.img+' )"></div> </div>';
		    	HTML += '	<div class="medico_info">';
		    	HTML += '		<div class="medico_nombre">'+v.name+'</div>';
		    	HTML += '		<div class="medico_ranking">'+v.ranking+'</div>';
		    	HTML += '		<div class="medico_precio">';
		    	HTML += '			<div>Servicios desde</div>';
		    	HTML += '			<span>'+v.price+'</span>';
		    	HTML += '		</div>';
		    	HTML += '	</div>';
		    	HTML += '</div>';
			});
			jQuery(".medicos_list").html( HTML );
			jQuery(".medico_item").unbind('click').bind('click', function(e){
				jQuery(".medico_item").removeClass("active");
				jQuery(this).addClass("active");
				jQuery(".medicos_container").removeClass("medico_ficha_no_select");
				jQuery(".medicos_container").addClass("medico_ficha_no_cargada");
				var id = e.currentTarget.dataset.id;
				cargar( id );
			});

			jQuery(".medicos_container").removeClass("medico_ficha_no_select");
			jQuery(".medicos_container").removeClass("medico_ficha_si_cargada");
			jQuery(".medicos_container").addClass("medico_ficha_no_cargada");

			if( parseInt( jQuery("body").width() ) > 768 ){
				jQuery(".medicos_list > .medico_item:first-child").click();
			}
		} ,
		'json'
	);
}

var item_actual = '';

function cargar( id ){

	jQuery.post(
		HOME+'/procesos/medicos/BUSQUEDA/info.php',
		{ id: id }, (data) => {

			debug( data );

			data = data[0];
			jQuery("#specialty_id").val( jQuery("#especialidad").val() );
			item_actual = data;
			var first_item = '';

			var img = ( data.profilePic != undefined && data.profilePic != "" ) ? data.profilePic : HOME+'images/image.png' ;
			jQuery(".medicos_details .medico_ficha_img").css("background-image", "url("+img+")");

			if( data.certifications != undefined ){
				jQuery(".medicos_details .medico_ficha_info_certificaciones > div").html( data.certifications );
				jQuery(".medicos_details .medico_ficha_info_certificaciones").css( 'display', 'block' );
				first_item = 'medico_ficha_info_certificaciones';
			}else{
				jQuery(".medicos_details .medico_ficha_info_certificaciones").css( 'display', 'none' );
			}

			if( data.medicInfo != undefined ){
				if( data.medicInfo.courses != undefined ){
					jQuery(".medicos_details .medico_ficha_info_cursos > div").html( data.medicInfo.courses );
					jQuery(".medicos_details .medico_ficha_info_cursos").css( 'display', 'block' );
					if( first_item == '' ){ first_item = 'medico_ficha_info_cursos'; }
				}else{
					jQuery(".medicos_details .medico_ficha_info_cursos").css( 'display', 'none' );
				}

				if( data.medicInfo.formerExperience != undefined ){
					jQuery(".medicos_details .medico_ficha_info_experiencia > div").html( data.medicInfo.formerExperience );
					jQuery(".medicos_details .medico_ficha_info_experiencia").css( 'display', 'block' );
					if( first_item == '' ){ first_item = 'medico_ficha_info_experiencia'; }
				}else{
					jQuery(".medicos_details .medico_ficha_info_experiencia").css( 'display', 'none' );
				}

				if( data.medicInfo.otherStudies != undefined ){
					jQuery(".medicos_details .medico_ficha_info_otros > div").html( data.medicInfo.otherStudies );
					jQuery(".medicos_details .medico_ficha_info_otros").css( 'display', 'block' );
					if( first_item == '' ){ first_item = 'medico_ficha_info_otros'; }
				}else{
					jQuery(".medicos_details .medico_ficha_info_otros").css( 'display', 'none' );
				}
			}else{
				jQuery(".medicos_details .medico_ficha_info_cursos").css( 'display', 'none' );
				jQuery(".medicos_details .medico_ficha_info_experiencia").css( 'display', 'none' );
				jQuery(".medicos_details .medico_ficha_info_otros").css( 'display', 'none' );
			}

			if( first_item != '' ){
				jQuery("."+first_item+" > label").click();
			}

			jQuery(".medicos_details .medico_ficha_info_name > label").html( data.firstName ); // +' '+data.lastName
			jQuery("#modal_final_medico").html( data.firstName ); // +' '+data.lastName
			jQuery(".medicos_details .medico_ficha_info_name > div").html( NF(data.distance)+' km de tu ubicación' );
			jQuery(".medicos_details .medico_ficha_info_name > span").html( '$'+NF(data.price) );
			jQuery("#input_modal_precio").val( parseFloat(data.price) );


			jQuery(".modal_img").css("background-image", "url("+img+")");
			jQuery(".modal_info h2").html( data.firstName+' '+data.lastName );
			jQuery(".modal_img_container .ranking").html( data.rating );
			jQuery("#medico_id").val( id );

			jQuery(".medico_ficha_titulo > div").html( data.firstName+' '+data.lastName );
			jQuery(".medico_ficha_titulo > span").html( NF(data.distance)+' km de tu ubicación' );
			jQuery(".medico_ficha_titulo > strong").html( '$'+NF(data.price) );

			var HORARIO = ''; 
			var MODAL = '';
			if( USER_ID == '0' ){
				MODAL = ' data-target="#popup-iniciar-sesion" role="button" data-toggle="modal" ';
			}else{
				MODAL = ' class="reservar_btn" ';
			}

			var SHOW_ITEMS = 9; var PASO = 11;
			if( parseInt( jQuery("body").width() ) < 768 ){
				SHOW_ITEMS = 4; PASO = 24;
			}

			jQuery.each(data.agenda, (i, v) => {
				HORARIO += '<div>';
				HORARIO += 		'<label>'+v.fecha+'</label>';
				if( v.items.length > SHOW_ITEMS ){
					HORARIO += '<img class="horario_flecha horario_flecha_left" src="'+HOME+'/recursos/img/MEDICOS/left.png" />';
				}
				HORARIO += 		'<div><div class="horario_box" data-actual=0 data-paso='+PASO+' data-lenght="'+(v.items.length-SHOW_ITEMS)+'" >';
					jQuery.each(v.items, (i2, v2) => {
						HORARIO +='<span '+MODAL+' data-date_full="'+v2[1]+'" data-date="'+v2[2]+'">'+v2[0]+'</span>';
					});
				HORARIO += 		'</div></div>';
				if( v.items.length > SHOW_ITEMS ){
					HORARIO += '<img class="horario_flecha horario_flecha_right" src="'+HOME+'/recursos/img/MEDICOS/right.png" />';
				}
				HORARIO += '</div>';
			});

			jQuery(".medico_ficha_horario_container > div").html( HORARIO );

			jQuery(".medico_ficha_horario_container > div > div > div").swipe( {
		 		swipe: function(event, direction, distance, duration, fingerCount, fingerData) {
					if( jQuery(window).width() <= 768 ){
						// debug( direction );
			 			if( direction == 'right' ){
			 				jQuery(this).parent().find('.horario_flecha_left').click();
			 			}else{
			 				jQuery(this).parent().find('.horario_flecha_right').click();
			 			}
		 			}
		  		}
			});

			jQuery(".reservar_btn").unbind("click").bind("click", (e) => {
				jQuery(".modal_fecha").html( e.currentTarget.dataset.date_full );
				jQuery("#cita_fecha").val( e.currentTarget.dataset.date );
				jQuery(".modal_precio").html( 'MXN$ '+item_actual.price );
				jQuery("#modal_final_costo").html( 'MXN$ '+item_actual.price );
				jQuery('#reservar_medico').modal('show');

				jQuery("#modal_final_horario").html( e.currentTarget.dataset.date_full );

				jQuery("#btn_reservar").css("display", "inline-block");
			});

			jQuery(".horario_flecha_right").unbind("click").bind("click", function(e) {
				var parent = jQuery(this).parent();
				var box = parent.find(".horario_box");
				var actual = parseInt(box.attr('data-actual'));
				var lenght = parseInt(box.attr('data-lenght'));
				var paso = parseInt(box.attr('data-paso'));
				// debug( paso );
				if( actual < lenght ){
					actual += 1;
					box.attr('data-actual', actual);
				}
				parent.find(".horario_box").animate({
					left: "-"+(actual*paso)+"%"
				}, 500);
				box.attr('data-actual', actual);
			});

			jQuery(".horario_flecha_left").unbind("click").bind("click", function(e) {
				var parent = jQuery(this).parent();
				var box = parent.find(".horario_box");
				var actual = parseInt(box.attr('data-actual'));
				var lenght = parseInt(box.attr('data-lenght'));
				var paso = parseInt(box.attr('data-paso'));
				if( actual > 0 ){
					actual -= 1;
					box.attr('data-actual', actual);
				}
				parent.find(".horario_box").animate({
					left: "-"+(actual*paso)+"%"
				}, 500);
				
			});

			jQuery(".medicos_container").removeClass("medico_ficha_no_cargada");
			jQuery(".medicos_container").addClass("medico_ficha_si_cargada");

		},
		'json'
	);

}

function NF(numero){
	return new Intl.NumberFormat("de-DE").format(numero);
}

(function(d, s){
    map = d.createElement(s), e = d.getElementsByTagName(s)[0];
    map.async=!0;
    map.setAttribute("charset","utf-8");
    map.src="//maps.googleapis.com/maps/api/js?v=3&key="+KEY_MAPS+'&callback=initialize';
    map.type="text/javascript";
    e.parentNode.insertBefore(map, e);
})(document,"script");