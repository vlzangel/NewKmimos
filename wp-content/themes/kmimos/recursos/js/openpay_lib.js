/* Configuración Openpay */

	OpenPay.setId( OPENPAY_TOKEN );
    OpenPay.setApiKey(OPENPAY_PK);
    OpenPay.setSandboxMode( OPENPAY_PRUEBAS == "1" );

    var deviceSessionId = OpenPay.deviceData.setup(__FORM_PAGO__, "deviceIdHiddenFieldName");

    var sucess_callbak = function(response) {
        var token_id = response.data.id;
        jQuery("#"+__FORM_PAGO__).append( jQuery("<input />").attr('type', 'hidden').attr('name', 'token').val(token_id) );
        jQuery(".errores_box").css("display", "none");
        __CB_PAGO_OK__(token_id);
    };

    var error_callbak = function(response) {
        var desc = (response.data.description != undefined) ? response.data.description : response.message;
        jQuery(".errores_box").css("display", "block");
        error = "";

        __CB_PAGO_KO__();

		var errores_txt = {
			"The expiration date has already passed": "La fecha de vencimiento ya pasó",
			"card_number is required": "N&uacute;mero de tarjeta requerido",
			"card_number length is invalid": "Longitud del N&uacute;mero de tarjeta invalido",
			"holder_name is required": "Nombre del tarjetahabiente requerido",
			"expiration_month 00 is invalid": "Mes de expiraci&oacute;n invalido",
			"valid expirations months are 01 to 12": "Mes de expiraci&oacute;n debe ser entre 01 y 12",
			"expiration_year expiration_month is required": "A&ntilde;o y Mes de expiraci&oacute;n requeridos",
			"The CVV2 security code is required": "C&oacute;digo de seguridad requerido",
			"cvv2 length must be 3 digits": "El c&oacute;digo de seguridad debe ser de 3 digitos",
			"cvv2 length must be 4 digits": "El c&oacute;digo de seguridad debe ser de 4 digitos",
		};

		debug( response );

        switch( response.status ){
        	case 422:
        		error += "<div> Numero de tarjeta invalido </div>";
        	break;
        	case 400:
        		descripciones = desc.split(", ");
        		jQuery.each(descripciones, function( index, item ) {
        			if( errores_txt[item] != undefined ){
        				error += "<div> "+errores_txt[item]+" </div>";
        			}
				});
        	break;
        	default:
        		error += "Error al procesar su solicitud ("+response.status+")";
        	break;
        }

        jQuery(".errores_box").html(error);
		jQuery(".errores_box").css("display", "block");
    };

/* Fin Configuración Openpay */