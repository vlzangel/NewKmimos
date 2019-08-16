function acepta_gatos(){
/*    var acepta = jQuery("#gatos").val();
    if( acepta == "Si" ){
        jQuery("#comportamiento_gatos_container").css("display", "block");
    }else{
        jQuery("#comportamiento_gatos_container").css("display", "none");
    }*/
}

jQuery( document ).ready(function() {
    
    var maxDatePets = new Date();
    jQuery('#fecha').datepick({
        dateFormat: 'dd/mm/yyyy',
        maxDate: maxDatePets,
        onSelect: function(xdate) {
            if( jQuery('#datepets').val() != '' ){
            }
        },
        yearRange: '1940:'+maxDatePets.getFullYear(),
    });

    jQuery("#estado").on("change", function(e){
        var estado_id = jQuery("#estado").val();       
        if( estado_id != "" ){
            jQuery.getJSON( 
                HOME+"procesos/generales/municipios.php", 
                {estado: estado_id} 
            ).done(
                function( data, textStatus, jqXHR ) {
                    var html = "<option value=''>Seleccione un municipio</option>";
                    jQuery.each(data, function(i, val) {
                        html += "<option value="+val.id+">"+val.name+"</option>";
                    });
                    jQuery("#delegacion").html(html);
                }
            ).fail(
                function( jqXHR, textStatus, errorThrown ) {
                    console.log( "Error: " +  errorThrown );
                }
            );
        }
    });

    jQuery("#delegacion").on("change", function(e){
        var estado_id = jQuery("#estado").val();       
        var delegacion = jQuery("#delegacion").val();       
        if( estado_id != "" ){
            jQuery.get(
                HOME+"procesos/generales/municipios.php", 
                {
                    estado: estado_id,
                    municipio: delegacion
                }, function(data){
                    console.log( data );
                    var html = "<option value=''>Seleccione una colonia</option>";
                    jQuery.each(data, function(i, val) {
                        html += "<option value="+val.id+">"+val.name+"</option>";
                    });
                    jQuery("#colonia").html(html);
                }, 'json'
            );
            /*
            jQuery.getJSON( 
                HOME+"procesos/generales/municipios.php", 
                {
                    estado: estado_id,
                    municipio: delegacion
                } 
            ).done(
                function( data, textStatus, jqXHR ) {
                    console.log( data );
                    var html = "<option value=''>Seleccione una colonia</option>";
                    jQuery.each(data, function(i, val) {
                        html += "<option value="+val.id+">"+val.name+"</option>";
                    });
                    jQuery("#colonia").html(html);
                }
            ).fail(
                function( jqXHR, textStatus, errorThrown ) {
                    console.log( "Error: " +  errorThrown );
                }
            );
            */
        }
    });

    postJSON( 
        "form_perfil",
        URL_PROCESOS_PERFIL, 
        function( data ) {
            jQuery("#btn_actualizar").val("Procesando...");
            jQuery("#btn_actualizar").attr("disabled", true);
            jQuery(".perfil_cargando").css("display", "inline-block");
        }, 
        function( data ) {
            jQuery("#btn_actualizar").val("Actualizar");
            jQuery("#btn_actualizar").attr("disabled", false);
            jQuery(".perfil_cargando").css("display", "none");
            location.reload();
        }, 'json'
    );

    jQuery("#tipo_doc").on("change", function(e){
        if( jQuery(this).val() == "IFE / INE" ){
            jQuery("#ife_label").html("IFE:");
            jQuery("#dni").attr("title", "Coloca los 13 Números que se encuentran en la parte trasera de tu IFE o INE");
            jQuery("#dni").attr("data-original-title", "Coloca los 13 Números que se encuentran en la parte trasera de tu IFE o INE");
        }else{
            jQuery("#ife_label").html("Pasaporte:");
            jQuery("#dni").attr("title", "Coloca tu identificador de pasaporte");
            jQuery("#dni").attr("data-original-title", "Coloca tu identificador de pasaporte");
        }
    });

    jQuery('[data-toggle="tooltip"]').tooltip();


    jQuery("#gatos").on("change", function(e){
        acepta_gatos();
    });
    acepta_gatos();

});