jQuery(document).ready(function(){

    jQuery.post(
        HOME+"/procesos/busqueda/ubicacion.php",
        {},
        function(data){
            jQuery("#ubicacion_list").html(data);
            jQuery("#ubicacion_list div").on("click", function(e){
                jQuery("#ubicacion_txt").val( jQuery(this).html() );
                jQuery("#ubicacion").val( jQuery(this).attr("value") );
                jQuery("#ubicacion").attr( "data-value", jQuery(this).attr("data-value") );
            });
            jQuery("#ubicacion_txt").attr("readonly", false);
        }
    );

    jQuery("#ubicacion_txt").on("keyup", function ( e ) {
        buscarLocacion(String(jQuery("#ubicacion_txt").val()).toLowerCase());
    });

    jQuery("#ubicacion_txt").on("focus", function ( e ) {     
        buscarLocacion(String(jQuery("#ubicacion_txt").val()).toLowerCase());
    });

    jQuery("#ubicacion_txt").on("change", function ( e ) {      
        var txt = getCleanedString( String(jQuery("#ubicacion_txt").val()).toLowerCase() );
        if( txt == "" ){
            jQuery("#ubicacion").val( "" );
            jQuery("#ubicacion").attr( "data-value", "" );
        }
    });

    function buscarLocacion(txt){
        var buscar_1 = getCleanedString( txt );
        jQuery("#ubicacion_list div").css("display", "none");
        jQuery("#ubicacion_list div").each(function( index ) {
            if( String(jQuery( this ).attr("data-value")).toLowerCase().search(buscar_1) != -1 ){
                jQuery( this ).css("display", "block");
            }
        });
    }

    function getCleanedString(cadena){
        cadena = cadena.toLowerCase();
        cadena = cadena.replace(/ /g," ");
        cadena = cadena.replace(/á/gi,"a");
        cadena = cadena.replace(/é/gi,"e");
        cadena = cadena.replace(/í/gi,"i");
        cadena = cadena.replace(/ó/gi,"o");
        cadena = cadena.replace(/ú/gi,"u");
        cadena = cadena.replace(/ñ/gi,"n");
        return cadena;
    }

});