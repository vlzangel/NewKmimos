var table;
var chart;

var lista_sucursales = [];
var datos_servidores = [];

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

    // Mensaje
    mostratMensaje( 'Cargando datos de servidores' );
    mostrarLoading();

    // cargar data de sucursales
    if( lista_sucursales.length > 0 ){    

        jQuery.each(lista_sucursales, function(index, row){        
           
            jQuery.post( row.dominio+'/monitor/reportes/ingresos/ajax/ingresos.php', 
                {
                    'desde': jQuery('[name="desde"]').val(),
                    'hasta': jQuery('[name="hasta"]').val()
                },
                function(data){

                    jQuery('#'+row.name).removeClass( 'fa-cog fa-spin fa-fw' );
                    if( data.estatus == 1 ){
                        jQuery('#'+row.name).addClass( 'fa-check' );

                        datos_servidores.push( data.datos );

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
    var total = 0;

    // Merge Datos de Sucursales
    jQuery.each(datos_servidores, function( index, rows ){
        if( rows.length > 0 ){
            jQuery.each( rows , function( d, row ){
                data_merge = merge_sucursal( row, data_merge );
            });
        }
    });

    // Analizar y estructurar los datos
    analizar_datos( data_merge );
}

function by_month(){
    var data_merge = [];
    var total = 0;

    // Merge Datos de Sucursales
    jQuery.each(datos_servidores, function( index, rows ){
        if( rows.length > 0 ){
            jQuery.each( rows , function( d, row ){
                var anio = row['fecha'].substr(date_year['ini'], date_year['fin']) ; 
                row['fecha'] = meses( row['fecha'].substr(date_month['init'], date_month['fin']) ) + anio; 
                data_merge = merge_sucursal( row, data_merge );
            });
        }
    });

    // Analizar y estructurar los datos
    analizar_datos( data_merge );
}

function by_year(){
    var data_merge = [];
    var total = 0;

    // Merge Datos de Sucursales
    jQuery.each(datos_servidores, function( index, rows ){
        if( rows.length > 0 ){
            jQuery.each( rows , function( d, row ){
                row['fecha'] = row['fecha'].substr(date_year['ini'], date_year['fin']) ; 
                data_merge = merge_sucursal( row, data_merge );
            });
        }
    });

    // Analizar y estructurar los datos
    analizar_datos( data_merge );
}


// ** *************************************** **
// Merge branch by date
// ** *************************************** **

function merge_sucursal( origen, destino ){

    var index = -1;

    // buscar y sumar valores
    jQuery.each(destino, function(i, row){
        if( row['date'] == origen['fecha'] ){
            destino[ i ] = {
                "date": origen['fecha'],
                "noches_total": eval(row['noches_total']) + eval(origen['noches_total']),
                "ventas_costo_total": eval(row['ventas_costo_total']) + eval(origen['ventas_costo_total'])
            };
            index = 0;
            return false;
        }
    });

    // agregar nuevos
    if( index == -1 ){
        destino.push({
            "date": origen['fecha'],
            "noches_total": origen['noches_total'],
            "ventas_costo_total": origen['ventas_costo_total']
        });
    }

    return destino;
}

// ** *************************************** **
// Analizar y estructurar datos 
// ** *************************************** **
function analizar_datos( datos ){

    mostratMensaje( 'Estructurando datos' );

    // reiniciar datos
        if( table ){
            table.clear();
            table.destroy();
        }
        jQuery('[data-header="in"]').html('');
        jQuery('[data-header="in"]').append('<td></td>');
        jQuery('[data-header="in"]').append('<td>Descripcion</td>');

        datos.reverse();

    // Primeras columnas
        var data = [
            ['1','<small><strong>Ingreso total</strong></small>'],
            ['2','<small>Comision total</small>'],
            ['3','<small>Comision promedio por noche</small>'],
            ['4','<small>Comision promedio por noche (USD)</small>']
        ];

    var grafico = [];
    jQuery.each(datos, function(index, row){
        
        jQuery('[data-header="in"]').append('<td>'+row['date']+'</td>');

        var parametro = 19;
        var ingresos_total = eval(row['ventas_costo_total']) || 0;
        var comision_total = (ingresos_total * 0.25) || 0;
        var comision_promedio_por_noche = ( comision_total / eval(row['noches_total']) ) || 0;
        var comision_promedio_por_noche_usd = (comision_promedio_por_noche / parametro ) || 0;

        data[0].push( "$ "+number_format( ingresos_total, 2 ) );
        data[1].push( "$ "+number_format( comision_total, 2 ) );
        data[2].push( "$ "+number_format( comision_promedio_por_noche, 2 ) );
        data[3].push( "$ "+number_format( comision_promedio_por_noche_usd, 2 ) );

        grafico.push({ 
            'date': row['date'],
            'total': ingresos_total,
            'comision': comision_total
        });

    });

    cargar_tabla(data);
    cargar_grafico(grafico);

console.log(grafico);

    mostratMensaje( '' );
   jQuery('#btn-cargar-datos').removeClass('disabled');

}

// ************************************
// Mostrar datos
// ************************************
function cargar_tabla(data){

    //jQuery('[data-header="in"]').append('<td>Descripci&oacute;n</td>');

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

function cargar_grafico(data){
    

	chart = AmCharts.makeChart("chartdiv", {
        "type": "serial",
        "theme": "light",
        "legend": {
            "equalWidths": false,
            "useGraphSettings": true,
            "valueAlign": "left",
            "valueWidth": 120
        },

        "dataProvider": data,
        "valueAxes": [
            {
                "id": "ventasAxis",
                "axisAlpha": 0,
                "gridAlpha": 0,
                "position": "left",
                "title": "Ingresos Total"
            }, 
            {
                "id": "clientesAxis",
                "clientes": "",
                "clientesUnits": {
                  "hh": "",
                  "mm": ""
                },
                "axisAlpha": 0,
                "gridAlpha": 0,
                "inside": true,
                "position": "right",
                "title": "Comision Total"
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
                "title": "Ingreso Total",
                "type": "column",
                "valueField": "total",
                "valueAxis": "ventasAxis"
            }, 
            {
                "balloonText": "[[value]]",
                "bullet": "round",
                "bulletBorderAlpha": 1,
                "useLineColorForBulletBorder": true,
                "bulletColor": "#FFFFFF",
                "bulletSizeField": "townSize",
                "dashLengthField": "dashLength",
                "descriptionField": "townName",
                "labelPosition": "right",
                "labelText": "[[townName2]]",
                "legendPeriodValueText": "total: [[value.sum]]",
                "legendValueText": "[[value]]",
                "title": "Comision Total",
                "fillAlphas": 0,
                "valueField": "comision",
                "valueAxis": "clientesAxis"
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


