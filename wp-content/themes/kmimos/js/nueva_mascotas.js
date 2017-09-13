jQuery( document ).ready(function() {
	postJSON( 
  		"form_perfil",
       	URL_PROCESOS_PERFIL, 
       	function( data ) {
			jQuery("#btn_actualizar").val("Procesando...");
            jQuery("#btn_actualizar").attr("disabled", true);
			jQuery(".perfil_cargando").css("display", "inline-block");
       	}, 
       	function( data ) {
       		jQuery(".vlz_img_portada_valor").val("");

            jQuery("#form_perfil").closest('form').find("input[type=text], textarea, input[type=date]").val("");
            jQuery("#form_perfil").closest('form').find("select option[value='']").prop("selected", true);

            jQuery('#form_perfil .vlz_img_portada_fondo').css("background-image", "url("+IMG_DEFAULT+")");
            jQuery('#form_perfil .vlz_img_portada_normal').css("background-image", "url("+IMG_DEFAULT+")");

			jQuery("#btn_actualizar").val("Crear Mascota");
			jQuery("#btn_actualizar").attr("disabled", false);
            jQuery(".perfil_cargando").css("display", "none");
       	}
   	);
    initImg("portada");

    jQuery("#form_perfil [data-valid]").each(function( index ) {
        pre_validar( jQuery( this ) );
    });

    jQuery("#pet_type").on("change", function(e){
        var valor = jQuery("#pet_type").val();
        if( valor == "2605" ){
            var opciones = jQuery("#razas_perros").html();
            jQuery("#pet_breed").html(opciones);
        }
        if( valor == "2608" ){
            var opciones = jQuery("#razas_gatos").html();
            jQuery("#pet_breed").html(opciones);
        }
    });

});