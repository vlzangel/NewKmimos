var CARRITO = [];
var MENSAJES_CUPONES = {
	"1pgkam": "Este cupón te da 1 paseo de regalo (válida hasta el 31 de Diciembre de 2018)",
	"1ngpet": "Este cupón te da 1 noche de regalo (válida hasta el 31 de Enero de 2019)",
	"2pgpet": "Este cupón te da 2 paseos de regalo (válidos hasta el 31 de Enero de 2019)",
	"2ngpet": "Este cupón te da 2 noche de regalo (válidas hasta el 31 de Enero de 2019)",
	"3pgpet": "Este cupón te da 3 paseos de regalo (válidos hasta el 31 de Enero de 2019)",
	"350desc": "",
	"+2masc": "",
};

var NOMBRE_CUPONES = {
	"cpc10%": "Descuento por conocer 10%",
};

var PAQs = [
	"",
	"p1sem",
	"p1mes",
	"p2meses",
	"p3meses",
];
var order_id = 0;
var PROCESAR_PAQUETE = true;
function initCarrito(){
	CARRITO = [];

	CARRITO["fechas"] = [];

		CARRITO["fechas"] = {
			"flash" : "",
			"inicio" : "",
			"fin" : "",
			"checkin" : "",
			"checkout" : "",
			"duracion" : ""
		};

	CARRITO["cantidades"] = [];

		CARRITO["cantidades"] = {
			"cantidad" : 0,
			"pequenos" : [],
			"medianos" : [],
			"grandes" : [],
			"gigantes" : [],
			"gatos" : []
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
			"fee" : 0,
			"total" : "",
			"tipo" : "tienda",
			"metodo" : "completo",
			"token" : "",
			"deviceIdHiddenFieldName" : "",
			"id_fallida" : 0,
			"reconstruir" : false
		};

	if( CARRITO["cupones"] == undefined ){
		CARRITO["cupones"] = [];
	}
	
	CARRITO["tarjeta"] = [];

		CARRITO["tarjeta"] = {
			"nombre" : "",
			"numero" : "",
			"mes" : "",
			"anio" : "",
			"codigo" : "",
			"puntos" : false,
		};

	CARRITO[ "pagar" ][ "tipo_servicio" ] = tipo_servicio;
	CARRITO[ "pagar" ][ "paquete" ] = PAQUETE;

	CARRITO[ "pagar" ][ "fee" ] = parseInt( fee_conocer );
}

