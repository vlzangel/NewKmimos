var _table = '';
jQuery( document ).ready(function() {
	init_table("#historial", "perfil", "historial");
	_table = jQuery("#historial").DataTable();

	initModal("historial_modal", function(data){
		if( data.status ){
			alert("Valoración enviada exitosamente!");
			jQuery("#historial_modal").modal('hide');
			jQuery("#historial_modal .modal-body").html();
		}else{
			alert("Error valorando el servicio");
		}
	});
});

function _cancelar(_this){
	var confirmed = confirm("¿Esta Seguro de cancelar esta cita?");
    if (confirmed == true) {
		jQuery.post(
			AJAX+"?action=kv&m=perfil&a=cancelar_paciente",
			{ id: _this.data('id') },
			function(data){
				if( data.status ){
					alert("Cita cancelada exitosamente");
				}else{
					alert("Error cancelando la cita");
				}
				_table.ajax.reload();
			}, 'json'
		);
	}
}

function _valorar(_this){
	openModal(
		"historial_modal", 
		'Valorar', 
		'Valorar', 
		'perfil', 
		'valorar', 
		_this.data('id')
	);
}