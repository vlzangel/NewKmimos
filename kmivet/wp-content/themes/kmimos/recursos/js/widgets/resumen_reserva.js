var table;
var am4charts;
jQuery(document).ready(function(){

	update_total_resumen();

});

function update_total_resumen(){
	jQuery.post(
		HOME+'widgets/ajax/resumen.php',
		{},
		function(data){
			jQuery('#resumen_confirmadas').html(data.mes.confirmadas);
			jQuery('#resumen_hoy').html(data.hoy.total);
			jQuery('#resumen_mes').html(data.mes.total)
			var estructura = [
				{
				    "label": "Completado",
				    "value": data.mes.completadas,
				    "tooltip": (data.mes.completadas > 0)? "Completado: "+data.mes.completadas: ''
				},
				{
				    "label": "Cancelado",
				    "value": data.mes.canceladas,
				    "tooltip": (data.mes.canceladas > 0)? "Cancelado: "+data.mes.canceladas: ''
				}, {
				    "label": "Modificado",
				    "value": data.mes.modificadas,
				    "tooltip": (data.mes.modificadas>0)?"Modificado: "+data.mes.modificadas: ''
				}, {
				    "label": "Pendiente",
				    "value": data.mes.pendientes,
				    "tooltip": (data.mes.pendientes>0)?"Pendiente: "+data.mes.pendientes: ''
				}];
			load_donu_chart('dona_resumen_este_mes', 'label', estructura);
			load_chart_byDay('grafico_resumen_por_dia', data.mes.por_dia);
		}
		, 'json');
}

/* Grafica Dona */
function load_donu_chart(id, label, data ){
	am4core.useTheme(am4themes_animated);

	var chart = am4core.create(id, am4charts.PieChart);

	chart.data = data;

	chart.innerRadius = am4core.percent(50);

	var series = chart.series.push(new am4charts.PieSeries());
	series.dataFields.value = "value";
	series.dataFields.category = label;

	series.labels.template.text = "{tooltip}";
	series.labels.template.text = "{tooltip}";

	series.slices.template.cornerRadius = 10;
	series.slices.template.innerCornerRadius = 7;
	series.alignLabels = false;
	series.labels.template.padding(0,0,0,0);
	series.labels.template.fill = am4core.color("#000");
	series.labels.template.bent = true;
	series.labels.template.radius = 4;

	series.slices.template.states.getKey("hover").properties.scale = 1.1;
	series.labels.template.states.create("hover").properties.fill = am4core.color("#000");

	series.slices.template.events.on("over", function (event) {
	    event.target.dataItem.label.isHover = true;
	})

	series.slices.template.events.on("out", function (event) {
	    event.target.dataItem.label.isHover = false;
	})

	series.ticks.template.disabled = true;

	// this creates initial animation
	series.hiddenState.properties.opacity = 1;
	series.hiddenState.properties.endAngle = -90;
	series.hiddenState.properties.startAngle = -90;

	chart.legend = new am4charts.Legend();
}

