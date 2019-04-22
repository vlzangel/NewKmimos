var CARRITO = [];

function initCarrito(){
	CARRITO = [];

	CARRITO["cantidades"] = [];

		CARRITO["cantidades"] = {
			"cantidad": 0
		};

	CARRITO["pagar"] = [];

		CARRITO["pagar"] = {
			"total" : "0",
			"tipo" : "",
			"token" : "",
			"deviceIdHiddenFieldName" : "",
			"id_fallida" : 0,
		};

	CARRITO["tarjeta"] = [];

		CARRITO["tarjeta"] = {
			"nombre" : "",
			"numero" : "",
			"mes" : "",
			"anio" : "",
			"codigo" : "",
			"punto" : false,
		};
}

function validar(status, txt){
	if( status ){
		jQuery(".valido").css("display", "block");
		jQuery(".invalido").css("display", "none");
	}else{
		jQuery(".invalido").html(txt);

		jQuery(".valido").css("display", "none");
		jQuery(".invalido").css("display", "block");
	}
}

function calcular(){

	var error = "";
	
	CARRITO["cantidades"]["cantidad"] = 1;
	CARRITO["cantidades"]["cupo_1"] = [3, 10];

	var cant = 0, duracion = 0;
	jQuery.each( CARRITO[ "cantidades" ], function( key, valor ) {
		if( key != "cantidad" && valor[0] != undefined && valor[1] > 0 ){
			cant += ( parseFloat( valor[0] ) * parseFloat( valor[1] ) );
		}
	});	
	
	if( error != "" ){
		jQuery(".invalido").html(error);
		jQuery(".valido").css("display", "none");
		jQuery(".invalido").css("display", "block");
	}else{
		jQuery(".valido").css("display", "block");
		jQuery(".invalido").css("display", "none");
		jQuery(".km-price-total").html("$"+numberFormat(cant));
	}
	
	if( error == "" ){
		jQuery(".pago_17").html( "$" + numberFormat(cant*0.2) );
		jQuery(".pago_cuidador").html( "$" +  numberFormat(cant-(cant*0.2)) );
		jQuery(".monto_total").html( "$" + numberFormat(cant) );

		jQuery(".km-price-total2").html( "$" + numberFormat(cant) );

		CARRITO["pagar"]["total"] = cant;
		jQuery("#reserva_btn_next_1").removeClass("km-end-btn-form-disabled");
		jQuery("#reserva_btn_next_1").removeClass("disabled");
	}else{
		jQuery("#reserva_btn_next_1").addClass("km-end-btn-form-disabled");
		jQuery("#reserva_btn_next_1").addClass("disabled");
	}
	initFactura();
    
}

function numberFormat(numero){
	return parseFloat(numero).toFixed(2);
}

function initFactura(){

	CARRITO["pagar"]["cliente"] = cliente;
	CARRITO["pagar"]["email"] = email;

	var items = "";

	var tamanos = {
		"cupo_1" : "créditos",
	};

	var subtotal = 0;
	jQuery.each(tamanos, function( key, tamano ) {
		if( CARRITO["cantidades"][key][0] != undefined && CARRITO["cantidades"][key][0] > 0 && CARRITO["cantidades"][key][1] > 0 ){
			subtotal = 	parseInt( CARRITO["cantidades"][key][0] ) *
						parseFloat( CARRITO["cantidades"][key][1] );
			items += '<div class="km-option-resume-service">'
			items += '	<span class="label-resume-service">'+CARRITO["cantidades"][key][0]+' '+tamano+' x $'+CARRITO["cantidades"][key][1]+' c/u </span>'
			items += '	<span class="value-resume-service">$'+numberFormat(subtotal)+'</span>'
			items += '</div>';
		}
	});

	jQuery(".items_reservados").html( items );
}

