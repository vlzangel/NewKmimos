function _despues(_this) {
	console.log( _this.val() );
	var opcion =  parseInt( _this.val() );

	switch( opcion ){
		case 0:
			jQuery("#campaing_despues_div").addClass("campaing_despues_hidden");
			jQuery("#campaing_despues_div").removeClass("campaing_despues_show");
		break;

		case 1:
			jQuery("#campaing_despues_div").removeClass("campaing_despues_hidden");
			jQuery("#campaing_despues_div").addClass("campaing_despues_show");
		break;
	}
}