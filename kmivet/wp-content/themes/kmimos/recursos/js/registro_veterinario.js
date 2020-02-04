jQuery( document ).ready(function() {

    jQuery("[name='kv_email']").on('change', function(e){
        jQuery.post(
            HOME+'/procesos/medicos/REGISTRO/existe_registro.php',
            { email: jQuery("[name='kv_email']").val() },
            function( res ){
                if ( res.status === false ) {
                    jQuery("[name='kv_email_no_usado']").prop('checked', true);
                }else{
                    jQuery("[name='kv_email_no_usado']").removeAttr('checked');
                }
            },
            'json'
        );
    });

    jQuery('[name="kv_estado"]').on("change", function(e){
        jQuery.post(
            HOME+'/procesos/medicos/GENERALES/provincias.php',
            { state: jQuery('[name="kv_estado"]').val() },
            function(provincias){
                jQuery('[name="kv_delegacion"]').html(provincias);
                jQuery('[name="kv_colonia"]').html("<option value=''>Seleccione...</option>");
            }
        );
    });

    jQuery('[name="kv_delegacion"]').on("change", function(e){
        jQuery.post(
            HOME+'/procesos/medicos/GENERALES/colonias.php',
            {
                state: jQuery('[name="kv_estado"]').val(),
                provincia: jQuery('[name="kv_delegacion"]').val()
            },
            function(colonias){
                jQuery('[name="kv_colonia"]').html(colonias);
            }
        );
    });

    jQuery(".vlz_item_servicios").on('click', function(e){
        jQuery(".vlz_item_servicios").removeClass('active');
        jQuery(this).addClass('active');
        jQuery(".vlz_info_servicios_img").css('background-image', 'url('+jQuery(this).data('img')+')' );
        jQuery(".vlz_info_servicios_data > div").html( jQuery(this).data('tit') );
        jQuery(".vlz_info_servicios_data > p").html( jQuery(this).data('desc') );
    });

    jQuery(".preguntas_container > div > div").on("click", function(e){
        jQuery(".preguntas_container > div > div p").css('display', 'none');
        jQuery(this).find('p').css('display', 'block');
    });

    jQuery("#kv_btn_registro").on('click', function(e){
        var current = parseInt( jQuery(this).parent().attr("data-step-show") );
        /*if( current < 5 ){
            current += 1;
            change_step(current);
        }else{*/
            var validacion = kv_validar(current);
            if( validacion.length == 0 ){
                if( current < 5 ){
                    current += 1;
                    change_step(current);
                }else{
                    completar_registro();
                }
            }
        // }
    });

    jQuery("#kv_btn_registro_atras").on('click', function(e){
        var current = parseInt( jQuery(this).parent().attr("data-step-show") );
        if( current > 1 ){
            current -= 1;
            change_step(current);
            jQuery("#kv_btn_registro").html("Continuar");
        }
    });

    jQuery(".kv-registro-nav > div").on('click', function(e){
        var id = parseInt( jQuery(this).data("id") );
        var current = parseInt( jQuery("#popup-registro-veterinario .modal-footer").attr("data-step-show") );
        if( jQuery(this).hasClass('active') ){
            var validacion = kv_validar(current);
            if( validacion.length == 0 ){
                change_step(id);
            }
        }
    });

    jQuery("[name='kv_tiene_otra_especialidad']").on('change', function(e){
        if( jQuery(this).val() == 'Si' ){
            jQuery(".kv_red_otra_especialidad").addClass('kv_visibility');
            jQuery(".kv_red_otra_especialidad").removeClass('kv_visibility_hidden');
        }else{
            jQuery(".kv_red_otra_especialidad").addClass('kv_visibility_hidden');
            jQuery(".kv_red_otra_especialidad").removeClass('kv_visibility');
        }
    });

    jQuery("[name='kv_red_seguro']").on('change', function(e){
        if( jQuery(this).val() == 'Si' ){
            jQuery(".kv_red_seguros").addClass('kv_visibility');
            jQuery(".kv_red_seguros").removeClass('kv_visibility_hidden');
        }else{
            jQuery(".kv_red_seguros").addClass('kv_visibility_hidden');
            jQuery(".kv_red_seguros").removeClass('kv_visibility');
        }
    });

    jQuery("[name='kv_seguro_responsabilidad']").on('change', function(e){
        if( jQuery(this).val() == 'Si' ){
            jQuery(".kv_datos_seguro_civil").css('display', 'block');
        }else{
            jQuery(".kv_datos_seguro_civil").css('display', 'none');
        }
    });

    get_ubicacion();
});

function completar_registro(){
    jQuery.post(
        HOME+'/procesos/medicos/REGISTRO/registro.php',
        jQuery("#popup-registro-veterinario form").serialize(),
        function(res){
            console.log( res );
            if( res.status ){
                jQuery(".kv-registro-nav").css("display", "none");
                jQuery(".modal-footer").css("display", "none");
                change_step(6);
            }else{
                jQuery(".kv-registro-nav").css("display", "flex");
                alert(res.error);
            }
        },
        'json'
    );
}

function get_ubicacion(){
    navigator.geolocation.getCurrentPosition( function(pos) {
        crd = pos.coords;
        jQuery('[name="lat"]').val( crd.latitude );
        jQuery('[name="lng"]').val( crd.longitude );
    }, 
    function error(err) {
        if( err.message == 'User denied Geolocation' ){
            alert("Estimado usuario, para poder acceder a esta función, es necesario desbloquear a kmivet en la configuración de ubicación de su dispositivo.");
        }else{
            alert(err.message);
        }
    },{
        enableHighAccuracy: true,
        timeout: 5000,
        maximumAge: 0
    });
}

function change_step(current){
    var actual = parseInt( jQuery("#popup-registro-veterinario .modal-footer").attr("data-step-current") );
    jQuery(".kv-registro-nav > div").removeClass("step_current");
    jQuery("#tab_step_"+current).addClass("step_current");
    jQuery(".step").removeClass("step_active");
    jQuery("#step_"+current).addClass("step_active");
    jQuery("#tab_step_"+current).addClass("active");
    if( current > 1 ){
        jQuery("#kv_btn_registro_atras").css("display", "inline-block");
    }else{
        jQuery("#kv_btn_registro_atras").css("display", "none");
    }
    if( current == 5 ){
        jQuery("#kv_btn_registro").html("Finalizar");
    }else{
        jQuery("#kv_btn_registro").html("Continuar");
    }
    jQuery("#popup-registro-veterinario .modal-footer").attr("data-step-show", current);
    if( actual > current ){
        jQuery("#popup-registro-veterinario .modal-footer").attr("data-step-current", current);
        jQuery(".kv-registro-nav > div").removeClass("active");
        jQuery(".kv-registro-nav > div").each(function(i, v){
            var id = jQuery(this).data("id");
            if( id <= current ){
                jQuery(this).addClass("active");
            }
        });
    }
}