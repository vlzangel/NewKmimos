var table = ""; 
var JSON = "";

jQuery(document).ready(function() {
    
} );

function getStatus(){
	if( !jQuery("#consultar").hasClass("disable") ){
		jQuery("#consultar").addClass("disable");
		jQuery("#consultar").val("Procesando...");
		jQuery.post(
			TEMA+"/admin/backend/openpay/ajax/getStatus.php",
			{
				user_id: USER_ID,
				reserva: jQuery("#reserva").val(),
			},
			function(HTML){
				HTML = HTML.split("==========================");
				JSON = HTML[1].trim();
				jQuery("#info_user").html( HTML[0] );
				jQuery("#info_user").css("display", "block");
				jQuery(".confirmaciones").css("display", "block");
	            jQuery("#consultar").removeClass("disable");
	            jQuery("#consultar").val("Actualizar");
	        }
	    ); 
	}
}

function mail_openpay(){
	if( !jQuery("#correo_openpay").hasClass("disable") ){
		var confirmed = confirm("Esta seguro de enviar el correo al equipo de Openpay.?");
    	if (confirmed == true) {
			jQuery("#correo_openpay").addClass("disable");
			jQuery("#correo_openpay").val("Procesando...");
			jQuery.post(
				TEMA+"/admin/backend/openpay/ajax/mail_openpay.php",
				{
					user_id: USER_ID,
					reserva: jQuery("#reserva").val(),
					json: JSON
				},
				function(HTML){
		            jQuery("#correo_openpay").removeClass("disable");
		            jQuery("#correo_openpay").val("Enviar solicitud");
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