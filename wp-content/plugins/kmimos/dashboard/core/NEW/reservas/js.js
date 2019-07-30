var table = "";
var tables_extras = [];

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
            "url": ADMIN_AJAX+"?action=list",
            "type": "POST"
        },
        dom: '<"col-md-6"B><"col-md-6"f><"#tblreserva"t>ip',
        buttons: [
            'excelHtml5',
            'csvHtml5',
        ],
        "lengthMenu": [[10, 20, 30, -1], [10, 20, 30, "Todos"]],
        "order": [[ 0, "desc" ]]
	});

} );