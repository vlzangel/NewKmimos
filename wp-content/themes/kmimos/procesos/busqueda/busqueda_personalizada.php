<?php
	
	error_reporting(0);

    date_default_timezone_set('America/Mexico_City');

	session_start();

	include(realpath(__DIR__."/../../../../../wp-load.php"));

	global $wpdb;
	$db = $wpdb; 

	$hoy = date("d/m/Y");
	$manana = date("d/m/Y", strtotime("+1 day") );	

	/*
		Array
		(
		    [mascotas_propias] => 1
		    [con_transporte] => 1
		    [areas_verdes] => 1
		    [es_agresiva] => 1
		)
	*/


	$ubicaciones_inner = '';
	$nombre_inner = '';
	$ubicaciones_filtro = "";
	$latitud = (isset($latitud))? $latitud: "";
	$longitud = (isset($longitud))? $longitud: "";

	extract($_POST);

	$DESCUENTO_CONDICION .= " AND atributos LIKE '%destacado_home\";s:1:\"1%' "; 

	if( $mascotas_propias == 1 ){
		$DESCUENTO_CONDICION .= " AND num_mascotas > 0 "; 
	}

	if( $areas_verdes == 1 ){
		$DESCUENTO_CONDICION .= " AND atributos LIKE '%green\";s:1:\"1%' ";  
	}

	if( $es_agresiva == 1 ){
		$DESCUENTO_CONDICION .= " AND comportamientos_aceptados LIKE '%no_sociables\";s:1:\"1%' ";  
	}

	if( $es_agresiva == 1 ){
		$DESCUENTO_CONDICION .= " AND ( adicionales LIKE '%status_transportacion_sencilla\";s:1:\"1%' OR adicionales LIKE '%status_transportacion_redonda\";s:1:\"1%' ) ";  
	}


    /* Filtros por Gatos */

    	$GATOS_CONDICION = "";
    	if( is_array($mascotas) ){
	    	if( in_array("gatos", $mascotas) ){
	    		$GATOS_CONDICION .= " AND atributos LIKE '%gatos\";s:2:\"Si%' "; 
	    	}
    	}

	if( $USER_ID+0 > 0 ){

		$filtros = array(
			"agresivo_mascotas" => 0,
			"agresivo_personas" => 0,
			"pequenos" => 0,
			"medianos" => 0,
			"grandes" => 0,
			"gigantes" => 0,
			"pet_sociable" => 0,
			"comportamiento_gatos" => []
		);

		$filtrar_tamanios = true;
		$solo_perros = false;
		$solo_gatos = false;
		if( is_array($mascotas) ){
	    	if( $mascotas[0] == "gatos" && count($mascotas) == 1 ){
				$filtrar_tamanios = false;
			}
		}else{
			$mascotas = [
				"perros"
			];
		}

		if( $mascotas[0] == "perros" && count($mascotas) == 1 ){
			$solo_perros = true;
		}

		if( $mascotas[0] == "gatos" && count($mascotas) == 1 ){
			$solo_gatos = true;
		}

		if( count($mascotas) == 2 ){
			$solo_gatos = false;
			$solo_perros = false;
		}

		$_mascotas = $db->get_results("SELECT * FROM wp_posts WHERE post_author = '{$USER_ID}' AND post_type = 'pets' AND post_status = 'publish' ");
		$perros = 0; $gatos = 0;
		foreach ($_mascotas as $key => $value) {
			$_metas = $db->get_results("SELECT * FROM wp_postmeta WHERE post_id = '{$value->ID}' AND meta_key IN ('aggressive_with_humans', 'aggressive_with_pets', 'size_pet', 'comportamiento_gatos', 'pet_type', 'pet_sociable')");
			$metas = array();
			foreach ($_metas as $key2 => $value2) {
				$metas[ $value2->meta_key ] = $value2->meta_value;
			}
			foreach ($_metas as $key2 => $value2) {
				switch ( $value2->meta_key ) {
					case 'aggressive_with_humans':
						if( $metas[ 'pet_type' ] == '2605' && $value2->meta_value == 1 ){
							$filtros["agresivo_personas"] = 1;
						}
					break;
					case 'aggressive_with_pets':
						if( $metas[ 'pet_type' ] == '2605' && $value2->meta_value == 1 ){
							$filtros["agresivo_mascotas"] = 1;
						}
					break;
					case 'pet_sociable':
						if( $metas[ 'pet_type' ] == '2605' && $value2->meta_value == 0 ){
							$filtros["pet_sociable"] = 1;
						}
					break;
					case 'size_pet':
						if( $metas[ 'pet_type' ] == '2605' && $filtrar_tamanios ){
							switch ($value2->meta_value) {
								case 0:
									$filtros["pequenos"] = 1;
								break;
								case 1:
									$filtros["medianos"] = 1;
								break;
								case 2:
									$filtros["grandes"] = 1;
								break;
								case 3:
									$filtros["gigantes"] = 1;
								break;
							}
						}
					break;
					case 'comportamiento_gatos':
						$temp_comp = json_decode($value2->meta_value);
						foreach ($temp_comp as $key => $value) {
							if( !in_array($key, $filtros["comportamiento_gatos"]) ){
								if( $value == 1 ){
									$filtros["comportamiento_gatos"][ $key ] = 1;
								}
							}
						}
					break;
					case 'pet_type':
						if( $value2->meta_value == '2605' ){
							$perros++;
						}
						if( $value2->meta_value == '2608' ){
							$gatos++;
						}
					break;
				}
			}
		}

		$FILTRO_ESPECIA = array();

		//if( $filtrar_perros ){
		if( $perros > 0 && $solo_gatos == false ){

			if( $filtros["agresivo_mascotas"] == 1 ){
				$FILTRO_ESPECIA[] = " (  cuidadores.comportamientos_aceptados LIKE '%agresivos_perros\";i:1%' OR  cuidadores.comportamientos_aceptados LIKE '%agresivos_perros\";s:1:\"1%' ) ";
			}

			if( $filtros["agresivo_personas"] == 1 ){
				$FILTRO_ESPECIA[] = " (  cuidadores.comportamientos_aceptados LIKE '%agresivos_personas\";i:1%' OR  cuidadores.comportamientos_aceptados LIKE '%agresivos_personas\";s:1:\"1%' ) ";
			}

			if( $filtros["pet_sociable"] == 1 ){
				$FILTRO_ESPECIA[] = " (  cuidadores.comportamientos_aceptados LIKE '%no_sociables\";i:1%' OR cuidadores.comportamientos_aceptados LIKE '%no_sociables\";s:1:\"1%' ) ";
			}

			if( $filtros["pequenos"] == 1 ){
				$FILTRO_ESPECIA[] = " (  cuidadores.tamanos_aceptados LIKE '%pequenos\";i:1%' OR  cuidadores.tamanos_aceptados LIKE '%pequenos\";s:1:\"1%' ) ";
			}

			if( $filtros["medianos"] == 1 ){
				$FILTRO_ESPECIA[] = " (  cuidadores.tamanos_aceptados LIKE '%medianos\";i:1%' OR  cuidadores.tamanos_aceptados LIKE '%medianos\";s:1:\"1%' ) ";
			}

			if( $filtros["grandes"] == 1 ){
				$FILTRO_ESPECIA[] = " (  cuidadores.tamanos_aceptados LIKE '%grandes\";i:1%' OR  cuidadores.tamanos_aceptados LIKE '%grandes\";s:1:\"1%' ) ";
			}

			if( $filtros["gigantes"] == 1 ){
				$FILTRO_ESPECIA[] = " (  cuidadores.tamanos_aceptados LIKE '%gigantes\";i:1%' OR  cuidadores.tamanos_aceptados LIKE '%gigantes\";s:1:\"1%' ) ";
			}

		}

		// if( $filtrar_gatos ){
		if( $gatos > 0 && $solo_perros == false ){
			if( $filtros["comportamiento_gatos"] != "" ){
				foreach ($filtros["comportamiento_gatos"] as $key_comportamiento_gato => $value_comportamiento_gato) {
					if( $value_comportamiento_gato == 1 ){
						$FILTRO_ESPECIA[] = " (  cuidadores.comportamientos_aceptados LIKE '%".$key_comportamiento_gato."\";s:1:\"1%' ) ";
					}
				}
			}
		}

		if( count($FILTRO_ESPECIA) > 0 ){
			$FILTRO_ESPECIA = " AND ( ".implode(" AND ", $FILTRO_ESPECIA)." )";
		}else{
			$FILTRO_ESPECIA = "";
		}

	}

	$condiciones = "";

    /* Filtros por fechas */

    	$FLASH = false;

	    if( isset($servicios) ){

	    	$servicios_extras = array(
		        "hospedaje",
		    	"guarderia",
		    	"paseos",
		    	"adiestramiento"
		    );

	    	$servicios_buscados = "";
			foreach ($servicios as $key => $value) {

				if( $value != "flash" ){ 
				
					if( in_array($value, $servicios_extras) ){ 
						if( $servicios_buscados == "" ){
							$servicios_buscados .= " cupos.tipo LIKE '%{$value}%' ";
						}else{
							$servicios_buscados .= " OR cupos.tipo LIKE '%{$value}%' ";
						}
					}
					if( $value != "hospedaje" ){
						// $condiciones .= " AND adicionales LIKE '%".$value."%'";
						// if( in_array($value, $servicios_extras) ){ 
							if( strpos($value,'adiestramiento') === false){
								$condiciones .= ' AND adicionales LIKE \'%status_'.$value.'";s:1:"1%\'';
							}else{
								$condiciones .= 'AND (';
								$condiciones .= ' 	adicionales LIKE \'%status_adiestramiento_basico";s:1:"1%\' 		OR ';
								$condiciones .= ' 	adicionales LIKE \'%status_adiestramiento_intermedio";s:1:"1%\' 	OR ';
								$condiciones .= ' 	adicionales LIKE \'%status_adiestramiento_avanzado";s:1:"1%\' 			';
								$condiciones .= ')';
							}
						// }
					}

				}else{
					$FLASH = true;
				}

			}
	    	
	    	if( $servicios_buscados != "" ){
	    		$servicios_buscados = "( ".$servicios_buscados." ) AND";
	    	}
		}

		if( isset($checkin)  && $checkin  != '' && isset($checkout) && $checkout != '' ){ 

			$checkin = date("Y-m-d", strtotime( str_replace("/", "-", $checkin) ) );
			$checkout = date("Y-m-d", strtotime( str_replace("/", "-", $checkout) ) );

	    	$_no_disponibles = $db->get_results("
			SELECT 
    				cuidador
    			FROM 
    				cupos 
    			WHERE 
    				{$servicios_buscados} 
    				cupos.fecha >= '{$checkin}' AND 
    				cupos.fecha <= '{$checkout}' AND (
    					cupos.full = 1 OR 
    					cupos.no_disponible = 1
    				)
			");
			$no_disponibles = array();
			if( $_no_disponibles !== false ){
				foreach ($_no_disponibles as $cuidador) {
					$no_disponibles[] = $cuidador->cuidador;
				}
			}
			if( count($no_disponibles) > 0 ){
				$condiciones .= " AND user_id NOT IN (".implode(",", $no_disponibles).") ";
			}
	   	}


    /* Fin Filtros por fechas */


    /* Filtros por Flash */

    	$FLASH_ORDEN = "";
    	if( $FLASH ){
    		$condiciones .= " AND atributos LIKE '%flash\";s:1:\"1%' "; 
    	}else{
    		if( $hoy == $_POST["checkin"] ||  $manana == $_POST["checkin"] ){
    			$FLASH_ORDEN = ", ( 
		    			SELECT 
		    				count(*)
		    			FROM 
		    				cuidadores AS cuidadores_2
		    			WHERE 
		    				cuidadores_2.id = cuidadores.id AND
		    				atributos LIKE '%flash\";s:1:\"1%'
		    		) AS FLASH
    			";
    		}
    	}

    /* Fin Filtros por Flash */

    /* Filtros por servicios y tamaños */
	  	
	  	if( is_array($tamanos) ){
	    	foreach ($tamanos as $key => $value) {
	     		$condiciones .= " AND ( tamanos_aceptados LIKE '%\"".$value."\";i:1%' || tamanos_aceptados LIKE '%\"".$value."\";s:1:\"1\"%' ) "; 
	     	} 
     	} 
     	
    /* Fin Filtros por servicios y tamaños */

    $orderby = "valoraciones DESC, rating DESC";


    /* Filtro de busqueda */
    	$ubicacion = explode("_", $ubicacion);
    	$estados = (count($ubicacion)>0)? $ubicacion[0] : '';
    	$municipios = (count($ubicacion)>1)? $ubicacion[1]: '';
	    
	    if( $estados != "" && $municipios != "" ){
            // $ubicaciones_inner = "INNER JOIN ubicaciones AS ubi ON ( cuidadores.id = ubi.cuidador )";
            $ubicaciones_inner = "";
            $ubicaciones_filtro = "AND ( estados LIKE '%=".$estados."=%' AND municipios LIKE '%=".$municipios."=%'  )";   
            $_SESSION['km5'] = "No"; 
            $FILTRO_UBICACION = "";
	    }else{ 
	        if( $estados != "" ){
	            // $ubicaciones_inner = "INNER JOIN ubicaciones AS ubi ON ( cuidadores.id = ubi.cuidador )";
	            $ubicaciones_inner = "";
	            $ubicaciones_filtro = "AND ( estados LIKE '%=".$estados."=%' )";
	            $_SESSION['km5'] = "No";
	            $FILTRO_UBICACION = "";
	        }else{
	            if( $latitud != "" && $longitud != "" && $km5 != "No" ){
	       			$calculo_distancia 	= "( 6371 * acos( cos( radians({$latitud}) ) * cos( radians(latitud) ) * cos( radians(longitud) - radians({$longitud}) ) + sin( radians({$latitud}) ) * sin( radians(latitud) ) ) )";
	                $DISTANCIA 			= ", {$calculo_distancia} as DISTANCIA";
	               	$FILTRO_UBICACION = "HAVING DISTANCIA < 10";

	                $_SESSION['km5'] = "Yes";

	                if( $orderby == "" ){ $orderby = "DISTANCIA ASC"; }
	            }else{
	                $DISTANCIA = "";
	                $FILTRO_UBICACION = "";
	                $_SESSION['km5'] = "No";
	            }
	        }
	    }
    /* Fin Filtro de busqueda */

    if( $FLASH_ORDEN != "" ){
    	$orderby = "FLASH DESC, ".$orderby;
    }

    if( $latitud != "" && $longitud != "" && ( $estados == "" ) ){
    	$orderby = "DISTANCIA ASC";
    	$FLASH_ORDEN = "";
    }

    $home = get_home_url();

    /* SQL cuidadores */

	    $sql = "
	    SELECT 
	        c.id,
	        c.nombre
	        {$DISTANCIA}
	        {$FLASH_ORDEN}
	    FROM 
	        cuidadores AS c
	    WHERE 
	        activo = '1' 
	        {$condiciones} 
	        {$FILTRO_ESPECIA}
	    	{$GATOS_CONDICION}
	    	$DESCUENTO_CONDICION
	        {$ubicaciones_filtro} 
	        {$FILTRO_UBICACION} 
	    ORDER BY {$orderby}
	    LIMIT 0, 3";

    /* FIN SQL cuidadores */

    $_cuidadores = $db->get_results($sql);

    $ids_validos = [];
    if( $_cuidadores != false ){
		foreach ($_cuidadores as $key => $_cuidador) {
			$ids_validos[] = $_cuidador->id;
		}
    }

    $cuidadores = get_destacados_home($ids_validos);

	$_POST = @array_filter($_POST);
	$_SESSION['busqueda'] = ($_POST);
	$_SESSION['sql'] = $sql;
    $_SESSION['resultado_busqueda'] = $cuidadores;
    $_SESSION['cuidadores'] = $ids_validos;


	echo json_encode( [
		$ids_validos,
		$_cuidadores,
		$cuidadores,
		$sql
	] );

?>