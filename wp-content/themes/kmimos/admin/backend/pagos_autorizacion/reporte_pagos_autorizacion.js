var table = ""; var CTX = "";
var fechas = '';

// Variables por defecto de busqueda
var _hiddenDefault = { "nuevo":[1,2,9,11], 'generados': [] };
var _tipo = 'generados';
var _hiddenColumns = _hiddenDefault.generados;

jQuery(document).ready(function() {

	loadTabla( _tipo, _hiddenColumns );

    jQuery("#close_modal").on("click", function(e){
        cerrar(e);
    });
 
    jQuery("#form-search").on("submit", function(e){
		e.preventDefault();
    });

    jQuery("#btn-search").on("click", function(e){
		loadTabla( _tipo, _hiddenColumns );
	});

    jQuery(document).on('click', "[data-action='error']", function(e){
    	e.preventDefault();
    	alert( jQuery(this).attr('title') );
    	return false;
    });

    jQuery("#select-all").on("click", function(e){
    	if( jQuery("input[data-type='item_selected']:checked").size() > 0 ){
			$("input[data-type='item_selected']").prop('checked', '' );
    	}else{
			$("input[data-type='item_selected']").prop('checked', 'checked' );
    	}
    });

	jQuery('.nav-link').on('click', function (e) {

		e.preventDefault();
		jQuery('.nav-link').removeClass('active');

	  	_tipo = jQuery(this).attr('href'); 

		var id = jQuery(this).attr('id');
		jQuery('#'+id).addClass('active');
		jQuery('#'+id+'-tab').addClass('active');

		_hiddenColumns = _hiddenDefault.nuevo;
		jQuery('[name="ini"]').val(fecha.ini);
		jQuery('[name="fin"]').val(fecha.fin);
		jQuery('#opciones-nuevo').css('display', 'block');

		if( jQuery('#'+id).attr('href') == 'generados' ){
			jQuery('#opciones-nuevo').css('display', 'none');
			jQuery('[name="ini"]').val("YYYY-MM-DD");
			jQuery('[name="fin"]').val("YYYY-MM-DD");
			_hiddenColumns = _hiddenDefault.generados;
		}
  		 
	  	loadTabla( _tipo, _hiddenColumns );
	});

	jQuery('#quitar-filtro').on('click', function(){
		jQuery('[name="ini"]').val("YYYY-MM-DD");
		jQuery('[name="fin"]').val("YYYY-MM-DD");
		loadTabla( _tipo, _hiddenColumns );
	});
 
    jQuery(document).on('click', '[data-action="procesar"]', function(e){
    	e.preventDefault();
    	var list = [];
		jQuery.each(jQuery("input:checked"), function(){
			var user = jQuery(this).val();
			list.push({'ID':user});
		});
		procesar_solicitud( list, jQuery(this).attr('data-target') );
	});

    jQuery(document).on('click', "[data-modal='comentarios']", function(){
		abrir_link( jQuery(this) );
    });
    
    jQuery("[data-modal='autorizar']").on('click', function(){
    	if( jQuery("input:checked").size() > 0 ){
    		abrir_link( jQuery(this) );
    	}else{
    		alert('Debe seleccionar los registros a procesar');
    	}
    });


});

function procesar_solicitud( list, accion ){
	jQuery.post(
		TEMA+'/admin/backend/pagos_autorizacion/ajax/procesar_solicitud.php',
		{
			'accion':accion, 
			'list':list, 
			'user_id': ID,
			'comentario': jQuery('[name="observaciones"]').val()
		},
		function(data){
			console.log(data);
			loadTabla( _tipo, _hiddenColumns );	
			cerrar();
		}
	);
}
 
function loadTabla( tipo, hiddenColumns ){
 	 
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
		columnDefs: [
            {
            	"targets": hiddenColumns,
                "visible": false
            }
        ],
        "scrollX": true,
        "ajax": {
            "url": TEMA+'/admin/backend/pagos_autorizacion/ajax/pagos_autorizacion.php',
            "data": { 
            	'user_id': ID,
            	'tipo': _tipo, 
            	'desde': jQuery('[name="ini"]').val(), 
            	'hasta': jQuery('[name="fin"]').val() 
            },
            "type": "POST"
        }
	});
}

function abrir_link(e){
	init_modal({
		"titulo": e.attr("data-titulo"),
		"modulo": "pagos_autorizacion",
		"modal": e.attr("data-modal"),
		"info": {
			"ID": e.attr("data-id")
		}
	});
}

 








 