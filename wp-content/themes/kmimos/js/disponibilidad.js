var fecha = new Date();
jQuery('#inicio').datepick(
    {
        dateFormat: 'dd/mm/yyyy',
        showTrigger: '#calImg',
        minDate: fecha,
        onSelect: seleccionar_checkin,
        yearRange: fecha.getFullYear()+':'+(parseInt(fecha.getFullYear())+1),
        firstDay: 1
    }
);

function seleccionar_checkin(date) {
    if( jQuery('#inicio').val() != '' ){
        var fecha = new Date();
        jQuery('#fin').attr('disabled', false);
        var ini = String( jQuery('#inicio').val() ).split('/');
        var fin = String( jQuery('#fin').val() ).split('/');
        var inicio = new Date( parseInt(ini[2]), parseInt(ini[1])-1, parseInt(ini[0]) );
        var fin = new Date( parseInt(fin[2]), parseInt(fin[1])-1, parseInt(fin[0]) );

        jQuery('#fin').removeClass('is-datepick');
        jQuery('#fin').datepick({
            dateFormat: 'dd/mm/yyyy',
            showTrigger: '#calImg',
            minDate: inicio,
            yearRange: fecha.getFullYear()+':'+(parseInt(fecha.getFullYear())+1),
            firstDay: 1
        });

        if( Math.abs(fin.getTime()) < Math.abs(inicio.getTime()) ){
            jQuery('#fin').datepick( 'setDate', jQuery('#inicio').val() );
        }
    }else{
        jQuery('#fin').val('');
        jQuery('#fin').attr('disabled', true);
    }
}

function volver_disponibilidad(){
    jQuery(".fechas").css("display", "none");
    jQuery(".tabla_disponibilidad_box").css("display", "block");
}

function editar_disponibilidad(){
    jQuery(".fechas").css("display", "block");
    jQuery(".tabla_disponibilidad_box").css("display", "none");
}

function guardar_disponibilidad(){
    var ini = jQuery("#inicio").val();
    var fin = jQuery("#fin").val();
    var user_id = jQuery("#user_id").val();
    if( ini == "" || fin == "" ){
        alert("Debes seleccionar las fechas primero");
    }else{

        var _ini = String( jQuery('#inicio').val() ).split('/');
        var _fin = String( jQuery('#fin').val() ).split('/');

        var _inicio = new Date( parseInt(_ini[2]), parseInt(_ini[1])-1, parseInt(_ini[0]) );
        var _fin = new Date( parseInt(_fin[2]), parseInt(_fin[1])-1, parseInt(_fin[0]) );

        var diferencia = parseInt(_fin.getTime()) - parseInt(_inicio.getTime());

        var servicio_str = jQuery("#servicio option:selected").attr("data-type");

        if( diferencia < 7776000000 || servicio_str == "hospedaje" ){
            jQuery.post(
                URL_PROCESOS_PERFIL, 
                {
                    servicio: jQuery("#servicio").val(),
                    tipo: jQuery("#tipo").val(),
                    status: jQuery("#status").val(),
                    inicio: ini,
                    fin: fin,
                    user_id: user_id,
                    accion: "new_disponibilidad"
                },
                function(data){
                     console.log(data);
                    //location.reload();
                }// , "json"
            );
        }else{
            alert("Los rangos de no disponibilidad deben ser menores a 90 días\nPara bloqueos mayores a este limite te recomendamos desactivar el servicio en la sección \"Mis Servicios\".");
        }
    }
}

jQuery("#editar_disponibilidad").on("click", function(e){
    editar_disponibilidad();
});

jQuery("#volver_disponibilidad").on("click", function(e){
    volver_disponibilidad();
});

jQuery("#guardar_disponibilidad").on("click", function(e){
    guardar_disponibilidad();
});

jQuery("#servicio").on("change", function(e){
    jQuery("#tipo").val( jQuery("#servicio option:selected").attr("data-type") );
});

jQuery(".vlz_cancelar").on("click", function(e){
    var valor = jQuery(this).attr("data-value");
    var user_id = jQuery("#user_id").val();
    var confirmed = confirm("Esta seguro de liberar estos días?");
    if (confirmed == true) {
        var id  = jQuery(this).attr("data-id");
        var ini = jQuery(this).attr("data-inicio");
        var fin = jQuery(this).attr("data-fin");
        jQuery.post(
            URL_PROCESOS_PERFIL, 
            {
                servicio: id,
                inicio: ini,
                fin: fin,
                user_id: user_id,
                accion: "delete_disponibilidad"
            },
            function(data){
                // console.log(data);
                location.reload();
            }
        );
    }
});