function serviciosAnterior(_this){
    jQuery(".servicios_principales_box").animate({ left: "0px"});
}

function serviciosSiguiente(_this){
    jQuery(".servicios_principales_box").animate({ left: "-34%"});
}

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

function show_hiden_arrow(){
    var paso = parseInt( jQuery(".destacados_box").attr("data-paso") );
    if( parseInt( jQuery("body").width() ) > 768 ){
        var final = parseInt( jQuery(".destacados_box").attr("data-final_pc") );
    }else{
        var final = parseInt( jQuery(".destacados_box").attr("data-final_movil") );
    }

    if( paso == 0 ){ jQuery(".seccion_destacados_izq").css("display", "none"); }else{ jQuery(".seccion_destacados_izq").css("display", "block"); }

    if( paso == final ){ jQuery(".seccion_destacados_der").css("display", "none"); }else{ jQuery(".seccion_destacados_der").css("display", "block"); }
}

function mover_destacado(dir){
    switch(dir){
        case 'izq':
            var paso = parseInt( jQuery(".destacados_box").attr("data-paso") );
            if( paso > 0 ){ paso--; }
            jQuery(".destacados_box").attr("data-paso", paso);
            jQuery(".destacados_box > div").animate({left: (-1*(paso*33.3333334))+"%" }, 1000);
        break;
        case 'der':
            if( parseInt( jQuery("body").width() ) > 768 ){
                var final = parseInt( jQuery(".destacados_box").attr("data-final_pc") );
            }else{
                var final = parseInt( jQuery(".destacados_box").attr("data-final_movil") );
            }
            var paso = parseInt( jQuery(".destacados_box").attr("data-paso") );
            if( paso < final ){ paso++; }
            jQuery(".destacados_box").attr("data-paso", paso);
            jQuery(".destacados_box > div").animate({left: (-1*(paso*33.3333334))+"%" }, 1000);
        break;
    }
    show_hiden_arrow();
}

jQuery( document ).ready(function() {

    jQuery(".seccion_destacados_izq").on('click', function(e){
        console.log("izq");
        mover_destacado("izq");
    });
    jQuery(".seccion_destacados_der").on('click', function(e){
        console.log("der");
        mover_destacado("der");
    });
    show_hiden_arrow();

    jQuery("#boton_buscar").on("click", function(e){

        evento_google_kmimos("buscar_home");
        evento_fbq_kmimos('buscar_home');

        var errores = 0;
        if( jQuery("#checkin").val() != "" && jQuery("#checkout").val() != "" ){
            jQuery(".fechas_container").removeClass("error_fecha");
        }else{
            errores++;
            jQuery(".fechas_container").addClass("error_fecha");
        }
        var seleccionados = 0;
        jQuery(".servicios_principales_box input").each(function(i, v){
            if( jQuery(this).prop("checked") ){ seleccionados++; }
        });
        if( seleccionados == 0 ){
            errores++;
            jQuery(".error_principales").css("display", "block");
        }else{
            jQuery(".error_principales").css("display", "none");
        }
        if( errores == 0 ){
            jQuery('#popup-servicios-new').modal('show');
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
            },
            {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 0
            }
        );
    });


    jQuery('[data-target="patitas-felices"]').on('click', function(){

        if( jQuery('#cp_email').val() != '' && jQuery('#cp_nombre').val() != ''){

            jQuery('#msg').html('Enviando solicitud.');
            jQuery('#cp_loading').removeClass('hidden');
            jQuery('#cp_loading').fadeIn(1500);

            var urls = RAIZ+"/landing/list-subscriber.php?source=kmimos-mx-clientes-referidos&email="+jQuery('#cp_email').val();
            jQuery.get( urls, function(e){});

            var urluser = RAIZ+"landing/registro-usuario.php?email="+jQuery('#cp_email').val()+"&name="+jQuery('#cp_nombre').val()+"&referencia=kmimos-home";
            jQuery.get( urluser, function(e){

                var redirect = RAIZ+"/referidos/compartir/?e="+jQuery('#cp_email').val();
                switch (jQuery.trim(e)){
                    case '0':
                        jQuery('#msg').html('¡No pudimos completar su solicitud!');
                        break;
                    case '1':
                        jQuery('#msg').html('¡Felicidades, ya formas parte de nuestro Club!');
                        jQuery('a[data-redirect="patitas-felices"]').attr('href', redirect);
                        jQuery('a[data-redirect="patitas-felices"]').click();
                        window.open( redirect, '_blank' );
                        break;
                    case '2':
                        jQuery('#msg').html('¡Ya formas parte de nuestro Club!');
                        jQuery('a[data-redirect="patitas-felices"]').attr('href', redirect);
                        jQuery('a[data-redirect="patitas-felices"]').click();
                        window.open( redirect, '_blank' );
                        break;
                    default:
                        jQuery('#msg').html('Registro: No pudimos completar su solicitud, intente nuevamente');
                        jQuery('#cp_loading').addClass('hidden');
                        break;
                }
                setTimeout(function() {
                    jQuery('#cp_loading').fadeOut(1500);
                },3000);
            })
            .fail(function() {
                jQuery('#msg').html('Registro: No pudimos completar su solicitud, intente nuevamente');
                jQuery('#cp_loading').addClass('hidden');
            }); 

        }else{
           
            var danger_color =  '#c71111';
            var border_color =  '#c71111';
            var visible = 'visible';
 
            jQuery('[data-error="cp_nombre"]').css('visibility', visible);
            jQuery('[data-error="cp_nombre"]').css('color', danger_color);
            jQuery('[data-error="cp_nombre"]').html(msg);
            jQuery('[name="cp_nombre"]').css('border-bottom', '1px solid ' + border_color);
            jQuery('[name="cp_nombre"]').css('color', danger_color);

            jQuery('[data-error="cp_email"]').css('visibility', visible);
            jQuery('[data-error="cp_email"]').css('color', danger_color);
            jQuery('[data-error="cp_email"]').html(msg);
            jQuery('[name="cp_email"]').css('border-bottom', '1px solid ' + border_color);
            jQuery('[name="cp_email"]').css('color', danger_color);

        }
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