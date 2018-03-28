var table = ""; var CTX = "";
jQuery(document).ready(function() {
    table = jQuery('#example').DataTable({
    	"language": {
			"emptyTable":			"No hay datos disponibles en la tabla.",
			"info":		   			"Del _START_ al _END_ de _TOTAL_ ",
			"infoEmpty":			"Mostrando 0 registros de un total de 0.",
			"infoFiltered":			"(filtrados de un total de _MAX_ registros)",
			"infoPostFix":			" (actualizados)",
			"lengthMenu":			"Mostrar _MENU_ registros",
			"loadingRecords":		"Cargando...",
			"processing":			"Procesando...",
			"search":				"Buscar:",
			"searchPlaceholder":	"Dato para buscar",
			"zeroRecords":			"No se han encontrado coincidencias.",
			"paginate": {
				"first":			"Primera",
				"last":				"Última",
				"next":				"Siguiente",
				"previous":			"Anterior"
			},
			"aria": {
				"sortAscending":	"Ordenación ascendente",
				"sortDescending":	"Ordenación descendente"
			}
		},
        "scrollX": true,
        "ajax": {
            "url": TEMA+'/admin/backend/saldos/ajax/saldos.php',
            "type": "POST"
        }
	});

    jQuery("#close_modal").on("click", function(e){
        cerrar(e);
    });

} );

function abrir_link(e){
	init_modal({
		"titulo": e.attr("data-titulo"),
		"modulo": "saldos",
		"modal": e.attr("data-modal"),
		"info": {
			"ID": e.attr("data-id")
		}
	});
}

function updateSaldo(){
	if( !jQuery("#Bloquear").hasClass("disable") ){
		jQuery("#Bloquear").addClass("disable");
		jQuery("#Bloquear").val("Procesando...");
		jQuery.post(
			TEMA+"/admin/backend/saldos/ajax/updateSaldo.php",
			{
				saldo: jQuery("#saldo").val(),
				ID: jQuery("#ID").val()
			},
			function(HTML){
	            jQuery("#Bloquear").removeClass("disable");
	            jQuery("#Bloquear").val("Actualizar");
	            table.ajax.reload();
	            cerrar();
	        }
	    ); 
	}
}