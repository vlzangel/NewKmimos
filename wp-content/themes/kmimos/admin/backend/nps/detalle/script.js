var table = ""; 

jQuery(document).ready(function() {
 
	jQuery(document).on('click', '[data-toggle="tab"]', function(e){
    	e.preventDefault();

    	// Item
    	jQuery(this).parent().parent().find('li').removeClass('active');
    	jQuery(this).parent().addClass('active');
    	
    	// Content
    	var group = jQuery(this).attr('group');
		jQuery('[group="'+group+'"]').removeClass('active');

    	var id = jQuery(this).attr('href');
		jQuery(id).addClass('active');
    });

    jQuery("#close_modal").on("click", function(e){
        cerrar(e);
    });
 
    jQuery('[name="redirect-pregunta"]').on("change", function(e){
        // location.href = RAIZ+'wp-admin/admin.php?page=nps_detalle&campana_id='+jQuery(this).val();
        ID = jQuery(this).val();
        jQuery( "#pregunta-title" ).html( jQuery('option:selected',this).attr('data-pregunta') );
        jQuery('#link_feedback').attr('href', RAIZ+"wp-admin/admin.php?page=nps_feedback&campana_id="+ID)
        loadTabla();
        load_dashboard();
    });
 
    jQuery("#form-search").on("submit", function(e){
		e.preventDefault();
    });

    jQuery("#btn-search").on("click", function(e){
		loadTabla( );
	});
 
    /* CAMPANAS */

    jQuery("[data-modal='crear']").on('click', function(){
		abrir_link( jQuery(this) );
    });
    jQuery(document).on('click', '[data-modal="generador_codigo"]', function(e){
		abrir_link( jQuery(this) );
    });

	loadTabla();
	load_dashboard();
});

function loadTabla( ){
 	 
	table = jQuery('#example').DataTable();
	table.destroy();

    table = jQuery('#example').DataTable({
    	"language": {
			"emptyTable":			"No hay datos disponibles en la tabla.",
			"info":		   			"Del _START_ al _END_ de _TOTAL_ ",
			"infoEmpty":			"Mostrando 0 registros de un total de 0.",
			"infoFiltered":			"(filtrados de un total de _MAX_ registros)",
			"infoPostFix":			" (actualizados)",
			"lengthMenu":			"Mostrar _MENU_ registros",
			"loadingRecords":		"Cargando...",
			"processing":			"Procesando...",
			"search":				"Buscar:",
			"searchPlaceholder":	"Dato para buscar",
			"zeroRecords":			"No se han encontrado coincidencias.",
			"paginate": {
				"first":			"Primera",
				"last":				"Última",
				"next":				"Siguiente",
				"previous":			"Anterior"
			},
			"aria": {
				"sortAscending":	"Ordenación ascendente",
				"sortDescending":	"Ordenación descendente"
			}
		},
		"dom": '<B><f><t>ip',
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
        "scrollX": true,
        "ajax": {
            "url": TEMA+'/admin/backend/nps/detalle/ajax/feedback_respuestas_detalle.php',
            "data": { 'id': ID },
            "type": "POST"
        }
	});
}

function abrir_link(e){
	init_modal({
		"titulo": e.attr("data-titulo"),
		"modulo": "nps/preguntas",
		"modal": e.attr("data-modal"),
		"info": {
			"ID": e.attr("data-id")
		}
	});
}

