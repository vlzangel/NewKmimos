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
				reserva: jQuery("#reserva").val()
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
					json: JSON
				},
				function(HTML){

					console.log( HTML );

		            jQuery("#correo_openpay").removeClass("disable");
		            jQuery("#correo_openpay").val("Correo a Openpay");
		        }
		    ); 
		}
	}
}

function mail_admin(){
	if( !jQuery("#correo_admin").hasClass("disable") ){
		var confirmed = confirm("Esta seguro de enviar el correo a Alfredo.?");
    	if (confirmed == true) {
			jQuery("#correo_admin").addClass("disable");
			jQuery("#correo_admin").val("Procesando...");
			jQuery.post(
				TEMA+"/admin/backend/openpay/ajax/mail_admin.php",
				{
					ORDEN_ID: jQuery("#orden").val(),
					status: jQuery("#status").val()
				},
				function(HTML){

					console.log( HTML );

		            jQuery("#correo_admin").removeClass("disable");
		            jQuery("#correo_admin").val("Confirmar");
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