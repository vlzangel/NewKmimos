var crd;
var prueba_ubicacion = false;

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


jQuery(document).on('click', '.km-select-background-click',function(){
	jQuery('.km-select-custom-list').css('display', 'none');
	jQuery(".km-select-background-click").remove();
});

jQuery(document).on('click', '[data-target="checkbox"]', function(){
	var obj = jQuery(this).parent().parent().parent().children('button');
		obj.html( obj.attr('title') );
	  
	var l = jQuery(this).parent().parent().find('[type="checkbox"]:checked');
	var contenido = '';
	jQuery.each(l, function(i,v){
		var value = jQuery(this).attr('content');	
		if( l[i].checked ){
			if(contenido.trim().indexOf( value ) == -1){
				var separador = ( contenido != '' )? ', ':''; 
				contenido = contenido+separador+value;
				obj.html( contenido );
			}
		}
	});
});

function km5(valor){
	jQuery('#km5').val(valor);
	jQuery(".km-formulario-buscador").submit();
}

jQuery(document).ready(function(){

	jQuery("#mi_ubicacion").on("click", function(e){
	    navigator.geolocation.getCurrentPosition(
	        function(pos) {
	            crd = pos.coords;

	            if( 
	                (
	                    limites.norte.lat >= crd.latitude && limites.sur.lat <= crd.latitude &&
	                    limites.norte.lng >= crd.longitude && limites.sur.lng <= crd.longitude
	                ) || 
	                prueba_ubicacion == true
	            ){
	                console.log( "En el rango" );

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
	                    }
	                });
	            }else{
	                console.log( "Fuera del rango" );
	                //jQuery( '[data-error="ubicacion"]' ).parent().addClass('has-error');
	                jQuery( '[data-error="ubicacion"]' ).removeClass('hidden');
	            }
	        }, 
	        function error(err) {
	            console.log("Error");
	        },
	        {
	            enableHighAccuracy: true,
	            timeout: 5000,
	            maximumAge: 0
	        }
	    );
	});
	jQuery(".km-formulario-buscador").on("submit", function(e){
		jQuery('#buscando_container').css('display', 'block');

		jQuery.post(
			jQuery(this).attr("action"),
			jQuery(this).serialize(),
			function(respuesta){
				location.reload();
			}
		);

		e.preventDefault();
	});

	jQuery('.km-premium-slider').bxSlider({
	    slideWidth: 200,
	    minSlides: 1,
	    maxSlides: 3,
	    slideMargin: 10
	});

	jQuery(document).on("click", '.show-map-mobile', function ( e ) {
		e.preventDefault();
		jQuery(".km-map-content").addClass("showMap");
	});

	jQuery(document).on("click", '.km-map-content .km-map-close', function ( e ) {
		e.preventDefault();
		jQuery(".km-map-content").removeClass("showMap");
	});

	jQuery(".datepick td").on("click", function(e){
		jQuery( this ).children("a").click();
	});

	function initCheckin(date, actual){
        if(actual){
            jQuery('#checkout').datepick({
                dateFormat: 'dd/mm/yyyy',
                defaultDate: date,
                selectDefaultDate: true,
                minDate: date,
                onSelect: function(xdate) {
                    if(typeof calcular === 'function') {
                        calcular();
                    }
                },
                yearRange: date.getFullYear()+':'+(parseInt(date.getFullYear())+1),
                firstDay: 1,
                onmonthsToShow: [1, 1]
            });
        }else{
            jQuery('#checkout').datepick({
                dateFormat: 'dd/mm/yyyy',
                minDate: date,
                onSelect: function(xdate) {
                    if(typeof calcular === 'function') {
                        calcular();
                    }
                },
                yearRange: date.getFullYear()+':'+(parseInt(date.getFullYear())+1),
                firstDay: 1,
                onmonthsToShow: [1, 1]
            });
        }
    }

    jQuery('#checkin').datepick({
        dateFormat: 'dd/mm/yyyy',
        minDate: fecha,
        onSelect: function(date1) {
            var ini = jQuery('#checkin').datepick( "getDate" );
            var fin = jQuery('#checkout').datepick( "getDate" );
            if( fin.length > 0 ){
                var xini = ini[0].getTime();
                var xfin = fin[0].getTime();
                if( xini > xfin ){
                    jQuery('#checkout').datepick('destroy');
                    initCheckin(date1[0], true);
                }else{
                    jQuery('#checkout').datepick('destroy');
                    initCheckin(date1[0], false);
                }
            }else{
                jQuery('#checkout').datepick('destroy');
                initCheckin(date1[0], true);
            }
            if(typeof calcular === 'function') {
                calcular();
            }
            if(typeof validar_busqueda_home === 'function') {
                validar_busqueda_home();
            }
        },
        yearRange: fecha.getFullYear()+':'+(parseInt(fecha.getFullYear())+1),
        firstDay: 1,
        onmonthsToShow: [1, 1]
    });

    jQuery('#checkout').datepick({
        dateFormat: 'dd/mm/yyyy',
        minDate: fecha,
        onSelect: function(xdate) {
            if(typeof calcular === 'function') {
                calcular();
            }
        },
        yearRange: fecha.getFullYear()+':'+(parseInt(fecha.getFullYear())+1),
        firstDay: 1,
        onmonthsToShow: [1, 1]
    });

    jQuery(".km-select-custom-list").on('click', function (e) {
	    if ( 
	    		( e.offsetX >= ( parseFloat(jQuery(this).outerWidth()) - 30 ) ) &&
	    		( e.offsetY <= 30 )
	    	)
	    {
	        var obj = jQuery(".km-select-custom-list").css('display', 'none');
	        var obj = jQuery(".km-select-background-click").css('display', 'none');
	    }
	});

});

