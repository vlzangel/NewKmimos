<?php
require_once( dirname(__DIR__).'/reportes/class/general.php' );

$g = new general();

$plataforma_list = [];

$plataformas = $g->get_plataforma();

$ruta = "http://mx.kmimos.la/";

echo "
	<script type'text/javascript'> 
		var HOME = '{$ruta}'; 
		var plataformas = ".json_encode($plataformas).";
		var data = [];
		var grafico = {};
		var sucursal = 'global';
		var periodo = 'mensual';
	</script>

	<link rel='stylesheet' type='text/css' href='{$ruta}/panel/assets/vendor/bootstrap/dist/css/bootstrap.css' >
	<link rel='stylesheet' type='text/css' href='{$ruta}/panel/assets/vendor/font-awesome/css/font-awesome.min.css' >
	<link rel='stylesheet' type='text/css' href='{$ruta}/panel/assets/vendor/datatables.net-bs/css/dataTables.bootstrap.min.css' >
	<link rel='stylesheet' type='text/css' href='{$ruta}/panel/assets/vendor/datatables.net-buttons-bs/css/buttons.bootstrap.min.css' >
	<link rel='stylesheet' type='text/css' href='{$ruta}/panel/assets/vendor/datatables.net-responsive-bs/css/responsive.bootstrap.min.css' >
	<link rel='stylesheet' type='text/css' href='{$ruta}/panel/assets/vendor/datatables.net-scroller-bs/css/scroller.bootstrap.min.css' >
	<link rel='stylesheet' type='text/css' href='{$ruta}/monitor/recursos/css/global.css' >

	<script type='text/javascript' charset='utf8' src='{$ruta}/monitor/recursos/js/jquery.min.js'></script>

	<script type='text/javascript' charset='utf8' src='{$ruta}/panel/assets/vendor/bootstrap/dist/js/bootstrap.min.js'></script>
	<script type='text/javascript' charset='utf8' src='{$ruta}/panel/assets/js/jszip.min.js'></script>
	<script type='text/javascript' charset='utf8' src='{$ruta}/panel/assets/vendor/datatables.net/js/jquery.dataTables.js'></script>
	<script type='text/javascript' charset='utf8' src='{$ruta}/panel/assets/vendor/datatables.net-bs/js/dataTables.bootstrap.min.js'></script>
	<script type='text/javascript' charset='utf8' src='{$ruta}/panel/assets/vendor/datatables.net-buttons/js/dataTables.buttons.min.js'></script>
	<script type='text/javascript' charset='utf8' src='{$ruta}/panel/assets/vendor/datatables.net-buttons-bs/js/buttons.bootstrap.min.js'></script>
	<script type='text/javascript' charset='utf8' src='{$ruta}/panel/assets/vendor/datatables.net-buttons/js/buttons.flash.min.js'></script>
	<script type='text/javascript' charset='utf8' src='{$ruta}/panel/assets/vendor/datatables.net-buttons/js/buttons.html5.min.js'></script>
	<script type='text/javascript' charset='utf8' src='{$ruta}/panel/assets/vendor/datatables.net-buttons/js/buttons.print.min.js'></script>
	<script type='text/javascript' charset='utf8' src='{$ruta}/panel/assets/vendor/datatables.net-scroller/js/dataTables.scroller.min.js'></script>

	<script type='text/javascript' charset='utf8' src='{$ruta}/monitor/recursos/js/global.js'></script>


	";

