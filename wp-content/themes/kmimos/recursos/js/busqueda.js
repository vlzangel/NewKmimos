/* BUSCAR */

	jQuery(document).ready(function(){

		if( landing == 'paseos' ){
    		jQuery("#quiero_ser_menu").html("Quiero ser Paseador");
    		jQuery("#buscar_cuidador_btn_nav span").html("Buscar Paseador");

    		calcular();
		}

		jQuery("footer").addClass("show_footer");

		jQuery('nav').addClass('nav_busqueda');
		jQuery('nav').addClass('nav_white');
		
		jQuery("#buscar input").on("change", function(e){ 
			if( parseInt( jQuery("body").width() ) > 768 ){ 
				buscar( jQuery(this).attr("id") ); 
			}
		});

		jQuery("#buscar select").on("change", function(e){
			if( parseInt( jQuery("body").width() ) > 768 ){
				buscar( jQuery(this).attr("id") ); 
			}
		});
		buscar( "" );

		jQuery("#ver_filtros").on("click", function(e){
			if( jQuery(".resultado_item").hasClass("full_width") ){
				jQuery(".resultado_item").removeClass('full_width');
			}else{
				jQuery(".resultado_item").addClass('full_width');
			}
			jQuery('html, body').animate({ scrollTop: parseInt( jQuery(".resultados_box")[0].offsetTop-50 ) });
			jQuery("footer").removeClass("show_footer");
		});

		jQuery("#ver_filtros_fechas").on("click", function(e){
			jQuery(".filtos_container").addClass('open_filtros');
			if( parseInt( jQuery("body").width() ) < 768 ){
				jQuery("body").css("overflow", "hidden");
			}
			jQuery("footer").removeClass("show_footer");
			jQuery("body").css("overflow", "hidden");

			jQuery('nav').addClass('open_filtros');
			jQuery('.zopim').css('z-index', "0");

		});

		jQuery("#ver_mapa").on("click", function(e){
			jQuery(".mapa_container").addClass('open_mapa');
			jQuery('.zopim').css('z-index', "0");

			google.maps.event.trigger(map, 'resize');
			map.setZoom(4);
	    	map.setCenter( new google.maps.LatLng(23.634501, -102.552784) );
	    	jQuery( "#mapa" ).addClass("resize");

		});

		jQuery(".cerrar_filtros_movil").on("click", function(e){
			cerrar_filtros();
		});

		jQuery(".cerrar_filtros_movil_panel").on("click", function(e){
			cerrar_filtros();
		});

		jQuery(".cerrar_mapa_movil").on("click", function(e){
			jQuery(".mapa_container").removeClass('open_mapa');
			jQuery("footer").addClass("show_footer");
			jQuery('.zopim').css('z-index', "16000002");
		});

		jQuery("#buscar").submit(function(e){
			e.preventDefault();
			cerrar_filtros();
		});

		jQuery(".filtros_botones input.boton.boton_verde").on("click", function(e){
			if( parseInt( jQuery("body").width() ) < 768 ){
				jQuery(".filtos_container").removeClass('open_filtros');
				jQuery("body").css("overflow-y", "auto");
				buscar("");
			}
		});

		jQuery("#descuento_movil").on("change", function(e){
			jQuery("#descuento").prop( "checked", jQuery(this).prop("checked") );
			buscar( 'descuento' );
		});
		jQuery("#flash_movil").on("change", function(e){
			jQuery("#flash").prop( "checked", jQuery(this).prop("checked") );
			buscar( 'flash' );
		});
		jQuery("#geo_movil").on("change", function(e){
			jQuery("#geo").prop( "checked", jQuery(this).prop("checked") );
			buscar( 'geo' );
		});

		jQuery(".principales_container").on("click", function(e){
			if( jQuery(this).hasClass("show_princ") ){
				jQuery(this).removeClass("show_princ");
				jQuery(this).find("i").addClass("fa-caret-down");
				jQuery(this).find("i").removeClass("fa-caret-up");
			}else{
				jQuery(this).addClass("show_princ");
				jQuery(this).find("i").removeClass("fa-caret-down");
				jQuery(this).find("i").addClass("fa-caret-up");
			}
		});
	});

	function cerrar_filtros(){
		jQuery(".filtos_container").removeClass('open_filtros');
		if( parseInt( jQuery("body").width() ) < 768 ){
			jQuery("body").css("overflow", "auto");
		}
		jQuery("footer").addClass("show_footer");
		jQuery("body").css("overflow", "auto");

		jQuery('nav').removeClass('open_filtros');
		jQuery('.zopim').css('z-index', "16000002");
	}

	function limpiar_filtros(){
		jQuery('input').prop("checked", false);
		jQuery('input').val("");
		jQuery('#rating_desc').prop("selected", true);
	}

	function filtros_buscar(){
		cerrar_filtros();
		buscar("");
	}

	jQuery(".resultados_container").on("scroll", function() {

		if( parseInt( jQuery("body").width() ) > 768 ){
			var margen = 
				parseInt( jQuery(".mesaje_reserva_inmediata_container").height() ) +
				parseInt( jQuery("#seccion_destacados").height() ) +
				parseInt( jQuery(".cantidad_resultados_container").height() )
		    var hTotal = parseInt( jQuery(".resultados_box").height() )+margen;
		    var scrollPosition = parseInt( jQuery(".busqueda_container").height() ) + parseInt( jQuery(".resultados_container").scrollTop() );
		    
		    if ( ( hTotal <= scrollPosition ) && CARGAR_RESULTADOS ) {
	    		CARGAR_RESULTADOS = false;
		        if( TOTAL_PAGE > (PAGE+1) ){
		        	PAGE = PAGE + 1;
		        	getResultados();
		        	jQuery(".cargando_mas_resultados").css("display", "block");
		        }else{
		        	jQuery(".cargando_mas_resultados").css("display", "none");
		        }
		    }
		    if( jQuery(".resultados_container").scrollTop() >= jQuery(".cantidad_resultados_container")[0].offsetTop ){
		    	jQuery(".cantidad_resultados_container").addClass("cantidad_resultados_fixed");
		    }else{
		    	jQuery(".cantidad_resultados_container").removeClass("cantidad_resultados_fixed");
		    }
		}
	});

	var PAGE = 0;
	var TOTAL_PAGE = 0;
	var CARGAR_RESULTADOS = true;
	var mapIniciado = false;

	function buscar( campo ){

		// console.log( campo );
		// if( campo == "checkin" || campo == "checkout" ){
			verificar_msg();
		// }

		if( campo != undefined ){
			if( campo == "ubicacion" ){
				jQuery(".latitud").val("");
				jQuery(".longitud").val("");
			}
			jQuery.post(
				jQuery("#buscar").attr("action"),
				jQuery("#buscar").serialize(),
				function(respuesta){

					// console.log( respuesta );

					if( respuesta != false ){
						jQuery(".cantidad_resultados_container span").html( respuesta.length );
						TOTAL_PAGE = Math.ceil(respuesta.length/10);
						PAGE = 0;
						jQuery(".resultados_box .resultados_box_interno").html( "" ).promise().done(function(){
							getResultados();
							if( mapIniciado ){ initMap(); }
						});

					}else{
						PAGE = 0;
						jQuery(".resultados_box .resultados_box_interno").html( '<h2 class="pocos_resultados">Si quieres obtener más resultados, por favor pícale <a style="color:#7c169e;" href="'+RAIZ+'">aquí</a> para ajustar los filtros de búsqueda.</h2>' );
						jQuery(".cantidad_resultados_container span").html( 0 );
					}
					if( campo == "flash" ||  campo == "descuento" ||  campo == "ubicacion" || campo == "latitud" || campo == "longitud" || campo == "" ){
						getDestacados();
					}

				}, 'json'
			);
		}
	}

	function verificar_msg(){
		var ini = String(jQuery("#checkin").val()).split("/");
		var actual = new Date();
		var fechaActual = new Date( actual.getFullYear()+"-"+(actual.getMonth()+1)+"-"+actual.getDate() ).getTime();
		var fechaInicio    = new Date( ini[2]+"-"+ini[1]+"-"+ini[0] ).getTime();
		var diff = fechaInicio - fechaActual;
		dias = parseInt( diff/(1000*60*60*24) );
		if( dias <= 3 ){
			var dias_str = ( dias != 1 ) ? "días": "día";
			var msg = ( dias == 0 ) ? "Tu reserva está por comenzar": "Tu reserva comienza en "+dias+" "+dias_str;
			jQuery(".mesaje_reserva_inmediata_container span").html( msg);
			jQuery(".msg_inicio_reserva").css("display", "block");
			jQuery(".resultados_container").css("padding-top", "0px");
		}else{
			jQuery(".msg_inicio_reserva").css("display", "none");
			jQuery(".resultados_container").css("padding-top", "20px");
		}
	}

	function getPage(indice){
		PAGE = indice;
		getResultados();
	}

	function getResultados(){
		cargando(1);
		jQuery.post(
			HOME+"/NEW/resultados.php",
			{ page: PAGE },
			function(html){
				if( parseInt( jQuery("body").width() ) > 768 ){
					jQuery(".resultados_box .resultados_box_interno").append( html );
				}else{
					jQuery(".resultados_box .resultados_box_interno").html( html );
					jQuery('html, body').animate({ scrollTop: 0 }, 1000);
				}

				accionFavorito();

				CARGAR_RESULTADOS = true;

				var PAG_HTML = "";
				var FIN_PAG = 0;
				var INIT_PAG = 0;
				var ACTIVE = '';
				if( PAGE > 3 ){
					INIT_PAG = PAGE-3;
				}
				if( TOTAL_PAGE > (INIT_PAG+10) ){
					FIN_PAG = INIT_PAG+10;
				}else{
					FIN_PAG = TOTAL_PAGE;
				}

				if( PAGE > 3 ){
					PAG_HTML += '<a onclick="getPage('+(PAGE-1)+')"> <img src="'+HOME+'/recursos/img/BUSQUEDA/SVG/Flecha_Atras.svg" /></a>';
				}

				for (var i = INIT_PAG; i < FIN_PAG; i++) {
					ACTIVE = ( PAGE == i ) ? 'active_page' : '';
					PAG_HTML += '<span onclick="getPage('+i+')" class="'+ACTIVE+'">'+(i+1)+'</span>';
				}
				PAG_HTML += '<a onclick="getPage('+(PAGE+1)+')"> <img src="'+HOME+'/recursos/img/BUSQUEDA/SVG/Flecha.svg" /></a>';
				jQuery(".paginacion_container").html(PAG_HTML);

				cargando(2);
			}
		);
	}

	function getDestacados(){
		if( landing != "paseos" ){
			jQuery.post(
				HOME+"/NEW/destacados.php",
				{},
				function(html){
					jQuery("#seccion_destacados").html( html );
					accionFavorito();
					if( String(html).trim() == "" ){
						jQuery("#seccion_destacados").addClass("sin_destacados");
					}else{
						jQuery("#seccion_destacados").removeClass("sin_destacados");
					}
				}
			);

		}

	}

	function cargando(estado){
		if( parseInt( jQuery("body").width() ) < 768 ){
			if( estado == 1 ){
				jQuery(".cargando_mas_resultados_externo").css("display", "block");
			}
			if( estado == 2 ){
				jQuery(".cargando_mas_resultados_externo").css("display", "none");
			}
		}
	}

