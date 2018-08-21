var values = {cuidado:'0', puntualidad:'0',limpieza:'0', confianza:'0'};
jQuery.noConflict(); 
jQuery(document).ready(document).ready(function() {


    console.log(URL_PROCESOS_PERFIL);

    jQuery("#form_perfil").submit(function( event ) {
        event.preventDefault();

        var primer_error = '';
        jQuery.each(values, function(i, v){
            if( v < 1 ){            
                primer_error = '[data-section="'+i+'"]';
                return false;
            }
        })

        if( jQuery('#comentarios').val() != '' && primer_error == '' ){
            postJSON( 
                "form_perfil",
                URL_PROCESOS_PERFIL, 
                function( data ) {
                    jQuery("#btn_actualizar").val("Procesando...");
                    jQuery("#btn_actualizar").attr("disabled", true);
                    jQuery(".perfil_cargando").css("display", "inline-block");
                }, 
                function( data ) {

                    jQuery("#btn_actualizar").val("Enviar valoración");
                    jQuery("#btn_actualizar").attr("disabled", false);
                    jQuery(".perfil_cargando").css("display", "none");

                    console.log(data);

                    var $mensaje="";
                    if( data.status == "OK"){             
                        $mensaje = "<h1>Valoración Enviada</h1><p>Gracias por regalarnos tu evaluación, es sumamente importante para Kmimos y todos los que somos parte de esta comunidad saber lo que opinas del servicio que has recibido.</p>";
                    }else{
                        $mensaje = "Lo sentimos no se pudo registrar a su mascota ";
                    }

                    jQuery('#btn_actualizar').before('<br><span class="mensaje">'+$mensaje+'</span><br>');  
                    setTimeout(function() { 
                        jQuery('.mensaje').remove(); 
                        if( data.status == "OK"){
                            location.href = RAIZ+"/perfil-usuario/historial/";
                        }
                    }, 5000); 
                },
                "json"
            );
        }else{
            jQuery("#btn_actualizar").val("Enviar valoración");
            jQuery("#btn_actualizar").attr("disabled", false);
            jQuery(".perfil_cargando").css("display", "none");
            alert( "todos los campos deben ser obligatorios");
            jQuery('html, body').animate({ scrollTop: jQuery(primer_error).offset().top-180 }, 2000);
        }

    });

/*
   
*/

    jQuery('.select_rate').on('mouseout',function(){
        var section = jQuery(this).attr('data-section');
        if(values[section]=='0'){
            jQuery('.select_rate.'+section+' > img').each(function(index){
                jQuery(this).attr('src', HOME+'/images/new/icon/icon-hueso.svg');
            });
        }
    });

    jQuery('.fila_rate').on('mouseout',function(){
        var section = jQuery(this).attr('data-section');
        if(values[section]=='0'){
            jQuery('.select_rate.'+section+' > img').each(function(index){
                jQuery(this).attr('src', HOME+'/images/new/icon/icon-hueso.svg');
            });
        }
    });

    jQuery('.reset_rate').on('click',function(){
        var section = jQuery(this).attr('data-section');
        jQuery('.select_rate.'+section+' > img').each(function(index){
            jQuery(this).attr('src', HOME+'/images/new/icon/icon-hueso.svg');
        });
        values[section]='0';
    });

    jQuery('.select_rate').on('click',function(){
        var rate = jQuery(this).attr('data-rate');
        var section = jQuery(this).attr('data-section');
        jQuery('.select_rate.'+section+' > img').each(function(index){
            jQuery(this).attr('src', HOME+'/images/new/icon/icon-hueso.svg');
        });

    jQuery('#'+section+'_'+rate).prop('checked',true);
        values[section]=rate;
        jQuery('.select_rate.'+section+' > img').each(function(index){
            if(index < rate) jQuery(this).attr('src', HOME+'/images/new/icon/icon-hueso-color.svg');
            else jQuery(this).attr('src', HOME+'/images/new/icon/icon-hueso.svg');
        });
    });

    jQuery('.select_rate').on('mouseover',function(){
        var rate = jQuery(this).attr('data-rate');
        var section = jQuery(this).attr('data-section');
        if(values[section]=='0'){
            jQuery('.select_rate.'+section+' > img').each(function(index){
                if(index < rate) jQuery(this).attr('src', HOME+'/images/new/icon/icon-hueso-color.svg');
                else jQuery(this).attr('src', HOME+'/images/new/icon/icon-hueso.svg');
            });
        }
    });
});