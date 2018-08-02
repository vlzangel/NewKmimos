jQuery( document ).ready(function() {

    // Enviar factura por mail
    jQuery("#btn_facturar_sendmail").on("click", function(e){
        var _this = jQuery(this);
        if( !_this.hasClass("disabled") ){
            _this.addClass("disabled");
            jQuery('.perfil_cargando').css('display', 'block');
            jQuery.post(
                URL_PROCESOS_PERFIL, 
                {
                    'accion': "factura_send_email",
                    'core': "SI",
                    'id_orden': jQuery('[name="id_orden"]').val()
                },
                function(data){
                    data = JSON.parse(data);
                    
                    if( data['estatus'] == 'enviado' ){
                        alert( "Comprobante Fiscal Digital enviado a "+data['email']+" ." );
                    }
                    
                    jQuery('.perfil_cargando').css('display', 'none');
                    _this.removeClass("disabled");

                }
            );
        }
    });

    // Generar Factura    
    jQuery("#btn_facturar").on("click", function(e){
        var _this = jQuery(this);

        var orden = jQuery('#id_orden').val();
        
        jQuery('.perfil_cargando').css('display', 'block');

        jQuery.post(
            URL_PROCESOS_PERFIL, 
            jQuery('#form_perfil').serialize(),
            function(data){
                data = JSON.parse(data);
                
                if( data['estatus'] == 'aceptado' ){
                    jQuery('#btn_factura_pdf').attr('href', data['pdf']);

                    jQuery('#solicitar-factura').css('display', 'none');
                    jQuery('#descargar-factura').css('display', 'block');
                }else{
                    alert("Ocurrio un problema al tratar de procesar la solitiud");
                    console.log(data['ack']);
                }
                
                jQuery('.perfil_cargando').css('display', 'none');

            }
        );

        return false;
            
    });

    jQuery(document).on('change', 'select[name="rc_estado"]', function(e){
        var estado_id = jQuery(this).val();
            
        if( estado_id != "" ){
            cambio_municipio(estado_id);
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