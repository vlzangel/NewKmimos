var noches_reservadas_data;
var am4charts;
var noches_reservadas_type='total';
jQuery(document).ready(function(){

	update_noches_reservadas();

	jQuery('[data-target="noches_reservadas-change"]').on('click', function(){
		noches_reservadas_type = jQuery(this).attr('data-id');
		jQuery('[data-target="noches_reservadas-change"]').removeClass('active');
		jQuery(this).addClass('active');
		chart_change_noches_reservadas();
	})

});

function update_noches_reservadas(){
	jQuery.post(
		HOME+'widgets/ajax/noches_reservadas.php',
		{},
		function(data){

			// console.log( data );

			noches_reservadas_data = data;
			// Graficos
			noches_reservadas_type = 'total';
			chart_change_noches_reservadas();
			
		}
		, 'json' );
}

function chart_change_noches_reservadas(){
	switch(noches_reservadas_type){
		case 'byDay':
			load_chart_noches_reservadas( 'grafico_noches_reservadas', noches_reservadas_data.byDay, 'day', "d MMMM YYYY");
			break;
		case 'byMonth':
			load_chart_noches_reservadas( 'grafico_noches_reservadas', noches_reservadas_data.byMonth, 'month', "YYYY MMMM");
			break;
		default:
			load_chart_noches_reservadas( 'grafico_noches_reservadas', noches_reservadas_data.total, 'month', "YYYY MMMM");
			break;
	}
}

/* Grafico Lineal Resumen por Dia */
function load_chart_noches_reservadas(id, data, tipo, format_date){
 
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
	valueAxis.title.text = "Noches Reservadas";

	var series = chart.series.push(new am4charts.LineSeries());
	series.dataFields.dateX = "date";
	series.dataFields.valueY = "value";
	series.tooltipText = "[bold]Total: {valueY} Noches Reservadas \n[/]";
	series.fillOpacity = 0.3;


	chart.cursor = new am4charts.XYCursor();
	chart.cursor.lineY.opacity = 0;
	chart.scrollbarX = new am4charts.XYChartScrollbar();
	chart.scrollbarX.series.push(series);

	// console.log(noches_reservadas_data.date);

	if( noches_reservadas_type == 'byDay' ){	
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