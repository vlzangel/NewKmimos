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
        var current = parseInt( jQuery(this).parent().attr("data-step-current") );
        if( current < 5 ){
            current += 1;
            change_step(current, true);
            
        }
    });

    jQuery("#kv_btn_registro_atras").on('click', function(e){
        var current = parseInt( jQuery(this).parent().attr("data-step-current") );
        if( current > 1 ){
            current -= 1;
            change_step(current, false);
            jQuery("#kv_btn_registro").html("Continuar");
        }
    });

    jQuery(".kv-registro-nav > div").on('click', function(e){
        var id = parseInt( jQuery(this).data("id") );
        var current = parseInt( jQuery("#popup-registro-veterinario .modal-footer").attr("data-step-current") );
        if( id <= current ){
            change_step(id, false);
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


function change_step(current, change_active, change_current){
    
    jQuery(".kv-registro-nav > div").removeClass("step_current");
    jQuery("#tab_step_"+current).addClass("step_current");

    jQuery(".step").removeClass("step_active");
    jQuery("#step_"+current).addClass("step_active");
    jQuery("#tab_step_"+current).addClass("active");

    if( current > 1 ){
        jQuery("#kv_btn_registro_atras").css("display", "inline-block");
    }
    if( current == 5 ){
        jQuery("#kv_btn_registro").html("Finalizar");
    }
    if( current == 1 ){
        jQuery("#kv_btn_registro_atras").css("display", "none");
    }

    if( change_current ){
        jQuery("#popup-registro-veterinario .modal-footer").attr("data-step-current", current);
    }

    if( change_active ){
        jQuery(".kv-registro-nav > div").removeClass("active");
        jQuery(".kv-registro-nav > div").each(function(i, v){
            var id = jQuery(this).data("id");
            if( id <= current ){
                jQuery(this).addClass("active");
            }
        });
    }
}