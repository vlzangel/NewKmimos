function serviciosAnterior(_this){
    jQuery(".servicios_principales_box").animate({ left: "0px"});
}

function serviciosSiguiente(_this){
    jQuery(".servicios_principales_box").animate({ left: "-34%"});
}

var hasGPS=false;
var crd;
var prueba_ubicacion = false;
var SELECCIONAR_PAQUETES = false;

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

function calcular(){
    var ini = String( jQuery('#checkin').val() ).split("/");
    inicio = ini[2]+"-"+ini[1]+"-"+ini[0];
    var fin = String( jQuery('#checkout').val() ).split("/");
    fin = fin[2]+"-"+fin[1]+"-"+fin[0];
    var inicio = new Date( inicio ).getTime();
    var fin = new Date( fin ).getTime();
    var dias = (fin-inicio)/86400000;
    dias++;
    SELECCIONAR_PAQUETES = dias; 
}

function form_is_valid(){
    var sin_error = 0;
    if( jQuery("#checkin").val() != "" && jQuery("#checkout").val() != "" ){
        jQuery(".fechas_container").removeClass("error_fecha");
    }else{
        jQuery(".fechas_container").addClass("error_fecha");
        sin_error++;
    }
    var dias_seleccionados = 0;
    jQuery(".input_check_box input").each(function(i, v){
        if( jQuery(this).prop("checked") ){
            dias_seleccionados++;
        }
    });
    if( dias_seleccionados == 0 ){
        jQuery(".dias_msg").addClass("error_dias");
        sin_error++;
    }else{
        jQuery(".dias_msg").removeClass("error_dias");
    }
    if( sin_error == 0 ){
        return true;
    }else{
        return false;
    }
}

jQuery( document ).ready(function() {

    jQuery("#quiero_ser_menu").html("Quiero ser Paseador");
    jQuery("#buscar_cuidador_btn_nav span").html("Buscar Paseador");

    jQuery("#boton_buscar").on("click", function(e){
        if( form_is_valid() ){
            var seccionados = 0;
            jQuery(".input_check_box input").each(function(i, v){
                if( jQuery(this).prop("checked") ){ seccionados++; }
            });
            if( ( jQuery("#paquete").val() == "" && ( SELECCIONAR_PAQUETES >= 7 ) ) || ( seccionados == 7 )  ){
                jQuery('body,html').stop(true, true).animate({ scrollTop: jQuery('#paquetes').offset().top }, 1000);
            }else{
                jQuery("#buscador").submit();
            }
        }
        e.preventDefault();
    });

    jQuery("#boton_ver_paquetes").on("click", function(e){
        jQuery('body,html').stop(true, true).animate({ scrollTop: jQuery('#paquetes').offset().top }, 1000);
        e.preventDefault();
    });

    jQuery(".btn_paq").on("click", function(e){
        jQuery("#paquete").val( jQuery(this).attr("data-id") );
        jQuery(".input_radio").prop("checked", false);
        jQuery("#paq_"+jQuery(this).attr("data-id")+"_radio").prop("checked", true);
        if( form_is_valid() ){
            jQuery("#buscador").submit();
        }else{
            if( parseInt( jQuery("body").width() ) > 768 ){ 
                jQuery('body,html').stop(true, true).animate({ scrollTop: jQuery('#banner_home').offset().top }, 1000);
            }else{
                jQuery('body,html').stop(true, true).animate({ scrollTop: jQuery('#buscador').offset().top }, 1000);
            }
        }
    });

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
                    jQuery('#latitud').val(crd.latitude);
                    jQuery('#longitud').val(crd.longitude);
                    var geocoder = new google.maps.Geocoder();
                    var latlng = {lat: parseFloat(crd.latitude), lng: parseFloat(crd.longitude)};
                    geocoder.geocode({'location': latlng}, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            var address = results[0]['formatted_address'];
                            jQuery(".ubicacion_txt").val(address);
                            jQuery(".ubicacion").val("");

                            jQuery(".icon_left").removeClass("fa-spinner fa-spin");

                            jQuery(".mi_ubicacion").attr("src", HOME+"/recursos/img/HOME/SVG/GPS_On.svg");
                            jQuery(".mi_ubicacion").css("display", "inline-block");
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
            },{
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