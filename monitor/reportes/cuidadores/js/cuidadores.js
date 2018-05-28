var table;
var chart;

var lista_sucursales = [];
var datos_servidores = [];

var marketing = [];
var datos_marketing = [];
var datos_usuarios_by_fecha = [];
var datos_usuarios = [];
var usuarios_wom = 0;

var usuarios_unicos = 1; // Filtrar los usuarios como unicos en todas las plataformas
var date_year = {init:0, fin:4}; // Year
var date_month = {init:5, fin:2}; // Month


jQuery(document).ready(function(){
    cargarDatos();
});

// ** *************************************** **
// Enviar solicitudes a los servidores
// ** *************************************** **
function cargarDatos(){

    jQuery('#btn-cargar-datos').addClass('disabled');

    // reiniciar variables
    lista_sucursales = [];
    datos_servidores = [];
    marketing = [];
    datos_marketing = [];
    datos_usuarios_by_fecha = [];

    // Mensaje
    mostratMensaje( 'Cargando datos de servidores' );
    mostrarLoading();

    // cargar data de sucursales
    if( lista_sucursales.length > 0 ){    

        jQuery.each(lista_sucursales, function(index, row){        
           
            jQuery.post( row.dominio+'/monitor/reportes/cuidadores/ajax/cuidadores.php', 
                {
                    'desde': jQuery('[name="desde"]').val(),
                    'hasta': jQuery('[name="hasta"]').val()
                },
                function(data){

                    jQuery('#'+row.name).removeClass( 'fa-cog fa-spin fa-fw' );
                    if( data.estatus == 1 ){

                        jQuery('#'+row.name).addClass( 'fa-check' );
                        datos_servidores.push( data.cuidadores );
                        marketing.push( data.marketing );

                    }else{
                        jQuery('#'+row.name).addClass( 'fa-close' );
                        datos_servidores.push([]);
                    }
                    procesar_datos();

                },"json")
                .fail(function(data){
                    jQuery('#'+row.name).removeClass( 'fa-cog fa-spin fa-fw' );
                    jQuery('#'+row.name).addClass( 'fa-close' );
                    datos_servidores.push([]);
                    procesar_datos();
                });
        });
    }
}

// ** *************************************** **
// Procesar los datos por ( Dia, Mes, Año )
// ** *************************************** **
function procesar_datos(){
    if(datos_servidores.length == lista_sucursales.length){
        mostratMensaje( 'Procesando datos' );
        switch( periodo ){
            case 'diario':
                by_day();
                break;
            case 'mensual':
                by_month();
                break;
            case 'anual':
                by_year();
                break;
        }
    }
}

function by_day(){
    var data_merge = [];
    var data_marketing_merge = [];
    var recompra_merge = [];
    var datos_usuarios = [];
    var usuarios_merge = [];
    var total = 0;

    // Merge Datos de Sucursales
    jQuery.each(datos_servidores, function( index, rows ){
        if( rows.length > 0 ){
            jQuery.each( rows , function( d, row ){
                data_merge = merge_usuarios( row, data_merge );
            });
        }
    });

    // Merge marketing de Sucursales
    var mk = marketing[0] || [];
    jQuery.each(mk, function( index, row ){
        datos_marketing = merge_marketing( row, datos_marketing );
    });    

    // Analizar y estructurar los datos
    analizar_datos( data_merge );
}

function by_month(){
    var data_merge = [];
    var recompra_merge = [];
    var usuarios_merge = [];
    var datos_usuarios = [];
    var total = 0;

    // Merge Datos de Sucursales
    jQuery.each(datos_servidores, function( index, rows ){
        if( rows.length > 0 ){
            jQuery.each( rows , function( d, row ){

                var anio = row['user_registered'].substr(date_year['ini'], date_year['fin']) ; 
                row['user_registered'] = anio +'-'+ row['user_registered'].substr(date_month['init'], date_month['fin']); 

                data_merge = merge_usuarios( row, data_merge );
            });
        }
    });

    // Merge marketing de Sucursales
    var mk = marketing[0] || [];
    jQuery.each(mk, function( index, row ){
        var anio = row['fecha'].substr(date_year['ini'], date_year['fin']) ; 
            row['fecha'] = anio +'-'+ row['fecha'].substr(date_month['init'], date_month['fin']); 
        datos_marketing = merge_marketing( row, datos_marketing );
    });    

    // Analizar y estructurar los datos
    analizar_datos( datos_usuarios_by_fecha );
}

