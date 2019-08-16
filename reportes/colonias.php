<?php
	include_once dirname(__DIR__).'/wp-load.php';
	
	$arbol = [];
	$arbol_all = [];

	$path_functions = dirname(__FILE__)."/colonias/";
	$directorio = opendir( $path_functions );
	while ($archivo = readdir($directorio)) {
		if( $archivo != "." && $archivo != ".." ){
	        $ruta = $path_functions."/".$archivo;
		    if ( file_exists($ruta) ) {
		    	$registros = []; $z = false;
		        $file = fopen($ruta, "r");
				while(!feof($file)){
					$data = fgets($file);
					$temp = explode(",", $data );
					if( $z && $temp[4] != '' && $temp[4] != 'd_estado' ){
						if( !in_array($temp[3], $arbol[ $temp[4] ]) ){
							$arbol[ $temp[4] ][] = $temp[3];
						}
						$arbol_all[ $temp[4] ][ $temp[3] ][] = $temp[1];
					}
					$z = true;
				}
				fclose($file);
		    }
		}
	}

	$municipios = [];
	foreach ($arbol as $estado_txt => $_mups) {
		foreach ($_mups as $key => $municipio_txt) {
			$txt = utf8_encode($municipio_txt);
			$municipios[$municipio_txt] = $wpdb->get_var("SELECT id FROM locations WHERE name LIKE '%{$txt}%' ");
		}
	}

	$arbol_all_new = [];
	foreach ($arbol_all as $_estado_txt => $_muns) {
		$txt = utf8_encode($_estado_txt);
		$estado = $wpdb->get_var("SELECT id FROM states WHERE name LIKE '%{$txt}%' ");
		foreach ($_muns as $_mun_txt => $_cols) {
			$arbol_all_new[ $estado ][ $municipios[$_mun_txt] ] = $_cols;
		}
	}

	foreach ($arbol_all_new as $estado_id => $_muns) {
		foreach ($_muns as $_mun_id => $_cols) {
			foreach ($_cols as $key => $colonia) {
				$SQL = "INSERT INTO colonias VALUES (
					NULL,
					{$estado_id},
					{$_mun_id},
					'{$colonia}'
				);";
				// echo $SQL.'<br>';
				$wpdb->query( $SQL );
			}
			sleep(1);
		}
		sleep(1);
	}

	/*
	
	echo "<pre>";
		print_r( $arbol_all_new );
		// print_r( $arbol_all_new );
	echo "</pre>";

	/*
	$registros = explode("\n", $CSV);
	foreach ($registros as $key => $registro) {
		$temp = explode(",", $registro);
		if( !in_array($temp[3], $arbol[ $temp[4] ]) ){
			$arbol[ $temp[4] ][] = $temp[3];
		}
		$arbol_all[ $temp[4] ][ $temp[3] ][] = $temp[1];
	}

	include_once dirname(__DIR__).'/wp-load.php';

	$municipios = [];
	foreach ($arbol["Aguascalientes"] as $key => $municipio_txt) {
		$txt = utf8_encode($municipio_txt);
		$municipios[$municipio_txt] = $wpdb->get_var("SELECT id FROM locations WHERE name LIKE '%{$txt}%' ");
	}
	
	$arbol_all_new = [];
	foreach ($arbol_all as $_estado_txt => $_muns) {
		$txt = utf8_encode($_estado_txt);
		$estado = $wpdb->get_var("SELECT id FROM states WHERE name LIKE '%{$txt}%' ");
		foreach ($_muns as $_mun_txt => $_cols) {
			$arbol_all_new[ $estado ][ $municipios[$_mun_txt] ] = $_cols;
		}
	}

	echo "<pre>";
		print_r( $municipios );
		print_r( $arbol_all_new );
	echo "</pre>";
	*/
	
?>