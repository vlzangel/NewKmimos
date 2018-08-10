jQuery(document).ready(function() { 
	jQuery(".navbar").removeClass("bg-transparent");
	jQuery(".navbar").addClass("bg-white-secondary");
	jQuery('.navbar-brand img').attr('src', HOME+'/images/new/km-logos/km-logo-negro.png');


	jQuery('[data-target="emitir_factura"]').on('click', function(){
		jQuery.post(HOME+"/procesos/generales/activar_envio_cfdi.php", 
		{  'user_id': jQuery(this).attr('data-id') },
		function( data ){
			jQuery('#emitir_factura').modal('show');
		});
	});
});