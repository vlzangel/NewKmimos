var __FORM_PAGO__ = 'reserva_form';
var __CB_PAGO_OK__ = function(){
	// debug('Ok');

	jQuery("#btn_reservar").html("Procesando...");

	jQuery.post(
		HOME+'/procesos/medicos/pagar.php',
		jQuery("#"+__FORM_PAGO__).serialize(),
		function(res){
			debug(res);

			if( res.errores.length == 0 ){
				jQuery("#reservar_medico .modal-title span").html("Cita Creada Exitosamente!");
				jQuery("#btn_reservar").css("display", "none");

				jQuery("#btn_reservar").html("Solicitar Cunsulta");
				jQuery("#btn_reservar").prop("disabled", false);

				jQuery(".vlz_limpiar").val('');

				jQuery("#modal_step_1").css('display', 'none');
				jQuery("#modal_step_2").css('display', 'block');
			}else{
				jQuery("#btn_reservar").html("Solicitar Cunsulta");
				jQuery("#btn_reservar").prop("disabled", false);
			}
			
		}, 
		'json'
	);
}

var __CB_PAGO_KO__ = function(){
	debug('Error');
}

jQuery( document ).ready(function() {

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

	jQuery("#btn_reservar").on("click", function(e){
		jQuery("#btn_reservar").html("Validando...");
		jQuery("#btn_reservar").prop("disabled", true);
		OpenPay.token.extractFormAndCreate(__FORM_PAGO__, sucess_callbak, error_callbak);
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
		HOME+'/procesos/medicos/buscar.php',
		{
			specialty: jQuery("#especialidad").val(),
			lat: lat,
			lng: lng
		},
		( data ) => {
			
			debug( data );

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

			jQuery(".medicos_list > .medico_item:first-child").click();
		} ,
		'json'
	);
}

var item_actual = '';

function cargar( id ){

	jQuery.post(
		HOME+'/procesos/medicos/info.php',
		{
			id: id
		}, (data) => {

			debug( data );

			jQuery("#specialty_id").val( jQuery("#especialidad").val() );

			item_actual = data;

			var first_item = '';

			var img = ( data.profilePic != undefined && data.profilePic != "" ) ? data.profilePic : 'http://www.psi-software.com/wp-content/uploads/2015/07/silhouette-250x250.png' ;
			jQuery(".medicos_details .medico_ficha_img").css("background-image", "url("+img+")");

			if( data.certifications != undefined ){
				jQuery(".medicos_details .medico_ficha_info_certificaciones > div").html( data.certifications );
				jQuery(".medicos_details .medico_ficha_info_certificaciones").css( 'display', 'block' );
				first_item = 'medico_ficha_info_certificaciones';
			}else{
				jQuery(".medicos_details .medico_ficha_info_certificaciones").css( 'display', 'none' );
			}

			if( data.medicInfo.courses != undefined ){
				jQuery(".medicos_details .medico_ficha_info_cursos > div").html( data.medicInfo.courses );
				jQuery(".medicos_details .medico_ficha_info_cursos").css( 'display', 'block' );
				if( first_item == '' ){ first_item = 'medico_ficha_info_cursos'; }
			}else{
				jQuery(".medicos_details .medico_ficha_info_cursos").css( 'display', 'none' );
			}

			if( data.medicInfo.courses != undefined ){
				jQuery(".medicos_details .medico_ficha_info_experiencia > div").html( data.medicInfo.formerExperience );
				jQuery(".medicos_details .medico_ficha_info_experiencia").css( 'display', 'block' );
				if( first_item == '' ){ first_item = 'medico_ficha_info_experiencia'; }
			}else{
				jQuery(".medicos_details .medico_ficha_info_experiencia").css( 'display', 'none' );
			}

			if( data.medicInfo.courses != undefined ){
				jQuery(".medicos_details .medico_ficha_info_otros > div").html( data.medicInfo.otherStudies );
				jQuery(".medicos_details .medico_ficha_info_otros").css( 'display', 'block' );
				if( first_item == '' ){ first_item = 'medico_ficha_info_otros'; }
			}else{
				jQuery(".medicos_details .medico_ficha_info_otros").css( 'display', 'none' );
			}

			if( first_item != '' ){
				jQuery("."+first_item+" > label").click();
			}

			jQuery(".medicos_details .medico_ficha_info_name > label").html( data.firstName+' '+data.lastName );
			jQuery("#modal_final_medico").html( data.firstName+' '+data.lastName );
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
			jQuery.each(data.agenda, (i, v) => {
				HORARIO += '<div>';
				HORARIO += 		'<label>'+v.fecha+'</label>';
				if( v.items.length > 9 ){
					HORARIO += '<img class="horario_flecha horario_flecha_left" src="'+HOME+'/recursos/img/MEDICOS/left.png" />';
				}
				HORARIO += 		'<div><div class="horario_box" data-actual=0 data-lenght="'+(v.items.length-9)+'" >';
					jQuery.each(v.items, (i2, v2) => {
						HORARIO +='<span '+MODAL+' data-date_full="'+v2[1]+'" data-date="'+v2[2]+'">'+v2[0]+'</span>';
					});
				HORARIO += 		'</div></div>';
				if( v.items.length > 9 ){
					HORARIO += '<img class="horario_flecha horario_flecha_right" src="'+HOME+'/recursos/img/MEDICOS/right.png" />';
				}
				HORARIO += '</div>';
			});

			jQuery(".medico_ficha_horario_container > div").html( HORARIO );

			jQuery(".reservar_btn").unbind("click").bind("click", (e) => {
				jQuery(".modal_fecha").html( e.currentTarget.dataset.date_full );
				jQuery("#cita_fecha").val( e.currentTarget.dataset.date );
				jQuery(".modal_precio").html( 'MXN$ '+item_actual.price );
				jQuery("#modal_final_costo").html( 'MXN$ '+item_actual.price );
				jQuery('#reservar_medico').modal('show');

				jQuery("#modal_final_horario").html( e.currentTarget.dataset.date_full );
			});

			jQuery(".horario_flecha_right").unbind("click").bind("click", function(e) {
				var parent = jQuery(this).parent();
				var box = parent.find(".horario_box");
				var actual = parseInt(box.attr('data-actual'));
				var lenght = parseInt(box.attr('data-lenght'));
				if( actual < lenght ){
					actual += 1;
					box.attr('data-actual', actual);
				}
				parent.find(".horario_box").animate({
					left: "-"+(actual*11)+"%"
				}, 500);
				box.attr('data-actual', actual);
			});

			jQuery(".horario_flecha_left").unbind("click").bind("click", function(e) {
				var parent = jQuery(this).parent();
				var box = parent.find(".horario_box");
				var actual = parseInt(box.attr('data-actual'));
				var lenght = parseInt(box.attr('data-lenght'));
				if( actual > 0 ){
					actual -= 1;
					box.attr('data-actual', actual);
				}
				parent.find(".horario_box").animate({
					left: "-"+(actual*11)+"%"
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