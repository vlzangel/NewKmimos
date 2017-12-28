jQuery( document ).ready(function() {

	jQuery(".ver_fotos_container > div").on("click", function(e){
		jQuery(".vlz_modal_container img").attr("src", jQuery(this).attr("data-value") );
		jQuery(".vlz_modal_container").css("display", "table");
	});

	jQuery(".vlz_modal_container i").on("click", function(e){
		jQuery(".vlz_modal_container").css("display", "none");
	});

});