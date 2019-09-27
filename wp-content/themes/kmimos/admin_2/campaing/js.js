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

function _test(id) {
    jQuery("#"+id).unbind("submit").bind("submit", function(e){
        e.preventDefault();

        var btn_txt = jQuery("#btn_submit_modal").html();
        jQuery("#btn_submit_modal").html("Procesando...");
        jQuery("#btn_submit_modal").prop("disabled", true);

        jQuery.post(
            ADMIN_AJAX+'?action=vlz_'+jQuery(this).attr("data-modulo")+"_test_send",
            jQuery(this).serialize(),
            function(data){
                // console.log( data );
                
                jQuery("#btn_submit_modal").html(btn_txt);
                jQuery("#btn_submit_modal").prop("disabled", false);

                // jQuery("#test_container").html( data.html );

                
                jQuery(".modal > div > p").addClass('sucess');
                setTimeout(function(){
	                hide_modal();
	            }, 1500);
	            
            },
            'json'
        );
    });
}

function _show_emojis(){
	jQuery(".emojis_container").css("display", "block");
}

function _copy(_this){
	var aux = document.createElement("div");
	aux.setAttribute("contentEditable", true);
	aux.innerHTML = _this.html();
	aux.setAttribute("onfocus", "document.execCommand('selectAll',false,null)"); 
	document.body.appendChild(aux);
	aux.focus();
	document.execCommand("copy");
	document.body.removeChild(aux);
	jQuery(".emojis_container").css("display", "none");
}

function _cerrar_emijins(){
	jQuery(".emojis_container").css("display", "none");
}