function convertCARRITO(){
	var transporte = "==="; 
	if( CARRITO["transportacion"] != undefined && CARRITO["transportacion"][1] > 0 ){
		transporte = JSON.stringify( CARRITO["transportacion"] )+"===";
	}

	var json =  
		JSON.stringify( CARRITO["pagar"] )+"==="+
		JSON.stringify( CARRITO["tarjeta"] )+"==="+
		JSON.stringify( CARRITO["cantidades"] )
	;
	return json;
}

function pagarReserva(id_invalido = false){

	jQuery("#reserva_btn_next_3 span").html("Procesando");
	jQuery("#reserva_btn_next_3").addClass("disabled");
	jQuery("#reserva_btn_next_3").addClass("cargando");

	var json = convertCARRITO();

	jQuery.post(
		HOME+"/procesos/conocer/pagar.php",
		{
			info: json,
			id_invalido: id_invalido
		},
		function(data){
			console.log( data );

			if( data.error != "" && data.error != undefined ){
				if( data.tipo_error != "3003" ){
					var error = "Error procesando la reserva<br>";
			    	error += "Por favor intente nuevamente.<br>";
			    	error += "Si el error persiste por favor comuniquese con el soporte Kmimos.<br>";
				}else{
					var error = "Error procesando la reserva<br>";
			    	error += "La tarjeta no tiene fondos suficientes.<br>";
				}
		    	jQuery(".errores_box").html(error);
				jQuery(".errores_box").css("display", "block");
				jQuery("#reserva_btn_next_3 span").html("TERMINAR RESERVA");
				jQuery("#reserva_btn_next_3").removeClass("disabled");
				jQuery("#reserva_btn_next_3").removeClass("cargando");
				CARRITO["pagar"]["id_fallida"] = data.error;
			}else{
				CARRITO["pagar"]["id_fallida"] = 0; 
				location.href = RAIZ+"/petsitters/"+cuidador+"/1/";
			}

		}, "json"
	).fail(function(e) {

    	console.log( e );

    	var error = "Error procesando la reserva<br>";
    	error += "Por favor intente nuevamente.<br>";
    	error += "Si el error persiste por favor comuniquese con el soporte Kmimos.<br>";

    	jQuery(".errores_box").html(error);
		jQuery(".errores_box").css("display", "block");

		jQuery("#reserva_btn_next_3 span").html("TERMINAR RESERVA");
		jQuery("#reserva_btn_next_3").removeClass("disabled");
		jQuery("#reserva_btn_next_3").removeClass("cargando");

  	});
}

function eliminarCuponesHandler(){
	jQuery(".cupones_desglose a").on("click", function(e){
		e.preventDefault();
		var tempCupones = [];
		var id = jQuery(this).attr("data-id");
		jQuery.each(CARRITO["cupones"], function( key, cupon ) {
			if( cupon[0] != id ){
				tempCupones.push(cupon);
			}
		});
		CARRITO["cupones"] = tempCupones;
		mostrarCupones();
		eliminarCuponesHandler();
		calcularDescuento();
	});
}

function mostrarCupones(){
	var items = "";
	jQuery.each(CARRITO["cupones"], function( key, cupon ) {
		var nombreCupon = cupon[0];

		if( nombreCupon != ""  ){ /* && cupon[1] > 0 */
			var eliminarCupo = '<a href="#" data-id="'+cupon[0]+'">Eliminar</a>';
			var es_saldo = false;
			if( nombreCupon.indexOf("saldo") > -1 ){
				nombreCupon = "Saldo a favor";
				eliminarCupo = "";
				es_saldo = true;
			}
			if( es_saldo ){
				if(cupon[1] > 0){
					items += '<div class="km-option-resume-service">';
					items += '	<span class="label-resume-service">'+nombreCupon+'</span>';
					items += '	<span class="value-resume-service">$'+numberFormat(cupon[1])+'</span>';
				}
			}else{
				items += '<div class="km-option-resume-service">';
				if( PAQs.indexOf(nombreCupon) != -1 ){

					items += '	<span class="label-resume-service"> Descuento '+(PAQUETE*5)+'%</span>';
					items += '	<span class="value-resume-service">$'+numberFormat(cupon[1])+'</span>';

				}else{
					items += '	<span class="label-resume-service">'+nombreCupon+'</span>';
					if(cupon[1] > 0){
						items += '	<span class="value-resume-service">$'+numberFormat(cupon[1])+' '+eliminarCupo+' </span>';
					}else{
						items += '	<span class="value-resume-service">'+eliminarCupo+' </span>';
						items += '	<div class="mensaje_cupon">'+MENSAJES_CUPONES[nombreCupon]+' </div>';
					}
				}
				items += '</div>';
			}

		}

	});
	if( items != "" ){
		jQuery(".cupones_desglose div").html(items);
		jQuery(".cupones_desglose").css("display", "block");
	}else{
		jQuery(".cupones_desglose").css("display", "none");
	}
	items = "";
}

