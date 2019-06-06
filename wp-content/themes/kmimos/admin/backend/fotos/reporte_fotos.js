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

var moderaciones = 0;
var contador = 0;
function moderar(){
	if( jQuery(".fotos_container").length > 0 ){
		moderaciones = jQuery(".fotos_container").length;
		contador = 0;
	    jQuery(".fotos_container").each(function(){
			CHECKEDS = {};
			CHECKEDS[ "ID_RESERVA" ] = jQuery(this).attr("data-reserva");
    		CHECKEDS[ "PERIODO" ] = jQuery(this).attr("data-periodo");
			var IMGS = ""; var CONT = 1;
			jQuery( "#"+jQuery(this).attr("id")+" .input_check" ).each(function(){
				if( jQuery(this).prop('checked') ){
		        	CHECKEDS[ jQuery(this).attr("id") ] = jQuery(this).val();
				}
		    });
			jQuery.post(
				TEMA+"/admin/backend/fotos/ajax/moderar.php",
				CHECKEDS,
				function(HTML){
		            console.log(HTML);
		            contador++;
		            if( contador == moderaciones ){
			            table.ajax.reload();
			            alert("Moderación completada exitosamente!");
		            }
		        }
		    ); 
	    });
    }else{
    	alert("No hay fotos para moderar");
    }
}


function ver_foto(e){
	jQuery(".modal > div > span").html("Foto");
	jQuery(".modal > div > div").html("<img src='"+e.attr("data-img")+"' style='width: 100%;' />");

	jQuery(".modal").css("display", "block");
    jQuery("body").css("overflow", "hidden");

    jQuery("#close_modal").on("click", function(e){
        cerrar(e);
    });
}


function des_excel(){
	jQuery.post(
		TEMA+"/admin/backend/fotos/ajax/excel.php",
		{
			title: ['Reserva', 'Cuidador', 'Cliente', 'Mascotas', 'Fotos 12 m', 'Fotos 06 pm', 'Bloqueo', 'Status'],
			urlbase : HOME
		},
		function(HTML){
            console.log(HTML);
            location.href = HTML["file"];
        }, 'json'
    ); 
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
		    	//procesar_fotos();
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