
jQuery(document).ready(function() {

	jQuery('#btn-grafico').on('click', function(){
		jQuery('#grafico-container').toggle();
		if( jQuery('#grafico-container').css('display') == 'none' ){
			jQuery('.grafico-icon').removeClass('fa-eye-slash');
			jQuery('.grafico-icon').addClass('fa-eye');
		}else{
			jQuery('.grafico-icon').removeClass('fa-eye');
			jQuery('.grafico-icon').addClass('fa-eye-slash');
		}
	});
	jQuery('#btn-tabla').on('click', function(){
		jQuery('#tabla-container').toggle();
		if( jQuery('#tabla-container').css('display') == 'none' ){
			jQuery('.tabla-icon').removeClass('fa-eye-slash');
			jQuery('.tabla-icon').addClass('fa-eye');
		}else{
			jQuery('.tabla-icon').removeClass('fa-eye');
			jQuery('.tabla-icon').addClass('fa-eye-slash');
		}
	});

	jQuery('[data-action]').on('click', function(){
		console.log( jQuery(this).data('action') );
		jQuery('#tipo_datos').html( jQuery(this).data('action') );
		jQuery('[name="sucursal"]').val( jQuery(this).data('action') );
	});

});

