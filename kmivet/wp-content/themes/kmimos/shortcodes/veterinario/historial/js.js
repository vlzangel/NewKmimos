var _table = '';
jQuery( document ).ready(function() {
	init_table("#historial", "veterinario", "historial");
	_table = jQuery("#historial").DataTable();

	initModal("historial_modal", function(r){
		console.log( r );

		switch( r.seccion ){
			case 'examen':
				alert("Examen Cargado Exitosamente!");
				_table.ajax.reload();
				jQuery("#historial_modal").modal('hide');
			break;
			case 'recipe':
				_table.ajax.reload();
				recipe_get();
			break;
			case 'diagnostico':
				alert("Diagnóstico Cargado Exitosamente!");
				_table.ajax.reload();
				jQuery("#historial_modal").modal('hide');
			break;
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

function get_list_diagnostic(id, level, lista_id){
	jQuery.post(
		AJAX+"?action=kv&m=veterinario&a=get_list_diagnostic",
		{
			id: id,
			level: level
		},
		function(r){
			var HTML = '<option>Seleccione...</option>';
			jQuery.each(r.result, function(i, v){
				HTML += '<option value="'+v.id+'">'+v.title+'</option>';
			});
			jQuery("#"+lista_id).html(HTML);
		},
		'json'
	);
}

function recipe_get(){
	jQuery.post(
		AJAX+"?action=kv&m=veterinario&a=recipe_get",
		{ appointment_id: jQuery("#appointment_id").val() },
		function(r){
			// console.log(r);
			jQuery("#search_medicamento_input").val("");
			jQuery("#indications").val("");
			jQuery("#medicine_id").val("");
			if( r.status ){
				var HTML = '';
				jQuery.each(r.lista.r, function(i, v){
					HTML += '<tr><td>'+v.medicine.name+' ('+v.medicine.presentation+')</td><td>'+v.indication+'</td></tr>';
				});
				jQuery("#search_medicamento_agregados tbody").html(HTML);
			}
		},
		'json'
	);
}

function _recipe(_this){
	openModal(
		"historial_modal", 
		'Recetar Medicamentos', 
		'Agregar / Actualizar', 
		'veterinario', 
		'recipe_examen', 
		_this.data('id')
	);
}
function _examen(_this){
	openModal(
		"historial_modal", 
		'Cargar Examen', 
		'Cargar', 
		'veterinario', 
		'cargar_examen', 
		_this.data('id')
	);
}

function _examen_ver(_this){
	openModal(
		"historial_modal", 
		'Ver Resumen', 
		'', 
		'veterinario', 
		'ver_examen', 
		_this.data('id')
	);
}

function _diagnostico(_this){
	openModal(
		"historial_modal", 
		'Cargar Diagnóstico', 
		'Actualizar Diagnóstico', 
		'veterinario', 
		'cargar_diagnostico', 
		_this.data('id')
	);
}

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