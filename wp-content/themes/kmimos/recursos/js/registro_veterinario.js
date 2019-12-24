jQuery( document ).ready(function() {

    jQuery(".vlz_item_servicios").on('click', function(e){
        jQuery(".vlz_item_servicios").removeClass('active');
        jQuery(this).addClass('active');
        jQuery(".vlz_info_servicios_img").css('background-image', 'url('+jQuery(this).data('img')+')' );
        jQuery(".vlz_info_servicios_data > div").html( jQuery(this).data('tit') );
        jQuery(".vlz_info_servicios_data > p").html( jQuery(this).data('desc') );
    });

    jQuery(".preguntas_container > div > div").on("click", function(e){
        console.log("Hola");
        jQuery(".preguntas_container > div > div p").css('display', 'none');
        jQuery(this).find('p').css('display', 'block');
    });

    jQuery("#kv_btn_registro").on('click', function(e){
        var current = parseInt( jQuery(this).parent().attr("data-step-current") );
        if( current < 5 ){
            current += 1;
            jQuery(this).parent().attr("data-step-current", current);
            jQuery(".step").removeClass("step_active");
            jQuery("#step_"+current).addClass("step_active");
            jQuery("#tab_step_"+current).addClass("active");
            if( current > 1 ){
                jQuery("#kv_btn_registro_atras").css("display", "inline-block");
            }
            if( current == 5 ){
                jQuery("#kv_btn_registro").html("Finalizar");
            }
        }
    });

    jQuery("#kv_btn_registro_atras").on('click', function(e){
        var current = parseInt( jQuery(this).parent().attr("data-step-current") );
        if( current > 1 ){
            jQuery("#tab_step_"+current).removeClass("active");
            current -= 1;
            jQuery(this).parent().attr("data-step-current", current);
            jQuery(".step").removeClass("step_active");
            jQuery("#step_"+current).addClass("step_active");
            if( current == 1 ){
                jQuery("#kv_btn_registro_atras").css("display", "none");
            }
            jQuery("#kv_btn_registro").html("Continuar");
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
        console.log( jQuery(this).val() );
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
