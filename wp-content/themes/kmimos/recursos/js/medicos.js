jQuery( document ).ready(function() {
	jQuery("#especialidad").on('change', (e) => {
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
} );

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
			var HTML = '';
			jQuery.each(data, (i, v) => {
				HTML += '<div class="medico_item" data-id="'+v.id+'">';
		    	HTML += '	<div class="medico_img_container"> <div class="medico_img" style="background-image: url( '+v.img+' )"></div> </div>';
		    	HTML += '	<div class="medico_info">';
		    	HTML += '		<div class="medico_nombre">'+v.name+'</div>';
		    	HTML += '		<div class="medico_universidad">'+v.univ+'</div>';
		    	HTML += '		<div class="medico_precio">'+v.price+'$</div>';
		    	HTML += '	</div>';
		    	HTML += '</div>';
			});
			jQuery(".medicos_list").html( HTML );
			jQuery(".medico_item").unbind('click').bind('click', (e) => {

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

function cargar( id ){

	jQuery.post(
		HOME+'/procesos/medicos/info.php',
		{
			id: id
		}, (data) => {

			// console.log( data );

			var img = ( data.profilePic != undefined ) ? data.profilePic : 'http://www.psi-software.com/wp-content/uploads/2015/07/silhouette-250x250.png' ;
			jQuery(".medicos_details .medico_ficha_img").css("background-image", "url("+img+")");
			if( data.certifications != undefined ){
				jQuery(".medicos_details .medico_ficha_info_certificaciones > div").html( data.certifications );
				jQuery(".medicos_details .medico_ficha_info_certificaciones").css( 'display', 'block' );
			}else{
				jQuery(".medicos_details .medico_ficha_info_certificaciones").css( 'display', 'none' );
			}

			if( data.medicInfo.courses != undefined ){
				jQuery(".medicos_details .medico_ficha_info_cursos > div").html( data.medicInfo.courses );
				jQuery(".medicos_details .medico_ficha_info_cursos").css( 'display', 'block' );
			}else{
				jQuery(".medicos_details .medico_ficha_info_cursos").css( 'display', 'none' );
			}

			if( data.medicInfo.courses != undefined ){
				jQuery(".medicos_details .medico_ficha_info_experiencia > div").html( data.medicInfo.formerExperience );
				jQuery(".medicos_details .medico_ficha_info_experiencia").css( 'display', 'block' );
			}else{
				jQuery(".medicos_details .medico_ficha_info_experiencia").css( 'display', 'none' );
			}

			if( data.medicInfo.courses != undefined ){
				jQuery(".medicos_details .medico_ficha_info_otros > div").html( data.medicInfo.otherStudies );
				jQuery(".medicos_details .medico_ficha_info_otros").css( 'display', 'block' );
			}else{
				jQuery(".medicos_details .medico_ficha_info_otros").css( 'display', 'none' );
			}

			jQuery(".medicos_details .medico_ficha_info_name > label").html( data.firstName+' '+data.lastName );
			jQuery(".medicos_details .medico_ficha_info_name > div").html( NF(data.distance)+' km de tu ubicación' );

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
				HORARIO += 		'<div>';
							jQuery.each(v.items, (i2, v2) => {
								HORARIO +='<span '+MODAL+' data-date="'+v2[1]+'">'+v2[0]+'</span>';
							});
				HORARIO += 		'</div>';
				HORARIO += '</div>';
			});

			jQuery(".medico_ficha_horario_container > div").html( HORARIO );

			jQuery(".reservar_btn").unbind("click").bind("click", (e) => {
				// var id = e.currentTarget.dataset.id;
				console.log( e.currentTarget.dataset );


				jQuery('#reservar_medico').modal('show');

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