function by_year(){
    var data_merge = [];
    var recompra_merge = [];
    var usuarios_merge = [];
    var datos_usuarios = [];
    var total = 0;

    // Merge Datos de Sucursales
    jQuery.each(datos_servidores, function( index, rows ){
        if( rows.length > 0 ){
            jQuery.each( rows , function( d, row ){
                row['user_registered'] = row['user_registered'].substr(date_year['ini'], date_year['fin']) ; 
                data_merge = merge_usuarios( row, data_merge );
            });
        }
    });

    // Merge marketing de Sucursales
    var mk = marketing[0] || [];
    jQuery.each(mk, function( index, row ){
        row['fecha'] = row['fecha'].substr(date_year['ini'], date_year['fin']); 
        datos_marketing = merge_marketing( row, datos_marketing );
    });    

    // Analizar y estructurar los datos
    analizar_datos( datos_usuarios_by_fecha );
}


// ** *************************************** **
// Merge branch by date
// ** *************************************** **
function merge_usuarios( origen, destino ){
    var index = -1;

    if( origen['cuidador_id'] > 0 ){

        // Buscar
            jQuery.each(destino, function(i, row){
                if( row['email'] == origen['user_email'] ){
                    index = 0;
                    return false;
                }
            });

        var fecha = origen['user_registered'];

        // List fechas
            var anio = fecha.substr(date_year['ini'], date_year['fin']) ; 
            switch (periodo){
                case 'diario':
                    if( datos_usuarios.indexOf(fecha) < 0 ){
                        datos_usuarios.push(fecha);
                    }
                    break;
                case 'mensual':
                    fecha_letra = anio +'-'+ fecha.substr(date_month['init'], date_month['fin']);
                    if( datos_usuarios.indexOf(fecha_letra) < 0 ){
                        datos_usuarios.push(fecha_letra);
                    }
                    break;
                case 'anual':
                    if( datos_usuarios.indexOf(anio) < 0 ){
                        datos_usuarios.push(anio);
                    }
                    break;
            }

        // Agrupar usuarios por fecha
            if( datos_usuarios_by_fecha[ "'"+fecha+"'" ] > 0 ){
                var valor = eval(datos_usuarios_by_fecha[ "'"+fecha+"'" ]) || 0 ;
                datos_usuarios_by_fecha[ "'"+fecha+"'" ] = valor + 1;
            }else{
                datos_usuarios_by_fecha[ "'"+fecha+"'" ] = 1;
            }

        // Contar usuarios WOM
            var referred = origen['user_referred'] || "Otros";
            if( origen['user_referred'] == 'Amigo/Familiar' ){
                usuarios_wom = usuarios_wom + 1;
            }

        // agregar nuevos
            if( index == 0 ){
                destino[index]['total'] = destino[index]['total'] + 1;
            }else{
                destino.push({
                    "date": origen['user_registered'],
                    "total": 1
                });
            }

    }

    return destino;
}

function merge_marketing( origen, destino ){

  var index = -1;

    // buscar y sumar valores
    jQuery.each(destino, function(i, row){
        if( row['fecha'] == origen['fecha'] ){
            destino[ i ] = {
                "fecha": origen['fecha'],
                "costo": eval(row['costo']) + eval(origen['costo']),
            };
            index = 0;
            return false;
        }
    });


    // agregar nuevos
    if( index == -1 ){
        destino.push({
            "fecha": origen['fecha'],
            "costo": eval(origen['costo']),
        });
    }

    return destino;
}

// ** *************************************** **
// Analizar y estructurar datos 
// ** *************************************** **
function analizar_datos( datos ){
 
    mostratMensaje( 'Estructurando datos' );

    var total_cliente=0;

    // reiniciar datos
        if( table ){
            table.clear();
            table.destroy();
        }
        jQuery('[data-header="in"]').html('');
        jQuery('[data-header="in"]').append('<td></td>');
        jQuery('[data-header="in"]').append('<td>Descripcion</td>');


    // Primeras columnas
        var data = [
            ['1','<small><strong>Total Cuidadores certificados</strong></small>'],
            ['2','<small>Nuevos Cuidadores certificados</small>'],
            ['3','<small>Costo por cuidador (CAC)</small>'],
            ['4','<small>Costo por cuidador (CAC) - USD</small>']
        ];

    datos_usuarios.sort();

    var grafico = [];
    var total = 0;
    jQuery.each(datos_usuarios, function( i, fecha ){

        // Calculo
            var mk_total = 0 ;
                jQuery.each( datos_marketing, function(i,a){
                    if( a['fecha'] == fecha ){
                        mk_total = a['costo'];
                        return false;
                    }
                });

            var nuevos_cuidadores = datos_usuarios_by_fecha["'"+fecha+"'"] || 0;
                total = total + nuevos_cuidadores;
            var costo_cuidador = (mk_total / nuevos_cuidadores) || 0;
            var costo_cuidador_USD = (costo_cuidador / 19) || 0;


        // Fecha letras
            var fecha_letra = '';
            var anio = fecha.substr(date_year['ini'], date_year['fin']) ; 
            switch (periodo){
                case 'diario':
                    if( fecha.length == 10){
                        fecha_letra = fecha;
                    }
                    break;
                case 'mensual':
                    if( fecha.length == 7){
                        fecha_letra = meses( fecha.substr(date_month['init'], date_month['fin']) ) +'-'+ anio;
                    }
                    break;
                case 'anual':
                    if( fecha.length == 4){
                        fecha_letra = anio;
                    }
                    break;
            }

        // Estructurar
            if( fecha_letra != "" ){
                
                jQuery('[data-header="in"]').append('<td>'+fecha_letra+'</td>');

                // tabla
                data[0].push( total );
                data[1].push( nuevos_cuidadores );
                data[2].push( '$ '+ number_format(costo_cuidador,2) );
                data[3].push( '$ '+ number_format(costo_cuidador_USD,2) );

                // grafico
                grafico.push({
                    'date': fecha_letra,
                    'total': total,
                    'nuevos': nuevos_cuidadores,
                    'costo_por_cuidador': costo_cuidador,
                    'costo_por_usd': costo_cuidador_USD
                });

            }

    });

    cargar_tabla(data);
    cargar_grafico(grafico);

    mostratMensaje( '' );
    jQuery('#btn-cargar-datos').removeClass('disabled');
}

