<?php
	include("../../../../../vlz_config.php");
	include("../funciones/db.php");

	$conn = new mysqli($host, $user, $pass, $db);
	$db = new db($conn);

	extract($_POST);
	$datos = [];
	if( isset($is_moral) ){
		$WHERE = '';
		if( $is_moral == 1 ){
			$WHERE = " WHERE moral = 1 ";
		}

		$sql = "SELECT * FROM facturas_uso_cfdi {$WHERE} ORDER BY descripcion ASC";
		$r = $db->get_results( $sql );
		foreach ($r as $key => $value) {
			$datos[] = [
				"clave" => $value->clave,
				"codigo"=> $value->codigo,
				"name" 	=> utf8_encode($value->descripcion),
			];
		}
		$_datos = json_encode( $datos, JSON_UNESCAPED_UNICODE );
		print_r( $_datos );
	}

