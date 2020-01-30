var _table = '';
jQuery( document ).ready(function() {
	init_table("#horario", "veterinario", "horario");
	_table = jQuery("#horario").DataTable();

	jQuery("#btn_edit_horario").on('click', function(e){
		e.preventDefault();

		console.log( jQuery(this).serialize() );

		/*
		jQuery.post(
			AJAX+"?action=kv&m=veterinario&a=ajustes",
			jQuery(this).serialize(),
			function(data){
				if( data.status ){
					alert("Ajustes actualizados exitosamente!");
				}else{
					alert("Error: "+data.error);
				}
				_table.ajax.reload();
			}, 'json'
		);
		*/
	});

	initModal("horario_modal", function(data){

		console.log( data );

		if( data.status ){
			alert("Nuevo horario agregado exitosamente!");
			jQuery("#historial_modal").modal('hide');
			jQuery("#historial_modal .modal-body").html();
		}else{
			alert(data.error);
		}
	});

});

function _edit(_this){
	openModal(
		"horario_modal", 
		'Nuevo Horario', 
		'Agregar', 
		'veterinario', 
		'new_horario', 
		_this.data('id')
	);
}

function _eliminar(_this){
	var confirmed = confirm("¿Esta Seguro de borrar este horario?");
    if (confirmed == true) {
		jQuery.post(
			AJAX+"?action=kv&m=veterinario&a=delete_horario",
			{ id: _this.data('id') },
			function(data){
				if( data.status ){
					alert("Horario borrado exitosamente");
				}else{
					alert("Error: "+data.error);
				}
				_table.ajax.reload();
			}, 'json'
		);
	}
}