// ************************************
// Mostrar datos
// ************************************
function cargar_tabla(data){

    table = jQuery('#example').DataTable({
	    "language": {
	        "emptyTable":           "No hay datos disponibles en la tabla.",
	        "info":                 "Del _START_ al _END_ de _TOTAL_ ",
	        "infoEmpty":            "Mostrando 0 registros de un total de 0.",
	        "infoFiltered":         "(filtrados de un total de _MAX_ registros)",
	        "infoPostFix":          " (actualizados)",
	        "lengthMenu":           "Mostrar _MENU_ registros",
	        "loadingRecords":       "Cargando...",
	        "processing":           "Procesando...",
	        "search":               "Buscar:",
	        "searchPlaceholder":    "Dato para buscar",
	        "zeroRecords":          "No se han encontrado coincidencias.",
	        "paginate": {
	            "first":            "Primera",
	            "last":             "Última",
	            "next":             "Siguiente",
	            "previous":         "Anterior"
	        },
	        "aria": {
	            "sortAscending":    "Ordenación ascendente",
	            "sortDescending":   "Ordenación descendente"
	        }
	    },
	    "data": data,
	    "scrollX": true,
	    "scrollCollapse": true,
	    "autoWidth": true,
	    "paging": false,
	    "buttons": [
	        {
	          extend: "csv",
	          className: "btn-sm"
	        },
	        {
	          extend: "excelHtml5",
	          className: "btn-sm"
	        },
	    ],
	});
}

function cargar_grafico(datos){
	var chart = AmCharts.makeChart("chartdiv", {
        "type": "serial",
        "theme": "light",
        "legend": {
            "equalWidths": false,
            "useGraphSettings": true,
            "valueAlign": "left",
            "valueWidth": 120
        },

        "dataProvider": datos,
        "valueAxes": [
            {
                "id": "cuidadoresAxis",
                "axisAlpha": 0,
                "gridAlpha": 0,
                "position": "left",
                "title": "Nuevos Cuidadores"
            }
        ],
        "graphs": [
            {
                "alphaField": "alpha",
                "balloonText": "[[value]]",
                "dashLengthField": "dashLength",
                "fillAlphas": 0.7,
                "legendPeriodValueText": "total: [[value.sum]]",
                "legendValueText": "[[value]]",
                "title": "Nuevos Cuidadores",
                "type": "column",
                "valueField": "nuevos",
                "valueAxis": "cuidadoresAxis"
            }
        ],

        "chartScrollbar": {
            "enabled": false
        },
        "chartCursor": {
            "categoryBalloonDateFormat": "MM",
            "cursorAlpha": 0.1,
            "cursorColor": "#000000",
            "fullWidth": true,
            "valueBalloonsEnabled": false,
            "zoomable": true
        },

        "dataDateFormat": "YYYY-MM",
        "categoryField": "date",
        "categoryAxis": {
            "dateFormats": [{
                  "period": "DD",
                  "format": "DD"
                },{
                  "period": "WW",
                  "format": "MMM"
                },{
                  "period": "MM",
                  "format": "MMM"
                },{
                  "period": "YYYY",
                  "format": "YYYY"
                }
            ],
            "parseDates": false,
            "autoGridCount": false,
            "axisColor": "#555555",
            "gridAlpha": 0.1,
            "gridColor": "#FFFFFF",
            "gridCount": 50
        },
        "export": {
            "enabled": true
        },
        "listeners": [{
            "event": "clickGraphItem",
            "method": function(e) {  
              // Find out X
              var x = Math.round( e.graph.categoryAxis.dateToCoordinate(e.item.category) );
              // Find out Y
              var y = Math.round( e.graph.valueAxis.getCoordinate(e.item.values.value) );              
            }
        }]
    });
}