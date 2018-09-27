jQuery( document ).ready(function() {
    jQuery("input[type='submit']").on("click", function(e){
                
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

        if( jQuery("#pet_type").val() == '2608' ){
            var selecciono_comportamiento = false;
            jQuery("#otras_opciones input").each(function(i, val){
                if( jQuery(val).val() == 1 ){
                    selecciono_comportamiento = true;
                }
            });
            if( selecciono_comportamiento == false ){
                jQuery(".error_seleccionar_uno").removeClass("no_error");
            }
        }else{
            var selecciono_comportamiento = true;
        }

        if( selecciono_comportamiento ){
            jQuery(".error_seleccionar_uno").addClass("no_error");
        }

        if (
                jQuery("input[name='pet_name']").val() != "" &&
                jQuery("input[name='pet_birthdate']").val() != "" &&
                jQuery("#pet_breed").val() != "" &&
                jQuery("[name='pet_gender']").val() != "" &&
                jQuery("[name='pet_size']").val() != "" &&
                jQuery("#pet_type").val() != "" &&
                jQuery("[name='pet_colors']").val() != "" &&
                jQuery("[name='pet_sterilized']").val() != "" &&
                jQuery("[name='pet_sociable']").val() != "" &&
                jQuery("[name='aggresive_humans']").val() != "" &&
                jQuery("[name='aggresive_pets']").val() != "" &&
                selecciono_comportamiento
            ) {

                var titulo = jQuery("#btn_actualizar").val();
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

                        if( titulo == 'Crear Mascota' ){
                            jQuery("#form_perfil").closest('form').find("input[type=text], textarea, input[type=date]").val("");
                            jQuery("#form_perfil").closest('form').find("select option[value='']").prop("selected", true);
                        }

                        jQuery("#btn_actualizar").val( titulo );
                        jQuery("#btn_actualizar").attr("disabled", false);
                        jQuery(".perfil_cargando").css("display", "none");

                        var $mensaje="";
                        var obj = jQuery.parseJSON( '{ "status": "OK" }' );
                        if( obj.status == "OK"){             
                            $mensaje = "El registro de su mascota fue exitoso";
                        }else{
                            $mensaje = "Lo sentimos no se pudo registrar a su mascota ";
                        }

                        console.log($mensaje);

                        jQuery('#btn_actualizar').before('<br><span class="mensaje">'+$mensaje+'</span><br>');  
                        setTimeout(function() { 
                            jQuery('.mensaje').remove(); 
                            if( obj.status == "OK"){
                                if( titulo == 'Crear Mascota' ){
                                    location.href = '../';
                                }else{
                                    location.reload();
                                }
                            }
                        },3000);
                        
                    }
                );

        }else{
            e.preventDefault();
            alert("Revise sus datos por favor, debe llenar los campos requeridos!");
        }
    });
    
    jQuery("#pet_type").on("change", function(e){
        var valor = jQuery("#pet_type").val();
        if( valor == "2605" ){
            var opciones = jQuery("#razas_perros").html();
            jQuery("#pet_breed").html(opciones);
            jQuery("#otras_opciones").css("display", "none");
        }
        if( valor == "2608" ){
            var opciones = jQuery("#razas_gatos").html();
            jQuery("#pet_breed").html(opciones);
            jQuery("#otras_opciones").css("display", "block");
        }
    });

    initImg("portada");

    jQuery("#form_perfil [data-valid]").each(function( index ) {
        pre_validar( jQuery(this) );
    });

    

    var minFecha = new Date();
    var min = minFecha.getFullYear();
    minFecha.setFullYear( parseInt(min)-30 );
    minFecha.setDate( parseInt(minFecha.getDate()) - 1);

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