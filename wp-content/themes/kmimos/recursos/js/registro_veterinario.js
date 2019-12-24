jQuery( document ).ready(function() {

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
        var validacion = kv_validar(current);
        if( validacion.length == 0 ){
            if( current < 5 ){
                current += 1;
                change_step(current);
            }
        }
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
            jQuery(".kv_red_otra_especialidad").css('visibility', 'visible');
        }else{
            jQuery(".kv_red_otra_especialidad").css('visibility', 'hidden');
        }
    });

    jQuery("[name='kv_red_seguro']").on('change', function(e){
        if( jQuery(this).val() == 'Si' ){
            jQuery(".kv_red_seguros").css('visibility', 'visible');
        }else{
            jQuery(".kv_red_seguros").css('visibility', 'hidden');
        }
    });

    jQuery("[name='kv_seguro_responsabilidad']").on('change', function(e){
        if( jQuery(this).val() == 'Si' ){
            jQuery(".kv_datos_seguro_civil").css('display', 'block');
        }else{
            jQuery(".kv_datos_seguro_civil").css('display', 'none');
        }
    });
});

function kv_validar(step){
    var errores = [];
    jQuery("#step_"+step+" .validar").each(function(i, v){
        var valid = jQuery(this).attr('valid');
        if( valid != undefined ){
            var validaciones = valid.split("|");
            for (var i = 0; i < validaciones.length; i++) {
                switch( validaciones[ i ] ){
                    case 'required':
                        var parent = jQuery(this).parent();
                        parent.find('.kv_error').remove();
                        if( String(jQuery(this).val()).trim() == '' ){
                            errores.push( jQuery(this).attr('name') );
                            parent.append('<span class="kv_error">El campo es requerido</span>');
                            i = validaciones.length;
                        }
                    break;
                    case 'checked':
                        var parent = jQuery(this).parent();
                        parent.find('.kv_error').remove();
                        if( jQuery(this).prop('checked') ){
                            errores.push( jQuery(this).attr('name') );
                            parent.append('<span class="kv_error">Debes marcar esta casilla</span>');
                            i = validaciones.length;
                        }
                    break;
                    case 'email':
                        var parent = jQuery(this).parent();
                        parent.find('.kv_error').remove();
                        if( !/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i.test( String(jQuery(this).val()).trim() ) ){
                            errores.push( jQuery(this).attr('name') );
                            parent.append('<span class="kv_error">El formato del correo es incorrecto</span>');
                        }
                    break;
                }
            }
        }
    });
    return errores;
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