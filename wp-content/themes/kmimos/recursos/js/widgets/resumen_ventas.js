var table;
var am4charts;
jQuery(document).ready(function(){

	update_ventas();

});

function update_ventas(){
	jQuery.post(
		HOME+'widgets/ajax/ventas.php',
		{},
		function(data){

			// TOP
			jQuery("#curso_dia").html(data.ventas_hoy);
			jQuery("#curso_mes").html(data.ventas_mes);
			jQuery("#curso_anterior").html(data.ventas_mes_anterior);
			
			// FOOTER
			jQuery("#ventas_90").html(data.ventas_90);
			jQuery("#ventas_12").html(data.ventas_12);
			jQuery("#ventas_curso").html(data.ventas_anio_curso);

			// Graficos
			load_chart_ventas( 'grafico_resumen_ventas', data.por_dia );

			console.log( data.por_dia );

		}
		, 'json');
}

/* Grafico Lineal Resumen por Dia */
function load_chart_ventas(id, data){
	// Themes begin
	am4core.useTheme(am4themes_animated);
	// Themes end


	// Create chart
	var chart = am4core.create(id, am4charts.XYChart);
	chart.paddingRight = 20;

	chart.data = data;

	var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
	dateAxis.baseInterval = {
	  "timeUnit": "minute",
	  "count": 1
	};
	dateAxis.tooltipDateFormat = "d MMMM Y";

	var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
	valueAxis.tooltip.disabled = true;
	valueAxis.title.text = "Ventas";

	var series = chart.series.push(new am4charts.LineSeries());
	series.dataFields.dateX = "date";
	series.dataFields.valueY = "monto";
	series.tooltipText = "$ [bold]{valueY}[/] MXN";
	series.fillOpacity = 0.3;


	chart.cursor = new am4charts.XYCursor();
	chart.cursor.lineY.opacity = 0;
	chart.scrollbarX = new am4charts.XYChartScrollbar();
	chart.scrollbarX.series.push(series);


	chart.events.on("datavalidated", function () {
	    dateAxis.zoom({start:0.8, end:1});
	});

}
function _load_chart_ventas(id, data){
	am4core.useTheme(am4themes_animated);
	var chart = am4core.create(id, am4charts.XYChart);
	chart.data = data;
	var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
	dateAxis.renderer.minGridDistance = 50;
	var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

	// Create series
	var series = chart.series.push(new am4charts.LineSeries());
	series.dataFields.valueY = "monto";
	series.dataFields.dateX = "date";
	series.strokeWidth = 2;
	series.minBulletDistance = 10;
	series.tooltipText = "{valueY}";
	series.tooltip.pointerOrientation = "vertical";
	series.tooltip.background.cornerRadius = 20;
	series.tooltip.background.fillOpacity = 0.5;
	series.tooltip.label.padding(12,12,12,12)

	// Add scrollbar
	chart.scrollbarX = new am4charts.XYChartScrollbar();
	chart.scrollbarX.series.push(series);

	// Add cursor
	chart.cursor = new am4charts.XYCursor();
	chart.cursor.xAxis = dateAxis;
	chart.cursor.snapToSeries = series;

}
 