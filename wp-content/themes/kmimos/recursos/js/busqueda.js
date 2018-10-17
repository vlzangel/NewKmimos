/* BUSCAR */

	jQuery(document).ready(function(){
		jQuery("#buscar input").on("change", function(e){ buscar( jQuery(this).attr("id") ); });
		jQuery("#buscar select").on("change", function(e){ buscar( jQuery(this).attr("id") ); });
		buscar( "" );
	});

	jQuery(".resultados_container").on("scroll", function() {
		var margen = 
			parseInt( jQuery(".mesaje_reserva_inmediata_container").height() ) +
			parseInt( jQuery("#seccion_destacados").height() ) +
			parseInt( jQuery(".cantidad_resultados_container").height() )
	    var hTotal = parseInt( jQuery(".resultados_box").height() )+margen;
	    var scrollPosition = parseInt( jQuery(".busqueda_container").height() ) + parseInt( jQuery(".resultados_container").scrollTop() );
	    //console.log(hTotal+" <= "+scrollPosition);
	    if ( ( hTotal <= scrollPosition ) && CARGAR_RESULTADOS ) {
    		CARGAR_RESULTADOS = false;
	        if( TOTAL_PAGE > (PAGE+1) ){
	    		// console.log("Cargando mas...");
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
	});

	var PAGE = 0;
	var TOTAL_PAGE = 0;
	var CARGAR_RESULTADOS = true;
	var mapIniciado = false;
	function buscar( campo ){
		if( campo != "ubicacion_txt" ){
			jQuery.post(
				jQuery("#buscar").attr("action"),
				jQuery("#buscar").serialize(),
				function(respuesta){
					jQuery(".cantidad_resultados_container strong").html( respuesta.length );
					TOTAL_PAGE = Math.ceil(respuesta.length/10);
					jQuery(".resultados_box .resultados_box_interno").html( "" );
					PAGE = 0;
					getResultados();
					getDestacados();
					if( mapIniciado ){ initMap(); }
				}, 'json'
			);
		}
	}

	function getResultados(){
		jQuery.post(
			HOME+"/NEW/resultados.php",
			{ page: PAGE },
			function(html){
				jQuery(".resultados_box .resultados_box_interno").append( html );
				CARGAR_RESULTADOS = true;
			}
		);
	}

	function getDestacados(){
		jQuery.post(
			HOME+"/NEW/destacados.php",
			{},
			function(html){
				jQuery("#seccion_destacados").html( html );
			}
		);
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

	jQuery(document).ready(function(){

		jQuery("#mi_ubicacion").on("click", function(e){

			jQuery(".icon_left").removeClass("fa-crosshairs");
			jQuery(".icon_left").addClass("fa-spinner fa-spin");

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

		                jQuery('#latitud').val(crd.latitude);
		                jQuery('#longitud').val(crd.longitude);


		                var geocoder = new google.maps.Geocoder();

		                var latlng = {lat: parseFloat(crd.latitude), lng: parseFloat(crd.longitude)};
		                geocoder.geocode({'location': latlng}, function(results, status) {
		                    if (status == google.maps.GeocoderStatus.OK) {
		                        var address = results[0]['formatted_address'];
		                        jQuery("#ubicacion_txt").val(address);
		                        jQuery("#ubicacion").val("");
                        		jQuery("#ubicacion").change();

								jQuery(".icon_left").removeClass("fa-spinner fa-spin");
								jQuery(".icon_left").addClass("fa-crosshairs");
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
					jQuery(".icon_left").addClass("fa-crosshairs");
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

	    oms = new OverlappingMarkerSpiderfier(map, { 
		    markersWontMove: true,
		    markersWontHide: true,
		    basicFormatEvents: true
		});

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

				        var servicios = "";
				        if( cuidador["ser"] != undefined && cuidador["ser"].length > 0 ){
					        jQuery.each(cuidador["ser"], function( index, servicio ) {
					        	servicios += '<img src="'+HOME+'/images/new/icon/'+servicio.img+'" height="40" title="'+servicio.titulo+'"> ';
					        });
				        }

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
				            content: 	'<h1 class="maps">'+cuidador.nom+'</h1>'
										+'<p style="margin-bottom:0px;">'+cuidador.exp+' a&ntilde;o(s) de experiencia</p>'
										+'<div class="km-ranking">'
										+	'<div class="km-ranking rating" style="display:inline-block">'
										+		rating
										+	'</div>'
										+'</div>'
										+'<div class="km-opciones maps">'
										+'    <div class="precio">MXN $ '+cuidador.pre+'</div>'
										+'    <a href="'+cuidador.url+'" class="km-btn-primary-new stroke">CON&Oacute;CELO +</a>'
										+'    <a href="'+cuidador.url+'" class="km-btn-primary-new basic">RESERVA</a>'
										+'</div>'
				        });

				        markers[index].addListener("click", function(e) { 
			                map.panTo(markers[index].getPosition());
				            infos[this.vlz_index].open(map, this);
				        });

						oms.addMarker(markers[index]);

					}
			    });

			    var markerCluster = new MarkerClusterer(map, markers, {imagePath: HOME+"/js/images/n"});
			    map.fitBounds(bounds);

			    minClusterZoom = 14;
			    markerCluster.setMaxZoom(minClusterZoom);
			    window.oms = oms;
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
		map.src="//maps.googleapis.com/maps/api/js?v=3&key=AIzaSyD-xrN3-wUMmJ6u2pY_QEQtpMYquGc70F8&callback=initMap";
		map.type="text/javascript";
		e.parentNode.insertBefore(map, e);
	})(document,"script");