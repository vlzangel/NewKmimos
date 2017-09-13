var CARRITO = [];
function initCarrito(){
	CARRITO = [];

	CARRITO["fechas"] = [];

		CARRITO["fechas"] = {
			"inicio" : "",
			"fin" : "",
			"duracion" : ""
		};

	CARRITO["cantidades"] = [];

		CARRITO["cantidades"] = {
			"cantidad" : 0,
			"pequenos" : [],
			"medianos" : [],
			"grandes" : [],
			"gigantes" : []
		};

	CARRITO["adicionales"] = [];

		CARRITO["adicionales"] = {
			"bano" : 0,
			"corte" : 0,
			"acupuntura" : 0,
			"limpieza_dental" : 0,
			"visita_al_veterinario" : 0
		};

	CARRITO["pagar"] = [];

		CARRITO["pagar"] = {
			"total" : "",
			"tipo" : "",
			"metodo" : "completo",
			"token" : "",
			"deviceIdHiddenFieldName" : ""
		};

	CARRITO["tarjeta"] = [];

		CARRITO["tarjeta"] = {
			"nombre" : "",
			"numero" : "",
			"mes" : "",
			"anio" : "",
			"codigo" : ""
		};
    
}
initCarrito();

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
	initCarrito();

	jQuery("#reservar .tamano").each(function( index ) {
		CARRITO["cantidades"]["cantidad"] += parseInt(jQuery( this ).val());
		CARRITO[ "cantidades" ][ jQuery( this ).attr("name") ] = [
			parseFloat( jQuery( this ).val() ),
			parseFloat( jQuery( this ).attr("data-valor") )
		];
	});

	var tranporte = jQuery('#transporte option:selected').val();
	if( tranporte != undefined && tranporte != "" ){
		var text_temp = String(jQuery('#transporte option:selected').text() ).split("(");
		CARRITO[ "transportacion" ] = [
			"Transportaci&oacute;n - "+text_temp[0],
			parseFloat(tranporte)
		];
	}

	jQuery("#adicionales input").each(function( index ) {
		var activo = jQuery( this ).attr('class');
        if(activo == "active"){
        	CARRITO[ "adicionales" ][ jQuery( this ).attr("name") ] = parseFloat( jQuery( this ).val() );
        }
	});

	if( jQuery('#checkin').val() != "" ){
		var ini = String( jQuery('#checkin').val() ).split("/");
		CARRITO[ "fechas" ][ "inicio" ] = new Date( ini[2]+"-"+ini[1]+"-"+ini[0] );

		jQuery("#fecha_ini").html( jQuery('#checkin').val() );
	}

	if( jQuery('#checkout').val() != "" ){
		var fin = String( jQuery('#checkout').val() ).split("/");
		CARRITO[ "fechas" ][ "fin" ] = new Date( fin[2]+"-"+fin[1]+"-"+fin[0] );

		jQuery("#fecha_fin").html( jQuery('#checkout').val() );
	}

	var error = "";

	var cupos = verificarCupos();

	if( cupos.excede.length > 0 ){
		error += "Hay cupos insuficientes en las siguientes fechas:<br><ul>";
		jQuery.each(cupos.excede, function( index, item ) {
			error += "<li>"+item[0]+", cupos disponibles: "+item[1]+"</li>";
		});
		error += "</ul>";
	}

	if( cupos.full.length > 0 ){
		error += "Las siguientes fechas no tienen cupos disponibles:<br><ul>";
		jQuery.each(cupos.full, function( index, item ) {
			error += "<li>"+item+"</li>";
		});
		error += "</ul>";
	}

	if( cupos.no_disponible.length > 0 ){
		error += "Las siguientes fechas estan bloqueadas por el cuidador:<br><ul>";
		jQuery.each(cupos.no_disponible, function( index, item ) {
			error += "<li>"+item+"</li>";
		});
		error += "</ul>";
	}

	var dias = 0;
	if( CARRITO[ "fechas" ][ "inicio" ] == undefined || CARRITO[ "fechas" ][ "inicio" ] == "" ){
		error = "Ingrese la fecha de inicio";
	}else{
		if( CARRITO[ "fechas" ][ "fin" ] == undefined || CARRITO[ "fechas" ][ "fin" ] == "" ){
			error = "Ingrese la fecha de finalizaci&oacute;n";
		}else{
			var fechaInicio = CARRITO[ "fechas" ][ "inicio" ].getTime();
			var fechaFin    = CARRITO[ "fechas" ][ "fin" ].getTime();
			var diff = fechaFin - fechaInicio;
			dias = parseInt( diff/(1000*60*60*24) );
	    }

		if( tipo_servicio != "hospedaje" ){
			if( dias == 0 ){
				dias=1;
			}else{
				dias += 1;
			}
		}else{
			if( dias == 0 ){
				error = "Fecha de finalizaci&oacute;n debe ser diferente a la de inicio";
			}
		}

        CARRITO[ "fechas" ][ "duracion" ] = dias;
	}

	var cant = 0, duracion = 0;
	jQuery.each( CARRITO[ "cantidades" ], function( key, valor ) {
		if( key != "cantidad" && valor[0]  != undefined && valor[1] > 0 ){
			cant += ( parseFloat( valor[0] ) * parseFloat( valor[1] ) );
		}
	});	

	if( error == "" ){
		if( cant == 0 ){
			error = "Ingrese la cantidad de mascotas";
		}else{
			cant *= parseFloat( dias );
			jQuery(".km-price-total").html("$"+numberFormat(cant));
		}
	}
	
	jQuery.each( CARRITO[ "adicionales" ], function( key, valor ) {
		if( valor > 0 ){
			cant += valor;
		}
	});	

	if( CARRITO[ "transportacion" ] != undefined ){
		cant = parseFloat( cant ) + parseFloat( CARRITO[ "transportacion" ][1] );
	}
	
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
		jQuery("#pago_17").html( "$" + numberFormat(cant-(cant/1.2)) );
		jQuery("#pago_cuidador").html( "$" + numberFormat(cant/1.2) );
		jQuery("#monto_total").html( "$" + numberFormat(cant) );

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

