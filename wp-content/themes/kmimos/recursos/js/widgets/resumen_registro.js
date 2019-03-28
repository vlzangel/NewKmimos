var registro_data;
var am4charts;
var registro_type='total';
jQuery(document).ready(function(){

	update_registro();

	jQuery('[data-target="registro-change"]').on('click', function(){
		type = jQuery(this).attr('data-id');
		jQuery('[data-target="registro-change"]').removeClass('active');
		jQuery(this).addClass('active');
		chart_registro_change();
	})

});

function update_registro(){
	jQuery.post(
		HOME+'widgets/ajax/registro.php',
		{},
		function(data){

			registro_data = data;
			// Graficos
			type = 'total';
			chart_registro_change();
		}
		, 'json');
}

function chart_registro_change(){

	switch(type){
		case 'byDay':
			load_chart_registro( 'grafico_registro', registro_data.byDay, 'day', "d MMMM YYYY");
			break;
		case 'byMonth':
			load_chart_registro( 'grafico_registro', registro_data.byMonth, 'month', "YYYY MMMM");
			break;
		default:
			load_chart_registro( 'grafico_registro', registro_data.total, 'month', "YYYY MMMM");
			break;
	}

}

/* Grafico Lineal Resumen por Dia */
function load_chart_registro(id, data, tipo, format_date){
 
	am4core.useTheme(am4themes_animated);
 
	var chart = am4core.create(id, am4charts.XYChart);
	chart.paddingRight = 20;

	chart.data = data;

	var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
	dateAxis.baseInterval = {
	  "timeUnit": tipo,
	  "count": 1
	};
	dateAxis.tooltipDateFormat = format_date;

	var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
	valueAxis.tooltip.disabled = true;
	valueAxis.title.text = "Registros";

	var series = chart.series.push(new am4charts.LineSeries());
	series.dataFields.dateX = "date";
	series.dataFields.valueY = "value";
	series.tooltipText = "[bold]Total Registros: {valueY} \n[/] Total Clientes: {cliente} \n[/] Total Cuidadores: {cuidador} \n[/]";
	series.fillOpacity = 0.3;


	chart.cursor = new am4charts.XYCursor();
	chart.cursor.lineY.opacity = 0;
	chart.scrollbarX = new am4charts.XYChartScrollbar();
	chart.scrollbarX.series.push(series);


	chart.events.on("datavalidated", function () {
	    dateAxis.zoom({start:0.8, end:1});
	});

}
