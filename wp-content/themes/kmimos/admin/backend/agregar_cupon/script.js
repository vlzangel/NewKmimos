jQuery(document).ready(function(){
	
	jQuery('[name="idReserva"]').on('change', function(e){
		desglose( jQuery(this).val(), 'view_data_old', 'view_data_new' );
		jQuery('#view_data_old').css('display','inline-block');
	});

	jQuery('#frm-cupon').on('submit', function(e){
		e.preventDefault();
		if( !jQuery('[type="submit"]').hasClass('disabled') ){

			jQuery('[type="submit"]').addClass('disabled');
			jQuery('[type="submit"]').html('Aplicando cupon');

			jQuery.post( TEMA+'/admin/backend/agregar_cupon/ajax/agregar_cupon.php', jQuery(this).serialize(),
				function(r){

					desglose( jQuery('#reserva').val(), 'view_data_new', 'view_data_new' );
					jQuery('#view_data_new').css('display','inline-block');

					jQuery('[type="submit"]').removeClass('disabled');
					jQuery('[type="submit"]').html('Aplicar cupon');

				}, 'json'
			);
		}

	});
});

function desglose ( id, _new, _old ){
	jQuery.post( TEMA+'/admin/backend/agregar_cupon/ajax/desglose.php', {idReserva: id},
		function(r){
			if( r.error == '' ){				
				jQuery('#' + _old).html('');
				jQuery('#' + _new).html(r.antes);
			}else{
				alert(r.error);
			}

		}, 'json'
	);
}