function verificarCupos(){
	var validacion = {
		"no_disponible": [],
		"full": [],
		"excede": [],
	};
	if( 
		CARRITO[ "fechas" ][ "inicio" ] != "" &&
		CARRITO[ "fechas" ][ "fin" ] != "" 
	){
		var ini = CARRITO[ "fechas" ][ "inicio" ].getTime();
		var fin = CARRITO[ "fechas" ][ "fin" ].getTime();
		var act = new Date();
		var tem = "";
		if( 
			ini != undefined && ini != "" &&
			fin != undefined && fin != ""
		){
			jQuery.each(cupos, function( index, item ) {
				tem = String( item.fecha ).split("-");
				act = new Date( tem[0]+"-"+tem[1]+"-"+tem[2] ).getTime();
				if( (ini <= act) && (act <= fin) ){
					if( item.full == 1 || item.no_disponible == 1 ){
						if( item.full == 1 ){
							validacion["full"].push( tem[2]+"/"+tem[1]+"/"+tem[0] );
						}
						if( item.no_disponible == 1 ){
							validacion["no_disponible"].push( tem[2]+"/"+tem[1]+"/"+tem[0] );
						}
					}else{
						var sub_total = parseInt(item.cupos) + parseInt(CARRITO["cantidades"]["cantidad"]);
						if( sub_total > item.acepta ){
							var cupos_disponibles = ( parseInt(item.acepta) - parseInt(item.cupos) );
							if( cupos_disponibles < 0 ){
								cupos_disponibles = 0;
							}
							validacion["excede"].push( [
								tem[2]+"/"+tem[1]+"/"+tem[0],
								cupos_disponibles
							] );
						}
					}
				}
			});
		}
	}
	return validacion;
}

function initFactura(){

	CARRITO["pagar"]["servicio"] = SERVICIO_ID;
	CARRITO["pagar"]["tipo_servicio"] = tipo_servicio;
	CARRITO["pagar"]["name_servicio"] = name_servicio;
	CARRITO["pagar"]["cliente"] = cliente;
	CARRITO["pagar"]["cuidador"] = cuidador;
	CARRITO["pagar"]["email"] = email;

	diaNoche = "d&iacute;a";
	if( tipo_servicio == "hospedaje" ){
		diaNoche = "Noche";
	}

	if( CARRITO["fechas"]["duracion"] > 1 ){
		diaNoche += "s";
	}

	var tamanos = {
		"pequenos" : "Peque&ntilde;a",
		"medianos" : "Mediana",
		"grandes" :  "Grande",
		"gigantes" : "Gigante"
	};

	var items = "";
	var subtotal = 0;
	jQuery.each(tamanos, function( key, tamano ) {
		
		if( CARRITO["cantidades"][key][0] != undefined && CARRITO["cantidades"][key][0] > 0 && CARRITO["cantidades"][key][1] > 0 ){
			
			var plural = "";
			if( CARRITO["cantidades"][key][0] > 1 ){
				plural += "s";
			}

			subtotal = 	parseInt( CARRITO["cantidades"][key][0] ) *
						parseInt( CARRITO["fechas"]["duracion"] ) *
						parseFloat( CARRITO["cantidades"][key][1] );

			items += '<div class="km-option-resume-service">'
			items += '	<span class="label-resume-service">'+CARRITO["cantidades"][key][0]+' Mascota'+plural+' '+tamano+plural+' x '+CARRITO["fechas"]["duracion"]+' '+diaNoche+' x $'+CARRITO["cantidades"][key][1]+' </span>'
			items += '	<span class="value-resume-service">$'+numberFormat(subtotal)+'</span>'
			items += '</div>';
		}

	});

	var adicionales = {
		"bano": "Ba&ntilde;o",
		"corte": "Corte de pelo y u&ntilde;as",
		"acupuntura": "Acupuntura",
		"limpieza_dental": "Limpieza Dental",
		"visita_al_veterinario": "Visita al veterinario"
	};

	jQuery.each(adicionales, function( key, adicional ) {
		
		if( CARRITO["adicionales"][key] != undefined && CARRITO["adicionales"][key] != "" && CARRITO["adicionales"][key] > 0 ){
			
			//console.log(CARRITO["cantidades"][key]);
			
			var plural = "";
			if( CARRITO["cantidades"]["cantidad"] > 1 ){
				plural += "s";
			}

			subtotal = 	parseInt( CARRITO["cantidades"]["cantidad"] ) *
						parseFloat( CARRITO["adicionales"][key] );

			items += '<div class="km-option-resume-service">'
			items += '	<span class="label-resume-service">'+adicional+' - '+CARRITO["cantidades"]["cantidad"]+' Mascota'+plural+' x $'+CARRITO["adicionales"][key]+'</span>'
			items += '	<span class="value-resume-service">$'+numberFormat(subtotal)+'</span>'
			items += '</div>';
		}

	});

	if( CARRITO["transportacion"] != undefined && CARRITO["transportacion"][1] > 0 ){
		items += '<div class="km-option-resume-service">'
		items += '	<span class="label-resume-service">'+CARRITO["transportacion"][0]+' - Precio por Grupo </span>'
		items += '	<span class="value-resume-service">$'+numberFormat(CARRITO["transportacion"][1])+'</span>'
		items += '</div>';
	}

	jQuery(".items_reservados").html( items );
}