function load_dashboard(){
	// NPS General
	calcular_score_nps();

	// Grafico #1 - NPS por Dia
	jQuery.post(
		TEMA+'/admin/backend/nps/detalle/ajax/feedback_por_dia.php',
		{ 'id': ID },
		function(data){
			grafico_nps_por_dia( data );
		},
	'json');

	// Grafico #2 Feedback recibidos
	jQuery.post(
		TEMA+'/admin/backend/nps/detalle/ajax/feedback_recibidos.php',
		{ 'id': ID },
		function(data){
			var label = [
		        'Completados',
		        'Pendientes',
		    ];
		    if( data.total > 0 ){
				grafico_feedback_recibidos( data );
		    }else{
		    	jQuery('#feedback_recibidos_container').html(
		    		'<div style="padding:20px 0px">La campa&ntilde;a a&uacute;n no posee destinatarios<div>'
		    	);
		    }
		},
	'json');

	// Grafico #3 Feedback Media
	jQuery.post(
		TEMA+'/admin/backend/nps/detalle/ajax/feedback_media.php',
		{ 'id': ID },
		function(data){
			jQuery('#progress-media').css('height', data.porcentaje+'%');
			jQuery('#text-media').html( data.media );
		},
	'json');
	
	// Grafico #4 Feedback Detalle
	jQuery.post(
		TEMA+'/admin/backend/nps/detalle/ajax/feedback_por_ptos.php',
		{ 'id': ID },
		function(data){
			desglose_de_puntuacion( data );
		},
	'json');
}

function calcular_score_nps(){
	jQuery.post(
		TEMA+'/admin/backend/nps/detalle/ajax/score_nps_global.php',
		{ 'id': ID },
		function(data){
			if( data.total_rows > 0 ){
				jQuery('#score_nps_global').html( data.score_nps + ' <small style="font-size:12px;"> NPS</small>');
				jQuery('#score_nps_progress').html( data.progress );

				jQuery('#grafico-score').html( data.score_nps );

				var score = data.score_nps;
				if( score <= 0 ){
					score = ( score * -1 ) / 2;
					score = 50 - score;
				}else{
					score = ( score ) / 2;
					score = 50 + score;					
				}
				jQuery('#grafico-score').css( 'left', score+'%' );

				// Graficos Score Detalle
				jQuery('#desglose-promoters-total').html( data.promoters.porcentaje+'%' );
				jQuery('#desglose-promoters').css( 'width', data.promoters.porcentaje+'%' );

				jQuery('#desglose-pasivos-total').html( data.pasivos.porcentaje+'%' );
				jQuery('#desglose-pasivos').css( 'width', data.pasivos.porcentaje+'%' );

				jQuery('#desglose-detractores-total').html( data.detractores.porcentaje+'%' );
				jQuery('#desglose-detractores').css( 'width', data.detractores.porcentaje+'%' );
			}
		},
	'json');
}

function grafico_feedback_recibidos(data){
	console.log(data);
 
	var feedback_recibidos = new Chart('feedback_recibidos', {
	  type: 'doughnut',
	  data: {
        labels: ['Completados', 'Pendientes'],
        datasets: [
          {
            label: 'Total enviados '+data.total,
            data: [data.recibidos, data.completado],
            backgroundColor: [
              '#5cb85c',
            ]
          }
        ]
	  },
	  options: {
	    plugins: {
	      // render 'label', 'value', 'percentage', 'image' or custom function, default is 'percentage'
	      	labels: {
      	        render: 'value',
      	        fontColor:['white'],
      	        precision: 2,
			}
	    },
		elements: {
			center: {
				title: 'Enviados',
				text: data.total,
				fontSize: '12px',
			}
		}
	  }
	});

	createChart('feedback_recibidos', 'doughnut', {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          labels: [
            {
              render: 'label',
              position: 'outside'
            },
            {
              render: 'percentage'
            }
          ]
        }
	});

	/*
	var feedback_recibidos = new Chart('feedback_recibidos', {
	    type: 'doughnut',
	    data: {
		    datasets: [{
		        data: [data.recibidos, data.total],
		        backgroundColor: ['#5cb85c']
		    }],
		    labels: label
		},
	});
	*/	
}

