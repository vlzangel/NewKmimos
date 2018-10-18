var timer;
var table;
var tr = document.getElementById('tiempo_restante');
var retiro_total = 0;

jQuery(document).ready(function(){
    timer = setInterval(contador, 1000);

    jQuery(".ver_reserva_init").on("click", function(e){
        jQuery(this).parent().parent().parent().addClass("vlz_desplegado");
    });

    jQuery(".ver_reserva_init_fuera").on("click", function(e){
	    jQuery(this).parent().parent().find('.vlz_tabla_inferior').removeClass("inactive_control");
	    jQuery(this).parent().find('.ver_reserva_init_closet').removeClass("inactive_control");

	    jQuery(this).addClass("inactive_control");
    });

    jQuery(".ver_reserva_init_closet").on("click", function(e){
	    jQuery(this).parent().parent().find('.vlz_tabla_inferior').addClass("inactive_control");
	    jQuery(this).parent().find('.ver_reserva_init_fuera').removeClass("inactive_control");

	    jQuery(this).addClass("inactive_control");
    });

    jQuery('[name="periodo"]').on('change', function(e){
        jQuery.post(
            HOME+'admin/frontend/balance/ajax/update_periodo.php',
            {'periodo': jQuery(this).val(), 'ID': user_id},
            function(d){
                if( d== 'YES' ){
                    alert( "Datos actualizados" );
                }else{
                    alert( "Datos actualizados" );
                }
        });
    });

    jQuery('#search-transacciones').on('click', function(e){
        e.preventDefault();
        loadTabla();
    });

    loadTabla();


    jQuery('[data-target="modal-retiros"]').on('click', function(e){
        retiro_total = 0;
        jQuery('#retiros').modal('show');
    });

    jQuery("[data-name='retiro_disponible']").on('change', function(e){
        retiro_total += parseFloat(jQuery(this).val()); 
        jQuery('#modal-subtotal').html( retiro_total );
        jQuery('#modal-total').html( retiro_total - 10 );
    });

    jQuery('#retirar').on('click', function(e){        
        var selected = [];
        jQuery.each(jQuery("[data-name='retiro_disponible']:checked"), function(){
            var reserva = jQuery(this).val();
            selected.push(reserva);
        });
        jQuery.post(
            HOME+'admin/frontend/balance/ajax/retirar.php',
            {'reservas_selected': selected, 'ID': user_id},
            function(d){
                console.log(d);
                window.reload;
            }
        );
    });

});

function loadTabla(){
     
    table = jQuery('#example').DataTable();
    table.destroy();

    table = jQuery('#example').DataTable({
        "language": {
            "emptyTable":           "No hay datos disponibles en la tabla.",
            "info":                 "Del _START_ al _END_ de _TOTAL_ ",
            "infoEmpty":            "Mostrando 0 registros de un total de 0.",
            "infoFiltered":         "(filtrados de un total de _MAX_ registros)",
            "infoPostFix":          " (actualizados)",
            "lengthMenu":           "Mostrar _MENU_ registros",
            "loadingRecords":       "Cargando...",
            "processing":           "Procesando...",
            "search":               "Buscar:",
            "searchPlaceholder":    "Dato para buscar",
            "zeroRecords":          "No se han encontrado coincidencias.",
            "paginate": {
                "first":            "Primera",
                "last":             "Última",
                "next":             "Siguiente",
                "previous":         "Anterior"
            },
            "aria": {
                "sortAscending":    "Ordenación ascendente",
                "sortDescending":   "Ordenación descendente"
            }
        },
        "dom": '<B><f><t><"col-sm-6 text-left"i><"col-sm-6"p>',
        "buttons": [
            {
              extend: "csv",
              className: "btn-sm"
            },
            {
              extend: "excelHtml5",
              className: "btn-sm"
            },
        ],       
        "scrollX": true,
        "ajax": {
            "url": HOME+'admin/frontend/balance/ajax/transacciones.php',
            "data": { 'ID': user_id, 'desde': jQuery('[name="ini"]').val(), "hasta":jQuery('[name="fin"]').val() },
            "type": "POST"
        }
    });

}

function contador(){
    var hoy=new Date()
        console.log(hoy)
        dias=0
        horas=0
        minutos=0
        segundos=0

    var diferencia=(fecha.getTime()-hoy.getTime())/1000
        dias=Math.floor(diferencia/86400)
        diferencia=diferencia-(86400*dias)
        horas=Math.floor(diferencia/3600)
        diferencia=diferencia-(3600*horas)
        minutos=Math.floor(diferencia/60)
        diferencia=diferencia-(60*minutos)
        segundos=Math.floor(diferencia)
     
    if ( horas==0 && minutos==0 && segundos==0 ){
        jQuery('#tiempo_restante_parent').addClass('hidden');
        jQuery('#boton-retiro').removeClass('hidden');
        clearInterval(timer);
        return;
    }else{
        tr.innerHTML = '';

        if( horas > 0 ){
            tr.innerHTML = horas + " h ";   
        }
        if( minutos > 0 ){
            tr.innerHTML += minutos + " min. "; 
        }
        if( segundos > 0 ){
            tr.innerHTML += segundos + ' s';    
        }

    }
}