/* LANDING PASEOS */

function calcular(){
	if( PAQUETE != "" && landing == "paseos" ){
		var dias = get_dias_paquete(PAQUETE);
		var init_array = String( jQuery('#checkin').val() ).split("/");
		var inicio = new Date( init_array[2]+'-'+init_array[1]+'-'+init_array[0] ).getTime();
		var fin = inicio+(dias*86400000);
		fin = new Date(fin);
		var dia = ( (fin.getDate()) < 10 ) ? "0"+(fin.getDate()) : (fin.getDate());
		var mes = ( (fin.getMonth()+1) < 10 ) ? "0"+(fin.getMonth()+1) : (fin.getMonth()+1);
		jQuery('#checkout').val( dia+"/"+mes+"/"+fin.getFullYear() );
		jQuery("#msg_paseos strong").html(get_paquete(PAQUETE));
		jQuery("#msg_paseos").css("display", "block");
		// jQuery('#checkout').prop("disabled", true);

		buscar( 'checkout' );
	}
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

function get_paquete(paq){
	switch( parseInt(paq) ){
		case 1:
			return '1 semana';
		break;
		case 2:
			return '1 mes';
		break;
		case 3:
			return '2 meses';
		break;
		case 4:
			return '3 meses';
		break;
	}
	return 0;
}

/* GALERIA */

	function imgAnterior(_this){
		var actual = _this.parent().attr("data-actual");
		var total = _this.parent().attr("data-total");
		if( actual == 0 ){
			actual = total-1;
		}else{
			actual--;
		}
		if( actual == 0 ){
			_this.addClass("Ocultar_Flecha");
		}
		if( actual != total-1 ){
			_this.parent().find(".Flecha_Derecha").removeClass("Ocultar_Flecha");
		}
		_this.parent().attr("data-actual", actual);
		_this.parent().find(".resultados_item_info_img_box").animate({left: "-"+(actual*100)+"%"});
	}

	function imgSiguiente(_this){
		var actual = _this.parent().attr("data-actual");
		var total = _this.parent().attr("data-total");
		if( actual == total-1 ){
			actual = 0;
		}else{
			actual++;
		}
		if( actual == total-1 ){
			_this.addClass("Ocultar_Flecha");
		}
		if( actual != 0 ){
			_this.parent().find(".Flecha_Izquierda").removeClass("Ocultar_Flecha");
		}
		_this.parent().attr("data-actual", actual);
		_this.parent().find(".resultados_item_info_img_box").animate({left: "-"+(actual*100)+"%"});
	}

	function destacadoAnterior(_this){
		var actual = _this.parent().parent().find(".destacados_container").attr("data-actual");
		var total = _this.parent().parent().find(".destacados_container").attr("data-total");
		var mostrando = getDestacadosMostrados();
		if( actual == 0 ){
			_this.parent().addClass("Ocultar_Flecha");
		}else{
			actual--;
			if( actual > 0 ){
				_this.parent().parent().find(".Flecha_Izquierda").removeClass("Ocultar_Flecha");
			}else{
				_this.parent().addClass("Ocultar_Flecha");
			}

			if( total > actual+actual ){
					_this.parent().parent().find(".Flecha_Derecha").removeClass("Ocultar_Flecha");
			}

			_this.parent().parent().find(".destacados_container").attr("data-actual", actual);
			_this.parent().parent().find(".destacados_container").find(".destacados_box").animate({left: "-"+(actual*( 100 / mostrando ) )+"%"});
		}
	}

	function destacadoSiguiente(_this){
		var actual = _this.parent().parent().find(".destacados_container").attr("data-actual");
		var total = _this.parent().parent().find(".destacados_container").attr("data-total");
		var mostrando = getDestacadosMostrados();
		if( actual == total-mostrando ){
			actual = 0;
		}else{
			actual++;
		}
		if( actual == total-mostrando ){
			_this.parent().addClass("Ocultar_Flecha");
		}
		if( actual != 0 ){
			_this.parent().parent().find(".Flecha_Izquierda").removeClass("Ocultar_Flecha");
		}
		_this.parent().parent().find(".destacados_container").attr("data-actual", actual);
		_this.parent().parent().find(".destacados_container").find(".destacados_box").animate({left: "-"+(actual*( 100 / mostrando ) )+"%"});
	}

	function getDestacadosMostrados(){
		if( parseInt( jQuery("body").width() ) >= 768 ){
			return 4;
		}else{
			return 2;
		}
	}

/* GEOLOCALIZACION */

	var crd;
	var limites = {
	    'norte': {
	        "lat": parseFloat("32.7187629"),
	        "lng": parseFloat("-86.5887")
	    },
	    'sur': {
	        "lat": parseFloat("14.3895"),
	        "lng": parseFloat("-118.6523001")
	    }
	};
	var limitesDestacados = {
	    'lat': {
	        "der": 0,
	        "izq": 0
	    },
	    'lng': {
	        "sup": 0,
	        "inf": 0
	    }
	};

	jQuery(document).ready(function(){

		jQuery(".mi_ubicacion").on("click", function(e){

			jQuery(".icon_left").addClass("fa-spinner fa-spin");
        	jQuery(this).css("display", "none");

		    navigator.geolocation.getCurrentPosition(
		        function(pos) {
		            crd = pos.coords;

		            /*if( 
		                (
		                    limites.norte.lat >= crd.latitude && limites.sur.lat <= crd.latitude &&
		                    limites.norte.lng >= crd.longitude && limites.sur.lng <= crd.longitude
		                ) || 
		                prueba_ubicacion == true
		            ){*/

		                jQuery( '[data-error="ubicacion"]' ).parent().removeClass('has-error');
		                jQuery( '[data-error="ubicacion"]' ).addClass('hidden');

		                jQuery('.latitud').val(crd.latitude);
		                jQuery('.longitud').val(crd.longitude);

		                var geocoder = new google.maps.Geocoder();

		                var latlng = {lat: parseFloat(crd.latitude), lng: parseFloat(crd.longitude)};
		                geocoder.geocode({'location': latlng}, function(results, status) {
		                    if (status == google.maps.GeocoderStatus.OK) {

		                    	// console.log( results );

		                    	limitesDestacados.lat.der = results[6].geometry.bounds.j.j;
		                    	limitesDestacados.lat.izq = results[6].geometry.bounds.j.l;

		                    	limitesDestacados.lng.sup = results[6].geometry.bounds.l.j;
		                    	limitesDestacados.lng.inf = results[6].geometry.bounds.l.l;

		                        var address = results[0]['formatted_address'];
		                        jQuery(".ubicacion_txt").val(address);
		                        jQuery(".ubicacion").val("");
                        		jQuery(".ubicacion").change();

								jQuery(".icon_left").removeClass("fa-spinner fa-spin");
                            	jQuery(".mi_ubicacion").attr("src", HOME+"/recursos/img/HOME/SVG/GPS_On.svg");
                            	jQuery(".mi_ubicacion").css("display", "inline-block");
		                    }
		                });
		            /*}else{
		                console.log( "Fuera del rango" );
		                //jQuery( '[data-error="ubicacion"]' ).parent().addClass('has-error');
		                jQuery( '[data-error="ubicacion"]' ).removeClass('hidden');
		            }*/
		        }, 
		        function error(err) {
					jQuery(".icon_left").removeClass("fa-spinner fa-spin");
                	jQuery(".mi_ubicacion").attr("src", HOME+"/recursos/img/HOME/SVG/GPS_Off.svg");
                	jQuery(".mi_ubicacion").css("display", "inline-block");
		            if( err.message == 'User denied Geolocation' ){
		                alert("Estimado usuario, para poder acceder a esta función, es necesario desbloquear a kmimos en la configuración de ubicación de su dispositivo.");
		            }else{
		                alert(err.message);
		            }
		        },
		        {
		            enableHighAccuracy: true,
		            timeout: 5000,
		            maximumAge: 0
		        }
		    );
		});

	});

/* MAPA */

	var markers = [];
	var infos = [];
	var map;
	var oms = "";

	function initMap() {

		mapIniciado = true;

		markers = [];
		infos = [];
		
		map = new google.maps.Map(document.getElementById("mapa"), {
	        zoom: 4,
	        mapTypeId: google.maps.MapTypeId.ROADMAP,
			scrollwheel: true,
			mapTypeControl: true,
			mapTypeControlOptions: {
				position: google.maps.ControlPosition.LEFT_BOTTOM,
				mapTypeIds: ['roadmap', 'satellite', 'terrain']
			}
	    });

	    /*oms = new OverlappingMarkerSpiderfier(map, { 
		    markersWontMove: true,
		    markersWontHide: true,
		    basicFormatEvents: true
		});*/

	    var bounds = new google.maps.LatLngBounds();
	    
	    jQuery.post(HOME+"/procesos/busqueda/pines.php", {}, function(pines){

	    	if( pines.length > 0 ){
		    	jQuery.each(pines, function( index, cuidador ) {

		    		if( 
	                    limites.norte.lat >= cuidador.lat && limites.sur.lat <= cuidador.lat &&
	                    limites.norte.lng >= cuidador.lng && limites.sur.lng <= cuidador.lng
		            ){

				        bounds.extend( new google.maps.LatLng(cuidador.lat, cuidador.lng) );
				        markers[index] = new google.maps.Marker({ 
				            vlz_index: index,
				            map: map,
				            draggable: false,
				            animation: google.maps.Animation.DROP,
				            position: new google.maps.LatLng(cuidador.lat, cuidador.lng),
				            icon: HOME+"/js/images/n2.png"
				        });

				        var rating = "";
				        var rating_value = 0;
				        if( cuidador["rating"] != undefined && cuidador["rating"] > 0 ){
				        	rating_value = cuidador["rating"];
				        }
				        for( var start = 1; start <= 5; start++){
				        	if( start <= rating_value ){
				        		rating += '<a href="#" class="active"></a>';
				        	}else{
				        		rating += '<a href="#" class="no_active"></a>';
				        	}
				        }

				        infos[index] = new google.maps.InfoWindow({ 
				            content: 	'<a href="'+cuidador.url+'" class="maps_h1">'+cuidador.nom+'</a>'
										+'<p class="maps_p" style="margin-bottom:0px;">'+cuidador.exp+' a&ntilde;o(s) de experiencia</p>'
										+'<div class="km-ranking maps_ranking">'
										+	'<div class="km-ranking rating" style="display:inline-block">'
										+		rating
										+	'</div>'
										+'</div>'
										+'<div class="km-opciones maps">'
										+'    <div class="precio"><span>desde</span> MXN $ '+cuidador.pre+'</div>'
										+'    <a href="'+cuidador.url+'" class="boton boton_border_gris" >Conocer cuidador</a>'
										/* +'    <a href="#" data-name="'+cuidador.nom+'" data-id="'+cuidador.post_id+'" class="boton boton_border_gris solo_pc" data-target="#popup-conoce-cuidador" onclick="open_conocer( jQuery( this ) )" >Conocer cuidador</a>' */
										+'    <a href="'+cuidador.url+'" class="boton boton_verde">Reservar</a>'
										+'</div>'
				        });

				        markers[index].addListener("click", function(e) { 
			                map.panTo(markers[index].getPosition());
				            infos[this.vlz_index].open(map, this);
				        });

						/*oms.addMarker(markers[index]);*/

					}
			    });

			    var markerCluster = new MarkerClusterer(map, markers, {imagePath: HOME+"/js/images/n"});
			    map.fitBounds(bounds);

			    minClusterZoom = 14;
			    markerCluster.setMaxZoom(minClusterZoom);
			    /*window.oms = oms;*/
			}else{
				map = new google.maps.Map(document.getElementById("mapa"), {
			        zoom: 4,
			        mapTypeId: google.maps.MapTypeId.ROADMAP,
					center: new google.maps.LatLng(23.634501, -102.552784), 
			        fullscreenControl: true,
					scrollwheel: true,
					streetViewControl: true,
					streetViewControlOptions: {
						position: google.maps.ControlPosition.LEFT_BOTTOM
					}
			    });
		   	}
	    }, 'json');
	   	
	}

	(function(d, s){
		map = d.createElement(s), e = d.getElementsByTagName(s)[0];
		map.async=!0;
		map.setAttribute("charset","utf-8");
		map.src="//maps.googleapis.com/maps/api/js?v=3&key=AIzaSyCLvX3VwG4eb4KjiCqKgYx1NfBTAuhVHmY&callback=initMap";
		map.type="text/javascript";
		e.parentNode.insertBefore(map, e);
	})(document,"script");