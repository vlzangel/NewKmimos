<?php
	include 'wp-load.php';
	
	if( !isset($_SESSION) ){ session_start(); }

	// update_ubicacion();
	// update_titulo();
	// update_cuidador_url();

	// update_servicios();

	// pre_carga_data_cuidadores();

	// echo serialize([ "subscriber" => true ]);

	// update_titulo();
	
	update_precios_paseos();
?>