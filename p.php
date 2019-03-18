<?php
	include 'wp-load.php';
	
	if( !isset($_SESSION) ){ session_start(); }

	global $wpdb;

	// update_ubicacion();
	// update_titulo();
	// update_cuidador_url();
	// update_servicios();
	// pre_carga_data_cuidadores();
	// echo serialize([ "subscriber" => true ]);
	// update_titulo();
	// update_precios_paseos();

	/*
	echo '<pre>';
		print_r($_SESSION["DATA_CUIDADORES"]);
	echo '</pre>';
	*/

	$cuidadores = $wpdb->get_results("SELECT * FROM cuidadores");
	$cambios_total = 0;
	foreach ($cuidadores as $indice => $cuidador) {
		if( $cuidador->hospedaje_desde > 0 && $cuidador->hospedaje_desde < 40 ){
			$cuidador->hospedaje_desde = 40;
			$wpdb->query("UPDATE cuidadores SET hospedaje_desde = '40' WHERE id = ".$cuidador->id);
		}
		if( $cuidador->paseos_desde > 0 && $cuidador->paseos_desde < 40 ){
			$cuidador->paseos_desde = 40;
			$wpdb->query("UPDATE cuidadores SET paseos_desde = '40' WHERE id = ".$cuidador->id);
		}
		$adicionales = unserialize($cuidador->adicionales);
		$cambio = false;
		foreach ($adicionales as $key => $value) {
			if( count($value) > 3 ){
				foreach ($value as $key_2 => $value_2 ) {
					if( $value_2 > 0 && $value_2 < 40 ){
						$adicionales[ $key ][ $key_2 ] = 40;
						$cambio = true;
					}
				}
			}
		}
		if( $cambio ){
			$cambios_total++;
			$cuidadores[ $indice ]->adicionales = serialize( $adicionales );
			$wpdb->query("UPDATE cuidadores SET adicionales = '".serialize( $adicionales )."' WHERE id = ".$cuidador->id);
		}
	}
	echo $cambios_total;
?>