function resizeMap(){
	if( jQuery( "body" ).width() > 975 && jQuery( "#mapa" ).hasClass("resize") ){
		google.maps.event.trigger(map, 'resize');
		map.setZoom(4);
    	map.setCenter( new google.maps.LatLng(23.634501, -102.552784) );
    	jQuery( "#mapa" ).removeClass("resize");

    	jQuery(".km-caja-resultados .km-columna-der").fadeOut("fast");

    	mapStatic();
	}
}

function mapStatic(){
	var w = jQuery(window);
	if ( w.width() > 991 ) {
		var scrollTop = w.scrollTop();
		var mapPrin = jQuery(".km-caja-resultados");
		var mapElem = jQuery(".km-caja-resultados .km-columna-der");
		var offset = mapPrin.offset();
		var topPre = 41;

		if ( scrollTop > 290 ) {
			mapElem.addClass("mapAbsolute");
			var topSumar = scrollTop - offset.top + topPre;
			mapElem.css({
				top: topSumar
			});
		} else {
			mapElem.removeClass("mapAbsolute");
		}
	}
}

jQuery(window).scroll(function() {

	if( pines != undefined ){
		if( pines.length > 1 ){
			mapStatic();
		}
	}
});
jQuery('#mapa-close').on('click', function(){
	jQuery("body").css("overflow", "auto");
	jQuery(".km-caja-resultados .km-columna-der").fadeOut("fast", function(){
		
	});
});

jQuery(document).on('click', '.km-select-custom-button', function(){
	var obj = jQuery(this).parent().find('ul');
	if( obj.css('display') != 'none' ){
		obj.css('display', 'none');
	}else{
		jQuery('.km-select-custom-list').css('display', 'none');
		jQuery('body').append('<div class="km-select-background-click" style="top:0px;left:0px;position:fixed;width:100%;height:100vh;background:transparent;z-index:2;"></div>')
		obj.css('z-index', '2');
		obj.css('display', 'block');
	}
});


function vlz_select(id){
	if( jQuery("#"+id+" input").prop("checked") ){
		jQuery("#"+id+" input").prop("checked", false);
		jQuery("#"+id).removeClass("vlz_check_select");
	}else{
		jQuery("#"+id+" input").prop("checked", true);
		jQuery("#"+id).addClass("vlz_check_select");
	}
}
jQuery(".vlz_checkbox_contenedor div").on("click", function(e){
	vlz_select( jQuery( this ).attr("id") );
});
jQuery(".vlz_sub_seccion_titulo").on("click", 
	function (){
		var con = jQuery(jQuery(this)[0].nextElementSibling);
		if( con.css("display") == "none" ){
			con.slideDown( "slow", function() { });
		}else{
			con.slideUp( "slow", function() { });
		}
	}
);
function vlz_top(){
	jQuery("html, body").animate({
        scrollTop: 0
    }, 500);
}

function vlz_tipo_ubicacion(){
	if( jQuery("#tipo_busqueda option:selected").val() == "mi-ubicacion" ){
		jQuery("#vlz_estados").css("display", "none");
		jQuery("#vlz_inputs_coordenadas").css("display", "block");
	}else{
		jQuery("#vlz_estados").css("display", "block");
		jQuery("#vlz_inputs_coordenadas").css("display", "none");
	}
}

jQuery(document).on("click", '.btnOpenPopupMap', function ( e ) {
	e.preventDefault();
	jQuery("body").css("overflow", "hidden");
	jQuery(".km-caja-resultados .km-columna-der").removeClass("mapAbsolute");
	jQuery(".km-caja-resultados .km-columna-der").css("top", 0);
	jQuery(".km-caja-resultados .km-columna-der").fadeIn("fast", function() {
		google.maps.event.trigger(map, 'resize');
		map.setZoom(4);
    	map.setCenter( new google.maps.LatLng(23.634501, -102.552784) );
    	jQuery( "#mapa" ).addClass("resize");
  	});
	
});

var markers = [];
var infos = [];
var map;

var oms = "";

function initMap() {
	
	map = new google.maps.Map(document.getElementById("mapa"), {
        zoom: 4,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
		scrollwheel: false,
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
								/*
								+'<div class="km-sellos maps">'
								+'    <div class="km-sellos"> '+servicios+' </div>'
								+'</div>'
								*/
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
				scrollwheel: false,
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

