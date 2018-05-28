var table;
var chart;

var lista_sucursales = [];
var datos_servidores = [];
var datos_recompra = [];
var datos_usuarios = [];
var datos_usuarios_by_fecha = [];
var usuarios_wom = 0;
var marketing = [];
var datos_marketing = [];

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
    datos_recompra = [];
    datos_usuarios = [];
    datos_usuarios_by_fecha = [];
    usuarios_wom = 0;
    marketing = [];
    datos_marketing = [];

    // Mensaje
    mostratMensaje( 'Cargando datos de servidores' );
    mostrarLoading();

    // cargar data de sucursales
    if( lista_sucursales.length > 0 ){    

        jQuery.each(lista_sucursales, function(index, row){        
           
            jQuery.post( row.dominio+'/monitor/reportes/ventas/ajax/ventas.php', 
                {
                    'desde': jQuery('[name="desde"]').val(),
                    'hasta': jQuery('[name="hasta"]').val()
                },
                function(data){

                    jQuery('#'+row.name).removeClass( 'fa-cog fa-spin fa-fw' );
                    if( data.estatus == 1 ){
                        jQuery('#'+row.name).addClass( 'fa-check' );

                        datos_servidores.push( data.datos );
                        marketing.push( data.marketing );
                        if( data.usuario != null ){
                            datos_usuarios.push(data.usuario);
                        }

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
    var recompra_merge = [];
    var usuarios_merge = [];
    var total = 0;

    // Merge Datos de Sucursales
    jQuery.each(datos_servidores, function( index, rows ){
        if( rows.length > 0 ){
            jQuery.each( rows , function( d, row ){
                data_merge = merge_sucursal( row, data_merge );
            });
        }
    });

    // Merge Usuarios de Sucursales
    if( usuarios_unicos == 1 ){    
        jQuery.each(datos_usuarios, function( index, rows ){      
            if( rows.length > 0 ){
                jQuery.each( rows , function( d, row ){
                    usuarios_merge = merge_usuarios( row, usuarios_merge );
                });
            }
        });
    }

    // Merge marketing de Sucursales
    var mk = marketing[0] || [];
    jQuery.each(mk, function( index, row ){
        datos_marketing = merge_marketing( row, datos_marketing );
    }); 

    // Analizar y estructurar los datos
    analizar_datos( data_merge, usuarios_merge );
}

function by_month(){
    var data_merge = [];
    var recompra_merge = [];
    var usuarios_merge = [];
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

    // Merge Usuarios de Sucursales
    if( usuarios_unicos == 1 ){    
        jQuery.each(datos_usuarios, function( index, rows ){      
            if( rows.length > 0 ){
                jQuery.each( rows , function( d, row ){
                    var anio = row['user_registered'].substr(date_year['ini'], date_year['fin']) ; 
                    row['user_registered'] = meses( row['user_registered'].substr(date_month['init'], date_month['fin']) )+ anio; 
                    usuarios_merge = merge_usuarios( row, usuarios_merge );
                });
            }
        });
    }

    // Merge marketing de Sucursales
    var mk = marketing[0] || [];
    jQuery.each(mk, function( index, row ){
        var anio = row['fecha'].substr(date_year['ini'], date_year['fin']) ; 
            row['fecha'] = anio +'-'+ row['fecha'].substr(date_month['init'], date_month['fin']); 
        datos_marketing = merge_marketing( row, datos_marketing );
    });    


    // Analizar y estructurar los datos
    analizar_datos( data_merge, usuarios_merge );
}

function by_year(){
    var data_merge = [];
    var recompra_merge = [];
    var usuarios_merge = [];
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

    // Merge Usuarios de Sucursales
    if( usuarios_unicos == 1 ){    
        jQuery.each(datos_usuarios, function( index, rows ){      
            if( rows.length > 0 ){
                jQuery.each( rows , function( d, row ){
                    row['user_registered'] = row['user_registered'].substr(date_year['ini'], date_year['fin']) ; 
                    usuarios_merge = merge_usuarios( row, usuarios_merge );
                });
            }
        });
    }

    // Merge marketing de Sucursales
    var mk = marketing[0] || [];
    jQuery.each(mk, function( index, row ){
        row['fecha'] = row['fecha'].substr(date_year['ini'], date_year['fin']); 
        datos_marketing = merge_marketing( row, datos_marketing );
    });    

    // Analizar y estructurar los datos
    analizar_datos( data_merge, usuarios_merge );
}


// ** *************************************** **
// Merge branch by date
// ** *************************************** **
function merge_usuarios( origen, destino ){
    var index = -1;

    if( origen['cuidador_id'] == null ){

        // buscar
        jQuery.each(destino, function(i, row){
            if( row['email'] == origen['user_email'] ){
                index = 0;
                return false;
            }
        });

        // agregar nuevos
        if( index == -1 ){        

            // Listado general de usuarios
            destino.push({
                "fecha": origen['user_registered'],
                "email": origen['user_email'],
                "referred": origen['user_referred']
            });

            // Agrupar usuarios por fecha
            var fecha = origen['user_registered'];
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
        }

    }

    return destino;
}

function merge_sucursal( origen, destino ){

    var index = -1;


    // buscar y sumar valores
    jQuery.each(destino, function(i, row){

        if( row['date'] == origen['fecha'] ){
            destino[ i ] = {
                "date": origen['fecha'],
                "clientes_nuevos": eval(row['clientes_nuevos']) + eval(origen['clientes_nuevos']),
                "mascotas_total": eval(row['mascotas_total']) + eval(origen['mascotas_total']),
                "noches_total": eval(row['noches_total']) + eval(origen['noches_total']),
                "noches_numero": eval(row['noches_numero']) + eval(origen['noches_numero']),
                "ventas_cantidad": eval(row['ventas_cantidad']) + eval(origen['ventas_cantidad']),
                "ventas_costo_total": eval(row['ventas_costo_total']) + eval(origen['ventas_costo_total']),
                "ventas_descuento": eval(row['ventas_descuento']) + eval(origen['ventas_descuento'])
            };
            index = 0;
            return false;
        }
    });

    // agregar nuevos
    if( index == -1 ){
        destino.push({
            "date": origen['fecha'],
            "clientes_nuevos": origen['clientes_nuevos'],
            "mascotas_total": origen['mascotas_total'],
            "noches_total": origen['noches_total'],
            "noches_numero": origen['noches_numero'],
            "ventas_cantidad": origen['ventas_cantidad'],
            "ventas_costo_total": origen['ventas_costo_total'],
            "ventas_descuento": eval(origen['ventas_descuento'])
        });
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
function analizar_datos( datos, recompras ){

    mostratMensaje( 'Estructurando datos' );

    var total_cliente = 0;
    var row_anterior = {
        "date": '0000-00-00',
        "clientes_nuevos": 0,
        "noches_total": 0,
        "noches_numero": 0
        };

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
            ['1','<small><strong>Costo por noche (CAN)</strong></small>'],
            ['2','<small>Costo por noche (CAN)-USD</small>'],
            ['3','<small>Costo por cliente (CAC)</small>'],
            ['4','<small>Costo por cliente (CAC)-USD</small>'],
        ];
        var grafico = [];

    jQuery.each(datos, function(index, row){
        
        // sustituir valor clientes nuevos por usuarios filtrados
        if( usuarios_unicos == 1 ){
            if( datos_usuarios_by_fecha[ "'"+row['date']+"'" ] > 0 ){
                row['clientes_nuevos'] = datos_usuarios_by_fecha[ "'"+row['date']+"'" ];
            }
        }

        var mk_total = 0 ;
            jQuery.each( datos_marketing, function(i,a){
                if( a['fecha'] == row['date'] ){
                    mk_total = a['costo'];
                    return false;
                }
            });

        total_cliente = total_cliente + eval(row['clientes_nuevos']);

        var parametro = 19;

        var descuento = row['ventas_descuento'] || 0;
        var costo_por_noche = (mk_total + descuento) / row['noches_total'] || 0;
        var costo_por_noche_dollar = costo_por_noche / parametro || 0;
        var costo_por_cliente = (mk_total + descuento) / row['clientes_nuevos'] || 0;
        var costo_por_cliente_dollar = costo_por_cliente / parametro || 0;

        jQuery('[data-header="in"]').append('<td>'+row['date']+'</td>');

        data[0].push( number_format(costo_por_noche, 2) );
        data[1].push( '$ '+number_format(costo_por_noche_dollar, 2)  );
        data[2].push( number_format(costo_por_cliente, 2) );
        data[3].push( '$ '+number_format(costo_por_cliente_dollar, 2)  );

        grafico.push({
            "date": row['date'],
            "costo_por_noche": number_format(costo_por_noche,2),
            "costo_por_noche_dollar": number_format(costo_por_noche_dollar,2),
            "costo_por_cliente": number_format(costo_por_cliente,2),
            "costo_por_cliente_dollar": number_format(costo_por_cliente_dollar,2)
        });

        // Row Anterior
        row_anterior = row;
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
                "title": "Costo por noche (CAN)"
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
                "title": "Costo por cliente (CAC)"
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
                "title": "Costo por noche (CAN)",
                "type": "column",
                "valueField": "costo_por_noche",
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
                "title": "Costo por cliente (CAC)",
                "fillAlphas": 0,
                "valueField": "costo_por_cliente",
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


