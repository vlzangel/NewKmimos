var table = ""; var CTX = "";
jQuery(document).ready(function() {
    
} );

function abrir_link(e){
	init_modal({
		"titulo": e.attr("data-titulo"),
		"modulo": "saldos",
		"modal": e.attr("data-modal"),
		"info": {
			"ID": e.attr("data-id")
		}
	});
}

function getStatus(){
	if( !jQuery("#consultar").hasClass("disable") ){
		jQuery("#consultar").addClass("disable");
		jQuery("#consultar").val("Procesando...");
		jQuery.post(
			TEMA+"/admin/backend/cupones/ajax/get.php",
			{
				email: jQuery("#email").val()
			},
			function(HTML){
				jQuery("#info_user").html(HTML);

				jQuery("#info_user").css("display", "block");
				jQuery(".confirmaciones").css("display", "block");

	            jQuery("#consultar").removeClass("disable");
	            jQuery("#consultar").val("Actualizar");

	            jQuery("#info_user > div span").unbind("click").bind("click", function(e){
	            	updateStatus( jQuery(this) );
	            });
	        }
	    ); 
	}
}

function updateStatus(_this){
	if( !jQuery("#confirmar").hasClass("disable") ){
		var confirmed = confirm("Esta seguro de eliminar el uso del cupon [ "+String( _this.attr("data-txt") ).toUpperCase()+" ].?");
    	if (confirmed == true) {
			jQuery("#confirmar").addClass("disable");
			jQuery("#confirmar").val("Procesando...");
			jQuery.post(
				TEMA+"/admin/backend/cupones/ajax/update.php",
				{
					cupon_id:  _this.attr("data-id"),
					user_id:  _this.attr("data-user")
				},
				function(HTML){

		            jQuery("#confirmar").removeClass("disable");
		            jQuery("#confirmar").val("Confirmar");

					getStatus();
		        }
		    ); 
		}
	}
}

function cerrarInfo(){
	jQuery("#saldo").val("");
	jQuery("#email").val("");
	jQuery("#info_user").css("display", "none");
	jQuery(".confirmaciones").css("display", "none");
}	