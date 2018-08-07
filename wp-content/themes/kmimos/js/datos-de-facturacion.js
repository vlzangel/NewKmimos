jQuery( document ).ready(function() {

	postJSON(
		'form_perfil', 
		URL_PROCESOS_PERFIL, 
		function( data ) {
			jQuery("#btn_actualizar").val("Procesando...");
			jQuery("#btn_actualizar").attr("disabled", true);
			jQuery(".perfil_cargando").css("display", "inline-block");
       	}, 
		function( data ) {
			data = JSON.parse(data);

			if( data.status == "OK"){
                $mensaje = "Los datos fueron actualizados";
            }else{
				$mensaje = "Lo sentimos no se pudo actualizar los datos";
            	if( typeof data.mensaje != 'undefined'  ){
            		$mensaje = data.mensaje;
            	}
            }

            jQuery('#btn_actualizar').before('<span class="mensaje">'+$mensaje+'</span>');            
			jQuery(".perfil_cargando").css("display", "none");
			jQuery("#btn_actualizar").val("Actualizar");
			jQuery("#btn_actualizar").removeAttr("disabled");

			setTimeout(function() {
            	jQuery('.mensaje').remove(); 
			},3000);
		}
	);

	jQuery(document).on('change', 'select[name="rc_estado"]', function(e){
		var estado_id = jQuery(this).val();
		    
	    if( estado_id != "" ){
	        cambio_municipio(estado_id);
	    }
	});

	jQuery(document).on('change', 'select[name="regimen_fiscal"]', function(e){
		var value = jQuery(this).val();
		jQuery('[data-regimen-fiscal]').addClass("hidden");
		jQuery('[name="razon_social"]').removeAttr('data-valid');
		jQuery('[name="nombre"]').removeAttr('data-valid');
		jQuery('[name="apellido_paterno"]').removeAttr('data-valid');
		jQuery('[name="apellido_materno"]').removeAttr('data-valid');

	    if( value == 'RGLPM' ){
			jQuery('[data-regimen-fiscal="razon_social"]').removeClass("hidden");
			jQuery('[name="razon_social"]').attr('data-valid','requerid');
			cambio_uso_cfdi(1);
	    }else{
			jQuery('[data-regimen-fiscal="persona_fisica"]').removeClass("hidden");
			jQuery('[name="nombre"]').attr('data-valid','requerid');
			jQuery('[name="apellido_paterno"]').attr('data-valid','requerid');
			jQuery('[name="apellido_materno"]').attr('data-valid','requerid');
			cambio_uso_cfdi();
	    }
	});

	jQuery('[data-selected]').on('click', function(e){
		e.preventDefault();
		if( !jQuery(this).hasClass('disabled') ){		
			var file = jQuery(this).attr("data-selected");
			var input = jQuery(this).attr("data-selected");
			jQuery('#'+file ).click();
		}
	});



	jQuery('input[type="file"]').on('change', function(e){

		var input = jQuery(this);
		var button = jQuery('[data-selected="'+jQuery(this).attr('name')+'"]');

		if( input[0].files.length > 0 && !button.hasClass("disabled") ){
			var destino = jQuery('[name="'+jQuery(this).attr('data-content')+'"]');

			var archivo = input[0].files[0];
			var form = new FormData();
				form.append("file", archivo);
				form.append("name", jQuery(this).attr('name') );

			jQuery('[type="submit"]').attr('disabled', 'disabled');
			button.addClass('disabled');
			button.html('<i class="fa fa-refresh fa-spin fa-3x fa-fw"></i> Cargando' );
			
			$.ajax({
			    url: HOME+"procesos/generales/convert_base64.php",
			    type: "post",
			    dataType: "html",
			    data: form,
			    cache: false,
			    contentType: false,
			    processData: false
			})
			.done(function(res){
				res = JSON.parse(res);
				if( res["estatus"] == 1 ){
					destino.val( res["codigo"] );
					input.val('');
				}
				button.html('Examinar' );
				button.removeClass('disabled');
				jQuery('[type="submit"]').removeAttr('disabled');
			})
			.fail(function(){
				button.html('Examinar' );
				button.removeClass('disabled');
				jQuery('[type="submit"]').removeAttr('disabled');				
			})

		}

	});

	jQuery('#rfc').on('change', function(e){
		var BUSQUEDA_REGEXP = '[A-Z&Ñ]{3,4}[0-9]{2}(0[1-9]|1[012])(0[1-9]|[12][0-9]|3[01])[A-Z0-9]{2}[0-9A]';
		var re = new RegExp(BUSQUEDA_REGEXP.toLowerCase());
		if ( re.test( jQuery(this).val().toLowerCase() ) ) {
			console.log(1);
		}else{
			console.log(0);
		}
	});

});


function cambio_uso_cfdi( is_moral = 0 ){
	jQuery.post( 
	HOME+"procesos/generales/uso_cfdi.php",
	{"is_moral": is_moral},
	'json'
	).done(function(data) {
		data = JSON.parse(data);
	    var html = "<option value=''>Seleccione el uso del CFDI</option>";
	    jQuery.each(data, function(i, val) {
	        html += "<option value="+val.codigo+">"+val.name+"</option>";
	    });
	    jQuery('select[name="uso_cfdi"]').html(html);
	})
	.fail(function(e) {
	   console.log('error');
	});
}

function cambio_municipio(estado_id, CB = false){
	jQuery.getJSON( 
        HOME+"procesos/generales/municipios.php", 
        {estado: estado_id} 
    ).done(
        function( data, textStatus, jqXHR ) {
            var html = "<option value=''>Seleccione un municipio</option>";
            jQuery.each(data, function(i, val) {
                html += "<option value="+val.name+">"+val.name+"</option>";
            });
            jQuery('[name="rc_municipio"]').html(html);
        }
    ).fail(
        function( jqXHR, textStatus, errorThrown ) {
            console.log( "Error: " +  errorThrown );
        }
    );
}