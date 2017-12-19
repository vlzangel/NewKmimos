jQuery(document).ready(function(){

    var fecha = new Date();

    jQuery('#frm_reclamo').on('submit', function(e){
        e.preventDefault();
    });

    jQuery('#reclamo_enviar').on('click', function(e){
        e.preventDefault();
        if( jQuery('[name="terminos"]:checked') ){        
            var a  =  HOME+"procesos/reclamos/crear.php";
            jQuery.post( a, jQuery("#frm_reclamo").serialize(), function( data ) {

                var data = jQuery.parseJSON(data);
                var color = 'alert-warning';
                if(data['code'] > 400){
                    color = 'alert-danger';
                }
                jQuery('#noti')
                    .html(data['mensaje'])
                    .removeClass('hidden')
                    .addClass(color)
                    ;                    
                setTimeout(function() { 
                    jQuery('#noti').html(''); 
                    jQuery('#noti').addClass('hidden'); 
                },3000);  

            });
        }else{            
            jQuery('#noti')
                .html('Debe aceptar la declaración')
                .removeClass('hidden');
            setTimeout(function() { 
                jQuery('#noti').html(''); 
                jQuery('#noti').addClass('hidden'); 
            },3000);  
            jQuery('#frm_reclamo').clear();
        }

    });


    jQuery(document).on('change', 'select[name="estado"]', function(e){
        var estado_id = jQuery(this).val();
            
        if( estado_id != "" ){
            cambio_municipio(estado_id);
        }

    });


    jQuery('#date_compra').datepick({
        dateFormat: 'dd/mm/yyyy',
        defaultDate: fecha,
        selectDefaultDate: true,
        minDate: fecha,
        onSelect: function(xdate) {
             
        },
        yearRange: fecha.getFullYear()+':'+(parseInt(fecha.getFullYear())+1),
        firstDay: 1,
        onmonthsToShow: [1, 1]
    });

    jQuery('#date_consumo').datepick({
        dateFormat: 'dd/mm/yyyy',
        defaultDate: fecha,
        selectDefaultDate: true,
        minDate: fecha,
        onSelect: function(xdate) {
             
        },
        yearRange: fecha.getFullYear()+':'+(parseInt(fecha.getFullYear())+1),
        firstDay: 1,
        onmonthsToShow: [1, 1]
    });

    jQuery('#date_vence').datepick({
        dateFormat: 'dd/mm/yyyy',
        defaultDate: fecha,
        selectDefaultDate: true,
        minDate: fecha,
        onSelect: function(xdate) {
             
        },
        yearRange: fecha.getFullYear()+':'+(parseInt(fecha.getFullYear())+1),
        firstDay: 1,
        onmonthsToShow: [1, 1]
    });

    jQuery( document ).on('keypress', '[data-charset]', function(e){

        var tipo= jQuery(this).attr('data-charset');

        if(tipo!='undefined' || tipo!=''){
            var cadena = "";

            if(tipo.indexOf('alf')>-1 ){ cadena = cadena + "abcdefghijklmnopqrstuvwxyzáéíóúñüÁÉÍÓÚÑÜ"; }
            if(tipo.indexOf('xlf')>-1 ){ cadena = cadena + "abcdefghijklmnopqrstuvwxyzáéíóúñüÁÉÍÓÚÑÜ "; }
            if(tipo.indexOf('mlf')>-1 ){ cadena = cadena + "abcdefghijklmnopqrstuvwxyz"; }
            if(tipo.indexOf('num')>-1 ){ cadena = cadena + "1234567890"; }
            if(tipo.indexOf('cur')>-1 ){ cadena = cadena + "1234567890,."; }
            if(tipo.indexOf('esp')>-1 ){ cadena = cadena + "-_.$%&@,/()"; }
            if(tipo.indexOf('cor')>-1 ){ cadena = cadena + ".-_@"; }
            if(tipo.indexOf('rif')>-1 ){ cadena = cadena + "vjegi"; }
            if(tipo.indexOf('dir')>-1 ){ cadena = cadena + ","; }

            var key = e.which,
                keye = e.keyCode,
                tecla = String.fromCharCode(key).toLowerCase(),
                letras = cadena;

            if(letras.indexOf(tecla)==-1 && keye!=9&& (key==37 || keye!=37)&& (keye!=39 || key==39) && keye!=8 && (keye!=46 || key==46) || key==161){
                e.preventDefault();
            }
        }
       
    });


});

function cambio_municipio(estado_id, CB = false){
	jQuery.getJSON( 
        HOME+"procesos/generales/municipios.php", 
        {estado: estado_id} 
    ).done(
        function( data, textStatus, jqXHR ) {
            var html = "<option value=''>Seleccione un "+BARRIO+"</option>";
            jQuery.each(data, function(i, val) {
                html += "<option value="+val.id+">"+val.name+"</option>";
            });
            jQuery('[name="municipio"]').html(html);

            if( CB != false ){
            	CB();
            }
        }
    ).fail(
        function( jqXHR, textStatus, errorThrown ) {
            console.log( "Error: " +  errorThrown );
        }
    );
}