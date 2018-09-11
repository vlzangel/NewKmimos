jQuery(document).ready(function(){

	jQuery('#descuento_tipo').on('change', function(e){
		show_discount_field(jQuery(this).val());
	});
	show_discount_field(jQuery('#descuento_tipo').val());

});

function show_discount_field(val){
	jQuery('.descuento_kmimos_field').addClass('hidden');
	jQuery('.descuento_cuidador_field').addClass('hidden');
	if( val == 'compartido' ){
		jQuery('.descuento_kmimos_field').removeClass('hidden');
		jQuery('.descuento_cuidador_field').removeClass('hidden');
	}
}