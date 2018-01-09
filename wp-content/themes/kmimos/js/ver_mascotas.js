jQuery( document ).ready(function() {
	
    jQuery("input[value='Actualizar']").on("click", function(e){
        if(jQuery("input[name='pet_name']").val()){
            jQuery("#spanpet_name").addClass('hidden');
        }else{
            jQuery("#spanpet_name").css('color', 'red');
            jQuery("#spanpet_name").removeClass('hidden');
            jQuery("input[name='pet_name']").focus(function() { jQuery("#spanpet_name").addClass('hidden'); });
        }

        if(jQuery("input[name='pet_birthdate']").val()){
            jQuery("#spanpet_birthdate").addClass('hidden');
        }else{
            jQuery("#spanpet_birthdate").css('color', 'red');
            jQuery("#spanpet_birthdate").removeClass('hidden');
            jQuery("input[name='pet_birthdate']").focus(function() {jQuery("#spanpet_birthdate").addClass('hidden'); });
        }

        if(jQuery("#pet_type").val()){     
            jQuery("#spanpet_type").addClass('hidden');
        }else{
            jQuery("#spanpet_type").css('color', 'red');
            jQuery("#spanpet_type").removeClass('hidden');
            jQuery("#pet_type").focus(function() { jQuery("#spanpet_type").addClass('hidden'); });
        }

        if(jQuery("#pet_breed").val()){     
            jQuery("#spanpet_breed").addClass('hidden');
        }else{
            jQuery("#spanpet_breed").css('color', 'red');
            jQuery("#spanpet_breed").removeClass('hidden');
            jQuery("#pet_breed").focus(function() { jQuery("#spanpet_breed").addClass('hidden'); });
        }

        if(jQuery("[name='pet_colors']").val()){     
            jQuery("#spanpet_colors").addClass('hidden');
        }else{
            jQuery("#spanpet_colors").css('color', 'red');
            jQuery("#spanpet_colors").removeClass('hidden');
            jQuery("[name='pet_colors']").focus(function() { jQuery("#spanpet_colors").addClass('hidden'); });
        }

        if(jQuery("[name='pet_gender']").val().length == 0){        
            jQuery("#spanpet_gender").css('color', 'red');
            jQuery("#spanpet_gender").removeClass('hidden');
            jQuery("#pet_breed").focus(function() { jQuery("#spanpet_gender").addClass('hidden'); });
        }else{
            jQuery("#spanpet_gender").addClass('hidden');
        }

        if(jQuery("[name='pet_size']").val()){
            jQuery("#spanpet_size").addClass('hidden');
        }else{
            jQuery("#spanpet_size").css('color', 'red');
            jQuery("#spanpet_size").removeClass('hidden');
            jQuery("#pet_breed").focus(function() { jQuery("#spanpet_size").addClass('hidden'); });
        }

        if(jQuery("[name='pet_sterilized']").val()){
            jQuery("#spanpet_sterilized").addClass('hidden');
        }else{
            jQuery("#spanpet_sterilized").css('color', 'red');
            jQuery("#spanpet_sterilized").removeClass('hidden');
            jQuery("#pet_breed").focus(function() { jQuery("#spanpet_sterilized").addClass('hidden'); });
        } 

        if(jQuery("[name='pet_sociable']").val()){
            jQuery("#spanpet_sociable").addClass('hidden');
        }else{
            jQuery("#spanpet_sociable").css('color', 'red');
            jQuery("#spanpet_sociable").removeClass('hidden');
            jQuery("#pet_breed").focus(function() { jQuery("#spanpet_sociable").addClass('hidden'); });
        }

        if(jQuery("[name='aggresive_humans']").val()){
            jQuery("#spanaggresive_humans").addClass('hidden');
        }else{
            jQuery("#spanaggresive_humans").css('color', 'red');
            jQuery("#spanaggresive_humans").removeClass('hidden');
            jQuery("#pet_breed").focus(function() { jQuery("#spanaggresive_humans").addClass('hidden'); });
        }

        if(jQuery("[name='aggresive_pets']").val()){
            jQuery("#spanaggresive_pets").addClass('hidden');
        }else{
            jQuery("#spanaggresive_pets").css('color', 'red');
            jQuery("#spanaggresive_pets").removeClass('hidden');
            jQuery("#pet_breed").focus(function() { jQuery("#spanaggresive_pets").addClass('hidden'); });
        }
        
        if (jQuery("input[name='pet_name']").val() != "" &&
            jQuery("input[name='pet_birthdate']").val() != "" &&
            jQuery("#pet_breed").val() != "" &&
            jQuery("[name='pet_gender']").val() != "" &&
            jQuery("[name='pet_size']").val() != "" &&
            jQuery("#pet_type").val() != "" &&
            jQuery("[name='pet_colors']").val() != "" &&
            jQuery("[name='pet_sterilized']").val() != "" &&
            jQuery("[name='pet_sociable']").val() != "" &&
            jQuery("[name='aggresive_humans']").val() != "" &&
            jQuery("[name='aggresive_pets']").val()) {

                postJSON( 
              		"form_perfil",
                   	URL_PROCESOS_PERFIL, 
                   	function( data ) {
            			jQuery("#btn_actualizar").val("Procesando...");
                        jQuery("#btn_actualizar").attr("disabled", true);
            			jQuery(".perfil_cargando").css("display", "inline-block");

                 	}, 
                   	function( data ) {

                        console.log(data);

                   		jQuery(".vlz_img_portada_valor").val("");

            			jQuery("#btn_actualizar").val("Actualizar");
            			jQuery("#btn_actualizar").attr("disabled", false);
                        jQuery(".perfil_cargando").css("display", "none");

                          var $mensaje="";

                         console.log(data);

                         var obj = jQuery.parseJSON( '{ "status": "OK" }' );
                         console.log(obj.status);

                        if( obj.status == "OK"){             

                            $mensaje = "Los datos de fueron actualizados";

                        }else{

                             $mensaje = "Lo sentimos no se pudo actualizar los datos ";
                        }

                        console.log($mensaje);

                        jQuery('#btn_actualizar').before('<br><span class="mensaje">'+$mensaje+'</span><br>');  

                              setTimeout(function() { 
                             jQuery('.mensaje').remove(); 

                        },3000);        
                        
                   	}
               	);            
        }else{
            e.preventDefault();
            alert("Revise sus datos por favor, debe llenar los campos requeridos!");
        }

    });
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

    var minFecha = new Date();
    var min = minFecha.getFullYear();
    minFecha.setFullYear( parseInt(min)-30 );

    var maxFecha = new Date();
    maxFecha.setDate( parseInt(maxFecha.getDate()) - 1);

    jQuery("#pet_birthdate").datepick({
        dateFormat: 'dd/mm/yyyy',
        minDate: minFecha,
        maxDate: maxFecha,
        onSelect: function(date1) {
            
        },
        yearRange: minFecha.getFullYear()+':'+maxFecha.getFullYear(),
        firstDay: 1,
        onmonthsToShow: [1, 1]
    });
});