jQuery(document).on("click", '[data-target="#popup-conoce-cuidador"]' ,function(e){
    open_conocer( jQuery(this) )
});


var hora_total = 21600;

function iniciar_cronometro(){
    setInterval(function(){
        var hora = new Date().getTime();
        hora /= 1000;
        hora = hora-contador_tcc;
        SR = parseInt( hora_total - hora ); // SR: segundos restantes
        if( SR > 0 ){
            var h = parseInt(SR/3600); // h: horas restantes
            var m = parseInt(SR/60)-(h*60); // m: minutos restantes
            var s = parseInt(SR)-( (h*3600)+(m*60)); // s: segundos restantes
            h = ( h < 10 ) ? "0"+h : h; 
            m = ( m < 10 ) ? "0"+m : m; 
            s = ( s < 10 ) ? "0"+s : s; 
            var h2 = String(h).split('');
            var m2 = String(m).split('');
            var s2 = String(s).split('');

            jQuery(".cronometro_h .sp_1").html(h2[0]);
            jQuery(".cronometro_h .sp_2").html(h2[1]);

            jQuery(".cronometro_m .sp_1").html(m2[0]);
            jQuery(".cronometro_m .sp_2").html(m2[1]);

            jQuery(".cronometro_s .sp_1").html(s2[0]);
            jQuery(".cronometro_s .sp_2").html(s2[1]);
        }else{
            jQuery(".cronometro_h .sp_1").html('0');
            jQuery(".cronometro_h .sp_2").html('0');

            jQuery(".cronometro_m .sp_1").html('0');
            jQuery(".cronometro_m .sp_2").html('0');

            jQuery(".cronometro_s .sp_1").html('0');
            jQuery(".cronometro_s .sp_2").html('0');
        }
            
    }, 1000);
}

function open_conocer( _this ){
    jQuery('.popup-iniciar-sesion-1 #meeting_when').val("");
    jQuery('.popup-iniciar-sesion-1 #meeting_where').val("");
    jQuery('.popup-iniciar-sesion-1 #service_start').val("");
    jQuery('.popup-iniciar-sesion-1 #service_end').val("");

    jQuery('#meeting_time option.vacio').attr("selected", "selected");
    jQuery('.popup-iniciar-sesion-1 #pet_conoce input').prop("checked", false);
    jQuery('.popup-iniciar-sesion-1 #pet_conoce input').removeClass("active");

    jQuery( '#modal-name-cuidador' ).html( _this.data('name') );
    jQuery( '.modal-name-cuidador' ).html( _this.data('name') );
    jQuery( '[name="post_id"]' ).val( _this.data('id') );

    if( _this.data('url') != undefined ){
        var url = RAIZ+_this.data('url');
        var reservar = RAIZ+_this.data('reservar');

        switch(test_conocer){
            case 'b':
                jQuery( '.boton_izq' ).attr("href", url );
                jQuery( '.boton_der' ).attr("href", url );
            break;
            case 'c':
                jQuery( '.boton_izq' ).attr("href", reservar );
                jQuery( '.boton_der' ).attr("href", "javascript: jQuery( '.conocer_c' ).css('display', 'none');" );
            break;
            default:
                
            break;
        }

        jQuery( '#btn_reserva_conocer' ).attr("href", RAIZ+_this.data('url') );
        jQuery( '#url_cuidador' ).val(RAIZ+_this.data('url') );
    }else{
        jQuery( '.boton_izq' ).attr("href", "javascript: jQuery( '#btn_reservar' ).click();" );
        // jQuery( '.btn_reservar' ).click();
    }

    
    if( _this.data('reservar') != undefined ){
        jQuery( '.boton_izq' ).attr("href", RAIZ+_this.data('reservar'));
    }
    

    if( jQuery("#tcc").val() == 'yes' ){
        if( contador_tcc == 0 ){
            jQuery.post(
                HOME+'/procesos/conocer/init_contador_tcc.php',
                { 
                    usar: 'YES',
                    cuidador: jQuery("#post_id").val()
                },
                function(data){
                    contador_tcc = parseInt( data );
                    //contador_tcc *= 1000;
                    iniciar_cronometro();
                }
            );
        }else{
            //contador_tcc *= 1000;
            iniciar_cronometro();
        }

        
    }
    

    jQuery( _this.data('target') ).modal('show');
    jQuery('.popup-iniciar-sesion-2').css('display', 'none');
    jQuery('.popup-iniciar-sesion-1').css('display', 'block');

    jQuery("#btn_reserva_conocer").attr("href", _this.parent().find(".active").attr("href") );
}

