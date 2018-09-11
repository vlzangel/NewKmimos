jQuery(document).ready(function(){

	jQuery('.limite').on('change', function(e){
		/*var kmi = parseFloat(jQuery('#descuento_kmimos').val());
		var cui = parseFloat(jQuery('#descuento_cuidador').val());
		var total = 0;
		if( kmi > 0 ){
			total += kmi;
		}
		if( cui > 0 ){
			total += cui;
		}
		if( total > 100 ){
			jQuery('#descuento_kmimos').val( 0 );
			jQuery('#descuento_cuidador').val( 0 );
			alert( "El descuento compartido entre Kmimos y el Cuidador no puede ser mayor al 100%" );
		}*/
	});
	jQuery('.limite').on('keydown', function(e){
		cancelKeypress = [',','-'];
		if( cancelKeypress.indexOf(e.key) > -1 ){ 
			console.log('poas1');
			return false;
		}else{		
	 		if( e.key >= 0 && e.key <= 9 ){
	 			var val = parseFloat(jQuery(this).val()+e.key);
				console.log(val);
				if( val < 0 && val > 100 ){
					console.log('poas3');
					return false;
				}
	 		}
		}
	});

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