function get_dias_paquete(paq){
	switch( parseInt(paq) ){
		case 1:
			return 7;
		break;
		case 2:
			return 30;
		break;
		case 3:
			return 60;
		break;
		case 4:
			return 90;
		break;
	}
	return 0;
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

var DIAS_SELECCIONADOS = [];

var PERROS = 0;
var GATOS = 0;
function calcular(){
	
	if( FLASH == "NO" ){
		if( jQuery("#checkin").val() == "" ){
			jQuery("#vlz_msg_bloqueo").addClass("vlz_bloquear_msg");
			jQuery("#bloque_info_servicio").addClass("vlz_bloquear");
			jQuery("#vlz_msg_bloqueo").removeClass("vlz_NO_bloquear_msg");
		}else{
			if( jQuery("#checkin").val() == HOY && HORA >= 7 ){
				jQuery("#vlz_msg_bloqueo").addClass("vlz_bloquear_msg");
				jQuery("#bloque_info_servicio").addClass("vlz_bloquear");
				jQuery("#vlz_msg_bloqueo").removeClass("vlz_NO_bloquear_msg");
			}else{
				if( jQuery("#checkin").val() == MANANA && HORA >= 18 ){
					jQuery("#vlz_msg_bloqueo").addClass("vlz_bloquear_msg");
					jQuery("#bloque_info_servicio").addClass("vlz_bloquear");
					jQuery("#vlz_msg_bloqueo").removeClass("vlz_NO_bloquear_msg");
				}else{
					jQuery("#vlz_msg_bloqueo").addClass("vlz_NO_bloquear_msg");
					jQuery("#vlz_msg_bloqueo").removeClass("vlz_bloquear_msg");
					jQuery("#bloque_info_servicio").removeClass("vlz_bloquear");
				}
			}
		}
	}else{
		if( jQuery("#checkin").val() == HOY && HORA >= 7 ){
			CARRITO["fechas"]["flash"] = "SI";
		}else{
			if( jQuery("#checkin").val() == MANANA && HORA >= 18 ){
				CARRITO["fechas"]["flash"] = "SI";
			}else{
				CARRITO["fechas"]["flash"] = "NO";
			}
		}
	}

	if( SUPERU != "Si" ){
		if( 
			( jQuery("#checkin").val() == HOY && ( (HORA >= 0 && HORA <= 6) || ( HORA == 23 ) ) ) ||
			( jQuery("#checkin").val() == MANANA && ( HORA == 23 ) )
		){
			jQuery("#vlz_msg_bloqueo_madrugada").addClass("vlz_bloquear_msg_madrugada");
			jQuery("#vlz_msg_bloqueo_madrugada").removeClass("vlz_NO_bloquear_msg_madrugada");

			jQuery("#bloque_info_servicio").addClass("bloquear_madrugada");
		}else{
			jQuery("#vlz_msg_bloqueo_madrugada").removeClass("vlz_bloquear_msg_madrugada");
			jQuery("#vlz_msg_bloqueo_madrugada").addClass("vlz_NO_bloquear_msg_madrugada");

			jQuery("#bloque_info_servicio").removeClass("bloquear_madrugada");
		}
	}

	if( CARRITO["pagar"]["id_fallida"] != 0 ){
		CARRITO["pagar"]["reconstruir"] = true;
	}

	PERROS = 0;
	GATOS = 0;

	CARRITO["cantidades"]["cantidad"] = 0;
	jQuery("#reservar .tamano").each(function( index ) {
		if( parseFloat( jQuery( this ).val() ) > 0 ){
			if( jQuery( this ).attr("name") == "gatos" ){
				GATOS++;
			}else{
				PERROS++;
				CARRITO["cantidades"]["cantidad"] += parseInt(jQuery( this ).val());
			}
		}
		CARRITO[ "cantidades" ][ jQuery( this ).attr("name") ] = [
			parseFloat( jQuery( this ).val() ),
			parseFloat( jQuery( this ).attr("data-valor") )
		];
	});

	if( PERROS == 0 && GATOS > 0 ){
		jQuery("#contenedor-adicionales").css("display", "none");
	}else{
		jQuery("#contenedor-adicionales").css("display", "block");
	}

	var tranporte = jQuery('#transporte option:selected').val();
	if( tranporte != undefined && tranporte != "" ){
		CARRITO[ "transportacion" ] = [
			jQuery('#transporte option:selected').attr("data-value"),
			parseFloat(tranporte)
		];
	}else{
		CARRITO[ "transportacion" ] = undefined;
	}

	jQuery("#adicionales input").each(function( index ) {
        if( jQuery( this ).hasClass("active") ){
        	CARRITO[ "adicionales" ][ jQuery( this ).attr("name") ] = parseFloat( jQuery( this ).val() );
        }else{
        	CARRITO[ "adicionales" ][ jQuery( this ).attr("name") ] = 0;
        }
	});

	if( jQuery('#checkin').val() != "" ){
		var ini = String( jQuery('#checkin').val() ).split("/");
		CARRITO[ "fechas" ][ "inicio" ] = ini[2]+"-"+ini[1]+"-"+ini[0]+" 00:00:00";
		jQuery(".fecha_ini").html( jQuery('#checkin').val() );
	}

	if( jQuery('#checkout').val() != "" ){
		var fin = String( jQuery('#checkout').val() ).split("/");
		CARRITO[ "fechas" ][ "fin" ] = fin[2]+"-"+fin[1]+"-"+fin[0]+" 23:59:59";
		jQuery(".fecha_fin").html( jQuery('#checkout').val() );
	}

	if( tipo_servicio == "paseos" && PAQUETE != "" ){
		var dias = get_dias_paquete(PAQUETE);
		var inicio = new Date(String(CARRITO[ "fechas" ][ "inicio" ]).split(" ")[0]).getTime();
		var fin = inicio+(dias*86400000);
		fin = new Date(fin);
		var dia = ( (fin.getDate()+1) < 10 ) ? "0"+(fin.getDate()+1) : (fin.getDate()+1);
		var mes = ( (fin.getMonth()+1) < 10 ) ? "0"+(fin.getMonth()+1) : (fin.getMonth()+1);
		CARRITO[ "fechas" ][ "fin" ] = fin.getFullYear()+"-"+mes+"-"+fin.getDate()+" 00:00:00";
		jQuery('#checkout').val( dia+"/"+mes+"/"+fin.getFullYear() );
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

	if( error == "" ){
		var dias = 0;
		if( CARRITO[ "fechas" ][ "inicio" ] == undefined || CARRITO[ "fechas" ][ "inicio" ] == "" ){
			error = "Ingrese la fecha de inicio";
		}else{
			if( CARRITO[ "fechas" ][ "fin" ] == undefined || CARRITO[ "fechas" ][ "fin" ] == "" ){
				error = "Ingrese la fecha de finalizaci&oacute;n";
			}else{
				if( tipo_servicio == "paseos" && es_landing_paseos && jQuery(".dias_container").css('display') == 'block' ){
					var dias_array = [];
					jQuery(".dias_container input").each(function(i, v){
						if( jQuery(this).prop("checked") ){
							dias_array.push( jQuery(this).val() );
						}
					});

					dias = parseInt( get_dias_semana(dias_array) );
					CARRITO[ "fechas" ][ "duracion" ] = dias;
					if( dias == 0 ){
						error = "Los días marcados no se encuentran en el rango seleccionado";
					}
				}else{
					var fechaInicio = new Date(String(CARRITO[ "fechas" ][ "inicio" ]).split(" ")[0]).getTime();
					var fechaFin    = new Date(String(CARRITO[ "fechas" ][ "fin" ]).split(" ")[0]).getTime();
					var temp = String(CARRITO[ "fechas" ][ "inicio" ]).split(" ")[0];
					var diff = fechaFin - fechaInicio;
					dias = parseInt( diff/(1000*60*60*24) );
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
		    }
		}
	}

	var cant = 0, duracion = 0;
	jQuery.each( CARRITO[ "cantidades" ], function( key, valor ) {
		if( key != "cantidad" && valor[0]  != undefined && valor[1] > 0 ){
			cant += ( parseFloat( valor[0] ) * parseFloat( valor[1] ) );
		}
	});	

	if( error == "" ){
		var cantidad = getCantidad();
		if( cantidad > acepta ){
			plural = "";
			if( acepta > 1 ){ plural = "s"; }
			error = "El cuidador solo acepta ["+acepta+"] mascota"+plural;
		}
	}

	if( error == "" ){
		if( cant == 0 ){
			error = "Seleccione número de mascotas en la sección de arriba";
		}else{
			cant *= parseFloat( dias );
			jQuery(".km-price-total").html("$"+numberFormat(cant));
		}
	}

	jQuery.each( CARRITO[ "adicionales" ], function( key, valor ) {
		if( valor > 0 ){
			cant += (valor*CARRITO["cantidades"]["cantidad"]);
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
		jQuery(".pago_17").html( "$" + numberFormat(cant*0.2) );
		jQuery(".pago_cuidador").html( "$" +  numberFormat(cant-(cant*0.2)) );
		jQuery(".monto_total").html( "$" + numberFormat(cant) );
		CARRITO["pagar"]["total"] = cant;
		jQuery("#reserva_btn_next_1").removeClass("km-end-btn-form-disabled");
		jQuery("#reserva_btn_next_1").removeClass("disabled");
		calcularDescuento();

		jQuery(".items_reservados_paso_1_container").css("display", "block");
	}else{
		jQuery("#reserva_btn_next_1").addClass("km-end-btn-form-disabled");
		jQuery("#reserva_btn_next_1").addClass("disabled");

		jQuery(".items_reservados_paso_1_container").css("display", "none");
	}
	initFactura();
    
}

function get_dias_semana(dias){
	var _dias = {
    	"domingo": 0,
    	"lunes": 1,
    	"martes": 2,
    	"miercoles": 3,
    	"jueves": 4,
    	"viernes": 5,
    	"sabado": 6
    };
    var _dias_seleccionados = [];
    jQuery.each(dias, function(i, v){
    	_dias_seleccionados.push(_dias[ v ] );
    });
    var num_dias = 0;
    var cont = 0;
    var inicio = new Date(String(CARRITO[ "fechas" ][ "inicio" ]).split(" ")[0]).getTime();
	var fin    = new Date(String(CARRITO[ "fechas" ][ "fin" ]).split(" ")[0]).getTime();

	var fecha_temp = 0;
	inicio += 86400000;
	fin += 86400000;
	DIAS_SELECCIONADOS = [];
	for (i=inicio; i <= fin; i+=86400000) { 
		cont++;
		fecha_temp = new Date(i);
		dia = fecha_temp.getDay();
		if( _dias_seleccionados.indexOf(dia) != -1 ){
			num_dias++;
			DIAS_SELECCIONADOS.push(dia);
		}
	}

	CARRITO[ "fechas" ][ "dias" ] = DIAS_SELECCIONADOS;
	CARRITO[ "fechas" ][ "dias_str" ] = dias;

	return num_dias;
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
		var ini = new Date(String(CARRITO[ "fechas" ][ "inicio" ]).split(" ")[0]);
		var fin    = new Date(String(CARRITO[ "fechas" ][ "fin" ]).split(" ")[0]);

		ini.setHours(0);
		ini.setMinutes(0);
		ini.setSeconds(0);
		ini = ini.getTime();

		fin.setHours(23);
		fin.setMinutes(59);
		fin.setSeconds(59);
		fin = fin.getTime();

		var act = new Date();
		var tem = "";

		if( ini != undefined && ini != "" && fin != undefined && fin != "" ){
			jQuery.each(cupos, function( index, item ) {
				tem = String( item.fecha ).split("-");
				act = new Date( tem[0]+"-"+tem[1]+"-"+tem[2] );
				act.setHours(0);
				act.setMinutes(0);
				act.setSeconds(0);
				act = act.getTime();

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

	var items = "";

	if( tipo_servicio == "paseos" && PAQUETE != ""  ){
		var _dias = [
	    	"Domingo",
	    	"Lunes",
	    	"Martes",
	    	"Miercoles",
	    	"Jueves",
	    	"Viernes",
	    	"Sábado"
	    ];
	    var dias_en_rango = [];
		jQuery.each(DIAS_SELECCIONADOS, function(i, v){
			if( dias_en_rango.indexOf( _dias[ v ] ) == -1 ){
				dias_en_rango.push( _dias[ v ] );
			}
		});
		items += '<div class="km-option-resume-service km-option-resume-service-dias">'
		items += '	<span class="label-resume-service">'+( dias_en_rango.join(" - ") )+'</span>'
		items += '	<span class="value-resume-service"></span>'
		items += '</div>';
	}

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
		"gigantes" : "Gigante",
		"gatos" : "Gato"
	};

	var tamanos_movil = {
		"pequenos" : "Peq.",
		"medianos" : "Med.",
		"grandes" :  "Grd.",
		"gigantes" : "Gig.",
		"gatos" : "Gato"
	};

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
			items += '	<span class="label-resume-service_movil">'+CARRITO["cantidades"][key][0]+' Masc. '+tamanos_movil[key]+' x '+CARRITO["fechas"]["duracion"]+' '+diaNoche+' x $'+CARRITO["cantidades"][key][1]+' </span>'
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

function convertCARRITO(){
	var transporte = "==="; 
	if( CARRITO["transportacion"] != undefined && CARRITO["transportacion"][1] > 0 ){
		transporte = JSON.stringify( CARRITO["transportacion"] )+"===";
	}
	var json =  
		JSON.stringify( CARRITO["pagar"] )+"==="+
		JSON.stringify( CARRITO["tarjeta"] )+"==="+
		JSON.stringify( CARRITO["fechas"] )+"==="+
		JSON.stringify( CARRITO["cantidades"] )+"==="+transporte+
		JSON.stringify( CARRITO["adicionales"] )+"==="+
		JSON.stringify( CARRITO["cupones"] )
	;
	return json;
}

function pagarReserva(id_invalido = false){

	jQuery("#reserva_btn_next_3 span").html("Procesando");
	jQuery("#reserva_btn_next_3").addClass("disabled");
	jQuery("#reserva_btn_next_3").addClass("cargando");

	var json = convertCARRITO();

	jQuery.post(
		HOME+"/procesos/reservar/pagar.php",
		{
			info: json,
			id_invalido: id_invalido
		},
		function(data){
			// console.log( data );
			if( data.error != "" && data.error != undefined ){

				var es_fallida_con_orden = true;
				switch( data.tipo_error ){
					case 'sin_mascotas':
						var error = "Error procesando la reserva<br>";
				    	error += "No hay mascotas seleccionadas<br>";

				    	setTimeout(function(e){
				    		location.reload();
				    	}, 1500);
				    	es_fallida_con_orden = false;
					break;
					case 'no_flash':
						var error = "Error procesando la reserva<br>";
				    	error += "Este cuidador no acepta reserva inmediata<br>";

				    	setTimeout(function(e){
				    		location.reload();
				    	}, 1500);
				    	es_fallida_con_orden = false;
					break;
					default:
						var error = "Error procesando la reserva<br>";
				    	error += "Por favor intente nuevamente.<br>";
				    	error += "Si el error persiste por favor comuniquese con el soporte Kmimos.<br>";
					break;
				}

		    	jQuery(".errores_box").html(error);
				jQuery(".errores_box").css("display", "block");
				jQuery("#reserva_btn_next_3 span").html("TERMINAR RESERVA");
				jQuery("#reserva_btn_next_3").removeClass("disabled");
				jQuery("#reserva_btn_next_3").removeClass("cargando");
				if( es_fallida_con_orden ){
					CARRITO["pagar"]["id_fallida"] = data.error;
				}
			}else{
				CARRITO["pagar"]["id_fallida"] = 0;
				if(data.urlPaypal == "" ){
					location.href = RAIZ+"/finalizar/"+data.order_id;
				}else{
					location.href = data.urlPaypal;
				}
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
					if( NOMBRE_CUPONES[nombreCupon] == undefined || NOMBRE_CUPONES[nombreCupon] == "" ){
						items += '	<span class="label-resume-service">'+nombreCupon+'</span>';
					}else{
						items += '	<span class="label-resume-service">'+NOMBRE_CUPONES[nombreCupon]+'</span>';
					}
					if(cupon[1] > 0 ){
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

	if( fee_conocer > 0 ){
		items += '<div class="km-option-resume-service">';
			items += '	<span class="label-resume-service"> Saldo Conocer</span>';
			items += '	<span class="value-resume-service">$'+numberFormat(fee_conocer)+'</span>';
		items += '</div>';
	}

	if( items != "" ){
		jQuery(".cupones_desglose div").html(items);
		jQuery(".cupones_desglose").css("display", "block");
	}else{
		jQuery(".cupones_desglose").css("display", "none");
	}
	items = "";
}

function calcularDescuento(){
	var descuentos = 0;
	var saldo = 0;

	var total = CARRITO["pagar"]["total"]-fee_conocer;


	jQuery.each(CARRITO["cupones"], function( key, cupon ) {
		if( cupon[1] == "" ){
			cupon[1] = 0;
		}
		if( String(cupon[0]).toLowerCase().search("saldo") != -1 ){
			saldo += parseFloat(cupon[1]);
        }else{
			descuentos += parseFloat(cupon[1]);
        }
	});

	var pre17 = total*0.2;
	var pagoCuidador = total-(total*0.20);

	var reciduo_0 = 0;
	if( pagoCuidador >= descuentos ){
		pagoCuidador -= descuentos;
	}else{
		reciduo_0 = descuentos-pagoCuidador; /* Reciduo de exceso de cupones normales, se pasa al saldo */
		pagoCuidador = 0;
	}

	var reciduo = 0;
	if( pre17 >= saldo ){
		pre17 -= (saldo+reciduo_0);
	}else{
		reciduo = (saldo+reciduo_0)-pre17;
		pre17 = 0;
	}

	if( pagoCuidador >= reciduo ){
		pagoCuidador -= reciduo;
	}

	descuentos = descuentos+saldo;

	CARRITO["pagar"]["deposito"] = pre17;
	CARRITO["pagar"]["pagoCuidador"] = pagoCuidador;
	CARRITO["pagar"]["descuento_total"] = descuentos;

	if( jQuery(".km-option-deposit").hasClass("active") ){

	}else{
		if( total == descuentos ){
			jQuery("#metodos_pagos").css("display", "none");
		}else{
			jQuery("#metodos_pagos").css("display", "block");
		}
	}

	jQuery(".sub_total").html( "$" + numberFormat(total) );
	if( descuentos == 0 ){
		jQuery(".descuento").html( "$" + numberFormat(descuentos) );

		jQuery(".sub_total").parent().css("display", "none");
		jQuery(".descuento").parent().css("display", "none");
	}else{
		jQuery(".descuento").html( "$" + numberFormat(descuentos) );

		jQuery(".sub_total").parent().css("display", "block");
		jQuery(".descuento").parent().css("display", "block");
	}

	jQuery(".pago_17").html( "$" + numberFormat(pre17) );
	jQuery(".pago_cuidador").html( "$" +  numberFormat(pagoCuidador) );
	
	jQuery(".monto_total").html( "$" + numberFormat(total-descuentos) );
	jQuery(".km-price-total2").html("$"+numberFormat( total-descuentos ));

}

function aplicarCupon(cupon = ""){

	var total = CARRITO["pagar"]["total"]-fee_conocer;
	var total = total - CARRITO["pagar"]["descuento_total"];

	if( total <= 0 ){
		alert( "El cupón no será aplicado. El total a pagar por su reserva es 0." );
		jQuery("#cupon_btn").html("Cup&oacute;n");
		jQuery("#cupon_btn").removeClass("disabled");
		jQuery("#cupon").val("");
		return;
	}


	jQuery("#cupon_btn").html("Aplicando");
	jQuery("#cupon_btn").addClass("disabled");

	if( jQuery("#cupon").val() != "" || cupon != ""){
		if( cupon == "" ){ cupon = jQuery("#cupon").val(); }
		jQuery.post(
			HOME+"/procesos/reservar/cupon.php",
			{
				servicio: SERVICIO_ID,
				cupon: cupon,
				cupones: CARRITO["cupones"],
				total: total,
				duracion: CARRITO["fechas"]["duracion"],
				inicio: CARRITO["fechas"]["inicio"],
				tipo_servicio: tipo_servicio,
				mascotas: CARRITO["cantidades"],
				cliente: cliente,
				reaplicar: 0
			},
			function(data){
				console.log( data );

				if( data.error == undefined ){
					CARRITO["cupones"] = data.cupones;

					mostrarCupones();
					eliminarCuponesHandler();
					jQuery("#cupon").val("");

					calcularDescuento();

				}else{
					alert(data.error);
				}

				jQuery("#cupon_btn").html("Cup&oacute;n");
				jQuery("#cupon_btn").removeClass("disabled");

			}, "json"
		).fail(function(e) {
	    	console.log( e );
	  	});
	}
}

function reaplicarCupones(){

	var total = CARRITO["pagar"]["total"]-fee_conocer;

	jQuery.post(
		HOME+"/procesos/reservar/cupon.php",
		{
			servicio: SERVICIO_ID,
			cupones: CARRITO["cupones"],
			total: total,
			mascotas: CARRITO["cantidades"],
			duracion: CARRITO["fechas"]["duracion"],
			inicio: CARRITO["fechas"]["inicio"],
			tipo_servicio: tipo_servicio,
			cliente: cliente,
			reaplicar: 1
		},
		function(data){
			if( data.error == undefined ){
				CARRITO["cupones"] = data.cupones;
				mostrarCupones();
				eliminarCuponesHandler();
				jQuery("#cupon").val("");
				calcularDescuento();

			}

		}, "json"
	).fail(function(e) {
    	console.log( e );
  	});
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

	if( tipo_servicio == "paseos" && PAQUETE != "" ){
		CARRITO["cupones"].push([
			PAQs[PAQUETE],
			0,
			0
		]);
	}

	if( cupon_conocer_c == 'YES' ){
		CARRITO["cupones"].push([
			"cpc10%", 
			0, 
			0
		]);
	}

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

	jQuery(document).on("click", '.page-reservation .km-method-paid-options .km-method-paid-option', function ( e ) {
		e.preventDefault();
		if( !jQuery(this).hasClass("km-option-3-lineas") ){
			var el = jQuery(this);
			
			jQuery(".km-method-paid-option", el.parent()).removeClass("active");

			el.addClass("active");
			
			if ( el.hasClass("km-option-deposit") ) {

			/*	jQuery(".page-reservation .km-detail-paid-deposit").slideDown("fast");
				jQuery(".page-reservation .km-services-total").slideUp("fast");
				
				CARRITO["pagar"]["metodo"] = "deposito";*/

				jQuery(".modal_20_porciento").css("display", "inline-block");

			} else {
				jQuery(".km-method-paid-option", el.parent()).removeClass("active");
				el.addClass("active");

				jQuery(".page-reservation .km-detail-paid-deposit").slideUp("fast");
				jQuery(".page-reservation .km-services-total").slideDown("fast");
				CARRITO["pagar"]["metodo"] = "completo";
			}
			
			if(typeof calcularDescuento === 'function') {
				calcularDescuento();
			}

			calcular();
		}

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
			
			if( CARRITO["cupones"].length == 0 ){
				aplicarCupon(saldo);
			}else{
				reaplicarCupones();
			}
			jQuery('.km-option-total').click();

			if( CARRITO["cantidades"]["cantidad"] == 0 ){
				CARRITO["adicionales"] = {
					"bano" : 0,
					"corte" : 0,
					"acupuntura" : 0,
					"limpieza_dental" : 0,
					"visita_al_veterinario" : 0
				}

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

	jQuery("#cupon_btn").on("click", function(e){
		e.preventDefault();
		if( jQuery(this).hasClass("disabled") ){

		}else{
			aplicarCupon();
		}
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
					
						/* }else if( CARRITO["pagar"]["tipo"] == "paypal" ){
							jQuery("#reserva_btn_next_3").addClass("disabled");
							jQuery("#reserva_btn_next_3").addClass("cargando");
							var info = convertCARRITO();
							jQuery.post(HOME+"/procesos/reservar/pasarelas/paypal/create.php",
								{
									'info': info,
									'ruta': RAIZ,
								},
								function(data){
									if( data.status == 'CREATED' ){
										console.log(data.links);
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
							jQuery.post(HOME+"/procesos/reservar/pasarelas/mercadopago/create.php",
								{
									'info': info,
									'ruta': RAIZ,
									'order_id': order_id,
									'cliente': cliente_data,
									'cuidador': cuidador_data,
								},
								function(data){
									if( data.status == 'CREATED' ){
										location.href = data.links;
									}
								}, 'json'
							);
						*/
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
			case 'paypal':
				jQuery(".errores_box").css("display", "none");
				CARRITO["pagar"]["tipo"] = "paypal";
				break;
			case 'mercadopago':
				jQuery(".errores_box").css("display", "none");
				CARRITO["pagar"]["tipo"] = "mercadopago";
				break;
			default:
				jQuery(".errores_box").css("display", "none");
				CARRITO["pagar"]["tipo"] = jQuery(this).val();
				break;
		}
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


	jQuery(document).on("click", '#pasarela-container .km-method-paid-option', function ( e ) {
		e.preventDefault();
		var el = jQuery(this);
		
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