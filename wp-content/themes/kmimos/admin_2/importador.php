<?php
	echo "
		<script type'text/javascript'> var TEMA = '".getTema()."'; </script>

		<script src='".getTema()."/admin/recursos/js/jquery-1.12.4.min.js'></script>
		<script src='".getTema()."/admin/recursos/js/jquery.dataTables.min.js'></script>
		<script src='".getTema()."/admin/recursos/js/dataTables.bootstrap4.min.js'></script>
		<script src='".getTema()."/admin/recursos/js/dataTables.buttons.min.js'></script>
		<script src='".getTema()."/admin/recursos/js/buttons.flash.min.js'></script>
		<script src='".getTema()."/admin/recursos/js/jszip.min.js'></script>
		<script src='".getTema()."/admin/recursos/js/index.js?v=".time()."'></script>
		<script src='".getTema()."/admin_2/recursos/index.js?v=".time()."'></script>

		<link rel='stylesheet' type='text/css' href='".getTema()."/admin/recursos/css/bootstrap.css'>
		<link rel='stylesheet' type='text/css' href='".getTema()."/admin/recursos/css/dataTables.bootstrap4.min.css'>
		<link rel='stylesheet' type='text/css' href='".getTema()."/admin/recursos/css/buttons.dataTables.min.css'>
		<link rel='stylesheet' type='text/css' href='".getTema()."/admin/recursos/css/index.css?v=".time()."'>
		<link rel='stylesheet' type='text/css' href='".getTema()."/admin_2/recursos/index.css?v=".time()."'>
	";
?>