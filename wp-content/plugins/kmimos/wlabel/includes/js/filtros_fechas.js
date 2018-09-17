var data = [];
var table = "";
function postCargaTable(json, indice){
    data = [];
    if( jQuery("body").hasClass("iOS") ){
        DESDE = new Date( String(jQuery("#desde").val()).replace(/-/g, "/") ).getTime();
        HASTA = new Date( String(jQuery("#hasta").val()).replace(/-/g, "/") ).getTime();
        jQuery.each(json.data, function( index, value ) {

            console.log( String(jQuery("#desde").val()).replace(/-/g, "/")+" <= "+value[indice].replace(/-/g, "/")+" <= "+String(jQuery("#hasta").val()).replace(/-/g, "/") );

            var FECHA = new Date( value[indice].replace(/-/g, "/") ).getTime();
            if( DESDE <= FECHA && FECHA <= HASTA ){
                data.push( value );
            }
        });
        json.data = data;
        return json;
    }else{
        DESDE = new Date( jQuery("#desde").val() ).getTime();
        HASTA = new Date( jQuery("#hasta").val() ).getTime();
        jQuery.each(json.data, function( index, value ) {
            var FECHA = new Date( value[indice] ).getTime();
            if( DESDE <= FECHA && FECHA <= HASTA ){
                data.push( value );
            }
        });
        json.data = data;
        return json;
    }
}


