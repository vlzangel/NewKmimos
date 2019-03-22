jQuery(document).on("click", '[data-target="#popup-conoce-cuidador"]' ,function(e){
    open_conocer( jQuery(this) )
});

function open_conocer( _this ){
    jQuery('.popup-iniciar-sesion-1 #meeting_when').val("");
    jQuery('.popup-iniciar-sesion-1 #meeting_where').val("");
    jQuery('.popup-iniciar-sesion-1 #service_start').val("");
    jQuery('.popup-iniciar-sesion-1 #service_end').val("");

    jQuery('#meeting_time option.vacio').attr("selected", "selected");
    jQuery('.popup-iniciar-sesion-1 #pet_conoce input').prop("checked", false);
    jQuery('.popup-iniciar-sesion-1 #pet_conoce input').removeClass("active");

    jQuery( '#modal-name-cuidador' ).html( _this.data('name') );
    jQuery( '[name="post_id"]' ).val( _this.data('id') );

    if( _this.data('url') != undefined ){
        jQuery( '.boton_izq' ).attr("href", RAIZ+_this.data('url') );
    }else{
        jQuery( '.boton_izq' ).attr("href", "javascript: jQuery( '#btn_reservar' ).click();" );
        // jQuery( '.btn_reservar' ).click();
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
        console.log("Hola");
        console.log( RAIZ+"recargar/"+jQuery('[name="post_id"]').val() );
        location.href = RAIZ+"recargar/"+jQuery('[name="post_id"]').val();
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