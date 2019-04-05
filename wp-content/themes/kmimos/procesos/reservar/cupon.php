<?php
	if( !isset($_SESSION)){ session_start(); }

	error_reporting(0);
	ini_set('display_errors', '0');

	$raiz = dirname(dirname(dirname(dirname(dirname(__DIR__)))));
	include_once($raiz."/vlz_config.php");
	include_once("../funciones/db.php");
	include_once("../funciones/generales.php");

	
	extract($_POST);

	$db = new db( new mysqli($host, $user, $pass, $db) );

	function es_petco($db, $user_id){
		$metas = $db->get_results("SELECT meta_value FROM wp_usermeta WHERE user_id = '{$user_id}' AND ( meta_key = '_wlabel' OR meta_key = 'user_referred' ) AND meta_value LIKE '%petco%'");
		return ( $metas !== false );
	}

	function es_nuevo($db, $user_id){
		$_cant_reservas = $db->get_var("SELECT COUNT(*) FROM wp_posts WHERE post_author = {$user_id} AND post_type = 'wc_booking'"); // AND post_status != 'cancelled'
		return ( $_cant_reservas == 0 );
	}

	function cant_mascotas($mascotas){
		$cant = 0;
		foreach ($mascotas as $key => $value) {
			if( $key != "cantidad" ){
				$cant += $value[0];
			}
		}
		return $cant;
	}

	function error($msg, $data = ""){
		echo json_encode(array( 
			"error" => $msg,
			"data" => $data
		)); 
		exit;
	}

	function get_cupon($db, $cupon, $cliente){
		$cupon_post = $db->get_var("SELECT ID FROM wp_posts WHERE post_name = '{$cupon}'");
		$uso_cupon = $db->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = {$cupon_post} AND meta_key = 'uso_{$cliente}' ");
		if( $uso_cupon != false ){
			$uso_cupon = json_decode($uso_cupon);
		}
		return $uso_cupon;
	}

	function cuidador_valido($db, $servicio){
		$user_id_cuidador = $db->get_var("SELECT post_author FROM wp_posts WHERE ID = {$servicio} ");
		$cuidador_email = $db->get_row("SELECT email FROM cuidadores WHERE user_id = {$user_id_cuidador} ");

		$cuidadores_destacados = [
			"chopskasalata@gmail.com",
			"jovanovska@hotmail.com",
			"jose.antonio.carsolio@gmail.com",
			"vbazani@gmail.com",
			"veronicaqbp@hotmail.com",
			"nohemi.pflc@gmail.com",
			"espinozapalmerosluzvictoria91@gmail.com",
			"merrikc@hotmail.com",
			"brendagales@hotmail.com",
			"naz_mvz@hotmail.com",
			"jessi-taz@hotmail.com",
			"parys9@hotmail.com",
			"kanonlabcorp@outlook.com",
			"maafercg.04@gmail.com",
			"maliderezgocanino@gmail.com",
			"salma_ac98@hotmail.com",
			"tulipinkmafer@gmail.com",
			"ami_disq.55@hotmail.com",
			"belbeder88@hotmail.com",
			"david.brena1@gmail.com",
			"huesca.79@hotmail.com",
			"monficalata@gmail.com",
			"rocioicela@gmail.com",
			"sophnf04@gmail.com",
			"zara.gamez.zk@gmail.com",
			"rmz.mtz.lourdes@hotmail.com",
			"netojamaicaska@hotmail.com",
			"Alelotof@gmail.com",
			"angelica.colion@gmail.com",
			"angelveloz91@gmail.com"
		];

		return ( in_array($cuidador_email, $cuidadores_destacados) );
	}

	function ini_valido($inicio){
		return ( strtotime($inicio) < strtotime("2019-02-01 00:00:00") );
	}

	function es_destacado($db, $servicio){
		$user_id_cuidador = $db->get_var("SELECT post_author FROM wp_posts WHERE ID = {$servicio} ");
		$atributos = $db->get_var("SELECT atributos FROM cuidadores WHERE user_id = {$user_id_cuidador} ");
		$atributos = unserialize($atributos);
		return ( $atributos["destacado"]+0 == 1 );
	}

	function hasta_valido($hasta){
		return ( strtotime(date('Y-m-d H:i:s')) < strtotime($hasta) );
	}

	function aplicarCupon($params){
		/* 
			$db, 
			$cupon, 
			$cupones, 
			$total, 
			$validar, 
			$cliente, 
			$servicio, 
			$duracion, 
			$tipo_servicio 
		*/

		extract($params);

		$sub_descuento = 0; $otros_cupones = 0;

		$cupon = trim( strtolower($cupon) );

		$xcupon = $db->get_row("SELECT * FROM wp_posts WHERE post_title = '{$cupon}'");
		$xmetas = $db->get_results("SELECT * FROM wp_postmeta WHERE post_id = '{$xcupon->ID}'");
		$metas = array();
		foreach ($xmetas as $value) {
			$metas[ $value->meta_key ] = $value->meta_value;
		}

		$individual_use = ( $metas["individual_use"] == "yes" ) ? 1 : 0;

		if( $cupon != "2pagk" ){
			if( count($cupones) > 0 ){
				foreach ($cupones as $value) {
					if( $value[2] == 1 ){
						if( $validar ){ error("El cupón [ {$value[0]} ] ya esta aplicado y no puede ser usado junto a otros cupones"); }else{ return false; }
					}
				}
			}
		}
		
		/* Cupones Especiales */
			
			if( $cupon == "bnv1sb" ){
				if( !es_nuevo($db, $servicio) ){ if( $validar ){ error("El cupón solo es válido para usuarios nuevos"); }else{ return false; } }
				$se_uso = $db->get_var("SELECT count(*) FROM wp_postmeta WHERE post_id = {$xcupon->ID} AND meta_key = '_used_by' AND meta_value = {$cliente}");
				if( $se_uso >= $metas["usage_limit_per_user"]+0 ){ if( $validar ){ error("El cupón ya fue usado"); }else{ return false; } }

				$noches = [];
				for ($i=0; $i < $duracion; $i++) { 
					foreach ($mascotas as $key => $value) {
						if( is_array($value) ){
							if( $value[0]+0 > 0 ){
								for ($i2=0; $i2 < $value[0]; $i2++) { 
									$noches[] = $value[1];
								}								
							}
						}
					}
				} sort($noches);

				if( $tipo_servicio == "hospedaje" || $tipo_servicio == "guarderia" || $tipo_servicio == "paseos" ){
					$descuento = $noches[0]; // CALCULO DESCUENTO
				}else{ if( $uso_cupon != false ){
					if( $validar ){ error("El cupón sólo es válido para servicios de hospedaje"); }else{ return false; }
				} }

				$sub_descuento += $descuento;
				$descuento += ( ($total-$sub_descuento) < 0 ) ? $descuento += ( $total-$sub_descuento ) : 0 ;
				return array( $cupon, $descuento, $individual_use, $_noches );

			}

			$cupones_solo_petco = [
				"kpet15",
				"cpet30",
				"cpet25",
			];
			if( in_array($cupon, $cupones_solo_petco) ){
				if( !es_petco($db, $cliente) ){ if( $validar ){ error("El cupón solo es válido para usuarios de Petco"); }else{ return false; } }
				if( !es_nuevo($db, $cliente) ){ if( $validar ){ error("El cupón solo es válido para usuarios nuevos"); }else{ return false; } }
			}



			if( $cupon == "1ngpet" ){
				if( !ini_valido($inicio) ){ if( $validar ){ error("La fecha de inicio valida para este cupón es hasta el 31/01/2019"); }else{ return false; } }

				if( !es_petco($db, $cliente) ){ if( $validar ){ error("El cupón solo es válido para usuarios de Petco"); }else{ return false; } }
				if( !es_nuevo($db, $cliente) ){ if( $validar ){ error("El cupón solo es válido para usuarios nuevos"); }else{ return false; } }

				$descuento = 0; $_noches = 1;
				$uso_cupon = get_cupon($db, $cupon, $cliente);
				if( $uso_cupon != false ){ $_noches = $uso_cupon->disponible; }
				if( $_noches == 0 ){ if( $validar ){ error("Ya se ha usado la noche gratis"); }else{ return false; } }

				$noches = [];
				for ($i=0; $i < $duracion; $i++) { 
					foreach ($mascotas as $key => $value) {
						if( is_array($value) ){
							if( $value[0]+0 > 0 ){
								for ($i2=0; $i2 < $value[0]; $i2++) { 
									$noches[] = $value[1];
								}								
							}
						}
					}
				} sort($noches);

				if( $uso_cupon == false ){ if( count($noches) < 7 ){ if( $validar ){ error("El cupón [ {$cupon} ] sólo es válido si reservas 7 noches o más"); }else{ return false; } } }

				if( $tipo_servicio == "hospedaje" ){
					$descuento = $noches[0]; // CALCULO DESCUENTO
				}else{ if( $uso_cupon != false ){
					if( $validar ){ error("El cupón sólo es válido para servicios de hospedaje"); }else{ return false; }
				} }

				if( $descuento > 0 ){ $_noches = 0; }
				
				$sub_descuento += $descuento;
				$descuento += ( ($total-$sub_descuento) < 0 ) ? $descuento += ( $total-$sub_descuento ) : 0 ;
				return array( $cupon, $descuento, $individual_use, $_noches );
			}

			if( $cupon == "2pgpet" ){

				if( !es_petco($db, $cliente) ){ if( $validar ){ error("El cupón solo es válido para usuarios de Petco"); }else{ return false; } }
				if( !es_nuevo($db, $cliente) ){ if( $validar ){ error("El cupón solo es válido para usuarios nuevos"); }else{ return false; } }

				$descuento = 0; $_paseos = 2;
				$uso_cupon = get_cupon($db, $cupon, $cliente);
				if( $uso_cupon != false ){ $_paseos = $uso_cupon->disponible; }
				if( $_paseos == 0 ){ if( $validar ){ error("Ya uso los 2 paseos gratis"); }else{ return false; } }

				$paseos = [];
				for ($i=0; $i < $duracion; $i++) { 
					foreach ($mascotas as $key => $value) {
						if( is_array($value) ){
							if( $value[0]+0 > 0 ){
								for ($i2=0; $i2 < $value[0]; $i2++) { 
									$paseos[] = $value[1];
								}			
							}
						}
					}
				} sort($paseos);

				if( $uso_cupon == false ){ if( count($paseos) < 7 ){ if( $validar ){ error("El cupón [ {$cupon} ] sólo es válido si reservas 7 noches o más"); }else{ return false; } } }

				if( $tipo_servicio == "paseos" ){
					$cont = 0; $disp = $_paseos; // CALCULO DESCUENTO
					foreach ($paseos as $key => $value) {
						if( $cont < $disp ){
							$descuento += $value;
							$_paseos--;
						}else{
							break;
						}
						$cont++;
					}
				}else{ if( $uso_cupon != false ){
					if( $validar ){ error("El cupón sólo es válido para servicios de paseos"); }else{ return false; }
				} }
				
				$sub_descuento += $descuento;
				$descuento += ( ($total-$sub_descuento) < 0 ) ? $descuento += ( $total-$sub_descuento ) : 0 ;
				return array( $cupon, $descuento, $individual_use, $_paseos );

			}

			if( $cupon == "2ngpet" ){
				if( !es_petco($db, $cliente) ){ if( $validar ){ error("El cupón solo es válido para usuarios de Petco"); }else{ return false; } }
				if( !es_nuevo($db, $cliente) ){ if( $validar ){ error("El cupón solo es válido para usuarios nuevos"); }else{ return false; } }

				$descuento = 0; $_noches = 2;
				$uso_cupon = get_cupon($db, $cupon, $cliente);
				if( $uso_cupon != false ){ $_noches = $uso_cupon->disponible; }
				if( $_noches == 0 ){ if( $validar ){ error("Ya se ha usado la noche gratis"); }else{ return false; } }

				$noches = [];
				for ($i=0; $i < $duracion; $i++) { 
					foreach ($mascotas as $key => $value) {
						if( is_array($value) ){
							if( $value[0]+0 > 0 ){
								for ($i2=0; $i2 < $value[0]; $i2++) { 
									$noches[] = $value[1];
								}								
							}
						}
					}
				} sort($noches);

				if( $uso_cupon == false ){ if( count($noches) < 7 ){ if( $validar ){ error("El cupón [ {$cupon} ] sólo es válido si reservas 7 noches o más"); }else{ return false; } } }

				if( $tipo_servicio == "hospedaje" ){
					$cont = 0; $disp = $_noches; // CALCULO DESCUENTO
					foreach ($noches as $key => $value) {
						if( $cont < $disp ){
							$descuento += $value;
							$_noches--;
						}else{
							break;
						}
						$cont++;
					}
				}else{ if( $uso_cupon != false ){
					if( $validar ){ error("El cupón sólo es válido para servicios de hospedaje"); }else{ return false; }
				} }
				
				$sub_descuento += $descuento;
				$descuento += ( ($total-$sub_descuento) < 0 ) ? $descuento += ( $total-$sub_descuento ) : 0 ;
				return array( $cupon, $descuento, $individual_use, $_noches );
			}

			if( $cupon == "3pgpet" ){

				if( !es_petco($db, $cliente) ){ if( $validar ){ error("El cupón solo es válido para usuarios de Petco"); }else{ return false; } }
				if( !es_nuevo($db, $cliente) ){ if( $validar ){ error("El cupón solo es válido para usuarios nuevos"); }else{ return false; } }

				$descuento = 0; $_paseos = 3;
				$uso_cupon = get_cupon($db, $cupon, $cliente);
				if( $uso_cupon != false ){ $_paseos = $uso_cupon->disponible; }
				if( $_paseos == 0 ){ if( $validar ){ error("Ya uso los 3 paseos gratis"); }else{ return false; } }

				$paseos = [];
				for ($i=0; $i < $duracion; $i++) { 
					foreach ($mascotas as $key => $value) {
						if( is_array($value) ){
							if( $value[0]+0 > 0 ){
								for ($i2=0; $i2 < $value[0]; $i2++) { 
									$paseos[] = $value[1];
								}			
							}
						}
					}
				} sort($paseos);

				if( $uso_cupon == false ){ if( count($paseos) < 7 ){ if( $validar ){ error("El cupón [ {$cupon} ] sólo es válido si reservas 7 noches o más"); }else{ return false; } } }

				if( $tipo_servicio == "paseos" ){
					$cont = 0; $disp = $_paseos; // CALCULO DESCUENTO
					foreach ($paseos as $key => $value) {
						if( $cont < $disp ){
							$descuento += $value;
							$_paseos--;
						}else{
							break;
						}
						$cont++;
					}
				}else{ if( $uso_cupon != false ){
					if( $validar ){ error("El cupón sólo es válido para servicios de paseos"); }else{ return false; }
				} }
				
				$sub_descuento += $descuento;
				return array( $cupon, $descuento, $individual_use, $_paseos );
				
			}

			if( $cupon == "+2masc" ){

				// if( $validar ){ error("Este cupón no es valido"); }else{ return false; }


				if( es_destacado($db, $servicio) ){ } else{ // cuidador_valido($db, $servicio) || 
					if( $validar ){ error("Este cuidador no acepta el cupón [ +2masc ]"); }else{ return false; }
				}

				$_mascotas = cant_mascotas($mascotas);
				if( $_mascotas < 2 ){
					if( $validar ){ error("Debe tener al menos 2 mascotas para poder aplicar este cupón"); }else{ return false; }
				}

				$descuento = 0;
				$sub_total = 0;
				$valor_mascotas = [];
				foreach ($mascotas as $key => $value) {
					if( is_array($value) ){
						if( $value[0]+0 > 0 ){
							for ($i=0; $i < $value[0]; $i++) { 
								$valor_mascotas[] = $value[1]*$duracion;
								$sub_total += $value[1]*$duracion;
							}
						}
					}
				}
				rsort($valor_mascotas);
				$cont = 0;
				foreach ($valor_mascotas as $value) {
					switch ( $cont ) {
						case 0:
							$descuento += 0;
						break;
						case 1:
							$descuento += ($value*0.5);
						break;
						default:
							$descuento += ($value*0.25);
						break;
					}
					$cont++;
				}
				
				$sub_descuento += $descuento;
				return array( $cupon, $descuento, $individual_use );
			}

			if( $cupon == "350desc" ){
				$_mascotas = cant_mascotas($mascotas);
				if( $_mascotas < 2 ){
					if( $validar ){
						echo json_encode(array( "error" => "Debe tener al menos 2 mascotas para poder aplicar este cupón" )); exit;
					}else{ return false; }
				}

				$paseos = [];
				for ($i=0; $i < $duracion; $i++) { 
					foreach ($mascotas as $key => $value) {
						if( is_array($value) ){
							if( $value[0]+0 > 0 ){
								for ($i2=0; $i2 < $value[0]; $i2++) { 
									$paseos[] = $value[1];
								}			
							}
						}
					}
				} sort($paseos);

				$_descuento = 0;
				if( count($paseos) < 7 ){
					if( $validar ){
						echo json_encode(array(
							"error" => "El cupón [ {$cupon} ] sólo es válido si reservas 7 noches o más",
						));
						exit;
					}else{
						return false;
					}
				}else{
					$cont = 0;
					foreach ($paseos as $value) {
						switch ( $cont ) {
							case 0:
								$_descuento += $value;
							break;
							case 1:
								$_descuento += ($value*0.5);
							break;
						}
						$cont++;
						if( $cont == 2 ){
							break;
						}
					}
				}

				$sub_descuento += $_descuento;
				return array( $cupon, $_descuento, $individual_use );
			}
 
			if( $cupon == "1pgkam" ){

				if( !hasta_valido("2018-12-31 11:59:00") ){ if( $validar ){ error("El cupón esta vencido"); }else{ return false; } }

				$descuento = 0; $_paseos = 1;
				$uso_cupon = get_cupon($db, $cupon, $cliente);
				if( $uso_cupon != false ){ $_paseos = $uso_cupon->disponible; }
				if( $_paseos == 0 ){ if( $validar ){ error("Ya uso el paseos gratis"); }else{ return false; } }

				$paseos = [];
				for ($i=0; $i < $duracion; $i++) { 
					foreach ($mascotas as $key => $value) {
						if( is_array($value) ){
							if( $value[0]+0 > 0 ){
								for ($i2=0; $i2 < $value[0]; $i2++) { 
									$paseos[] = $value[1];
								}			
							}
						}
					}
				} sort($paseos);

				if( $uso_cupon == false ){ if( count($paseos) < 7 ){ if( $validar ){ error("El cupón [ {$cupon} ] sólo es válido si reservas 7 noches o más"); }else{ return false; } } }

				if( $tipo_servicio == "paseos" ){
					$cont = 0; $disp = $_paseos; // CALCULO DESCUENTO
					foreach ($paseos as $key => $value) {
						if( $cont < $disp ){
							$descuento += $value;
							$_paseos--;
						}else{
							break;
						}
						$cont++;
					}
				}else{ if( $uso_cupon != false ){
					if( $validar ){ error("El cupón sólo es válido para servicios de paseos"); }else{ return false; }
				} }
				
				$sub_descuento += $descuento;
				$descuento += ( ($total-$sub_descuento) < 0 ) ? $descuento += ( $total-$sub_descuento ) : 0 ;
				return array( $cupon, $descuento, $individual_use, $_paseos );

			}

			if( $cupon == "kpet30" ){
				if( !es_petco($db, $cliente) ){ if( $validar ){ error("El cupón solo es válido para usuarios de Petco"); }else{ return false; } }
			}








			if( $cupon == "2pagk" ){  // Agregado el 03-04-19

				if( $_SESSION['landing_paseos'] != 'yes' ) { if( $validar ){ error("El cupón solo es válido desde el landing de Paseos"); }else{ return false; } }
				if( !es_petco($db, $cliente) ){ if( $validar ){ error("El cupón solo es válido para usuarios de Petco"); }else{ return false; } }
				// if( !es_nuevo($db, $cliente) ){ if( $validar ){ error("El cupón solo es válido para usuarios nuevos"); }else{ return false; } }

				$descuento = 0; $_paseos = 2;
				$uso_cupon = get_cupon($db, $cupon, $cliente);
				if( $uso_cupon != false ){ $_paseos = $uso_cupon->disponible; }
				if( $_paseos == 0 ){ if( $validar ){ error("Ya uso los 2 paseos gratis"); }else{ return false; } }

				$paseos = [];
				for ($i=0; $i < $duracion; $i++) { 
					foreach ($mascotas as $key => $value) {
						if( is_array($value) ){
							if( $value[0]+0 > 0 ){
								for ($i2=0; $i2 < $value[0]; $i2++) { 
									$paseos[] = $value[1];
								}			
							}
						}
					}
				} sort($paseos);

				// if( $uso_cupon == false ){ if( count($paseos) < 7 ){ if( $validar ){ error("El cupón [ {$cupon} ] sólo es válido si reservas 7 noches o más"); }else{ return false; } } }

				if( $tipo_servicio == "paseos" ){
					$cont = 0; $disp = $_paseos; // CALCULO DESCUENTO
					foreach ($paseos as $key => $value) {
						if( $cont < $disp ){
							$descuento += $value;
							$_paseos--;
						}else{
							break;
						}
						$cont++;
					}
				}else{ if( $uso_cupon != false ){
					// if( $validar ){ error("El cupón sólo es válido para servicios de paseos"); }else{ return false; }
				} }
				
				if( $total <= $descuento ){
					$descuento = $total;
				}
				$sub_descuento += $descuento;
				return array( $cupon, $descuento, $individual_use, $_paseos );
				
			}





















			
			if( strtolower($cupon) == "buenfin17" || strtolower($cupon) == "grito2018" ){
				$cuidador = $db->get_var("SELECT post_author FROM wp_posts WHERE ID = '{$servicio}'");
				$cuidador = $db->get_row("SELECT * FROM cuidadores WHERE user_id = '{$cuidador}'");
				$atributos = unserialize($cuidador->atributos);
				if( $atributos['destacado'] != 1 ){
					echo json_encode(array(
						"error" => "El cupón [ {$cupon} ] no puede ser aplicado con este cuidador."
					));
					exit;
				}
			}

			if( strtolower($cupon) == "vol150" ){ // kp200p
				/*
					2 noches minimo
					Solo la primera reserva
				*/
				
				// echo "SELECT * FROM wp_usermeta WHERE user_id = {$cliente} AND ( meta_key = 'user_referred' OR meta_key = '_wlabel' ) ";
				$_metas_cliente = $db->get_results("SELECT * FROM wp_usermeta WHERE user_id = {$cliente} AND ( meta_key = 'user_referred' OR meta_key = '_wlabel' ) ");
				foreach ($_metas_cliente as $key => $value) {
					$metas_cliente[ $value->meta_key ] = $value->meta_value;
				}

				$aplicar = false;
				if( $metas_cliente["user_referred"] == "Volaris" ){
					$aplicar = true;
				}

				if( $metas_cliente["_wlabel"] == "volaris" ){
					$aplicar = true;
				}

				if( $aplicar === false ){
					echo json_encode(array(
						"error" => "Este cupón no esta disponible para tu usuario"
					));
					exit;
				}
			}

			if( strtolower($cupon) == "kp200p" ){ // kp200p
				/*
					2 noches minimo
					Solo la primera reserva
				*/

				$_cant_reservas = $db->get_var("SELECT COUNT(*) FROM wp_posts WHERE post_author = {$cliente} AND post_type = 'wc_booking' AND post_status != 'cancelled' ");
				
				if( $validar ){
					if( $_cant_reservas > 0 ){
						echo json_encode(array(
							"error" => "Este cupón no esta disponible para tu usuario"
						));
						exit;
					}
					if( $duracion <= 1){
						echo json_encode(array(
							"error" => "La reserva debe ser de minimo 2 noches para aplicar este cupón"
						));
						exit;
					}
				}else{
					if( $_cant_reservas > 0 ){
						return false;
					}
					if( $duracion <= 1){
						return false;
					}
				}
				
			}

		/* Fin Cupones Especiales */

		/* Get Data */

			if( count($cupones) > 0 ){
				foreach ($cupones as $value) {
					$sub_descuento += $value[1];
					if( strpos( $value[0], "saldo" ) === false ){
						$otros_cupones++;
					}
					if( $value[2] == 1 ){
						echo json_encode(array(
							"error" => "El cupón [ {$value[0]} ] ya esta aplicado y no puede ser usado junto a otros cupones"
						));
						exit;
					}
				}
			}

			$se_uso = $db->get_var("SELECT count(*) FROM wp_postmeta WHERE post_id = {$xcupon->ID} AND meta_key = '_used_by' AND meta_value = {$cliente}");

		/* Validaciones */

			if( $validar === true ){

				if( $otros_cupones > 0 && $metas["individual_use"] == "yes" ){
					echo json_encode(array(
						"error" => "El cupón [ {$cupon} ] no puede ser usado junto a otros cupones"
					));
					exit;
				}

				if( isset($cupones) ){
					if( ya_aplicado($cupon, $cupones) ){
						echo json_encode(array(
							"error" => "El cupón ya fue aplicado"
						));
						exit;
					}
				}

				if( $xcupon == false ){
					echo json_encode(array(
						"error" => "Cupón Invalido"
					));
					exit;
				}

				if( $metas["expiry_date"] != "" ){
				$hoy = time();
					$expiracion = (strtotime($metas["expiry_date"]))+86399;
					if( $hoy > $expiracion ){
						echo json_encode(array(
							"error" => "El cupón ya expiro"
						));
						exit;
					}
				}

				if( $metas["usage_limit_per_user"]+0 > 0 ){
					if( $se_uso >= $metas["usage_limit_per_user"]+0 ){
						echo json_encode(array(
							"error" => "El cupón ya fue usado"
						));
						exit;
					}
				}

				if( $metas["usage_limit"]+0 > 0 ){
					if( $se_uso >= $metas["usage_limit"]+0 ){
						echo json_encode(array(
							"error" => "El cupón ya fue usado"
						));
						exit;
					}
				}
				
			}

		/* Calculo */
			$descuento = 0;
			switch ( $metas["discount_type"] ) {
				case "percent":
					$descuento = $total*($metas["coupon_amount"]/100);
				break;
				case "fixed_cart":
					$descuento = $metas["coupon_amount"];
				break;
			}


			if( $servicio != 0){
				if( !isset($_SESSION)){ session_start(); }
				$id_session = 'MR_'.$servicio."_".md5($cliente);
				if( isset($_SESSION[$id_session] ) ){
					if( strpos( $cupon, "saldo" ) !== false ){
						$descuento += $_SESSION[$id_session]['saldo_temporal'];
					}
				}
			}

			$sub_descuento += $descuento;
			if( ($total-$sub_descuento) < 0 ){
				$descuento += ( $total-$sub_descuento );
			}

			if( $descuento == 0 ){
				if( strpos( $cupon, "saldo" ) === false ){
					echo json_encode(array(
						"error" => "El cupón no será aplicado. El total a pagar por su reserva es 0.",
						"cupon" => $cupon,
					));
					exit;
				}
			}

			if( $metas["individual_use"] == "yes" ){
				return array(
					$cupon,
					$descuento,
					1
				);
			}else{
				return array(
					$cupon,
					$descuento,
					0
				);
			}
	}

	if( $reaplicar == "1" ){
		$xcupones = array();
		if( count($cupones) > 0 ){
			foreach ($cupones as $cupon) {
				$r = aplicarCupon([
					"db" => $db, 
					"cupon" => $cupon[0], 
					"cupones" => $xcupones, 
					"total" => $total, 
					"validar" => false, 
					"cliente" => $cliente, 
					"servicio" => $servicio, 
					"tipo_servicio" => $tipo_servicio,
					"inicio" => $inicio,
					"duracion" => $duracion,
					"mascotas" => $mascotas
				]);
				if( $r !== false ){
					$xcupones[] = $r;
				}
			}
			$cupones = $xcupones;
		}
	}else{
		$cupones[] = aplicarCupon([
			"db" => $db, 
			"cupon" => $cupon, 
			"cupones" => $cupones, 
			"total" => $total, 
			"validar" => true, 
			"cliente" => $cliente, 
			"servicio" => $servicio, 
			"inicio" => $inicio,
			"duracion" => $duracion,
			"tipo_servicio" => $tipo_servicio,
			"mascotas" => $mascotas
		]);

	}

	/* Retorno */
		echo json_encode(array(
			"cupones" => $cupones,
			"reaplicar"    => $reaplicar,
			"post"		=> $_POST,

		));

?>