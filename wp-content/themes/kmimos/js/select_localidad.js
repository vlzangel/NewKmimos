
jQuery(document).ready(function(){
    var data= {};
    if( jQuery("#ubicacion_list").hasClass('tag-list') ){
        data = {'tag':true};
    }
    jQuery.post(
        HOME+"/procesos/busqueda/ubicacion.php",
        data,
        function(data){
            jQuery("#ubicacion_list").html(data);
            jQuery("#ubicacion_txt").attr("readonly", false);
        }
    );
});

jQuery(document).on("click", '[data-action="tag-list"]', function(e){
    jQuery("#ubicacion_txt").val( jQuery(this).html() );
    jQuery("#ubicacion").val( jQuery(this).attr("value") );
    jQuery("#ubicacion").attr( "data-value", jQuery(this).attr("data-value") );
});

jQuery('#ubicacion_txt').on("keyup", function(e){
    buscarLocacion(String(jQuery("#ubicacion_txt").val()).toLowerCase());
});

jQuery('#ubicacion_txt').on("change", function(e){
    buscarLocacion(String(jQuery("#ubicacion_txt").val()).toLowerCase());
});

function buscarLocacion(txt){
    var buscar_1 = getCleanedString( txt );
    jQuery( '#ubicacion_list div[data-clear]' ).remove();
    if( buscar_1 != '' ){        
        jQuery( '#ubicacion_list li' ).css("display", "none");
        if( jQuery( '#ubicacion_list li[data-value*="'+buscar_1+'"]' ).length > 0 ){
            jQuery( '#ubicacion_list li[data-value*="'+buscar_1+'"]' ).css("display", "block");    
        }else{
            jQuery( '#ubicacion_list' ).append('<li data-clear style="padding-left:15px;">Sin resultados</li>').css('display','block');
        }
    }else{
        jQuery( '#ubicacion_list li' ).css("display", "block");
    }    
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


