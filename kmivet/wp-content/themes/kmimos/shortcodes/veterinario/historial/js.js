var _table = '';
jQuery( document ).ready(function() {
	init_table("#historial", "veterinario", "historial");
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

	jQuery("[name='motivo']").on('change', function(e){
		if( jQuery(this).val() == 'otro' ){
			jQuery("#otro_motivo").css('display', 'block');
			jQuery("[name='otro_motivo']").prop('required', true);
		}else{
			jQuery("#otro_motivo").css('display', 'none');
			jQuery("[name='otro_motivo']").removeAttr('required');
		}
	});
});



function _ver(_this){
	openModal(
		"historial_modal", 
		'Ver', 
		'', 
		'veterinario', 
		'ver', 
		_this.data('id')
	);
}

function _cancelar(_this){
	openModal(
		"historial_modal", 
		'Cancelar', 
		'Cancelar', 
		'veterinario', 
		'cancelar', 
		_this.data('id')
	);
}

function _arribar(_this){
	var confirmed = confirm("Presiona aceptar solo si ya llegaste o estas por llegar a la ubicación del paciente");
    if (confirmed == true) {
    	var t = btn_load_on( _this );
		jQuery.post(
			AJAX+"?action=kv&m=veterinario&a=arribo",
			{ cita_id: _this.data('id') },
			function(data){
				if( data.status ){
					alert("Notificación de Arribo a Domicilio enviada exitosamente!");
				}else{
					alert("Error: "+data.error);
				}
				btn_load_off( _this, t );
				_table.ajax.reload();
			}, 'json'
		);
	}
}

function _finalizar(_this){
	var confirmed = confirm("Presiona aceptar solo si ya completaste el servicio");
    if (confirmed == true) {
    	var t = btn_load_on( _this );
		jQuery.post(
			AJAX+"?action=kv&m=veterinario&a=finalizar",
			{ id: _this.data('id') },
			function(data){
				if( data.status ){
					alert("Cita completada exitosamente!");
				}else{
					alert("Error: "+data.error);
				}
				btn_load_off( _this, t );
				_table.ajax.reload();
			}, 'json'
		);
	}
}