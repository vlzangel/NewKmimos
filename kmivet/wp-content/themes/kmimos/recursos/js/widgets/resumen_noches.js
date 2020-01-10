var table;
var am4charts;
jQuery(document).ready(function(){

	update_noches();

});

function update_noches(){
	jQuery.post(
		HOME+'widgets/ajax/noches.php',
		{},
		function(data){

			jQuery('#venta_90').html(data.ventas_90);
			jQuery('#venta_12').html(data.ventas_12);
			jQuery('#anio_curso').html(data.anio_curso);

			var $estructura = [{
				"label": "Mes en Curso",
				"value": data.mes_curso,
				},{
				"label": "Mes Anterior",
				"value": data.mes_anterior,
			}];
			load_chart_noches('grafica_vs_meses', $estructura);

			var estructura2 = [{
				  "category": "Noches ("+data.dia_curso+")",
				  "value": data.dia_curso,
				  "dif": (data.mes_curso - data.dia_curso),
				  "total": (parseInt(data.mes_curso) + 1),
				}];
				// console.log(estructura2);
			load_chart_cilindro('grafica_dia_curso', estructura2);

		}
		, 'json');
}

/* Grafico Lineal Resumen por Dia */
function load_chart_noches(id, data){
	am4core.useTheme(am4themes_animated);
	var chart = am4core.create(id, am4charts.XYChart);
	chart.exporting.menu = new am4core.ExportMenu();
	var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
	categoryAxis.dataFields.category = "label";
	categoryAxis.renderer.minGridDistance = 30;
	var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

	/* Create series */
	var mesCursoSeries = chart.series.push(new am4charts.ColumnSeries());
	mesCursoSeries.name = "Mes en curso";
	mesCursoSeries.dataFields.valueY = "value";
	mesCursoSeries.dataFields.categoryX = "label";

	mesCursoSeries.columns.template.tooltipText = "[#000 font-size: 15px]{categoryX}:\n[/][#000 font-size: 20px]{valueY}[/] [#fff]{additional}[/]"
	mesCursoSeries.columns.template.propertyFields.fillOpacity = "fillOpacity";
	mesCursoSeries.columns.template.propertyFields.stroke = "stroke";
	mesCursoSeries.columns.template.propertyFields.strokeWidth = "strokeWidth";
	mesCursoSeries.columns.template.propertyFields.strokeDasharray = "columnDash";
	mesCursoSeries.tooltip.label.textAlign = "middle";
	 
	chart.data = data;
}

function load_chart_cilindro( id, data){
	am4core.useTheme(am4themes_animated);
	var chart = am4core.create(id, am4charts.XYChart3D);
	chart.titles.create().text = "";

	// Add data
	chart.data = data;

	// Create axes
	var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
	categoryAxis.dataFields.category = "category";
	categoryAxis.renderer.grid.template.location = 0;
	categoryAxis.renderer.grid.template.strokeOpacity = 0;

	var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
	valueAxis.renderer.grid.template.strokeOpacity = 0;
	valueAxis.min = 0;
	valueAxis.max = data.total;
	valueAxis.strictMinMax = false;
	valueAxis.renderer.baseGrid.disabled = true;
	valueAxis.renderer.labels.template.adapter.add("text", function(text, data) {
	  if ((text > data.total ) || (text < 0)) {
	    return '';
	  }
	  else {
	    return text;
	  }
	})

	// Create series
	var series1 = chart.series.push(new am4charts.ConeSeries());
	series1.dataFields.valueY = "value";
	series1.dataFields.categoryX = "category";
	series1.columns.template.width = am4core.percent(80);
	series1.columns.template.fillOpacity = 0.9;
	series1.columns.template.strokeOpacity = 1;
	series1.columns.template.strokeWidth = 2;

	var series2 = chart.series.push(new am4charts.ConeSeries());
	series2.dataFields.valueY = "dif";
	series2.dataFields.categoryX = "category";
	series2.stacked = true;
	series2.columns.template.width = am4core.percent(80);
	series2.columns.template.fill = am4core.color("#000");
	series2.columns.template.fillOpacity = 0.1;
	series2.columns.template.stroke = am4core.color("#000");
	series2.columns.template.strokeOpacity = 0.2;
	series2.columns.template.strokeWidth = 2;

}