/* Grafico Lineal Resumen por Dia */
function load_chart_byDay(id, data){
	am4core.useTheme(am4themes_animated);
	var chart = am4core.create(id, am4charts.XYChart);
	chart.exporting.menu = new am4core.ExportMenu();
	var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
	categoryAxis.dataFields.category = "date";
	categoryAxis.renderer.minGridDistance = 30;
	var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

	// Pendientes
	// ****************************
	var pendientesSeries = chart.series.push(new am4charts.LineSeries());
		pendientesSeries.name = "Pendientes";
		pendientesSeries.dataFields.valueY = "pendientes";
		pendientesSeries.dataFields.categoryX = "date";
		pendientesSeries.stroke = am4core.color("#5a5a5a");
		pendientesSeries.strokeWidth = 1;
		//pendientesSeries.propertyFields.strokeDasharray = "lineDash";
		pendientesSeries.tooltip.label.textAlign = "middle";
		var pendientesBullet = pendientesSeries.bullets.push(new am4charts.Bullet());
		pendientesBullet.fill = am4core.color("#fff"); // tooltips grab fill from parent by default
		pendientesBullet.tooltipText = "[#000 font-size: 15px]{name} ( Día: {categoryX} ) \n[/][#000 font-size: 15px]{valueY} Reserva(s)"
		var circle = pendientesBullet.createChild(am4core.Circle);
		circle.radius = 4;
		circle.fill = am4core.color("#fff");
		circle.strokeWidth = 3;

	// Canceladas
	// ****************************
	var canceladasSeries = chart.series.push(new am4charts.LineSeries());
		canceladasSeries.name = "Canceladas";
		canceladasSeries.dataFields.valueY = "canceladas";
		canceladasSeries.dataFields.categoryX = "date";
		canceladasSeries.stroke = am4core.color("#f10606");
		canceladasSeries.strokeWidth = 1;
		//canceladasSeries.propertyFields.strokeDasharray = "lineDash";
		canceladasSeries.tooltip.label.textAlign = "middle";
		var canceladasBullet = canceladasSeries.bullets.push(new am4charts.Bullet());
		canceladasBullet.fill = am4core.color("#fff"); // tooltips grab fill from parent by default
		canceladasBullet.tooltipText = "[#000 font-size: 15px]{name} ( Día: {categoryX} ) \n[/][#000 font-size: 15px]{valueY} Reserva(s)"
		var circle = canceladasBullet.createChild(am4core.Circle);
		circle.radius = 4;
		circle.fill = am4core.color("#fff");
		circle.strokeWidth = 3;

	// Completadas
	// ****************************
	var completadasSeries = chart.series.push(new am4charts.LineSeries());
		completadasSeries.name = "Completadas";
		completadasSeries.dataFields.valueY = "completadas";
		completadasSeries.dataFields.categoryX = "date";
		completadasSeries.stroke = am4core.color("#31ca0b");
		completadasSeries.strokeWidth = 1;
		//completadasSeries.propertyFields.strokeDasharray = "lineDash";
		completadasSeries.tooltip.label.textAlign = "middle";
		var completadasBullet = completadasSeries.bullets.push(new am4charts.Bullet());
		completadasBullet.fill = am4core.color("#fff"); // tooltips grab fill from parent by default
		completadasBullet.tooltipText = "[#000 font-size: 15px]{name} ( Día: {categoryX} ) \n[/][#000 font-size: 15px]{valueY} Reserva(s)"
		var circle = completadasBullet.createChild(am4core.Circle);
		circle.radius = 4;
		circle.fill = am4core.color("#fff");
		circle.strokeWidth = 3;

	// Confirmadas
	// ****************************
	/*var confirmadasSeries = chart.series.push(new am4charts.LineSeries());
		confirmadasSeries.name = "Confirmadas";
		confirmadasSeries.dataFields.valueY = "confirmadas";
		confirmadasSeries.dataFields.categoryX = "date";
		confirmadasSeries.stroke = am4core.color("#0564f3");
		confirmadasSeries.strokeWidth = 3;
		//confirmadasSeries.propertyFields.strokeDasharray = "lineDash";
		confirmadasSeries.tooltip.label.textAlign = "middle";
		var confirmadasBullet = confirmadasSeries.bullets.push(new am4charts.Bullet());
		confirmadasBullet.fill = am4core.color("#fff"); // tooltips grab fill from parent by default
		confirmadasBullet.tooltipText = "[#000 font-size: 15px]{name} ( Día: {categoryX} ) \n[/][#000 font-size: 15px]{valueY} Reserva(s)"
		var circle = confirmadasBullet.createChild(am4core.Circle);
		circle.radius = 4;
		circle.fill = am4core.color("#fff");
		circle.strokeWidth = 3;*/

	// Modificadas
	// ****************************
	var modificadaSeries = chart.series.push(new am4charts.LineSeries());
		modificadaSeries.name = "Modificadas";
		modificadaSeries.dataFields.valueY = "modificadas";
		modificadaSeries.dataFields.categoryX = "date";
		modificadaSeries.stroke = am4core.color("#f7ef03");
		modificadaSeries.strokeWidth = 1;
		//modificadaSeries.propertyFields.strokeDasharray = "lineDash";
		modificadaSeries.tooltip.label.textAlign = "middle";
		var modificadaBullet = modificadaSeries.bullets.push(new am4charts.Bullet());
		modificadaBullet.fill = am4core.color("#fff"); // tooltips grab fill from parent by default
		modificadaBullet.tooltipText = "[#000 font-size: 15px]{name} ( Día: {categoryX} ) \n[/][#000 font-size: 15px]{valueY} Reserva(s)"
		var circle = modificadaBullet.createChild(am4core.Circle);
		circle.radius = 4;
		circle.fill = am4core.color("#fff");
		circle.strokeWidth = 3;

	chart.data = data;
	chart.legend = new am4charts.Legend();
}

