var intervalo = 0;
var cant = 0;
var vlz_primer_item_localidad = '';

jQuery(document).ready(function(){

    jQuery.post(
        HOME+"/procesos/busqueda/ubicacion.php",
        {},
        function(data){
            jQuery(".ubicacion_list").html(data);
            jQuery(".ubicacion_list li").on("click", function(e){
                if( jQuery(this).html() != "X" ){
                    jQuery(".ubicacion_txt").val( jQuery(this).html() );
                    jQuery(".ubicacion").val( jQuery(this).attr("value") );
                    jQuery(".ubicacion").attr( "data-value", jQuery(this).attr("data-value") );
                    jQuery(".ubicacion").attr( "data-txt", jQuery(this).html() );
                    jQuery( ".cerrar_list_box" ).css("display", "none");
                    jQuery(".latitud").val( "" );
                    jQuery(".longitud").val( "" );
                    
                    buscar("ubicacion");
                } 
                jQuery(".ubicacion_list").removeClass("ubicacion_list_hover");
            });
            jQuery(".ubicacion_txt").attr("readonly", false);
        }
    );

    jQuery(".ubicacion_txt").on("keyup", function ( e ) {
        var txt = String(jQuery(this).val()).toLowerCase();
        var code = e.which;

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
                buscarLocacion(txt, jQuery(this));
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

    jQuery(".ubicacion_txt").on("paste", function ( e ) {
        var txt = String(jQuery(this).val()).toLowerCase();
        if( txt.trim() != "" ){
            buscarLocacion(txt, jQuery(this));
        }else{
            if(typeof buscar === 'function') {
                jQuery(".ubicacion_txt").val( "" );
                jQuery(".ubicacion").val( "" );
                jQuery(".latitud").val( "" );
                jQuery(".longitud").val( "" );
                buscar("ubicacion");
            }
        }
    });

    jQuery(".ubicacion_txt").on("focus", function ( e ) { 
        jQuery(this).attr("placeholder", "Escribe aquí el municipio");
        jQuery(this).parent().find(".ubicacion_list").addClass("ubicacion_list_hover");    
    });

    jQuery(".ubicacion_txt").on("blur", function ( e ) { 
        jQuery(this).attr("placeholder", "UBICACIÓN, ESTADO, MUNICIPIO");

        // vlz_primer_item_localidad.attr("value")

        jQuery(".ubicacion_txt").val( vlz_primer_item_localidad.html() );
        jQuery(".ubicacion").val( vlz_primer_item_localidad.attr("value") );
        jQuery(".ubicacion").attr( "data-value", vlz_primer_item_localidad.attr("data-value") );
        jQuery(".ubicacion").attr( "data-txt", vlz_primer_item_localidad.html() );
        jQuery( ".cerrar_list_box" ).css("display", "none");
        jQuery(".latitud").val( "" );
        jQuery(".longitud").val( "" );
        
        buscar("ubicacion");

    });

    jQuery(".ubicacion_txt").on("change", function ( e ) {    
        var txt = getCleanedString( String(jQuery(this).val()).toLowerCase() );
        if( txt == "" ){
            jQuery(this).parent().find(".ubicacion").val( "" );
            jQuery(this).parent().find(".ubicacion").attr( "data-value", "" );
            jQuery(this).parent().find(".latitud").val( "" );
            jQuery(this).parent().find(".longitud").val( "" );
            buscar("ubicacion");
        }else{
            if( jQuery(this).parent().find(".ubicacion").val() != "" ){
                if( jQuery(this).val() != jQuery(this).parent().find(".ubicacion").val() ){
                    jQuery(this).val( jQuery(this).parent().find(".ubicacion").attr( "data-txt" ) );
                }
            }
        }
    });

    jQuery(".cerrar_list").on("click", function(e){
        jQuery(this).parent().css("display", "none");
    });

    function buscarLocacion(txt, _this){
        clearInterval(intervalo);
        intervalo = setInterval(function(){ 
            var buscar_1 = String(getCleanedString( txt )).trim();
            _this.parent().find(".ubicacion_list li").css("display", "none");
            if( buscar_1 != "" ){
                cant = 0;
                _this.parent().find(".ubicacion_list li").each(function( index ) {
                    if( String(jQuery( this ).attr("data-value")).toLowerCase().search(buscar_1) != -1 ){
                        jQuery( this ).css("display", "block");
                        if( cant == 0 ){
                            vlz_primer_item_localidad = jQuery( this );
                        }
                        cant++;
                    }
                });

                if( cant > 0 ){
                    _this.parent().find( ".cerrar_list_box" ).css("display", "block");
                }else{
                    _this.parent().find( ".cerrar_list_box" ).css("display", "none");
                }
            }
            clearInterval(intervalo); 
        }, 300);
            
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
        cadena = cadena.replace(/,/gi,"");
        return cadena;
    }

});

