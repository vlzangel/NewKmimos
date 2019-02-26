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


function show_hiden_arrow(){

}

function resize_carrusel(_w){
    if( _w == "" ){
        var w = parseInt( jQuery(".destacados_box > div").width() );
        if( w > 768 ){
            var h = 0.33333334;
        }else{ var h = 1; }
        jQuery(".destacados_item").css("width", w*h);
    }else{
        jQuery(".destacados_item").css("width", _w);
    }
    if( parseInt( jQuery("body").width() ) > 768 ){
        jQuery(".destacados_item").css("height", jQuery(".destacados_container").css("height") );
    }else{
        jQuery(".destacados_item").css("height", "auto" );
    }
}

function mover_destacado(dir){
    if( parseInt( jQuery("body").width() ) > 768 ){
        var h = 33.333333334;
    }else{
        var h = 100;
    }
    switch(dir){
        case 'izq':
            var paso = parseInt( jQuery(".destacados_box").attr("data-paso") );
            if( paso > 0 ){ paso--; }
            jQuery(".destacados_box").attr("data-paso", paso);
            jQuery(".destacados_box > div > div").animate({left: (-1*(paso*h))+"%" }, 1000);
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
            jQuery(".destacados_box > div > div").animate({left: (-1*(paso*h))+"%" }, 1000);
        break;
    }
    show_hiden_arrow();
}

function buscar( campo ){
    if( campo != undefined ){
        if( campo == "ubicacion" ){
            jQuery(".latitud").val("");
            jQuery(".longitud").val("");
        }
        jQuery.post(
            HOME+'procesos/busqueda/busqueda_personalizada.php',
            jQuery("#buscador").serialize(),
            function(respuesta){

                var desta_str = '';

                var items = respuesta[2].length;
                var final_pc = items-3;
                var final_movil = items-1;

                if( respuesta[2].length > 0){
                    jQuery.each(respuesta[2], function(i, cuidador){
                        var BANDERIN = '';
                        if( cuidador['atributos']['supercuidador'] == "1" ){
                            BANDERIN = '<img class="img_destacado_2" src="'+HOME+'/recursos/img/PERSONALIZADA/SVG/Banderin_top_cuidador.svg" />';
                        }
                        desta_str += 
                        '<div class="destacados_item">'+
                            '<div class="img_destacado" style="background-image: url('+cuidador.img+');">'+
                                BANDERIN+
                                '<img class="img_patitas" src="'+HOME+'/recursos/img/PERSONALIZADA/SVG/icono_kmimos.svg" />'+
                            '</div>'+
                            '<div class="datos_destacado_containder">'+
                                '<div class="datos_top_destacado_containder">'+
                                    '<div class="avatar_destacado" style="background-image: url('+cuidador.cliente+');"></div>'+
                                    '<div class="nombre_destacado">'+
                                        '<a href="'+cuidador.link+'">'+cuidador.nombre+'</a>'+
                                        '<span>'+cuidador.experiencia+'</span>'+
                                    '</div>'+
                                    '<div class="ranking_destacado">'+cuidador.ranking+'</div>'+
                                    '<div class="ubicacion">'+cuidador.ubicacion+'</div>'+
                                '</div>'+
                                '<div class="datos_bottom_destacado_container">'+
                                    cuidador.precio+
                                '</div>'+
                            '</div>'+
                            '<a href="'+cuidador.link+'" class="boton">Ver perfil</a>'+
                        '</div>';
                    });
                }else{
                    desta_str += 
                    '<div class="destacados_item" height=>'+
                        '<table style="width: 100%; height: 100%;">'+
                            '<tr>'+
                                '<td class="sin_resultados">'+
                                    'Lo sentimos, no hay cuidadores que cumplan con tus preferencias o características de tus mascotas. Ajusta los filtros personalizados o <span>llámanos al 01 800 9 KMIMOS</span> (01 800 9 564667), donde <span>Con gusto te ayudaremos a encontrar un Cuidador ideal</span> para tu peludo.'+
                                '</td>'+
                            '</tr>'+
                        '</table>'+
                    '</div>';
                }

                jQuery(".destacados_box").attr("data-paso", 0);
                jQuery(".destacados_box").attr("data-final_pc", final_pc);
                jQuery(".destacados_box").attr("data-final_movil", final_movil);

                jQuery(".destacados_box > div > div").html( desta_str );

                if( respuesta[2].length > 0){
                    resize_carrusel("");
                }else{
                    resize_carrusel("100%");
                }

            }, 'json'
        );
    }
}

jQuery( document ).ready(function() {

    jQuery("#btn_aplicar_filtros").on("click", function(e){
        e.preventDefault();

        jQuery("body").css("overflow", "auto");
        jQuery("#buscador").animate({
            "top": "-100%"
        }, 1000);

        setTimeout(function(e){
            jQuery("#banner_home").css("display", "none");
        }, 1000);
    });

    jQuery("#aplicar_btn").on("click", function(e){
        e.preventDefault();
        jQuery("#banner_home").css("display", "block");
        jQuery("body").css("overflow", "hidden");

        jQuery("#buscador").animate({
            "top": "10px"
        }, 1000);

    });

    jQuery("#buscador i.fa.fa-times").on("click", function(e){
        e.preventDefault();

        jQuery("body").css("overflow", "auto");
        jQuery("#buscador").animate({
            "top": "-100%"
        }, 1000);

        setTimeout(function(e){
            jQuery("#banner_home").css("display", "none");
        }, 1000);

    });

    jQuery(window).on('resize', function () {
        resize_carrusel();
    });

    resize_carrusel();

    jQuery("#buscador input").on("change", function(e){ 
        // if( parseInt( jQuery("body").width() ) > 768 ){ 
            buscar( jQuery(this).attr("id") ); 
        // }
    });

    buscar( "" ); 

    jQuery("#buscador").on("submit", function(e){
        e.preventDefault();
    });

    jQuery(".seccion_destacados_izq").on('click', function(e){
        mover_destacado("izq");
    });
    jQuery(".seccion_destacados_der").on('click', function(e){
        mover_destacado("der");
    });
    show_hiden_arrow();

    jQuery("#boton_buscar").on("click", function(e){

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

});

(function(d, s){
    map = d.createElement(s), e = d.getElementsByTagName(s)[0];
    map.async=!0;
    map.setAttribute("charset","utf-8");
    map.src="//maps.googleapis.com/maps/api/js?v=3&key=AIzaSyD-xrN3-wUMmJ6u2pY_QEQtpMYquGc70F8";
    map.type="text/javascript";
    e.parentNode.insertBefore(map, e);
})(document,"script");