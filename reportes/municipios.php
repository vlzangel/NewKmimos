<?php
	include_once dirname(__DIR__).'/wp-load.php';

	global $wpdb;
	
	$municipios = [];
	$colonias = [];

	$path_functions = dirname(__FILE__)."/colonias/";
	$directorio = opendir( $path_functions );
	while ($archivo = readdir($directorio)) {
		if( $archivo != "." && $archivo != ".." ){
			if( $archivo == "5.csv" ){
		        $ruta = $path_functions."/".$archivo;
			    if ( file_exists($ruta) ) {
			    	$registros = []; $z = false;
			        $file = fopen($ruta, "r");
					while( !feof( $file ) ){
						$data = fgets($file);
						$temp = explode(",", $data );
						if( $z && $temp[4] != '' && $temp[4] != 'd_estado' ){
							if( !in_array($temp[3], $municipios) ){
								$municipios[] = $temp[3];
							}
							$colonias[ 12 ][ $temp[3] ][] = $temp[1];
						}
						$z = true;
					}
					fclose($file);
			    }
			}
		}
	}

	$_munis = [];
	foreach ($municipios as $key => $municipio) {
		// if( $municipio != "Colima" ){
			$_municipio = utf8_encode($municipio);
			$sql = "
				INSERT INTO locations VALUES (
					NULL,
					'{$_municipio}',
					11,
					'{$_municipio}',
					9999
				)
			";
			$wpdb->query($sql);
			$id = $wpdb->insert_id;
			$_munis[ md5( $municipio ) ] = $id;
		// }else{
		// 	$_munis[ md5( $municipio ) ] = 2526;
		// }
	}

	// 121705
	// 12
	// 2526

	/*
	foreach ($colonias[12] as $muni => $munis) {
		foreach ($munis as $colonia) {
			$colonia = utf8_encode($colonia);
			$muni_id = $_munis[ md5( $muni ) ];
			$sql = "
				INSERT INTO colonias VALUES (
					NULL,
					12,
					{$muni_id},
					'{$colonia}'
				)
			";
			$wpdb->query($sql);
		}
	}
	*/
	/*
	echo "<pre>";
		print_r( $colonias );
	echo "</pre>";
	*/
?>