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

console.log(value);

	    if( value == 'persona_moral' ){
			jQuery('[data-regimen-fiscal="razon_social"]').removeClass("hidden");
			jQuery('[name="razon_social"]').attr('data-valid','requerid');
	    }else{
			jQuery('[data-regimen-fiscal="persona_fisica"]').removeClass("hidden");
			jQuery('[name="nombre"]').attr('data-valid','requerid');
			jQuery('[name="apellido_paterno"]').attr('data-valid','requerid');
			jQuery('[name="apellido_materno"]').attr('data-valid','requerid');
	    }
	});
});

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