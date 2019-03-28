var leads_data;
var am4charts;
var leads_type='total';
jQuery(document).ready(function(){

	update_leads();

	jQuery('[data-target="leads-change"]').on('click', function(){
		leads_type = jQuery(this).attr('data-id');
		jQuery('[data-target="leads-change"]').removeClass('active');
		jQuery(this).addClass('active');
		chart_change();
	})

});

function update_leads(){
	jQuery.post(
		HOME+'widgets/ajax/leads.php',
		{},
		function(data){

			leads_data = data;
			// Graficos
			leads_type = 'total';
			chart_change();
		}
		, 'json');
}

function chart_change(){
	switch(leads_type){
		case 'byDay':
			load_chart_leads( 'grafico_leads', leads_data.byDay, 'day', "d MMMM YYYY");
			break;
		case 'byMonth':
			load_chart_leads( 'grafico_leads', leads_data.byMonth, 'month', "YYYY MMMM");
			break;
		default:
			load_chart_leads( 'grafico_leads', leads_data.total, 'month', "YYYY MMMM");
			break;
	}
}

/* Grafico Lineal Resumen por Dia */
function load_chart_leads(id, data, tipo, format_date){
 
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
	valueAxis.title.text = "Leads";

	var series = chart.series.push(new am4charts.LineSeries());
	series.dataFields.dateX = "date";
	series.dataFields.valueY = "value";
	series.tooltipText = "[bold]Total: {valueY} Leads \n[/]";
	series.fillOpacity = 0.3;


	chart.cursor = new am4charts.XYCursor();
	chart.cursor.lineY.opacity = 0;
	chart.scrollbarX = new am4charts.XYChartScrollbar();
	chart.scrollbarX.series.push(series);

	console.log(leads_data.date);


	if( leads_type == 'byDay' ){	
		var f = new Date();
		chart.events.on("ready", function () {
		  dateAxis.zoomToIndexes(0,0.5);
		});
	}else{	
		chart.events.on("datavalidated", function () {
		    dateAxis.zoom({start:0.8, end:1});
		});
	}


}
/*function _load_chart_ventas(id, data){
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
 */