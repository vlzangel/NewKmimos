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
            "url": TEMA+'/admin/backend/fotos/ajax/fotos.php',
            "type": "POST"
        }
	});

    jQuery("#close_modal").on("click", function(e){
        cerrar(e);
    });

	var c = document.getElementById("myCanvas");
    CTX = c.getContext("2d");
    

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
	if( jQuery(".fotos_container").length > 0 ){
		jQuery(".modal").css("display", "block");
	    jQuery("body").css("overflow", "hidden");
	    INDEX = 0;
	    procesar_fotos();
    }else{
    	alert("No hay fotos para moderar");
    }
}

var INDEX = 0;
var CANTIDAD = 0;
var CONTADOR = 0;
var CHECKEDS = {};
function procesar_fotos() {
    CANTIDAD = 0;
	CONTADOR = 0;
	CHECKEDS = {};
	CHECKEDS[ "ID_RESERVA" ] = jQuery(".fotos_"+INDEX).attr("data-reserva");
    CHECKEDS[ "PERIODO" ] = jQuery(".fotos_"+INDEX).attr("data-periodo");
    jQuery(".modal > div > span").html("Moderaci&oacute;n de fotos: Reserva <strong>"+CHECKEDS[ "ID_RESERVA" ]+"</strong>");
	var IMGS = ""; var CONT = 1;
	jQuery(".fotos_"+INDEX+" input").each(function(){
		if( jQuery(this).prop('checked') ){
        	IMGS += jQuery( "<img src='"+jQuery(this).attr("data-url")+"' class='img' id='base_"+CONT+"' >" )[0].outerHTML;
        	CHECKEDS[ jQuery(this).attr("id") ] = jQuery(this).val();
        	CONT++;
		}
    });
    jQuery("#base").html( IMGS ).promise().done(function(){
        var img = document.getElementById("fondo");
	    CTX.drawImage(img, 0, 0, 600, 495);
	    CANTIDAD = jQuery( "#base img" ).length;
	    jQuery( "#base img" ).each(function( xindex ) {
	        var img = document.getElementById( jQuery(this).attr("id") );
	        img.onload = function () {
		        CONTADOR++;
		        if( CONTADOR == CANTIDAD ){
		        	actualizar_info();
		        }    
		    };	        
	    });
    });
    jQuery(".modal > div > div").html( "<div style='text-align: center; padding-bottom: 27px;'>"+IMGS+"</div><div class='procesando_fotos'>Procesando...</div>" );
}

function actualizar_info(){
	jQuery( "#base img" ).each(function( xindex ) {
		var img = document.getElementById( jQuery(this).attr("id") );
        var i = jQuery(this).attr("id");
        CTX.drawImage(
            img, 
            jQuery( "#"+i )[0].offsetLeft, 
            jQuery( "#"+i )[0].offsetTop, 
            jQuery( "#"+i )[0].offsetWidth, 
            jQuery( "#"+i )[0].offsetHeight
        ); 	        
    });
	CHECKEDS[ "COLLAGE" ] = jQuery( "#myCanvas" )[ 0 ].toDataURL("image/jpg");
    INDEX++;
    jQuery.post(
		TEMA+"/admin/backend/fotos/ajax/moderar.php",
		CHECKEDS,
		function(HTML){
            if( jQuery(".fotos_container").length != INDEX ){
		    	procesar_fotos();
		    }else{
		    	table.ajax.reload();
		    	cerrar();
		    }
        }
    ); 
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