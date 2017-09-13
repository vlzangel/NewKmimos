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
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    }
}
function showPosition(position) {
	if( jQuery("#tipo_busqueda option:selected").val() == "mi-ubicacion" ){
		jQuery("#latitud").val(position.coords.latitude);
	    jQuery("#longitud").val(position.coords.longitude);
	}
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

var markers = [];
var infos = [];
var map;
function initMap() {
	if( pines.length > 0 ){
		map = new google.maps.Map(document.getElementById("mapa"), {
	        zoom: 3,
	        mapTypeId: google.maps.MapTypeId.ROADMAP,
	        fullscreenControl: true,
			scrollwheel: false
	    });

	    var oms = new OverlappingMarkerSpiderfier(map, { 
	        markersWontMove: true,
	        markersWontHide: true,
	        basicFormatEvents: true
	  	});

	    var bounds = new google.maps.LatLngBounds();
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
		        	servicios += '<img src="http://mx.kmimos.dev/wp-content/themes/kmimos/images/new/icon/'+servicio.img+'" height="40" title="'+servicio.titulo+'"> ';
		        });
	        }

	        var rating = "";
	        if( cuidador["rating"] != undefined && cuidador["rating"].length > 0 ){
		        jQuery.each(cuidador["rating"], function( index, xrating ) {
		        	if( xrating == 1 ){
		        		rating += '<a href="#" class="active"></a>';
		        	}else{
		        		rating += '<a href="#"></a>';
		        	}
		        });
	        }

	        infos[index] = new google.maps.InfoWindow({ 
	            content: 	'<h1 class="maps">'+cuidador.nom+'</h1>'
							+'<p>'+cuidador.exp+' a&ntilde;o(s) de experiencia</p>'
							+'<div class="km-ranking">'
							+	rating
							+'</div>'
							+'<div class="km-sellos maps">'
							+'    <div class="km-sellos"> '+servicios+' </div>'
							+'</div>'
							+'<div class="km-opciones maps">'
							+'    <div class="precio">MXN $ '+cuidador.pre+'</div>'
							+'    <a href="'+cuidador.url+'" class="km-btn-primary-new stroke">CON&Oacute;CELO +</a>'
							+'    <a href="'+cuidador.url+'" class="km-btn-primary-new basic">RESERVA</a>'
							+'</div>'
	        });

	        markers[index].addListener("click", function(e) { 
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
			scrollwheel: false
	    });
   	}

		    
}
(function(d, s){
	map = d.createElement(s), e = d.getElementsByTagName(s)[0];
	map.async=!0;
	map.setAttribute("charset","utf-8");
	map.src="//maps.googleapis.com/maps/api/js?v=3&key=AIzaSyD-xrN3-wUMmJ6u2pY_QEQtpMYquGc70F8&callback=initMap";
	map.type="text/javascript";
	e.parentNode.insertBefore(map, e);
})(document,"script");

