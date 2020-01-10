<?php
	$raiz = dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))));
	include_once($raiz."/wp-load.php");
	
	extract($_POST);
	global $wpdb;

	$_provincias = $wpdb->get_results( "SELECT * FROM colonias WHERE estado = '{$state}' AND municipio = '{$provincia}' ORDER BY name ASC" );

	if( count($_provincias) > 0 ){
		echo '<option value="">Seleccione...</option>';
		foreach ($_provincias as $key => $provincia) {
			echo '<option value="'.$provincia->id.'" >'.($provincia->name).'</option>';
		}
	}else{
		$_name = $wpdb->get_var( "SELECT name FROM locations WHERE id = '{$provincia}' " );
		echo '<option value="-" >'.utf8_decode($_name).'</option>';
	}

	die();
?>