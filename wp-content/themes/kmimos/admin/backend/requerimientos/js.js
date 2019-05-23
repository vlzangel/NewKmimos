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
            "url": TEMA+'/admin/backend/requerimientos/ajax/list.php',
            "type": "POST"
        },
        "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, "Todos"]]
	});

    jQuery("#close_modal").on("click", function(e){
        cerrar(e);
    });

    jQuery("#actualizar_list").on("click", function(e){
    	jQuery("#actualizar_list").html('<i class="fa fa-spinner fa-spin"></i> Actualizando...');
        jQuery("#actualizar_list").prop("disabled", true);

    	table.ajax.reload(function(r){
			jQuery("#actualizar_list").html('Actualizar');
			jQuery("#actualizar_list").prop("disabled", false);
		}, true);
    });

    jQuery("#nuevo").on("click", function(e){
    	init_modal({
			"titulo": "Nuevo Requerimiento",
			"modulo": "requerimientos",
			"modal": "create",
			"info": {
				"ID": 0
			}
		});
    });

} );

function updateInfo(_this){

	console.log( _this.val() );

    jQuery.post(
        TEMA+'/admin/backend/requerimientos/ajax/update.php',
        {
        	id: _this.data("id"),
        	valor: _this.val(),
        	campo: _this.data("type"),
        },
        function(data){
        	console.log( data );
        }, 'json'
    );

}