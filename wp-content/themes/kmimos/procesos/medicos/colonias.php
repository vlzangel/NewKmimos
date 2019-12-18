<?php
	$raiz = dirname(dirname(dirname(dirname(dirname(__DIR__)))));
	include_once($raiz."/wp-load.php");
	
	extract($_POST);
	global $wpdb;

	$_provincias = $wpdb->get_results( "SELECT * FROM colonias WHERE estado = '{$state}' AND municipio = '{$provincia}' ORDER BY name ASC" );

	echo '<option value="">Seleccione...</option>';
	foreach ($_provincias as $key => $provincia) {
		echo '<option value="'.$provincia->id.'" >'.($provincia->name).'</option>';
	}

	die();
?>