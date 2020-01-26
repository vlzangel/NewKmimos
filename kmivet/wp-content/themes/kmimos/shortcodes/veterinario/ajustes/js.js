jQuery( document ).ready(function() {
	jQuery("#kv_form").on('submit', function(e){
		e.preventDefault();
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
	});
});