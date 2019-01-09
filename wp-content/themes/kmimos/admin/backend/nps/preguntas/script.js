var table = ""; 

jQuery(document).ready(function() {

	loadTabla();

	jQuery(document).on('click', '[data-toggle="tab"]', function(e){
    	e.preventDefault();

    	// Item
    	jQuery(this).parent().parent().find('li').removeClass('active');
    	jQuery(this).parent().addClass('active');
    	
    	// Content
    	var group = jQuery(this).attr('group');
		jQuery('[group="'+group+'"]').removeClass('active');

    	var id = jQuery(this).attr('href');
		jQuery(id).addClass('active');
    });

    jQuery("#close_modal").on("click", function(e){
        cerrar(e);
    });
 
    jQuery("#form-search").on("submit", function(e){
		e.preventDefault();
    });

    jQuery("#btn-search").on("click", function(e){
		loadTabla();
	});
 
    /* CAMPANAS */

    jQuery("[data-modal='crear']").on('click', function(){
		abrir_link( jQuery(this) );
    });
    jQuery(document).on('click', '[data-modal="generador_codigo"]', function(e){
		abrir_link( jQuery(this) );
    });

    // Guardar campanas

    jQuery(document).on( 'submit', '#crear_campana', function(e){
    	e.preventDefault();
    	var btn = jQuery('#crear_campana_submit');
		if( !btn.hasClass('disabled') ){
			btn.addClass('disabled');
			btn.html('Guardando');
			
			jQuery.post(
				TEMA+'/admin/backend/nps/preguntas/ajax/crear.php',
				jQuery(this).serialize(),
				function(data){
					loadTabla();
					if( data.id > 0 ){
						cerrar();
						init_modal({
							"titulo": 'INTEGRACI&Oacute;N',
							"modulo": "nps/preguntas",
							"modal": 'generador_codigo',
							"info": {
								"ID": data.id,
								"email_user": email
							}
						});
					}else{
						btn.removeClass('disabled');
						btn.html('Guardar');
					}
				},
			'json');

		}
    });

	calcular_score_nps();
});

function calcular_score_nps(){
	jQuery.post(
		TEMA+'/admin/backend/nps/preguntas/ajax/score_nps_global.php',
		{},
		function(data){
			if( data.total_rows > 0 ){
				jQuery('#score_nps_global').html( data.score_nps );
				jQuery('#score_nps_progress').html( data.progress );
			}
		},
	'json');
}

function loadTabla( ){
 	 
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
            "url": TEMA+'/admin/backend/nps/preguntas/ajax/list.php',
            "data": { 
            	'desde': jQuery('[name="ini"]').val(), 
            	"hasta":jQuery('[name="fin"]').val(),
            	"email": email_user
            },
            "type": "POST"
        }
	});
}

function abrir_link(e){
	init_modal({
		"titulo": e.attr("data-titulo"),
		"modulo": "nps/preguntas",
		"modal": e.attr("data-modal"),
		"info": {
			"ID": e.attr("data-id"),
			"email": email_user,
		}
	});
}

 








 