var _table = '';
jQuery( document ).ready(function() {
	init_table("#historial", "perfil", "historial");
	_table = jQuery("#historial").DataTable();
});

function _cancelar(_this){

	var confirmed = confirm("Esta Seguro de cancelar esta cita?");
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