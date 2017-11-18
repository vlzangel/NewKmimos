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
                                        "<img src='"+img_reducida+"' class='img' id='img_subir_"+IMGS_CONT_INTERNO+"' >"+
                                        '<i class="fa fa-minus img_quitar" aria-hidden="true"></i>'+
                                        '<i class="fa fa-undo img_rotar_izq" data-id="img_subir_'+IMGS_CONT_INTERNO+'" aria-hidden="true"></i>'+
                                        '<i class="fa fa-repeat img_rotar_der" data-id="img_subir_'+IMGS_CONT_INTERNO+'" aria-hidden="true"></i>'+
                                    "</div>"
                                ) );

                                jQuery('#cargar_ico').removeClass("fa-spinner");
                                jQuery('#cargar_ico').addClass("fa-plus");

                                jQuery( ".img_quitar" ).unbind("click");
                                jQuery( ".img_quitar" ).bind( "click", function() {
                                    jQuery(this).parent().remove();
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

        jQuery( "#img_container img" ).each(function( index ) {
            jQuery('#img_table_'+index).attr("src", jQuery(this).attr("src"));
        });

        Div2IMG("galeria");

    });
});

function cargar_imagenes(){
    jQuery.post( RUTA_IMGS+"/procesar.php", {img: img_reducida}, function( url ) {
        
        console.log( IMGS );

    });
}

function Div2IMG(divID){
    html2canvas([document.getElementById(divID)], {
        onrendered: function (canvas) {
            var img = canvas.toDataURL('image/png');
            jQuery("#mostrar").html('<img src="'+img+'"/>');
        }
    });
}