jQuery(document).ready(function() {
    jQuery('#example').DataTable({
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
            "url": TEMA+'/admin/backend/fotos/ajax/fotos.php',
            "type": "POST"
        }
	});

	
} );

function abrir_link(e){
	init_modal({
		"titulo": e.attr("data-titulo"),
		"modulo": "fotos",
		"modal": e.attr("data-modal"),
		"info": {
			"ID": e.attr("data-id")
		}
	});
}

function moderar(){

	var IMGS = ""; var CONT = 1;
	var checkes = {};
	jQuery("#form_moderar input").each(function(){
		if( jQuery(this).prop('checked') ){
        	checkes[ jQuery(this).attr("id") ] = jQuery(this).val();
        	IMGS += jQuery( "<img src='"+jQuery(this).attr("data-url")+"' class='img' id='base_"+CONT+"' >" )[0].outerHTML;
        	CONT++;
		}
    });

    jQuery("#base").html( IMGS );

    checkes[ "ID_RESERVA" ] = ID_RESERVA;
    checkes[ "CANTIDAD" ] = jQuery("#cantidad").val();

    var c = document.getElementById("myCanvas");
    var ctx = c.getContext("2d");
    var img = document.getElementById("fondo");
    ctx.drawImage(img, 0, 0, 600, 495);

    jQuery( "#base img" ).each(function( index ) {
        var img = document.getElementById( jQuery(this).attr("id") );
        var i = jQuery(this).attr("id");
        console.log();
        ctx.drawImage(
            img, 
            jQuery( "#"+i )[0].offsetLeft, 
            jQuery( "#"+i )[0].offsetTop, 
            jQuery( "#"+i )[0].offsetWidth, 
            jQuery( "#"+i )[0].offsetHeight
        );
    });

	jQuery.post(
		TEMA+"/admin/backend/fotos/ajax/moderar.php",
		checkes,
		function(HTML){
            console.log(HTML);
        }
    );
}