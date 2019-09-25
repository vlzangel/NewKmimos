jQuery(document).ready(function() {

	loadTabla();

	jQuery(document).on('click','[data-target="load-comentarios"]', function(){
		loadComentarios( jQuery(this).attr('data-code') );
		if( $(window).width() <= 500 ){		
			jQuery('[data-id]').removeClass('active');
			jQuery('[data-objetivo]').css('position', 'absolute');
			jQuery('[data-objetivo]').css('left', '-1000px');

			jQuery('[data-id="comentario"]').addClass('active');
			jQuery('[data-objetivo="list-comentario"]').css('position', 'initial');
			jQuery('[data-objetivo="list-comentario"]').css('rigth', '0px');
		}

	});

    jQuery('[name="redirect-pregunta"]').on("change", function(e){
        // location.href = RAIZ+'wp-admin/admin.php?page=nps_feedback&campana_id='+jQuery(this).val();
        ID = jQuery(this).val();
        jQuery( "#pregunta-title" ).html( jQuery('option:selected',this).attr('data-pregunta') );
        loadTabla();
        loadComentarios( '' );
    });

	jQuery('#email-feedback').on('submit', function(e){
		e.preventDefault();
		var btn = jQuery('#enviar_email');
		if( !btn.hasClass('disabled') ){
			btn.addClass('disabled');
			btn.html('<i class="fa fa-circle-o-notch fa-spin fa-fw"></i> Enviando');
			jQuery.post(
				TEMA+'/admin/backend/nps/feedback/ajax/email.php',
				jQuery(this).serialize(),
				function(data){
					if( data.sts == 1 ){
						loadComentarios( jQuery('[name="code"]').val() );
					}else{						
					}
					btn.removeClass('disabled');
					btn.html('<i class="fa fa-envelope-o" aria-hidden="true"></i> Enviar comentario');
					jQuery('[name="comentario"]').val('');
				},
			'json');		
		}
	});

	jQuery('[data-id]').on('click', function(){
		var id = jQuery(this).attr('data-id');
		 
		if( id == 'usuario' ){		
			jQuery('[data-id]').removeClass('active');
			jQuery('[data-objetivo]').css('position', 'absolute');
			jQuery('[data-objetivo]').css('left', '-1000px');

			jQuery('[data-id="'+id+'"]').addClass('active');
			jQuery('[data-objetivo="list-'+id+'"]').css('position', 'initial');
			jQuery('[data-objetivo="list-'+id+'"]').css('rigth', '0px');
		}else{
			alert( "Debe seleccionar un usuario para ver sus comentarios." );
		}
	});

});

function loadComentarios( code ){
	jQuery('#comentarios').html('<div class="media alert alert-warning"><div class="media-body">Selecciona un usuario para cargar los comentarios</div></div>');
	jQuery.post(
		TEMA+'/admin/backend/nps/feedback/ajax/comentarios.php',
		{ 'code': code },
		function(data){
			jQuery('#comentarios').html(data.comentarios);
			jQuery('[name="email"]').val(data.email);
			jQuery('[name="code"]').val(code);
			jQuery('[name="respuesta_id"]').val(data.id);

			jQuery('#enviar_email').removeClass('disabled');
			jQuery('textarea').removeAttr('readonly');
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
		"dom": '<f><t>ip',
        "scrollX": true,
        "ajax": {
            "url": TEMA+'/admin/backend/nps/feedback/ajax/list.php',
            "data": { 'id': ID },
            "type": "POST"
        }
	});
}

function _valorar(_this){
	jQuery.post(
		TEMA+"/admin/backend/nps/feedback/ajax/valorar.php",
		{
			respuesta_id: _this.data('id')
		},
		function(data){
			// console.log( data );
			alert(data.respuesta);
		}, 'json'
	);
}