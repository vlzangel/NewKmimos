var listado_liquidacion = {};
var selected_liquidacion = [];

var listado_comision = {};
var selected_comision = [];

jQuery(document).ready(function(){

    jQuery(".vlz_accion").on("click", function(e){
        var value = jQuery(this).attr("data-accion");
        if(value != ''){
             
        }
    });

    filtrar(); 
    jQuery('[data-action="filtro"]').on('change', function(e){
        filtrar();
    })

    jQuery(".ver_reserva_init").on("click", function(e){
        jQuery(this).parent().parent().parent().addClass("vlz_desplegado");
    });

    jQuery(".ver_reserva_init_fuera").on("click", function(e){
        jQuery(this).parent().parent().addClass("vlz_desplegado");
    });

    jQuery(".ver_reserva_init_closet").on("click", function(e){
        jQuery(this).parent().removeClass("vlz_desplegado");
    });

    jQuery('#download-selected').on('click', function(e){
        e.preventDefault();
        var tab_active = jQuery('.nav-tabs li.active a').attr('href');
        if( tab_active == '#Liquidaciones' ){
            download( selected_liquidacion );
        }else{
            download( selected_comision );
        }

    });

    jQuery('#download-todo').on('click', function(e){
        e.preventDefault();
        download( listado_comision );
        download( listado_liquidacion );
    });

    jQuery('[data-PdfXml]').on('click', function(e){
        e.preventDefault();
        var file = [];
            file.push( jQuery(this).attr('data-PdfXml') );
        download( file );
    });
});

function download( archivos ){
    jQuery.post(HOME+"procesos/generales/download_zip.php", {'fact_selected': archivos}, function(e){
        e = JSON.parse(e);
        if( e['estatus'] == "listo" ){
            location.href = e['url'];
        }
    });
}

function filtrar(){
    var mes = jQuery('[name="filtro_mes"]').val();
    var anio = jQuery('[name="filtro_anio"]').val();

    if( mes == "0" && anio == "0" ){
        jQuery('[data-list]').css('display', 'block');
    }else if( mes != '0' || anio != '0' ){
        var list = jQuery('[data-list]');

        selected_comision = [];
        selected_liquidacion = [];
        jQuery.each( list, function(i,v){
            var dmes = jQuery(this).attr('data-mes');
            var danio = jQuery(this).attr('data-anio');
            var sts = false;

            // buscar ambos
            if( dmes == mes && danio == anio ){ sts = true; }

            // buscar solo mes
            if( dmes == mes && anio == "0" ){ sts = true; }

            // buscar solo año
            if( mes == "0" && danio == anio ){ sts = true; }

            if( sts ){
                jQuery(this).css('display', 'block');
                if( jQuery(this).attr('data-list') == 'cuidador' ){
                    selected_comision.push( jQuery(this).attr('data-reserva') );
                }else{
                    selected_liquidacion.push( jQuery(this).attr('data-reserva') );
                }
            }else{
                jQuery(this).css('display', 'none');
            }

        });
    }    
}