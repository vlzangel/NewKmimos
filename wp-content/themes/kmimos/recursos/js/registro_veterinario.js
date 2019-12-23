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

});
