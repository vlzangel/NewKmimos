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
        if( validarAll( 'form_perfil' ) && !_this.hasClass("disabled") ){

            _this.addClass("disabled");

            var orden = jQuery('#id_orden').val();
            if(!confirm("Esta seguro que desea emitir la factura de la orden #"+orden+"?") ) {
                return false;
            } else {
                
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
                        }
                        
                        jQuery('.perfil_cargando').css('display', 'none');
                        _this.removeClass("disabled");

                    }
                );

                return false;
            }  
        }else{
            alert( "Datos incompletos" );
        }
    });



});
