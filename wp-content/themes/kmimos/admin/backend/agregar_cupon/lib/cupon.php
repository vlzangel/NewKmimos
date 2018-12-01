<?php

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
		// echo json_encode(array( 
		// 	"error" => $msg,
		// 	"data" => $data
		// )); 
		// exit;
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
 
		/* Cupones Especiales */
			
			if( $cupon == "bnv1sb" ){ 
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
				}else{   }

				$sub_descuento += $descuento;
				$descuento += ( ($total-$sub_descuento) < 0 ) ? $descuento += ( $total-$sub_descuento ) : 0 ;
				return array( $cupon, $descuento, $individual_use, $_noches );

			}
 
			if( $cupon == "1ngpet" ){
				 
				$descuento = 0; $_noches = 1;
				$uso_cupon = get_cupon($db, $cupon, $cliente); 
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
 
				if( $tipo_servicio == "hospedaje" ){
					$descuento = $noches[0]; // CALCULO DESCUENTO
				}else{  }

				if( $descuento > 0 ){ $_noches = 0; }
				
				$sub_descuento += $descuento;
				$descuento += ( ($total-$sub_descuento) < 0 ) ? $descuento += ( $total-$sub_descuento ) : 0 ;
				return array( $cupon, $descuento, $individual_use, $_noches );
			}

			if( $cupon == "2pgpet" ){
 
				$descuento = 0; $_paseos = 2; 
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
				}else{  }
				
				$sub_descuento += $descuento;
				$descuento += ( ($total-$sub_descuento) < 0 ) ? $descuento += ( $total-$sub_descuento ) : 0 ;
				return array( $cupon, $descuento, $individual_use, $_paseos );

			}

			if( $cupon == "2ngpet" ){
 
				$descuento = 0; $_noches = 2;
 
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
				}else{   }
				
				$sub_descuento += $descuento;
				$descuento += ( ($total-$sub_descuento) < 0 ) ? $descuento += ( $total-$sub_descuento ) : 0 ;
				return array( $cupon, $descuento, $individual_use, $_noches );
			}

			if( $cupon == "3pgpet" ){
 
				$descuento = 0; $_paseos = 3;
 
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
				}else{  }
				
				$sub_descuento += $descuento;
				return array( $cupon, $descuento, $individual_use, $_paseos );

			}

			if( $cupon == "+2masc" ){

				$descuento = 0;
				$sub_total = 0;
				$valor_mascotas = [];
				foreach ($mascotas as $key => $value) {
					if( is_array($value) ){
						if( (int) $value[0] > 0 ){
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
				  
				$sub_descuento += $_descuento;
				return array( $cupon, $_descuento, $individual_use );
			}
 
			if( $cupon == "1pgkam" ){
 
				$descuento = 0; $_paseos = 1;
 
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
				}
				
				$sub_descuento += $descuento;
				$descuento += ( ($total-$sub_descuento) < 0 ) ? $descuento += ( $total-$sub_descuento ) : 0 ;
				return array( $cupon, $descuento, $individual_use, $_paseos );

			}

			if( $cupon == "kpet30" ){

			}
 
			if( strtolower($cupon) == "buenfin17" || strtolower($cupon) == "grito2018" ){
				 
			}

			if( strtolower($cupon) == "vol150" ){ // kp200p
				 
			}

			if( strtolower($cupon) == "kp200p" ){ // kp200p
				 
			}

		/* Fin Cupones Especiales */

		/* Get Data */

			if( count($cupones) > 0 ){
				foreach ($cupones as $value) {
					$sub_descuento += $value[1];
					if( strpos( $value[0], "saldo" ) === false ){
						$otros_cupones++;
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
