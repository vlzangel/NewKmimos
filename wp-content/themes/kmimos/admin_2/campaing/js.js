function _hacer_despues(_this) {
	var opcion =  parseInt( _this.val() );
	switch( opcion ){
		case 0:
			jQuery("#campaing_hacer_despues_div").addClass("campaing_despues_hidden");
			jQuery("#campaing_hacer_despues_div").removeClass("campaing_despues_show");
			jQuery("#listas_div").removeClass("campaing_despues_hidden");
			jQuery("#listas_div").addClass("campaing_despues_show");
			jQuery("[data-name]").each(function(i, v){
				jQuery(this).attr("name", jQuery(this).data("name") );
				jQuery(this).prop("required", jQuery(this).data("required") );
			});
		break;
		case 1:
			jQuery("#campaing_hacer_despues_div").removeClass("campaing_despues_hidden");
			jQuery("#campaing_hacer_despues_div").addClass("campaing_despues_show");
			jQuery("#listas_div").addClass("campaing_despues_hidden");
			jQuery("#listas_div").removeClass("campaing_despues_show");
			jQuery("[data-name]").each(function(i, v){
				jQuery(this).attr("name", "" );
				jQuery(this).prop("required", false );
			});
		break;
	}
}

function _verificar_names(){
	var opcion =  parseInt( jQuery("#hacer_despues").val() );
	switch( opcion ){
		case 0:
			jQuery("[data-name]").each(function(i, v){
				jQuery(this).attr("name", jQuery(this).data("name") );
				jQuery(this).prop("required", jQuery(this).data("required") );
			});
		break;
		case 1:
			jQuery("[data-name]").each(function(i, v){
				jQuery(this).attr("name", "" );
				jQuery(this).prop("required", false );
			});
		break;
	}
}