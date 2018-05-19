<?php
$ruta = get_home_url()."/panel/assets/";
echo "
	<script type'text/javascript'> var TEMA = '".getTema()."'; </script>
	<script type'text/javascript'> var HOME = '".get_home_url()."'; </script>

	<link rel='stylesheet' type='text/css' href='{$ruta}vendor/bootstrap/dist/css/bootstrap.css' >
	<link rel='stylesheet' type='text/css' href='{$ruta}vendor/font-awesome/css/font-awesome.min.css' >
	<link rel='stylesheet' type='text/css' href='{$ruta}vendor/datatables.net-bs/css/dataTables.bootstrap.min.css' >
	<link rel='stylesheet' type='text/css' href='{$ruta}vendor/datatables.net-buttons-bs/css/buttons.bootstrap.min.css' >
	<link rel='stylesheet' type='text/css' href='{$ruta}vendor/datatables.net-responsive-bs/css/responsive.bootstrap.min.css' >
	<link rel='stylesheet' type='text/css' href='{$ruta}vendor/datatables.net-scroller-bs/css/scroller.bootstrap.min.css' >

	<script type='text/javascript' charset='utf8' src='{$ruta}vendor/bootstrap/dist/js/bootstrap.min.js'></script>
	<script type='text/javascript' charset='utf8' src='{$ruta}js/jszip.min.js'></script>
	<script type='text/javascript' charset='utf8' src='{$ruta}vendor/datatables.net/js/jquery.dataTables.js'></script>
	<script type='text/javascript' charset='utf8' src='{$ruta}vendor/datatables.net-bs/js/dataTables.bootstrap.min.js'></script>
	<script type='text/javascript' charset='utf8' src='{$ruta}vendor/datatables.net-buttons/js/dataTables.buttons.min.js'></script>
	<script type='text/javascript' charset='utf8' src='{$ruta}vendor/datatables.net-buttons-bs/js/buttons.bootstrap.min.js'></script>
	<script type='text/javascript' charset='utf8' src='{$ruta}vendor/datatables.net-buttons/js/buttons.flash.min.js'></script>
	<script type='text/javascript' charset='utf8' src='{$ruta}vendor/datatables.net-buttons/js/buttons.html5.min.js'></script>
	<script type='text/javascript' charset='utf8' src='{$ruta}vendor/datatables.net-buttons/js/buttons.print.min.js'></script>
	<script type='text/javascript' charset='utf8' src='{$ruta}vendor/datatables.net-scroller/js/dataTables.scroller.min.js'></script>

	 
	<script type='text/javascript' charset='utf8' src='".get_home_url()."/monitor/recursos/js/global.js'></script>
	";