function grafico_nps_por_dia(data){
	var chart = AmCharts.makeChart("nps_por_dia", {
	  "type": "serial",
	  "theme": "light",
	  "legend": {
	    "equalWidths": false,
	    "useGraphSettings": true,
	    "valueAlign": "left",
	    "valueWidth": 120
	  },
	  "dataProvider": data,
	  "valueAxes": [{
	    "id": "totalAxis",
	    "axisAlpha": 0,
	    "gridAlpha": 0,
	    "position": "left",
	    "title": "total"
	  }],
	  "graphs": [ {
	    "balloonText": "<div style='text-align:left;font-weight:bold;'>Fecha: [[date]]</div>"+
	    	"<div style='text-align:left;'>NPS: [[score_nps]]</div>"+
	    	"<div style='text-align:left;'>Promoters: [[promoters_porcentaje]]%</div>"+
	    	"<div style='text-align:left;'>Pasivos: [[pasivos_porcentaje]]%</div>"+
	    	"<div style='text-align:left;'>Detractores: [[detractores_porcentaje]]%</div>",
	    "bullet": "round",
	    "bulletBorderAlpha": 1,
	    "useLineColorForBulletBorder": true,
	    "bulletColor": "#FFFFFF",
	    "bulletSizeField": "300",
	    "dashLengthField": "dashLength",
	    "descriptionField": "date",
	    "labelPosition": "right",
	    "labelText": "",
	    "legendValueText": "[[value]]/[[description]]",
	    "title": "Total",
	    "fillAlphas": 0,
	    "valueField": "total",
	    "valueAxis": "totalAxis"
	  }],
	  "chartCursor": {
	    "categoryBalloonDateFormat": "MMM-DD",
	    "cursorAlpha": 0.1,
	    "cursorColor": "#000000",
	    "fullWidth": true,
	    "valueBalloonsEnabled": false,
	    "zoomable": false
	  },
	  "dataDateFormat": "YYYY-MM-DD",
	  "categoryField": "date",
	  "categoryAxis": {
	    "dateFormats": [{
	      "period": "DD",
	      "format": "DD"
	    }, {
	      "period": "WW",
	      "format": "MMM DD"
	    }, {
	      "period": "MM",
	      "format": "MMM"
	    }, {
	      "period": "YYYY",
	      "format": "YYYY"
	    }],
	    "parseDates": true,
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
	      console.log("x: " + x, "y: " + y);
	    }
	  }]
	});
}

function desglose_de_puntuacion(data){
	var chart = AmCharts.makeChart("grafico_detalle", {
	  "type": "serial",
	  "theme": "light",
	  "legend": {
	    "equalWidths": false,
	    "useGraphSettings": true,
	    "valueAlign": "left",
	    "valueWidth": 120
	  },
	  "dataProvider": data,
	  "valueAxes": [{
	    "id": "totalAxis",
	    "axisAlpha": 0,
	    "gridAlpha": 0,
	    "position": "left",
	    "title": "total"
	  }],
	  "graphs": [ {
        "alphaField": "alpha",
	    "balloonText": "[[total]]",
	    "dashLengthField": "dashLength",
	    "fillAlphas": 0.7,
	    "legendPeriodValueText": "",
	    "legendValueText": "[[total]]",
	    "title": "Puntos",
	    "type": "column",
	    "valueField": "total",
	    "valueAxis": "totalAxis",
	    "colorField": "color",
	    "lineColorField": "color"
	  }],
	  "chartCursor": {
	    "categoryBalloonDateFormat": "DD",
	    "cursorAlpha": 0.1,
	    "cursorColor": "#000000",
	    "fullWidth": true,
	    "valueBalloonsEnabled": false,
	    "zoomable": false
	  },
	  "dataDateFormat": "DD",
	  "categoryField": "date",
	  "categoryAxis": {
	    "dateFormats": [{
	      "period": "DD",
	      "format": "DD"
	    }, {
	      "period": "WW",
	      "format": "MMM DD"
	    }, {
	      "period": "MM",
	      "format": "MMM"
	    }, {
	      "period": "YYYY",
	      "format": "YYYY"
	    }],
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
	      console.log("x: " + x, "y: " + y);
	    }
	  }]
	});
}





 