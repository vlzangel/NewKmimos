jQuery(document).ready(function(){

	jQuery('.col-item').on('click', function(){
		jQuery('.col-item').removeClass('col-item-active');
		jQuery(this).addClass('col-item-active');
		jQuery('[name="respuesta"]').val( jQuery(this).attr('data-item') );
	});


	jQuery('#feedback-form').on('submit', function(e){
		e.preventDefault();
    	var btn = jQuery('#enviar-feedback');
		var respuesta = jQuery('[name="respuesta"]').val();
		if( respuesta > 0 ){
			if( !btn.hasClass('disabled') ){
				btn.addClass('disabled');
				btn.html('Guardando');
				jQuery.post(
					HOME+'/procesos/nps_feedback/update_feedback.php',
					jQuery(this).serialize(),
					function(data){
						if( data.sts == 1 ){
							location.reload();					
						}else{
							btn.removeClass('disabled');
							btn.html('Enviar comentarios');
						}
					},
				'json');
			}
		}else{
			jQuery('.col-item').css("border", "1px solid red");
		}
	})

});