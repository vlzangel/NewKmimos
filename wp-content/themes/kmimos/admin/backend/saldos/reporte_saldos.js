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

function getSaldo(CB){
	if( !jQuery("#consultar").hasClass("disable") ){
		jQuery("#consultar").addClass("disable");
		jQuery("#consultar").val("Procesando...");
		jQuery.post(
			TEMA+"/admin/backend/saldos/ajax/getSaldo.php",
			{
				saldo: jQuery("#saldo").val(),
				email: jQuery("#email").val()
			},
			function(HTML){
				jQuery("#info_user").html(HTML);

				jQuery("#info_user").css("display", "block");
				jQuery(".confirmaciones").css("display", "block");

	            jQuery("#consultar").removeClass("disable");
	            jQuery("#consultar").val("Actualizar");

	            jQuery(".confirmaciones").css("display", "block");

	            if( CB != undefined ){
	            	CB();
	            }
	        }
	    ); 
	}
}

function quitarSaldo(){
	if( !jQuery("#quitar").hasClass("disable") ){
		jQuery("#quitar").addClass("disable");
		jQuery("#quitar").val("Procesando...");
		jQuery("#saldo").val("0");
		jQuery.post(
			TEMA+"/admin/backend/saldos/ajax/getSaldo.php",
			{
				saldo: 0,
				email: jQuery("#email").val()
			},
			function(HTML){
				jQuery("#info_user").html(HTML);

				jQuery("#info_user").css("display", "block");
				jQuery(".confirmaciones").css("display", "block");

	            jQuery("#quitar").removeClass("disable");
	            jQuery("#quitar").val("Quitar Saldo");

	            jQuery(".confirmaciones").css("display", "block");
	        }
	    ); 
	}
}

function updateSaldo(){
	if( !jQuery("#confirmar").hasClass("disable") ){
		var confirmed = confirm("Esta seguro de cambiar el saldo de "+jQuery(".montoActual").html()+" a "+jQuery(".montoModificado").html()+".?");
    	if (confirmed == true) {
			jQuery("#confirmar").addClass("disable");
			jQuery("#confirmar").val("Procesando...");
			jQuery.post(
				TEMA+"/admin/backend/saldos/ajax/updateSaldo.php",
				{
					saldo: jQuery("#saldo").val(),
					email: jQuery("#email").val()
				},
				function(HTML){

					console.log( HTML );

		            jQuery("#confirmar").removeClass("disable");
		            jQuery("#confirmar").val("Confirmar");

					// jQuery("#info_user").css("display", "none");
					getSaldo(function(){
						jQuery(".confirmaciones").css("display", "none");
					});				

		        }
		    ); 
		}
	}
}

function cerrarInfo(){
	jQuery("#saldo").val("");
	jQuery("#email").val("");
	jQuery("#info_user").css("display", "none");
	jQuery(".confirmaciones").css("display", "none");
}	