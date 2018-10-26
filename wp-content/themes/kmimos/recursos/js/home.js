var hasGPS=false;
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

function ancla_form() {
  	let ww = jQuery(window).scrollTop();
  	if ( jQuery('#buscar').offset().top <= 378 ) {
    	jQuery('body,html').stop(true,true).animate({ scrollTop: jQuery('#buscar').offset().top }, 1000);
  	} else {
    	jQuery('body,html').stop(true,true).animate({ scrollTop: 200 }, 1000);
  	}
}

jQuery( document ).ready(function() {

    jQuery("#boton_buscar").on("click", function(e){
        if( jQuery("#checkin").val() != "" && jQuery("#checkout").val() != "" ){
            jQuery(".fechas_container").removeClass("error_fecha");
            jQuery('#popup-servicios-new').modal('show');
        }else{
            jQuery(".fechas_container").addClass("error_fecha");
        }
        e.preventDefault();
    });

    jQuery("#agregar_servicios").on("click", function(e){
        jQuery("#buscador").submit();
    });

    jQuery("#buscar_no").on("click", function(e){
        jQuery("#buscador").submit();
    });

    jQuery('#popup-servicios-new').on('hidden.bs.modal', function () {
        jQuery("#buscar_no").click();
    });

    jQuery("#mi_ubicacion").on("click", function(e){

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

                    jQuery('#latitud').val(crd.latitude);
                    jQuery('#longitud').val(crd.longitude);

                    var geocoder = new google.maps.Geocoder();

                    var latlng = {lat: parseFloat(crd.latitude), lng: parseFloat(crd.longitude)};
                    geocoder.geocode({'location': latlng}, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            var address = results[0]['formatted_address'];
                            jQuery("#ubicacion_txt").val(address);
                            jQuery("#ubicacion").val("");

                            jQuery(".icon_left").removeClass("fa-spinner fa-spin");

                            jQuery("#mi_ubicacion").attr("src", HOME+"/recursos/img/HOME/SVG/GPS_On.svg");
                            jQuery("#mi_ubicacion").css("display", "inline-block");
                        }
                    });
                /*}else{
                    jQuery( '[data-error="ubicacion"]' ).parent().addClass('has-error');
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

(function(d, s){
    map = d.createElement(s), e = d.getElementsByTagName(s)[0];
    map.async=!0;
    map.setAttribute("charset","utf-8");
    map.src="//maps.googleapis.com/maps/api/js?v=3&key=AIzaSyD-xrN3-wUMmJ6u2pY_QEQtpMYquGc70F8";
    map.type="text/javascript";
    e.parentNode.insertBefore(map, e);
})(document,"script");