jQuery(document).on("click", '[data-id="enviar_datos"]' ,function(e){
    e.preventDefault();

    if( conocer_es_valido() ){
        if( !jQuery("#btn_enviar_conocer").hasClass("disabled") ){
            jQuery("#btn_enviar_conocer").addClass("disabled");
            var a = HOME+"procesos/conocer/index.php";
            jQuery(this).html('<i style="font-size: initial;" class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i> ENVIANDO DATOS...');
            jQuery.post( a, jQuery("#conoce_cuidador").serialize(), function( data ) {

                console.log(data);
                
                if( data != "" ){

               	    if( data['error'] != '' ){
                        alert(data['error']);
                        if( data['error'] == "Error, debe recargar para poder realizar más solicitudes!" ){
                            location.href = RAIZ+"petsitters/"+data['cuidador']+"/1";
                        }
                    }else{
                        jQuery("#cupos_disponibles").html( data['cupos_disponibles'] );

                        jQuery("#fecha").html( jQuery("#meeting_when").val() );
                        jQuery("#hora_reu").html( jQuery("#meeting_time").val() );
                        jQuery("#lugar_reu").html( jQuery("#meeting_where").val() );
                        jQuery("#fecha_ini").html( jQuery("#service_start").val() );              
                        jQuery("#fecha_fin").html( jQuery("#service_end").val() );
                        jQuery("#n_solicitud").html( data['n_solicitud'] );
                        jQuery("#nombre").html( data['nombre']);
                        jQuery("#telefono").html( data['telefono']);
                        jQuery("#email").html( data['email'] );
                        jQuery('#popup-conoce-cuidador').modal('show');
                        jQuery('.popup-iniciar-sesion-1').css('display', 'none');
                        jQuery('.popup-iniciar-sesion-2').css('display', 'block');

                        evento_google("conocer_cuidador");
                        evento_fbq("track", "traking_code_conocer_cuidador"); 
                    }
                }
                jQuery("#btn_enviar_conocer").html('ENVIAR SOLICITUD');
                jQuery("#btn_enviar_conocer").removeClass("disabled");
                
        
                
            }, 'json').fail(function(e) {
                console.log( e );
            });
        }
    }
});

function error(id, error, transparente = false){
    if( error ){
        jQuery("#"+id).css("border-bottom", "1px solid rgb(199, 17, 17)");
        jQuery( '[data-error="'+id+'"]' ).css( "display", "block" );
    }else{
        if( transparente ){
            jQuery("#"+id).css("border-bottom", "1px solid transparent");
        }else{
            jQuery("#"+id).css("border-bottom", "1px solid #ccc");
        }
        jQuery( '[data-error="'+id+'"]' ).css( "display", "none" );
    }
}

function conocer_es_valido(){
    var hay_error = true;
    var primer_error='';

    var campos = [
        "meeting_when",
        "meeting_time",
        "meeting_where",
        "service_start",
        "service_end"
    ];

    jQuery.each(campos, function( index, item ) {
        if( jQuery("#"+item).val() == "" ){
            error(item, true);
            hay_error = false;
            primer_error = (primer_error=='')? item : primer_error ;
        }else{
            error(item, false);
        }
    });

    if( jQuery(".km-group-checkbox input.active").length == 0 ){
        error("pet_conoce", true);
            hay_error = false;
    }else{
        error("pet_conoce", false, true);
    }

    if( !hay_error ){
        jQuery('html, body').animate({ scrollTop: jQuery("#"+primer_error).offset().top-180 }, 2000);
    }
    return hay_error;
}

jQuery.noConflict();
var fecha = new Date();
jQuery(document).ready(function(){
    jQuery('#meeting_when').datepick({
        dateFormat: 'dd/mm/yyyy',
        minDate: fecha,
        onSelect: function(date1) {
            var preDate = getFecha("service_start", date1);
            initDate('service_start', date1[0], function(date2) {
                var preDate2 = getFecha("service_end", date2);
                initDate('service_end', date2[0], function(date3) { }, preDate2);
            }, preDate);
        },
        yearRange: fecha.getFullYear()+':'+(parseInt(fecha.getFullYear())+1),
        firstDay: 1,
        onmonthsToShow: [1, 1]
    });

    jQuery(".km-group-checkbox input").on("change", function(e){
        jQuery(this).toggleClass("active");
    });

    jQuery("#recargar_saldo").on('click', function(e){
        location.href = RAIZ+"recargar/"+jQuery('[name="post_id"]').val();
    });

    jQuery("#no_usar_descuento").on('click', function(e){
        e.preventDefault();
        jQuery.post(
            HOME+'/procesos/conocer/uso_cupon_test_c.php',
            { 
                usar: 'NO',
                cuidador: jQuery("#post_id").val() 
            },
            function(data){
                jQuery(".conocer_c").css('display', 'none');
                jQuery("#conoce_cuidador").css('display', 'block');
            }
        );
    });

    jQuery("#usar_descuento").on('click', function(e){
        e.preventDefault();

        jQuery( '#url_cuidador' ).val( jQuery(this).attr("href") );

        jQuery.post(
            HOME+'/procesos/conocer/uso_cupon_test_c.php',
            { 
                usar: 'YES',
                cuidador: jQuery("#post_id").val()
            },
            function(data){
                location.href = jQuery( '#url_cuidador' ).val();
            }
        );
    });


    jQuery("#popup-conoce-cuidador").on('hidden.bs.modal', function () {
        jQuery(".conocer_c").css('display', 'block');
        jQuery("#conoce_cuidador").css('display', 'none');
    });

});

    
function initDate(id, date, onSelect, preDate){
    if( jQuery('#'+id).hasClass("is-datepick") ){
        jQuery('#'+id).datepick('destroy');
    }
    jQuery('#'+id).datepick({
        dateFormat: 'dd/mm/yyyy',
        minDate: date,
        defaultDate: preDate,
        selectDefaultDate: true,
        onSelect: onSelect,
        yearRange: date.getFullYear()+':'+(parseInt(date.getFullYear())+1),
        firstDay: 1,
        onmonthsToShow: [1, 1]
    });
}

function getFecha(id, preDate){
    var fecha = jQuery('#'+id).datepick( "getDate" );
    if( fecha.length > 0 ){
        if( preDate[0].getTime() > fecha[0].getTime() ){
            return preDate[0];
        }else{
            return fecha[0];
        }
    }
    return preDate[0];
}