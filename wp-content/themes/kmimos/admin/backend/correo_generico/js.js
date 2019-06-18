var table; 
jQuery(document).ready(function() {

	// loadTabla();

    jQuery("#close_modal").on("click", function(e){
        cerrar(e);
    });
 
    jQuery("#form").on("submit", function(e){
		e.preventDefault();
		// console.log("Hola");
		if( !jQuery("#submit").hasClass("disable") ){
    		jQuery("#submit").addClass("disable");
			var confirmed = confirm("Esta seguro de enviar el correo.?");
	    	if (confirmed == true) {
				
				jQuery.post(
					TEMA+'/admin/backend/'+MODULO+'/ajax/enviar.php',
					jQuery(this).serialize(),
					function(data){

						// jQuery(".modal > div > div").html( data.html );
						// jQuery(".modal").css("display", "block");

						if( data.error == "" ){
							alert( data.respuesta );
						}else{
							alert( data.error );
						}

						jQuery("#submit").removeClass("disable");
					}, 'json'
				);

			}else{
				jQuery("#submit").removeClass("disable");
			}
		}
    });

    jQuery("#btn-search").on("click", function(e){
		loadTabla();
	});

});

function loadTabla(){
 	/*
	table = jQuery('#example').DataTable();
	table.destroy();

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
		"dom": '<B><f><t>ip',
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
            "url": TEMA+'/admin/backend/seguimiento/ajax/list.php',
            "data": { 'desde': jQuery('[name="ini"]').val(), "hasta":jQuery('[name="fin"]').val() },
            "type": "POST"
        }
	});
	*/
 
}

function abrir_link(e){
	init_modal({
		"titulo": e.attr("data-titulo"),
		"modulo": "seguimiento",
		"modal": e.attr("data-modal"),
		"info": {
			"ID": e.attr("data-id")
		}
	});
}

 








 