function getCantidad(){
	var resultado = 0;
	jQuery(".km-content-new-pet .tamano").each(function( index ) {
	  	resultado += parseInt(jQuery( this ).val());
	});
	return resultado;
}

var descripciones = "";
jQuery(document).ready(function() { 
	jQuery("#reservar").trigger("reset");
	jQuery('nav').addClass("nav_busqueda");
	initCarrito(); 

	jQuery(".dias_container input").on("change", function(e){
		calcular();
	});

	jQuery(".km-option-total").click();

	jQuery(".solo_numeros").on("keyup", function(e){
		var valor = jQuery( this ).val();
		if( valor != "" ){
			var resul = ""; var no_permitido = false;
			jQuery.each(valor.split(""), function( index, value ) {
			  	if( /^[0-9]*$/g.test(value) ){
					resul += value;
				}else{
					no_permitido = true;
				}
			});
			if( no_permitido ){
				jQuery( this ).val(resul);
			}
		}
	});

	jQuery(".solo_letras").on("keyup", function(e){
		var valor = jQuery( this ).val();
		if( valor != "" ){
			var resul = ""; var no_permitido = false;
			jQuery.each(valor.split(""), function( index, value ) {
			  	if( /^[a-zA-Z ]*$/g.test(value) ){
					resul += value;
				}else{
					no_permitido = true;
				}
			});
			if( no_permitido ){
				jQuery( this ).val(resul);
			}
		}
	});

	jQuery(".next").on("keyup", function(e){
		if( jQuery(this).val().length >= jQuery(this).attr("data-max") ){
			if( jQuery(this).attr("data-next") != "null" ){
				jQuery("#"+jQuery(this).attr("data-next")).focus();
			}else{
				jQuery(this).blur();
			}
		}
	});

	jQuery("#numero").on("keypress", function(e){
		var txt = jQuery(this).val();
		if( txt.length == 19 ){
			e.preventDefault();
			return false;
		}
	});

	jQuery("#numero").on("focus", function(e){
		var txt = jQuery(this).val();
		txt = txt.replaceAll(" ", "");
		jQuery(this).val(txt);
		jQuery("#numero_oculto").val(txt);
	});

	jQuery("#numero").on("blur", function(e){
		var txt = jQuery(this).val();
		txt = txt.replaceAll(" ", "");
		jQuery(this).val(txt);
		jQuery("#numero_oculto").val(txt);
	});

	jQuery(".maxlength").on("blur", function(e){
		var txt = jQuery(this).val();
		txt = txt.replaceAll(" ", "");
		var temp = ""; var l = txt.length; var length = jQuery(this).attr("data-max");
		if( l > length ){ l = length; }
		for(var i=0; i<l; i++){
			if( i > 0 && i%4 == 0){
				temp += " ";
			}
			temp += txt[i];
		}
		jQuery(this).val(temp);
	});

    jQuery("#numero").bind({
        paste : function(){
           	var txt = jQuery(this).val();
			txt = txt.replaceAll(" ", "");
			jQuery("#numero_oculto").val(txt);
			var temp = ""; var l = txt.length;
			if( l > 19 ){ l = 19; }
			for(var i=0; i<l; i++){
				if( i > 0 && i%4 == 0){
					temp += " ";
				}
				temp += txt[i];
			}
			jQuery(this).val(temp);
        }
    });

	jQuery('.navbar-brand img').attr('src', HOME+'images/new/km-logos/km-logo-negro.png');

	
	jQuery(document).on("click", '.page-reservation .km-quantity .km-minus', function ( e ) {
		e.preventDefault();
		var el = jQuery(this);
		var div = el.parent();
		var span = jQuery(".km-number", div);
		var input = jQuery("input", div);
		if ( span.html() > 0 ) {
			var valor = parseInt(span.html()) - 1;
			span.html( valor );
			input.val( valor );
		}
		if ( span.html() <= 0 ) {
			el.addClass("disabled");
		}
		calcular();
	});

	jQuery(document).on("click", '.page-reservation .km-quantity .km-plus', function ( e ) {
		e.preventDefault();

		if( !jQuery(this).hasClass("disabled") ){
			var el = jQuery(this);
			var div = el.parent();
			var span = jQuery(".km-number", div);
			var minus = jQuery(".km-minus", div);
			var input = jQuery("input", div);
			
			var valor = parseInt(span.html()) + 1;

			var cantidad = parseInt(getCantidad())+1;
			if(cantidad <= acepta){
				span.html( valor );
				input.val( valor );

				if ( span.html() > 0 ) {
					minus.removeClass("disabled");
				}

				calcular();
			}
		}
	});

	jQuery(document).on("change", '.page-reservation .km-height-select', function ( e ) {
		e.preventDefault();
		var el = jQuery(this);
		el.removeClass("small");
		el.removeClass("medium");
		el.removeClass("large");
		el.removeClass("extra-large");

		el.addClass( el.val() );
	});

	jQuery(document).on("click", '.page-reservation .optionCheckout', function ( e ) {
		e.preventDefault();
		var el = jQuery(this);
		var div = el.parent();
		var input = jQuery("input", div);
		el.toggleClass("active");
		input.toggleClass("active");
		if(typeof calcular === 'function') {
			calcular();
		}
	});

	jQuery("#click_pago_total").on("click", function(e){
		jQuery("#reserva_btn_next_2").click();
	}); 

	jQuery(document).on("click", '.page-reservation .list-dropdown .km-tab-link', function ( e ) {
		e.preventDefault();
		var el = jQuery(this);
		jQuery(".km-tab-content", el.parent()).slideToggle("fast");
	});

	jQuery(".navbar").removeClass("bg-transparent");
	jQuery(".navbar").addClass("bg-white-secondary");
	jQuery('.navbar-brand img').attr('src', HOME+'/images/new/km-logos/km-logo-negro.png');

	jQuery("#transporte").on("change", function(e){
		calcular();
	});

	jQuery("#reserva_btn_next_1").on("click", function(e){
		if( jQuery(this).hasClass("disabled") ){

		}else{
			jQuery(".km-col-steps").css("display", "none");
			jQuery("#step_2").css("display", "block");
			jQuery(document).scrollTop(0);
			
			if( CARRITO["cantidades"]["cantidad"] == 0 ){
				initFactura();
			}

		}
		e.preventDefault();
	});

	jQuery("#reserva_btn_next_2").on("click", function(e){
		jQuery(".km-col-steps").css("display", "none");
		jQuery("#step_3").css("display", "block");
		jQuery(document).scrollTop(0);

		e.preventDefault();
	});

	jQuery("#reserva_btn_next_3").on("click", function(e){
		if( jQuery(this).hasClass("disabled") && !jQuery(this).hasClass("cargando") ){
			alert("Debes aceptar los terminos y condiciones");
		}else{
			if( jQuery(this).hasClass("cargando") ){
				alert("Proceso de pago en curso, por favor espere...");
			}else{
				if( jQuery("#metodos_pagos").css("display") != "none" ){
					CARRITO["pagar"]["deviceIdHiddenFieldName"] = jQuery("#deviceIdHiddenFieldName").val();
					CARRITO["pagar"]["tipo"] = jQuery("#tipo_pago").val();
					if( CARRITO["pagar"]["tipo"] == "tarjeta" ){
						jQuery("#reserva_btn_next_3 span").html("Validando...");
						jQuery("#reserva_btn_next_3").addClass("disabled");
						OpenPay.token.extractFormAndCreate('reservar', sucess_callbak, error_callbak); 
					}else if( CARRITO["pagar"]["tipo"] == "paypal" ){
						jQuery("#reserva_btn_next_3").addClass("disabled");
						jQuery("#reserva_btn_next_3").addClass("cargando");
						var info = convertCARRITO();
						jQuery.post(HOME+"/procesos/conocer/pasarelas/paypal/create.php",
							{
								'info':info,
								'ruta':RAIZ,
							},
							function(data){
								if( data.status == 'CREATED' ){
									jQuery.each(data.links, function(i,r){								
										if(r.rel == 'approve'){
											location.href = r.href;
											return false;
										}
									});
								}
							}, 'json'
						);
					}else if( CARRITO["pagar"]["tipo"] == "mercadopago" ){
						jQuery("#reserva_btn_next_3").addClass("disabled");
						jQuery("#reserva_btn_next_3").addClass("cargando");
						// pagarReserva();
						var info = convertCARRITO();
						jQuery.post(HOME+"/procesos/conocer/pasarelas/mercadopago/create.php",
							{
								'info': info,
								'ruta': RAIZ,
							},
							function(data){
								if( data.status == 'CREATED' ){
									location.href = data.links;
								}
							}, 'json'
						);
					}else{
						pagarReserva();
					}
				}else{
					CARRITO["pagar"]["tipo"] = "Saldo y/o Descuentos";
					pagarReserva();
				}
		 	}
		}
		e.preventDefault();
	});

	jQuery("#atras_1").on("click", function(e){
		jQuery(".km-col-steps").css("display", "none");
		jQuery("#step_1").css("display", "block");
	});

	jQuery("#atras_2").on("click", function(e){
		jQuery(".km-col-steps").css("display", "none");
		jQuery("#step_2").css("display", "block");
	});

	jQuery("#step_3 input").on("keyup", function(e){
		if( e.key != "Backspace" ){
			if(jQuery(this).attr("id") == "expira"){
				var expira = jQuery(this).val();
				if( expira.length == 2 ){
					jQuery(this).val( expira+"/" );
				}
			}
		}
		var txtTemp = jQuery(this).val();
		if( jQuery(this).attr("id") == "numero" ){
			txtTemp = txtTemp.replaceAll(" ", "");
		}
		CARRITO["tarjeta"][ jQuery(this).attr("id") ] = txtTemp;
	});

	jQuery("#tipo_pago").on("change", function(e){
		jQuery(".metodos_container").css("display", "none");
		jQuery("#"+jQuery(this).val()+"_box").css("display", "block");
        switch( jQuery(this).val() ){
            case 'tarjeta':
                CARRITO["pagar"]["tipo"] = "tarjeta";
                break;
            case 'mercadopago':
                jQuery(".errores_box").css("display", "none");
                CARRITO["pagar"]["tipo"] = "mercadopago";
                break;
            case 'paypal':
                jQuery(".errores_box").css("display", "none");
                CARRITO["pagar"]["tipo"] = "paypal";
                break;
            default:
                jQuery(".errores_box").css("display", "none");
                CARRITO["pagar"]["tipo"] = jQuery(this).val();
                break;
		}
		/*
		if( jQuery(this).val() != "tarjeta" ){
			jQuery(".errores_box").css("display", "none");
			CARRITO["pagar"]["tipo"] = "tienda";
		}else{
			CARRITO["pagar"]["tipo"] = "tarjeta";
		}
		*/
	});

	jQuery('#term-conditions').on("change", function ( e ) {
		e.preventDefault();

		if( !jQuery(this).hasClass("active") ){
			jQuery(this).addClass("active");
			jQuery("#reserva_btn_next_3").removeClass("disabled");
			jQuery(this).attr("disabled", true);
		}else{
			jQuery(this).removeClass("active");
			jQuery("#reserva_btn_next_3").addClass("disabled");
		}
	});

	calcular();

	// jQuery(document).on("click", '.page-reservation .km-medio-paid-options .km-method-paid-option', function ( e ) {
    jQuery(document).on("click", '#pasarela-container .km-method-paid-option', function ( e ) {
		e.preventDefault();
		var el = jQuery(this);
		//jQuery(".km-method-paid-option", el.parent()).removeClass("active");
        jQuery("#pasarela-container .active").removeClass("active");

		el.addClass("active");

		if ( el.hasClass("km-tarjeta") ) {
			jQuery("#tipo_pago").val("tarjeta");
			jQuery("#tipo_pago").change();
			if( wlabel == 'petco' ){
				evento_google("boton_nueva_reserva_tarjeta");
				evento_fbq("track", "traking_code_boton_nueva_reserva_tarjeta");
				console.log('traking_code_boton_nueva_reserva_tarjeta');
			}
		}

		if ( el.hasClass("km-tienda") ) {
			jQuery("#tipo_pago").val("tienda");
			jQuery("#tipo_pago").change();
			if( wlabel == 'petco' ){
				evento_google("boton_nueva_reserva_tienda");
				evento_fbq("track", "traking_code_boton_nueva_reserva_tienda");
				console.log('traking_code_boton_nueva_reserva_tienda');
			}
		} 
		
        if ( el.hasClass("km-paypal") ) {
            jQuery("#tipo_pago").val("paypal");
            jQuery("#tipo_pago").change();
            if( wlabel == 'petco' ){
                evento_google("boton_nueva_reserva_paypal");
                evento_fbq("track", "traking_code_boton_nueva_reserva_paypal");
                console.log('traking_code_boton_nueva_reserva_paypal');
            }
        } 

        if ( el.hasClass("km-mercadopago") ) {
            jQuery("#tipo_pago").val("mercadopago");
            jQuery("#tipo_pago").change();
            if( wlabel == 'petco' ){
                evento_google("boton_nueva_reserva_mercadopago");
                evento_fbq("track", "traking_code_boton_nueva_reserva_mercadopago");
                console.log('traking_code_boton_nueva_reserva_mercadopago');
            }
        } 


		if(typeof calcularDescuento === 'function') {
			calcularDescuento();
		}
	});

	jQuery("#points-yes-button").on('click', function(){
		CARRITO["tarjeta"]['puntos'] = true;
    	pagarReserva();
	});

	jQuery("#points-no-button").on('click', function(){
		CARRITO["tarjeta"]['puntos'] = false;
    	pagarReserva();
	});

	jQuery("#reserva_btn_next_1").click();

	/* Configuración Openpay */

		OpenPay.setId( OPENPAY_TOKEN );
	    OpenPay.setApiKey(OPENPAY_PK);
	    OpenPay.setSandboxMode( OPENPAY_PRUEBAS == 1 );

	    var deviceSessionId = OpenPay.deviceData.setup("reservar", "deviceIdHiddenFieldName");

	    var sucess_callbak = function(response) {
	    	var token_id = response.data.id;
	        CARRITO["pagar"]["token"] = token_id;
	        jQuery(".errores_box").css("display", "none");
	        if (response.data.card.points_card) {
				jQuery("#card-points-dialog").modal("show");
			} else {
	        	pagarReserva();
			}
	        // var token_id = response.data.id;
	        // CARRITO["pagar"]["token"] = token_id;
	        // jQuery(".errores_box").css("display", "none");
	        // pagarReserva();
	    };

	    var error_callbak = function(response) {
	        var desc = (response.data.description != undefined) ? response.data.description : response.message;
	        jQuery(".errores_box").css("display", "block");
	        error = "";

			jQuery("#reserva_btn_next_3 span").html("TERMINAR RESERVA");
			jQuery("#reserva_btn_next_3").removeClass("disabled");
			jQuery("#reserva_btn_next_3").removeClass("cargando");

			var errores_txt = {
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

			console.log( response );

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

});