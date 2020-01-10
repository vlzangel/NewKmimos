<?php

	global $wpdb;

	extract( $_POST );
	$user = wp_get_current_user();
	$user_id = $user->ID;

	$respuesta['status'] = '';

	if( isset($user_id) && $user_id > 0 ){


	// Datos para Cuidadores y Clientes	
		update_user_meta( $user_id, "billing_rfc", $rfc);
		update_user_meta( $user_id, "billing_first_name", $nombre); 
		update_user_meta( $user_id, "billing_last_name", $apellido_paterno); 
		update_user_meta( $user_id, "billing_second_last_name", $apellido_materno); 
		update_user_meta( $user_id, "billing_uso_cfdi", $uso_cfdi); 

		if( $regimen_fiscal != 'RGLPM' ){
			$razon_social = "{$nombre} {$apellido_paterno} {$apellido_materno}";
			$_POST['razon_social'] = $razon_social;
		}
		update_user_meta( $user_id, "billing_razon_social", $razon_social); 
		update_user_meta( $user_id, "billing_regimen_fiscal", $regimen_fiscal); 
		update_user_meta( $user_id, "billing_calle", $calle); 
		update_user_meta( $user_id, "billing_postcode", $cp); 
		update_user_meta( $user_id, "billing_noExterior", $noExterior); 
		update_user_meta( $user_id, "billing_noInterior", $noInterior); 
		update_user_meta( $user_id, "billing_state", $rc_estado);
		update_user_meta( $user_id, "billing_city", $rc_municipio);
		update_user_meta( $user_id, "billing_colonia", $colonia);
		update_user_meta( $user_id, "billing_localidad", $localidad); 
		update_user_meta( $user_id, "auto_facturar", $auto_facturar); 

	// Datos para Cuidadores
		if( is_petsitters() ){

			// Guardar datos en kmimos 
				$datos = $wpdb->get_row( 'SELECT * FROM facturas_aliados WHERE user_id = '.$user_id);
				$sql = '';
				if( isset($datos->id) && $datos->id > 0 ){
					$sql = "UPDATE facturas_aliados SET 
						rfc = '".$rfc."',
						nombreFiscal = '".$nombre."',
						regimenFiscal = '".$regimen_fiscal."',
 						fielCer = '".$fielCer."',
						fielKey = '".$fielKey."',
						fielPass = '".$fielPass."'
					WHERE id = ".$datos->id;
				}else{
					$sql = "INSERT INTO facturas_aliados (
						rfc,
						user_id,
						nombreFiscal,
						regimenFiscal,
						folioSat,
						fielCer,
						fielKey,
						fielPass,
						idSucursal,
						plan,
						serie,
						tipoComprobante,
						numFolioFiscal,
						estatus
					) VALUES (
						'".$rfc."',
						'".$user_id."',
						'".$nombre."',
						'".$regimen_fiscal."',
						'1',
						'".$fielCer."',
						'".$fielKey."',
						'".$fielPass."',
						'0',
						'Personal',
						'PC',
						'FA',
						'1',
						'Pendiente'				
					) ";
				}
				$wpdb->query($sql);


			// Registrar datos en enlaceFiscal
				include( dirname(dirname(__DIR__)) . '/lib/enlaceFiscal/Aliados.php' );

				// Validar las Firmas Digitales
				$valid_result = $aliados->fielValidar( $fielCer, $fielKey, $rfc, $fielPass );
				$valid = json_decode($valid_result);
				if( isset($valid->AckEnlaceFiscal->estatusDocumento) && 
					$valid->AckEnlaceFiscal->estatusDocumento == 'aceptado' ){

					// Registrar cuidador en enlaceFiscal
					$registro = $aliados->prospectosDesglose( $user_id, $_POST );
					$prospecto_result = $aliados->prospectosAlta( $registro );

					$prospecto = json_decode($prospecto_result);
					if( isset($prospecto->AckEnlaceFiscal->estatusDocumento) && 
						$prospecto->AckEnlaceFiscal->estatusDocumento == 'aceptado' ){

						$wpdb->query( 
							"UPDATE facturas_aliados SET 
								selloDigital = '".$prospecto->AckEnlaceFiscal->Prospecto->selloDigitalSolicitud."' 
							WHERE user_id = {$user_id}" 
						);
						$respuesta['status'] = 'OK';
					}else{
						$respuesta['status']='ERROR1';
						$respuesta['mensaje']= (isset($prospecto->AckEnlaceFiscal->mensajeError->descripcionError))? $prospecto->AckEnlaceFiscal->mensajeError->descripcionError : '';					
					}

				}else{
					$respuesta['status']='ERROR2';
					$respuesta['mensaje']= (isset($valid->AckEnlaceFiscal->mensajeError->descripcionError))? $valid->AckEnlaceFiscal->mensajeError->descripcionError : '';
				}

		}

		if( $respuesta['status'] == '' ){
			$respuesta['status'] = 'OK';
		}

	}else{
		$respuesta['status']='ERROR3';
	} 
