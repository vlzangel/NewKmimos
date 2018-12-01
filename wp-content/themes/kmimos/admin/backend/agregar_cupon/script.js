jQuery(document).ready(function(){
	jQuery('#frm-cupon').on('submit', function(e){
		e.preventDefault();
		jQuery.post( TEMA+'/admin/backend/agregar_cupon/ajax/agregar_cupon.php', jQuery(this).serialize(),
			function(r){
				
				jQuery('#view_data').html(r.contenido);

			}, 'json'
		);

	});
});