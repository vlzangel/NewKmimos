var table = "";
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

var checkes = {};
function moderar(){

	if( !jQuery("#Moderar").hasClass("disable") ){

		jQuery("#Moderar").addClass("disable");
		jQuery("#Moderar").val("Procesando...");
		
		var IMGS = ""; var CONT = 1;
		jQuery("#form_moderar input").each(function(){
			if( jQuery(this).prop('checked') ){
	        	IMGS += jQuery( "<img src='"+jQuery(this).attr("data-url")+"' class='img' id='base_"+CONT+"' >" )[0].outerHTML;
	        	CONT++;
			}
	    });
	    jQuery("#base").html( IMGS );

	    var c = document.getElementById("myCanvas");
	    var ctx = c.getContext("2d");
	    var img = document.getElementById("fondo");
	    ctx.drawImage(img, 0, 0, 600, 495);

	    jQuery( "#base img" ).each(function( index ) {
	        var img = document.getElementById( jQuery(this).attr("id") );
	        img.onload = function () {
		        var i = jQuery(this).attr("id");
		        ctx.drawImage(
		            img, 
		            jQuery( "#"+i )[0].offsetLeft, 
		            jQuery( "#"+i )[0].offsetTop, 
		            jQuery( "#"+i )[0].offsetWidth, 
		            jQuery( "#"+i )[0].offsetHeight
		        );     
		    };	        
	    });

	    setTimeout(function(){

	    	var checkes = {};
			jQuery("#form_moderar input").each(function(){
				if( jQuery(this).prop('checked') ){
		        	checkes[ jQuery(this).attr("id") ] = jQuery(this).val();
		        	CONT++;
				}
		    });
		    checkes[ "ID_RESERVA" ] = ID_RESERVA;
		    checkes[ "PERIODO" ] = PERIODO;
		    checkes[ "COLLAGE" ] = jQuery( "#myCanvas" )[ 0 ].toDataURL("image/jpg");

	        jQuery.post(
				TEMA+"/admin/backend/fotos/ajax/moderar.php",
				checkes,
				function(HTML){

					console.log( HTML );

		            jQuery("#Moderar").removeClass("disable");
		            jQuery("#Moderar").val("Moderar");

		            table.ajax.reload();
		        }
		    );   
	    }, 500);

	}

}

function Bloquear(){
	if( !jQuery("#Bloquear").hasClass("disable") ){

		jQuery("#Bloquear").addClass("disable");
		jQuery("#Bloquear").val("Procesando...");

		jQuery.post(
			TEMA+"/admin/backend/fotos/ajax/bloqueo.php",
			{
				bloquear: jQuery("#bloquear").val(),
				ID_RESERVA: ID_RESERVA
			},
			function(HTML){
	            jQuery("#Bloquear").removeClass("disable");
	            jQuery("#Bloquear").val("Bloquear");

	            table.ajax.reload();
	        }
	    ); 
	}
}