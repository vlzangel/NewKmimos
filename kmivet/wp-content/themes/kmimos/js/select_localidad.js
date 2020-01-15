var intervalo = 0;
var intervalo_blur = 0;
var cant = 0;
var vlz_primer_item_localidad = '';

jQuery(document).ready(function(){

    jQuery(".ubicacion_txt").on("keyup", function ( e ) {
        var txt = String(jQuery(this).val()).toLowerCase();
        var code = e.which;

        jQuery(".cerrar_list_box").css("display", "block");

        if( code == 13 ){
            jQuery(".ubicacion_txt").val( vlz_primer_item_localidad.html() );
            jQuery(".ubicacion").val( vlz_primer_item_localidad.attr("value") );
            jQuery(".ubicacion").attr( "data-value", vlz_primer_item_localidad.attr("data-value") );
            jQuery(".ubicacion").attr( "data-txt", vlz_primer_item_localidad.html() );
            jQuery( ".cerrar_list_box" ).css("display", "none");
            jQuery(".latitud").val( "" );
            jQuery(".longitud").val( "" );
            buscar("ubicacion");
        }else{
            if( txt.trim() != "" ){
                console.log( code );
                var arr = [
                    37, 38, 39, 40
                ];
                if( jQuery.inArray( code, arr ) == -1 ){
                    buscarLocacion(txt, jQuery(this));
                }

            }else{
                if(typeof buscar === 'function') {
                    jQuery(".ubicacion_txt").val( "" );
                    jQuery(".ubicacion").val( "" );
                    jQuery(".latitud").val( "" );
                    jQuery(".longitud").val( "" );
                    buscar("ubicacion");
                }
            }
        }
    });

    function buscarLocacion(txt, _this){
        clearInterval(intervalo);
        intervalo = setInterval(function(){ 
            
            jQuery.get(
                HOME+"procesos/buscar/locations.php",
                { s: txt.trim() },
                function(data){
                    jQuery(".ubicacion_list").html(data);
                    jQuery(".ubicacion_list li").css("display", "block");
                    vlz_primer_item_localidad = jQuery( ".ubicacion_list li:first-child" );
                    jQuery(".ubicacion_list li").on("click", function(e){
                        if( jQuery(this).html() != "X" ){

                            clearInterval(intervalo_blur);

                            console.log(jQuery(this).attr("data-id"));

                            jQuery(".ubicacion_txt").val( jQuery(this).html() );
                            jQuery(".ubicacion").val( jQuery(this).attr("data-id") );
                            jQuery(".ubicacion").attr( "data-value", jQuery(this).attr("data-value") );
                            jQuery(".ubicacion").attr( "data-txt", jQuery(this).html() );
                            jQuery( ".cerrar_list_box" ).css("display", "none");
                            jQuery(".latitud").val( "" );
                            jQuery(".longitud").val( "" );
                            
                            if(typeof buscar === 'function') { buscar("ubicacion"); }

                        } 
                        jQuery(".ubicacion_list").removeClass("ubicacion_list_hover");
                    });
                    jQuery(".ubicacion_txt").attr("readonly", false);
                }
            );

            clearInterval(intervalo); 
        }, 400);
            
    }

    jQuery(".ubicacion_txt").on("paste", function ( e ) {
        
    });

    jQuery(".cerrar_list").on("click", function(e){
        jQuery(this).parent().css("display", "none");
    });
    
});

