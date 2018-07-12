jQuery(document).ready(function(){

    jQuery(".vlz_accion").on("click", function(e){
        var value = jQuery(this).attr("data-accion");
        if(value != ''){
             
        }
    });

    jQuery(".ver_reserva_init").on("click", function(e){
        jQuery(this).parent().parent().parent().addClass("vlz_desplegado");
    });

    jQuery(".ver_reserva_init_fuera").on("click", function(e){
        jQuery(this).parent().parent().addClass("vlz_desplegado");
    });

    jQuery(".ver_reserva_init_closet").on("click", function(e){

        jQuery(this).parent().removeClass("vlz_desplegado");
    });

});