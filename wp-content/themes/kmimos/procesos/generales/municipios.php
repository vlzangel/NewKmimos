<?php
	include("../../../../../vlz_config.php");
	include("../funciones/db.php");

	$conn = new mysqli($host, $user, $pass, $db);
	$db = new db($conn);

	if( isset($_GET['estado']) ){

		extract($_GET);

		$sql = "SELECT * FROM locations WHERE state_id = '{$estado}' ORDER BY name ASC";
		$r = $db->get_results( $sql );

 
		if( !empty($r) ){
			foreach ($r as $key => $value) {
				$municipios[] = array(
					"id" 	=> $value->id,
					"name" 	=> $value->name
				);
			}
		}

		if( empty($municipios) ){
			$es = "SELECT * FROM states WHERE country_id = 1 AND id = '{$estado}' ORDER BY name ASC";
			$r = $db->get_results( $es );

			foreach ($r as $key => $value) {
				$municipios[] = array(
					"id" 	=> $value->id,
					"name" 	=> $value->name
				);
			}
		}

		echo json_encode($municipios);

	}
?>