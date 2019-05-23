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
            "url": TEMA+'/admin/backend/consolidados/ajax/list.php',
            "type": "POST"
        },
        /*
        dom: 'lBfrtip',
        buttons: [
            {
                columns: ':not(:first-child)',
            },
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5',
        ],
        */
        "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, "Todos"]],

        "initComplete": function(settings, json) {
            confirmadas_handler();
        }
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
    jQuery("#mostrar_confirmadas").on("click", function(e){
        confirmadas_handler(); 
    });
} );

function confirmadas_handler(){
    if( jQuery("#mostrar_confirmadas").attr("data-mostrar") == "true" ){
        jQuery.fn.dataTable.ext.search.pop();
        table.draw();
        jQuery("#mostrar_confirmadas").attr("data-mostrar", "false");
        jQuery("#mostrar_confirmadas").addClass("button-secundary");
        jQuery("#mostrar_confirmadas").removeClass("button-primary");
        jQuery("#mostrar_confirmadas").html("Ocultar Confirmadas");
    }else{
        console.log( "Entro" );
        jQuery.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                return jQuery(table.row(dataIndex).node()).find("td")[3].innerHTML != "Confirmado";
            }
        );
        table.draw();
        jQuery("#mostrar_confirmadas").attr("data-mostrar", "true");
        jQuery("#mostrar_confirmadas").removeClass("button-secundary");
        jQuery("#mostrar_confirmadas").addClass("button-primary");
        jQuery("#mostrar_confirmadas").html("Mostrar Confirmadas");
    }
}

function updateInfo(_this){
    jQuery.post(
        TEMA+'/admin/backend/consolidados/ajax/update.php',
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