<?php
	include("../../../../../vlz_config.php");
	include("../funciones/db.php");

	$conn = new mysqli($host, $user, $pass, $db);
	$db = new db($conn);

	extract($_GET);

	if( isset($_GET['estado']) && isset($_GET['municipio']) ){
		$sql = "SELECT * FROM colonias WHERE estado = '{$estado}' AND municipio = '{$municipio}' ORDER BY name ASC";
		$r = $db->get_results( $sql );
		$colonias = [];
		foreach ($r as $key => $value) {
			$colonias[] = array(
				"id" 	=> $value->id,
				"name" 	=> utf8_encode($value->name),
			);
		}
		echo json_encode($colonias);

	}else{
		if( isset($_GET['estado']) ){
			$sql = "SELECT * FROM locations WHERE state_id = '{$estado}' ORDER BY name ASC";
			$r = $db->get_results( $sql );
			$municipios = [];
			foreach ($r as $key => $value) {
				$municipios[] = array(
					"id" 	=> $value->id,
					"name" 	=> $value->name,
				);
			}
			echo json_encode($municipios);
		}
	}
?>