function pagarReserva(id_invalido = false){

	jQuery("#reserva_btn_next_3").html("Procesando");
	jQuery("#reserva_btn_next_3").addClass("disabled");

	var transporte = []+"===";
	if( CARRITO["transportacion"] != undefined && CARRITO["transportacion"][1] > 0 ){
		transporte = JSON.stringify( CARRITO["transportacion"] )+"===";
	}

	var json =  
		JSON.stringify( CARRITO["pagar"] )+"==="+
		JSON.stringify( CARRITO["tarjeta"] )+"==="+
		JSON.stringify( CARRITO["fechas"] )+"==="+
		JSON.stringify( CARRITO["cantidades"] )+"==="+transporte+
		JSON.stringify( CARRITO["adicionales"] );

	jQuery.post(
		HOME+"/procesos/reservar/pagar.php",
		{
			info: json,
			id_invalido: id_invalido
		},
		function(data){
			console.log( data );
			location.href = RAIZ+"/finalizar/"+data.order_id;
		}, "json"
	).fail(function(e) {
    	console.log( "Error" );
    	console.log( e );
    	pagarReserva(true);
  	});
}

jQuery(document).ready(function() { 
	// activar_continuar(); 

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
		if( jQuery(this).hasClass("disabled") ){

		}else{
			console.log("Entro");
			CARRITO["pagar"]["deviceIdHiddenFieldName"] = jQuery("#deviceIdHiddenFieldName").val();
			CARRITO["pagar"]["tipo"] = jQuery("#tipo_pago").val();
			if( CARRITO["pagar"]["tipo"] == "tarjeta" ){
				OpenPay.token.extractFormAndCreate('reservar', sucess_callbak, error_callbak); 
			}else{
				pagarReserva();
			}
		}
		e.preventDefault();
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
		CARRITO["tarjeta"][ jQuery(this).attr("id") ] = jQuery(this).val();
	});

	jQuery("#tipo_pago").on("change", function(e){
		jQuery(".metodos_container").css("display", "none");
		jQuery("#"+jQuery(this).val()+"_box").css("display", "block");
		if( jQuery(this).val() != "tarjeta" ){
			jQuery(".errores_box").css("display", "none");
		}
	});

	calcular();

	/* Configuración Openpay */

		OpenPay.setId('mae56tbxscnuqozgio7b');
	    OpenPay.setApiKey('pk_ade086de44594d1187aeab6e824b2ffd');
	    OpenPay.setSandboxMode(true);

	    var deviceSessionId = OpenPay.deviceData.setup("reservar", "deviceIdHiddenFieldName");

	    var sucess_callbak = function(response) {
	        var token_id = response.data.id;
	        CARRITO["pagar"]["token"] = token_id;
	        jQuery(".errores_box").css("display", "none");
	        pagarReserva();
	    };

	    var error_callbak = function(response) {
	        var desc = response.data.description != undefined ? response.data.description : response.message;
	        jQuery(".errores_box").css("display", "block");
	        switch( response.status ){
	        	case 422:
	        		alert("Numero invalido");
	        	break;
	        	case 400:
	        		switch( desc ){
	        			case "cvv2 length must be 3 digits":
	        				alert("Codigo invalido, debe ser de 3 digitos");
	        			break;
	        			case "The expiration date has already passed":
	        				alert("Fecha de expirancion invalida");
	        			break;
	        		}
	        	break;
	        }
	    };

   	/* Fin Configuración Openpay */

});