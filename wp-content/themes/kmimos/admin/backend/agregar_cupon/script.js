jQuery(document).ready(function(){
	jQuery('#frm-cupon').on('submit', function(e){
		e.preventDefault();
		jQuery.post( TEMA+'/admin/backend/agregar_cupon/ajax/agregar_cupon.php', jQuery(this).serialize(),
			function(r){

				if( r.error == '' ){				
					jQuery('#view_data_antes').html(r.antes);
					jQuery('#view_data_despues').html(r.despues);
				}else{
					alert(r.error);
				}

			}, 'json'
		);

	});
});