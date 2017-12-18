jQuery(document).ready(function(){

    var fecha = new Date();

    jQuery('#frm_reclamo').on('submit', function(e){
        e.preventDefault();
    });

    jQuery('#reclamo_enviar').on('click', function(e){
        e.preventDefault();
        var a  =  HOME+"procesos/reclamos/crear.php";
        jQuery.post( a, jQuery("#frm_reclamo").serialize(), function( data ) {

            var data = jQuery.parseJSON(data);
            jQuery('#noti')
                .html(data['mensaje'])
                .removeClass('hidden');
            setTimeout(function() { 
                jQuery('#noti').html(''); 
                jQuery('#noti').addClass('hidden'); 
            },3000);  
            jQuery('#frm_reclamo').clear();

        });

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