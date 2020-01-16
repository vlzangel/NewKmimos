jQuery( document ).ready(function() {
	
	jQuery("[name='motivo']").on('change', function(e){
		if( jQuery(this).val() == 'otro' ){
			jQuery("#otro_motivo").css('display', 'block');
			jQuery("[name='otro_motivo']").prop('required', true);
		}else{
			jQuery("#otro_motivo").css('display', 'none');
			jQuery("[name='otro_motivo']").removeAttr('required');
		}
	});

	jQuery("#cancelar_cita").on("submit", function(e){
		e.preventDefault();

		_post(
			AJAX+'?action=kv&m=citas&a=cancelar',
			jQuery("#cancelar_cita").serialize(),
			function(r){
				// console.log( r );
				if( r.status ){
					jQuery(".kv_msg").html("Estatus Cambiado Exitosamente!");
					jQuery(".kv_msg").attr("class", "kv_msg success");

					setTimeout(function(e){
						location.href = RAIZ;
					}, 1500);
					
				}else{
					jQuery(".kv_msg").html(r.error);
					jQuery(".kv_msg").attr("class", "kv_msg error");
				}
			}
		);

	});

});