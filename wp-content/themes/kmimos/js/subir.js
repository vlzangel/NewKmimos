var IMGS = 0;var IMGS_CONT_INTERNO = 0;
var ERRORES_IMGS = 0;
function subirImgs(evt){
	var files = evt.target.files;

    ERRORES_IMGS = 0;

    jQuery.each( files, function(index, FILE){

        if( IMGS < 4 ){

            IMGS++;

            getRealMime(files[index]).then(function(MIME){

                if( MIME.match("image.*") ){

                    jQuery('#cargar_ico').removeClass("fa-plus");
                    jQuery('#cargar_ico').addClass("fa-spinner");

                    var reader = new FileReader();
                    reader.onload = (function(theFile) {
                        return function(e) {
                            redimencionar(e.target.result, function(img_reducida){

                                IMGS_CONT_INTERNO++;

                                jQuery("#img_container").append( d(
                                    "<div class='img_box'>"+
                                        "<div class='img_box_interno'>"+
                                            "<img src='"+img_reducida+"' class='img_vista' id='img_subir_"+IMGS_CONT_INTERNO+"' data-index="+IMGS_CONT_INTERNO+" >"+
                                            '<i class="fa fa-minus img_quitar" aria-hidden="true"></i>'+
                                            '<i class="fa fa-undo img_rotar_izq" data-id="img_subir_'+IMGS_CONT_INTERNO+'" aria-hidden="true"></i>'+
                                            '<i class="fa fa-repeat img_rotar_der" data-id="img_subir_'+IMGS_CONT_INTERNO+'" aria-hidden="true"></i>'+
                                        "</div>"+
                                    "</div>"
                                ) );

                                jQuery('#cargar_ico').removeClass("fa-spinner");
                                jQuery('#cargar_ico').addClass("fa-plus");

                                jQuery( ".img_quitar" ).unbind("click");
                                jQuery( ".img_quitar" ).bind( "click", function() {
                                    jQuery(this).parent().parent().remove();
                                    IMGS--;
                                });

                                jQuery( ".img_rotar_izq" ).unbind("click");
                                jQuery( ".img_rotar_izq" ).bind( "click", function() {
                                    rotar_img("left", jQuery(this).attr("data-id") );
                                });

                                jQuery( ".img_rotar_der" ).unbind("click");
                                jQuery( ".img_rotar_der" ).bind( "click", function() {
                                    rotar_img("right", jQuery(this).attr("data-id") );
                                });

                                if( ERRORES_IMGS > 0 ){
                                    jQuery("#img_msg").addClass("img_msg_show");
                                }else{
                                    jQuery("#img_msg").removeClass("img_msg_show");
                                    jQuery("#img_msg").html("");
                                }

                            });      
                        };
                   })(files[index]);
                   reader.readAsDataURL(files[index]);

                }else{
                    jQuery("#img_msg").append("El archivo ["+files[index].name+"], no es una im&aacute;gen.<br>");
                    jQuery("#img_msg").addClass("img_msg_show");
                    ERRORES_IMGS++;
                }

            }).catch(function(error){
                jQuery("#img_msg").append("El archivo ["+files[index].name+"], no es una im&aacute;gen.<br>");
                jQuery("#img_msg").addClass("img_msg_show");
                ERRORES_IMGS++;
            });

        }else{
            jQuery("#img_msg").html("Solo se permite un maximo de 4 fotos.");
            jQuery("#img_msg").addClass("img_msg_show");
            ERRORES_IMGS++;
        }

    } );

}

jQuery( document ).ready(function() {
    document.getElementById("cargar_imagenes").addEventListener("change", subirImgs, false);

    jQuery("#enviar_ico").on("click", function(e){

        /*var IMGS = ""; var CONT = 1;
        jQuery( "#img_container img" ).each(function( index ) {
            IMGS += d( "<img src='"+jQuery(this).attr("src")+"' class='img' id='base_"+jQuery(this).attr("data-index")+"' >" );
            CONT++;
        });

        jQuery("#base").html( IMGS );

        var c = document.getElementById("myCanvas");
        var ctx = c.getContext("2d");
        var img = document.getElementById("fondo");
        ctx.drawImage(img, 0, 0, 600, 495);

        jQuery( "#img_container img" ).each(function( index ) {
            var img = document.getElementById( jQuery(this).attr("id") );
            var i = jQuery(this).attr("data-index");
            ctx.drawImage(
                img, 
                jQuery( "#base_"+i )[0].offsetLeft, 
                jQuery( "#base_"+i )[0].offsetTop, 
                jQuery( "#base_"+i )[0].offsetWidth, 
                jQuery( "#base_"+i )[0].offsetHeight
            );
        });*/

        cargar_imagenes();

    });
});

function cargar_imagenes(){
    var imgs = [];
    jQuery( "#img_container img" ).each(function( index ) {
        imgs.push( jQuery(this).attr("src") );
    });

    jQuery(".cargando_container").css("display", "block");

    jQuery.post( HOME+"procesos/reservar/subir_fotos.php", {
        imgs: imgs,
        id_reserva: ID_RESERVA,
        periodo: PERIODO
    }, function( data ) {
        location.reload();
    });
}
