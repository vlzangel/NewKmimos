var hasGPS=false;

(function($) {
    'use strict';

    $('.adicionales_button').on('click', function(){
        console.log( "Hola");
        if( $('.modal_servicios').css('display') == 'none' ){
            $('.modal_servicios').css('display', 'table');
        }else{
            $('.modal_servicios').css('display', 'none');
        }
    });

    $('#close_mas_servicios').on('click', function(){
        $('.modal_servicios').css('display', 'none');
    });

    if (navigator.geolocation) {
        // navigator.geolocation.getCurrentPosition(coordenadas);
    } else {
        $('#selector_locacion').removeClass('hide');
        $('#selector_coordenadas').addClass('hide');
        $('#selector_tipo').addClass('hide');
    }
    if(navigator.platform.substr(0, 2) == 'iP') $('html').addClass('iOS');
    $(function(){
        var edos = $('#estado_cuidador').val();
        
        function cargar_municipios(CB){
            var estado_id = jQuery('#estado_cuidador').val();       
            if( estado_id != '' ){
                jQuery.getJSON( 
                    URL_MUNICIPIOS, 
                    {estado: estado_id} 
                ).done(
                    function( data, textStatus, jqXHR ) {
                        var html = "<option value=''>Seleccione un municipio</option>";
                        if( data != undefined ){
                            jQuery.each(data, function(i, val) {
                                html += '<option value='+val.id+'>'+val.name+'</option>';
                            });
                            jQuery('#municipio_cuidador').html(html);
                        }

                        if( CB != undefined) {
                            CB();
                        }
                    }
                ).fail(
                    function( jqXHR, textStatus, errorThrown ) {
                        console.log( 'Error: ' +  errorThrown );
                    }
                );
            }
        }
        jQuery('#estado_cuidador').on('change', function(e){
            cargar_municipios();
        });
        /*cargar_municipios(function(){
            jQuery("#municipio_cuidador > option[value='"+jQuery('#municipio_cache').val()+"']").attr('selected', 'selected');
        });*/
        jQuery('#municipio_cuidador').on('change', function(e){
            jQuery('#municipio_cache').attr('value', jQuery('#municipio_cuidador').val() );
        });

        $('.boton_servicio > input').on('change',function(e){
            var activo = $(this).prop('checked');
            if(activo) $( this ).parent().addClass('check_select');
            else $( this ).parent().removeClass('check_select');
        });

        $('label > input').on('change',function(e){
            var activo = $(this).prop('checked');
            $( ".por_ubicacion" ).removeClass('input_select');
            if(activo) $( this ).parent().addClass('input_select');

            switch( $(this).parent().attr("for") ){
                case "mi-ubicacion":
                    $(".selects_ubicacion_container").hide();
                break;
                case "otra-localidad":
                    $(".selects_ubicacion_container").show();
                break;
            }

        });

        $("#boton_buscar").on("click", function(e){
            $("#buscador").submit();
        });

        $("#close_video").on("click", function(e){
            close_video();
        });

    });
})(jQuery);

function coordenadas(position){
    if(position.coords.latitude != '' && position.coords.longitude != '') {
        document.getElementById('latitud').value=position.coords.latitude;
        document.getElementById('longitud').value=position.coords.longitude;        
    } else {
        var mensaje = 'No es posible leer su ubicación,\nverifique si su GPS está encendido\ny vuelva a recargar la página.'+$('#latitud').val()+','+$('#longitud').val();
        alert(mensaje);        
    }
}

function show_video(){
    $(".modal_video iframe").attr("src", "https://www.youtube.com/embed/xjyAXaTzEhM?rel=0&showinfo=0&autoplay=1");
    $(".modal_video").css("display", "table");
}

function close_video(){
    $(".modal_video iframe").attr("src", "");
    $(".modal_video").hide();
}