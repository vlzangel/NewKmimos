<?php
	
	session_start();

	extract($_GET);
	if( isset($_GET["id_orden"]) ){
		include((dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))))."/wp-load.php");
	}

	date_default_timezone_set('America/Mexico_City');

	$time_ahora = time();

	global $URL_LOCAL;

	$PATH_TEMPLATE = (dirname(dirname(dirname(__DIR__))));

	$info = kmimos_get_info_syte();
	add_filter( 'wp_mail_from_name', function( $name ) { global $info; return $info["titulo"]; });
    add_filter( 'wp_mail_from', function( $email ) { global $info; return $info["email"]; });

    global $wpdb;
	$id = $id_orden;

	if( $id_orden+0 == 0 ){ exit(); }
	$es_orden = $wpdb->get_var("SELECT post_type FROM wp_posts WHERE ID = '".$id."'");
	if( $es_orden != "shop_order" ){ exit(); }

	$data = kmimos_desglose_reserva_data($id, true);

	// $_SERVER;

	extract($data);

	$inf_seguimiento = [
		$_SERVER,
		$data
	];
	$inf_seguimiento = json_encode($inf_seguimiento, JSON_UNESCAPED_UNICODE);

	$SQL_SEGUIMIENTO = "
		INSERT INTO vlz_seguimiento_reservas VALUES(
			NULL,
			'{$servicio["id_reserva"]}',
			'{$id}',
			'{$inf_seguimiento}',
			NOW()
		)
	";

	$wpdb->query( $SQL_SEGUIMIENTO );



	if( 
		$servicio["id_reserva"] == "" || 
		$cliente["id"] 			== "" || 
		$cuidador["id"] 		== ""
	){
		// Data incompleta
	}else{

	 	$modificacion_de = get_post_meta($servicio["id_reserva"], "modificacion_de", true);
	    if( $modificacion_de != "" ){ 
	    	$modificacion = "
	    	<div style='width: 100%;
			    background-color: #e4e4e4;
			    margin: 0px auto;
			    font-family: Arial;
			    font-size: 15px;
			    letter-spacing: 0.3px;
			    color: #000000;
			    padding: 14px 0px;
			    text-align: center;'>
	            Esta es una modificación de la reserva #: ".$modificacion_de."
	        </div>";
	 	}else{ $modificacion = ""; }

		$email_admin = $info["email"];

		$mascotas_plantilla = $PATH_TEMPLATE.'/template/mail/reservar/partes/mascotas.php';
	    $mascotas_plantilla = file_get_contents($mascotas_plantilla);
	    $mascotas = "";
		foreach ($cliente["mascotas"] as $mascota) {
			$tempEdad = explode(" ", $mascota["edad"]);
			$mascota["edad"] = ( count($tempEdad) == 4 ) ? $tempEdad[0]."<span style='color: #FFF;'>_</span>".$tempEdad[1]."<br>".$tempEdad[2]."<span style='color: #FFF;'>_</span>".$tempEdad[3] : $mascota["edad"];
			$temp = str_replace('[NOMBRE]', $mascota["nombre"], $mascotas_plantilla);
			$temp = str_replace('[TYPE]', $mascota["tipo"], $temp);
			$temp = str_replace('[RAZA]', $mascota["raza"], $temp);
			$temp = str_replace('[EDAD]', $mascota["edad"], $temp);
			$temp = str_replace('[TAMANO]', $mascota["tamano"], $temp);
			$temp = str_replace('[CONDUCTA]', $mascota["conducta"], $temp);
			$mascotas .= $temp;
		}
		
		$desglose_plantilla = $PATH_TEMPLATE.'/template/mail/reservar/partes/desglose.php';
	    $desglose_plantilla = file_get_contents($desglose_plantilla);

	    $desglose = ""; $gatos = 0;
		foreach ($servicio["variaciones"] as $variacion) {
			$plural = ""; if($variacion[0]>1){$plural="s";}
			if( strtoupper($variacion[1]) == 'GATOS' ){ $gatos++; }
			$temp = str_replace('[TAMANO]', strtoupper($variacion[1]), $desglose_plantilla);
			$temp = str_replace('[CANTIDAD]', $variacion[0]." mascota".$plural, $temp);
			$temp = str_replace('[TIEMPO]', $variacion[2], $temp);
			$temp = str_replace('[PRECIO_C_U]', "$ ".$variacion[3], $temp);
			$temp = str_replace('[SUBTOTAL]', "$ ".$variacion[4], $temp);
			$desglose .= $temp;
		}

		$adicionales_desglose_plantilla = $PATH_TEMPLATE.'/template/mail/reservar/partes/adicionales_desglose.php';
	    $adicionales_desglose_plantilla = file_get_contents($adicionales_desglose_plantilla);

	    $adicionales = "";
	    foreach ($servicio["adicionales"] as $adicional) {
			$temp = str_replace('[SERVICIO]', $adicional[0], $adicionales_desglose_plantilla);
			$temp = str_replace('[CANTIDAD]', $adicional[1], $temp);
			$temp = str_replace('[PRECIO_C_U]', "$ ".$adicional[2], $temp);
			$temp = str_replace('[SUBTOTAL]', "$ ".$adicional[3], $temp);
			$adicionales .= $temp;
		}

		if( $adicionales != "" ){
			$adicionales_plantilla = $PATH_TEMPLATE.'/template/mail/reservar/partes/adicionales.php';
	    	$adicionales_plantilla = file_get_contents($adicionales_plantilla);
	    	$adicionales = $adicionales_plantilla.$adicionales;
		}
		
		$transporte_desglose_plantilla = $PATH_TEMPLATE.'/template/mail/reservar/partes/transporte_desglose.php';
	    $transporte_desglose_plantilla = file_get_contents($transporte_desglose_plantilla);

	    $transporte = "";
	    foreach ($servicio["transporte"] as $valor) {
			$temp = str_replace('[SERVICIO]', $valor[0], $transporte_desglose_plantilla);
			$temp = str_replace('[SUBTOTAL]', "$ ".$valor[2], $temp);
			$transporte .= $temp;
		}

		if( $transporte != "" ){
			$transporte_plantilla = $PATH_TEMPLATE.'/template/mail/reservar/partes/transporte.php';
	    	$transporte_plantilla = file_get_contents($transporte_plantilla);
	    	$transporte = $transporte_plantilla.$transporte;
		}

		$totales_plantilla = $PATH_TEMPLATE.'/template/mail/reservar/partes/totales.php';
	    $totales_plantilla = file_get_contents($totales_plantilla);
	    $totales_plantilla = str_replace('[TIPO_PAGO]', $servicio["tipo_pago"], $totales_plantilla);

		if( $servicio["desglose"]["descuento"]+0 > 0 ){
			$descuento_plantilla = $PATH_TEMPLATE.'/template/mail/reservar/partes/descuento.php';
		    $descuento_plantilla = file_get_contents($descuento_plantilla);
		    $descuento_plantilla = str_replace('[DESCUENTO]', number_format( $servicio["desglose"]["descuento"], 2, ',', '.'), $descuento_plantilla);
		    $totales_plantilla = str_replace('[DESCUENTO]', $descuento_plantilla, $totales_plantilla);
		}else{
			$totales_plantilla = str_replace('[DESCUENTO]', "", $totales_plantilla);
		}

		$deposito_plantilla = $PATH_TEMPLATE.'/template/mail/reservar/partes/deposito.php';
		$deposito_plantilla = file_get_contents($deposito_plantilla);
	    $MONTO = "";

	    if( $servicio["desglose"]["enable"] == "yes" ){
	    	$deposito_plantilla = str_replace('[REMANENTE]', number_format( $servicio["desglose"]["remaining"], 2, ',', '.'), $deposito_plantilla);
	        $totales_plantilla = str_replace('[TOTAL]', number_format( $servicio["desglose"]["total"], 2, ',', '.'), $totales_plantilla);
	    	$totales_plantilla = str_replace('[PAGO]', number_format( $servicio["desglose"]["deposit"], 2, ',', '.'), $totales_plantilla);
	    	$totales_plantilla = str_replace('[DETALLES]', $deposito_plantilla, $totales_plantilla);
	    	$MONTO = number_format( $servicio["desglose"]["deposit"], 2, ',', '.');
	    }else{
	    	$deposito_plantilla = str_replace('[REMANENTE]', number_format( 0, 2, ',', '.'), $deposito_plantilla);
	        $totales_plantilla = str_replace('[TOTAL]', number_format( $servicio["desglose"]["total"], 2, ',', '.'), $totales_plantilla);
	    	$totales_plantilla = str_replace('[PAGO]', number_format( $servicio["desglose"]["deposit"], 2, ',', '.'), $totales_plantilla);
	    	$totales_plantilla = str_replace('[DETALLES]', $deposito_plantilla, $totales_plantilla);
	    	$MONTO = number_format( $servicio["desglose"]["deposit"], 2, ',', '.');
	    }
		
		if( $servicio["desglose"]["descuento"]+0 > 0 ){
			$descuento_plantilla = $PATH_TEMPLATE.'/template/mail/reservar/partes/descuento.php';
		    $descuento_plantilla = file_get_contents($descuento_plantilla);
		    $descuento_plantilla = str_replace('[DESCUENTO]', number_format( $servicio["desglose"]["descuento"], 2, ',', '.'), $descuento_plantilla);
		    $totales_plantilla = str_replace('[DESCUENTO]', $descuento_plantilla, $totales_plantilla);
		}else{
			$totales_plantilla = str_replace('[DESCUENTO]', "", $totales_plantilla);
		}
		
		$detalles_plantilla = $PATH_TEMPLATE.'/template/mail/reservar/partes/detalles_servicio.php';
	    $detalles_plantilla = file_get_contents($detalles_plantilla);
		$detalles_plantilla = str_replace('[inicio]', date("d/m", $servicio["inicio"]), $detalles_plantilla);
	    $detalles_plantilla = str_replace('[fin]', date("d/m", $servicio["fin"]), $detalles_plantilla);
	    $detalles_plantilla = str_replace('[anio]', date("Y", $servicio["fin"]), $detalles_plantilla);
	    $detalles_plantilla = str_replace('[tiempo]', $servicio["duracion"], $detalles_plantilla);
	    $detalles_plantilla = str_replace('[tipo_pago]', $servicio["metodo_pago"], $detalles_plantilla);
	    $detalles_plantilla = str_replace('[tipo_servicio]', $servicio["tipo"], $detalles_plantilla);
	    $detalles_plantilla = str_replace('[hora_inicio]', $servicio["checkin"], $detalles_plantilla);
	    $detalles_plantilla = str_replace('[hora_fin]', $servicio["checkout"], $detalles_plantilla);
	    $detalles_plantilla = str_replace('[URL_IMGS]', get_home_url()."/wp-content/themes/kmimos/images/emails", $detalles_plantilla);


	    if( $servicio["desglose"]["reembolsar"]+0 > 0 ){
	        $reembolsar_plantilla = $PATH_TEMPLATE.'/template/mail/reservar/partes/reembolsar.php';
	        $reembolsar_plantilla = file_get_contents($reembolsar_plantilla);
	        $reembolsar_plantilla = str_replace('[DEVOLVER]', number_format( $servicio["desglose"]["reembolsar"], 2, ',', '.'), $reembolsar_plantilla);
	    }else{
	        $reembolsar_plantilla = "";
	    }

		$servicios_plantilla = $PATH_TEMPLATE.'/template/mail/reservar/partes/servicios.php';
	    $servicios_plantilla = file_get_contents($servicios_plantilla);

	    if( $gatos == count($servicio["variaciones"]) ){
	    	$servicios_plantilla = str_replace('dog_black', 'cat_black', $servicios_plantilla);
	    	
	    }

		$servicios_plantilla = str_replace('[inicio]', date("d/m", $servicio["inicio"]), $servicios_plantilla);
		$servicios_plantilla = str_replace('[desglose]', $desglose, $servicios_plantilla);
	    $servicios_plantilla = str_replace('[ADICIONALES]', $adicionales, $servicios_plantilla);
	    $servicios_plantilla = str_replace('[TRANSPORTE]', $transporte, $servicios_plantilla);

	    $confirmacion_titulo = "Confirmación de Reserva";
	    if( $servicio["flash"] == "SI" && $acc == "" ){
	    	$status_reserva = $wpdb->get_var("SELECT post_status FROM wp_posts WHERE ID = ".$servicio["id_orden"]);
	    	if ( strtolower($servicio["metodo_pago"]) == "tienda" && $status_reserva != "wc-on-hold" ){
		    	$acc = "CFM";
		    	$CONFIRMACION = "YES";
		    	$confirmacion_titulo = "Confirmación de Reserva Inmediata";
	    	}
	    	if ( strtolower($servicio["metodo_pago"]) == "tarjeta" && $status_reserva != "pending" ){
		    	$acc = "CFM";
		    	$CONFIRMACION = "YES";
		    	$confirmacion_titulo = "Confirmación de Reserva Inmediata";
	    	}
	    	if ( strtolower($servicio["metodo_pago"]) == "saldo y/o descuentos" && $status_reserva != "pending" ){
		    	$acc = "CFM";
		    	$CONFIRMACION = "YES";
		    	$confirmacion_titulo = "Confirmación de Reserva Inmediata";
	    	}
	    	if ( strtolower($servicio["metodo_pago"]) == "paypal" && $status_reserva != "pending" ){
		    	$acc = "CFM";
		    	$CONFIRMACION = "YES";
		    	$confirmacion_titulo = "Confirmación de Reserva Inmediata";
	    	}
	    	if ( strtolower($servicio["metodo_pago"]) == "mercadopago" && $status_reserva != "pending" ){
		    	$acc = "CFM";
		    	$CONFIRMACION = "YES";
		    	$confirmacion_titulo = "Confirmación de Reserva Inmediata";
	    	}
	    }

		$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	    $vence = strtotime( $servicio["vence"]);

	    $fecha = date('d', $vence)." de ".$meses[date('n', $vence)-1]. " ".date('Y', $vence) ;
	    $hora = "(".date('H:i A', $vence).")";

	    $_datos_cliente = getTemplate("reservar/partes/datos_cliente");
	    $_datos_cuidador = getTemplate("reservar/partes/datos_cuidador");
	    $instrucciones = getTemplate("reservar/partes/instrucciones");

	    $_SESSION["USER_ID_CLIENTE_CORREOS"] = $cliente["id"];
	    $_SESSION["ID_RESERVA_CORREOS"] = $servicio["id_reserva"];

	    $superAdmin = (  $_SESSION['admin_sub_login'] == 'YES' ) ? 'YES' : '';

	    $INFORMACION = [
	        // GENERALES

	            'HEADER'                => "",
	            'ID_RESERVA'            => $servicio["id_reserva"],
	            'SERVICIOS'             => $servicios_plantilla,
	            'MASCOTAS'              => $mascotas,
	            'DESGLOSE'              => $desglose,
	            'ADICIONALES'           => $adicionales,
	            'TRANSPORTE'            => $transporte,
	            'MODIFICACION'          => $modificacion,
	            'TIPO_SERVICIO'         => trim($servicio["tipo"]),
	            'DETALLES_SERVICIO'     => $detalles_plantilla,
	            'TOTALES'               => str_replace('[REEMBOLSAR]', "", $totales_plantilla),

	            'ACEPTAR'               => $servicio["aceptar_rechazar"]["aceptar"],
	            'RECHAZAR'              => $servicio["aceptar_rechazar"]["cancelar"],

	            'INSTRUCCIONES'			=> $instrucciones,
	            'CODIGO'				=> end( explode("/", $servicio["pdf"]) ),
	            'MONTO'					=> $MONTO,
	            'FECHA'					=> $fecha,
	            'HORA'					=> $hora,
	            'PDF'					=> $servicio["pdf"],

	        // CLIENTE
	            'DATOS_CLIENTE'         => $_datos_cliente,
	            'NAME_CLIENTE'          => $cliente["nombre"],
	            'AVATAR_CLIENTE'        => kmimos_get_foto($cliente["id"]),
	            'TELEFONOS_CLIENTE'     => $cliente["telefono"],
	            'CORREO_CLIENTE'        => $cliente["email"],
	            
	        // CUIDADOR
	            'DATOS_CUIDADOR'        => $_datos_cuidador,
	            'NAME_CUIDADOR'         => $cuidador["nombre"],
	            'AVATAR_CUIDADOR'       => kmimos_get_foto($cuidador["id"]),
	            'TELEFONOS_CUIDADOR'    => $cuidador["telefono"],
	            'CORREO_CUIDADOR'       => $cuidador["email"],
	            'DIRECCION_CUIDADOR'    => $cuidador["direccion"],

	        // INFO PASEOS
	            'DISPLAY_PASEOS' => ( count($servicio["info_paquete"]) > 0 ) ? 'block' : 'none' ,
	            'PAQUETE' => ( count($servicio["info_paquete"]) > 0 ) ? $servicio["info_paquete"]['paquete'] : '' ,
	            'DIAS' =>    ( count($servicio["info_paquete"]) > 0 ) ? $servicio["info_paquete"]['dias']    : ''
	    ];


	    /* Funciones Temporales */

		    function getUrlImgs_TEMP(){
				$url = get_home_url()."/wp-content/themes/kmimos/images/emails";
				return $url;
			}

		    function getTemplate_TEMP($plantilla){
				$template = dirname(dirname(dirname(__DIR__))).'/template/mail/'.$plantilla.'.php';
				return file_get_contents($template);
			}

			function buildEmailTemplate_TEMP($plantilla, $params){
				$HTML = getTemplate_TEMP($plantilla);
				foreach ($params as $key => $value) {
		            $HTML = str_replace('['.strtolower($key).']', $value, $HTML);
		            $HTML = str_replace('['.strtoupper($key).']', $value, $HTML);
		        }
		        $HTML = str_replace('[URL_IMGS]', getUrlImgs_TEMP($test), $HTML);
		        return $HTML;
			}

	    /* Fin Funciones Temporales */

		$status_orden = $wpdb->get_var("SELECT post_status FROM wp_posts WHERE ID = ".$servicio["id_orden"]);

		if( $status_orden == "wc-pending" ){
			include(__DIR__."/fallidos.php");
			exit();
		}

		if( $acc == "" || $confirmacion_titulo == "Confirmación de Reserva Inmediata" ){
			if( strtolower($servicio["metodo_pago"]) == "tienda" && $status_orden == "wc-on-hold"  ){
				include(__DIR__."/tienda.php");
			}else{
				/*
				$pago_completado = get_post_meta($servicio["id_reserva"], '_pago_completado', true);

				$continuar_proceso = false;
				if( empty($pago_completado) ){
					$continuar_proceso = true;
					$pago_completado = 1;
				}else{
					if( $CONFIRMACION_ENVIO_DOBLE == "YES" ){
						$continuar_proceso = true;
						$pago_completado += 1;
					}
				}
				
				if( $continuar_proceso ){
				*/
					if( $status_orden == "wc-on-hold"  ){
						include(__DIR__."/nuevos.php");
					}else{
						$pre_reserva = get_post_meta($servicio["id_orden"], '_pre_reserva', true);
						if( $status_orden == "wc-por-pagar" && $pre_reserva == 'Si'  ){
							include(__DIR__."/pre_reserva.php");
						}else{
							include(__DIR__."/otro.php");
						}
					}
				/*
					update_post_meta($servicio["id_reserva"], '_pago_completado', $pago_completado);

				}
				*/
			}
		}

		if( $acc != ""  ){

			$status = $wpdb->get_var("SELECT post_status FROM wp_posts WHERE ID = '".$servicio["id_reserva"]."'");
			$continuar = true;
			$usuario = $cuidador["nombre"];

			if( $superAdmin == "" ){
				if( $usu == "CLI" ){ 
					$usuario = $cliente["nombre"]; 

					if( $status == "cancelled" || $status == "modified" ){
						$estado = array(
							"modified"  => "Modificada",
							"cancelled" => "Cancelada"
						);
						$msg = "
						<div class='msg_acciones'>
							<div style='font-family: Arial; font-size: 14px; line-height: 1.07; letter-spacing: 0.3px; color: #000000; padding-bottom: 10px; text-align: left;'>
						    	Hola <strong>".$usuario."</strong>
						    </div>
							<div style='font-family: Arial; font-size: 14px; line-height: 1.07; letter-spacing: 0.3px; color: #000000; padding-bottom: 10px; text-align: left;'>
						    	Te notificamos que la reserva N° <strong>".$servicio["id_reserva"]."</strong> ya ha sido <strong>".$estado[$status]."</strong> anteriormente.
						    </div>
							<div style='font-family: Arial; font-size: 14px; line-height: 1.07; letter-spacing: 0.3px; color: #000000; padding-bottom: 10px; text-align: left;'>
						    	Por tal motivo ya no es posible realizar cambios en el estatus de la misma.
						    </div>
						</div>";
				   		
				   		$CONTENIDO .= $msg;
				   		$continuar = false;
					}
				}else{

					$mostrar_msgs = false;

					if(  $_SESSION['admin_sub_login'] != 'YES' && $status == "confirmed" ){
						$mostrar_msgs = true;
					}

					if(  $status == "cancelled" || $status == "modified" ){
						$mostrar_msgs = true;
					}

					if( $mostrar_msgs ){
						$estado = array(
							"confirmed" => "Confirmada",
							"modified"  => "Modificada",
							"cancelled" => "Cancelada"
						);
						$msg = "
						<div class='msg_acciones'>
							<div style='font-family: Arial; font-size: 14px; line-height: 1.07; letter-spacing: 0.3px; color: #000000; padding-bottom: 10px; text-align: left;'>
						    	Hola <strong>".$usuario."</strong>
						    </div>
							<div style='font-family: Arial; font-size: 14px; line-height: 1.07; letter-spacing: 0.3px; color: #000000; padding-bottom: 10px; text-align: left;'>
						    	Te notificamos que la reserva N° <strong>".$servicio["id_reserva"]."</strong> ya ha sido <strong>".$estado[$status]."</strong> anteriormente.
						    </div>
							<div style='font-family: Arial; font-size: 14px; line-height: 1.07; letter-spacing: 0.3px; color: #000000; padding-bottom: 10px; text-align: left;'>
						    	Por tal motivo ya no es posible realizar cambios en el estatus de la misma.
						    </div>
						</div>";
				   		
				   		$CONTENIDO .= $msg;
				   		$continuar = false;
					}

				}

			}

			if( $NO_ENVIAR != "" ){ $continuar = true; }
			
			$CONFIRMACION = "YES";
			if( $continuar ){
				$CORRECTO = false;
				if( $acc == "CFM" || $acc == "CCL" ){
					if( $_GET['CONFIRMACION_BACK'] == "" ){
						$pre_change_status = get_post_meta($servicio["id_reserva"], 'pre_change_status', true);
						if( $pre_change_status == null ){
							$confirmado_por = "";
							if( isset($usu) ){
								if( $superAdmin != "YES" ){
							        $confirmado_por = $usu;
							    }else{
							        $confirmado_por = $usu."_super_admin";
							    }
							}
							$data = [
								"acc" => $acc,
								"usu" => $usu,
								"hora" => $time_ahora,
								"confirmado_por" => $confirmado_por
							];
							$data = json_encode($data);
							update_post_meta($servicio["id_reserva"], 'pre_change_status', $data );	
							$wpdb->query( "UPDATE wp_posts SET ping_status = '{$acc}' WHERE ID = ".$servicio["id_orden"] );	
							if( $acc == "CFM" ){
								$CONTENIDO .= "<div class='msg_acciones'><strong>¡Todo esta listo!</strong><br>La reserva #".$servicio["id_reserva"].", ha sido confirmada exitosamente de acuerdo a tu petición.</div>";
							}else{
								$CONTENIDO .= '<h1 style="margin: 10px 0px 5px 0px; padding: 0px; text-align:left;"><div class="'.$style.'">Te notificamos que la reserva <strong>#'.$servicio["id_reserva"].'</strong>, ha sido cancelada exitosamente.</div></h1>';
							}			
						}else{
							$pre_change_status = json_decode( $pre_change_status );
							if( $pre_change_status->acc != $acc ){
								delete_post_meta($servicio["id_reserva"], 'pre_change_status');		
								$_data = [
									"antes" => $acc,
									"ahora" => $pre_change_status->acc,
									"hora" => time()
								];
								$_data = json_encode($_data);
								update_post_meta( $servicio["id_reserva"], 'eliminacion_de_pre_change', $_data );	
								if( $acc == "CFM" ){
									$CONTENIDO .= "<div class='msg_acciones'>La reserva #".$servicio["id_reserva"].", recibió una solicitud de cancelación hace menos de 60 seg, por medidas de seguridad debe esperar al menos 2 min para confirmarla. </div>";
								}else{
									$CONTENIDO .= '<h1 style="margin: 10px 0px 5px 0px; padding: 0px; text-align:left;"><div class="'.$style.'">La reserva #".$servicio["id_reserva"].", recibió una solicitud de confirmación hace menos de 60 seg, por medidas de seguridad debe esperar al menos 2 min para cancelarla. </div></h1>';
								}	
							}else{
								if( time() > ($pre_change_status->hora+60) ){
									$CORRECTO = true;
								}else{
									if( $acc == "CFM" ){
										$CONTENIDO .= "<div class='msg_acciones'><strong>¡Todo esta listo!</strong><br> La reserva #".$servicio["id_reserva"].", ha sido confirmada pronto recibiras los correos de confirmación.</div>";
									}else{
										$CONTENIDO .= '<h1 style="margin: 10px 0px 5px 0px; padding: 0px; text-align:left;"><div class="'.$style.'">La reserva <strong>#'.$servicio["id_reserva"].'</strong>, ha sido cancelada pronto recibiras los correos de cancelación.</div></h1>';
									}	
								}
							}
						}
					}else{
						$CORRECTO = true;
						$_data = [
							"acc" => $acc,
							"desde" => 'Backpanel',
							"hora" => time()
						];
						$_data = json_encode($_data);
						update_post_meta($servicio["id_reserva"], '_change_status_log_'.time(), $_data);
						if( $acc == "CFM" ){
							$CONTENIDO .= "<div class='msg_acciones'><strong>¡Todo esta listo!</strong><br> La reserva #".$servicio["id_reserva"].", ha sido confirmada pronto recibiras los correos de confirmación.</div>";
						}else{
							$CONTENIDO .= '<h1 style="margin: 10px 0px 5px 0px; padding: 0px; text-align:left;"><div class="'.$style.'">La reserva <strong>#'.$servicio["id_reserva"].'</strong>, ha sido cancelada pronto recibiras los correos de cancelación.</div></h1>';
						}	
					}

					if( $CORRECTO ){

						delete_post_meta($servicio["id_reserva"], 'pre_change_status');
						update_post_meta($servicio["id_reserva"], 'pre_change_status_log_'.time(), $pre_change_status);

						if( $acc == "CFM" ){
							$continuar_accion = true;
							/*
							$time_cancelado = get_post_meta($servicio["id_reserva"], 'cancelado_a', true);
							if( $time_cancelado != false && $time_cancelado > 0 ){
								$time_cancelado = $time_cancelado - time();
								if( $time_cancelado <= 600 ){
									$continuar_accion = false;
								}
							}
							*/

							if( $continuar_accion ){

								if( $CONFIRMACION == "YES" ){

									update_post_meta($servicio["id_reserva"], 'confirmado_a', time() );

									$wpdb->query("UPDATE wp_posts SET post_status = 'wc-confirmed' WHERE ID = '{$servicio["id_orden"]}';");
						    		$wpdb->query("UPDATE wp_posts SET post_status = 'confirmed' WHERE ID = '{$servicio["id_reserva"]}';");
									include("confirmacion.php");
									
									// BEGIN Club de las Patitas Felices
									$count_reservas = $wpdb->get_var( "SELECT  
										count(ID) as cant
									FROM wp_posts
									WHERE post_type = 'wc_booking' 
										AND not post_status like '%cart%' AND post_status = 'confirmed' 
										AND post_author = ".$cliente["id"]."
										AND DATE_FORMAT(post_date, '%m-%d-%Y') between DATE_FORMAT('2017-05-12','%m-%d-%Y') and DATE_FORMAT(now(),'%m-%d-%Y')
									");
									
									if(  $_SESSION['admin_sub_login'] != 'YES' && $count_reservas == 1){
								   		if(isset($cliente["id"])){ // buscar cupones
									   		$cupones = $wpdb->get_results("SELECT items.order_item_name as name
									            FROM `wp_woocommerce_order_items` as items 
									                INNER JOIN wp_woocommerce_order_itemmeta as meta ON 
									                	meta.order_item_id = items.order_item_id
									                INNER JOIN wp_posts as p ON 
									                	p.ID = ".$servicio["id_reserva"]." and p.post_type = 'wc_booking' 
									                WHERE meta.meta_key = 'discount_amount'
									                    and items.`order_id` = p.post_parent
									                    and not items.order_item_name like ('saldo-%')
									            ;");

									   		// validar si son del club
										   		$propietario_id=0;
										   		$propietario_nombre = '';
										   		$propietario_apellido = '';
										   		$propietario_email = '';
										   		$cupon_code = '';
									   		if( !empty($cupones) ){			   			

										   		// Validar si son del club 
										   		foreach ($cupones as $key => $cupon) {
										   			$propietario_id = $wpdb->get_var("
										   				select user_id from wp_usermeta where meta_key = 'club-patitas-cupon' and meta_value = '".$cupon->name."'
										   			");
										   			if( $propietario_id > 0 ){
										   				$propietario_nombre = get_user_meta( $propietario_id, 'first_name', true );
										   				$propietario_apellido = get_user_meta( $propietario_id, 'last_name', true );
										   				$cupon_code = $cupon->name;
										   				break;
										   			}else{

										   				$propietario_id = 0;
										   			}
										   		}
												if( $propietario_id > 0 ){

													if( !is_petsitters( $propietario_id ) ){
														// agregar saldo a favor
														$saldo = get_user_meta( $propietario_id, 'kmisaldo', true );
														$saldo += 150;
														update_user_meta( $propietario_id, 'kmisaldo', $saldo );
													}else{
														// agregar pago a cuidador
														include_once( $PATH_TEMPLATE.'/lib/pagos_cuidador.php');
														$pagos->cargar_retiros( $propietario_id, 150, 'Pago por uso de cupon Club patitas felices' );
													}

													// agregar transaccion en balance
													$wpdb->query("INSERT INTO cuidadores_transacciones (
														tipo,
														user_id,
														fecha,
														referencia,
														descripcion,
														monto,
														reservas,
														comision
													)values(
														'saldo_club',
														{$propietario_id},
														NOW(),
														'".$servicio["id_reserva"]."',
														'Saldo a favor Club de las patitas felices ".$cupon_code."',
														150,
														'',
														0									
													) 
													");

													// enviar email
													$mail_info = realpath( $PATH_TEMPLATE.'/template/mail/clubPatitas/partes/info_sin_perfil.php');
													$phone = get_user_meta( $propietario_id, 'user_phone', true );
													if( !empty($phone) ){
														$mail_info = realpath(
															$PATH_TEMPLATE.'/template/mail/clubPatitas/partes/info_con_perfil.php'
														);
													}
													$message_info = file_get_contents($mail_info);

													$mail_file = realpath( 
														$PATH_TEMPLATE.'/template/mail/clubPatitas/notificacion_de_uso.php'
													);
													$message_mail = file_get_contents($mail_file);

													$message_mail = str_replace('[INFO]', $message_info, $message_mail);
													$message_mail = str_replace('[URL_IMG]', site_url()."/wp-content/themes/kmimos/images", $message_mail);
													$message_mail = str_replace('[name]', $propietario_nombre.' '.$propietario_apellido, $message_mail);
													$message_mail = str_replace('[url]', site_url(), $message_mail);
													$message_mail = str_replace('[CUPON]', $cupon_code, $message_mail);

													$propietario = get_userdata($propietario_id);
													if( isset($propietario->user_email) ){
														wp_mail( $propietario->user_email, "Confirmación de uso cupon Club Patitas Felices!", $message_mail);
														// wp_mail( 'italococchini@gmail.com', "Confirmación de uso cupon Club Patitas Felices!", $message_mail);
													}

												}				   		

									   		}
										}
									}

								}else{ // Bloque Confirmacion
									$CONTENIDO .= "
									<div class='msg_acciones'>
						                <strong>Esta seguro de CONFIRMAR la reserva #".$servicio["id_reserva"].".</strong>
						            </div>";
								}

								// **********************************
								// END Club de las Patitas Felices
								// **********************************
							
							}else{
								$CONTENIDO .= "
								<div class='msg_acciones'>
					                <strong>¡Lo sentimos!</strong><br>
					                La reserva #".$servicio["id_reserva"]." ha sido cancelada previamente, si desea confirmarla, por favor esperar unos 5 minutos antes de intentar nuevamente.
					            </div>";
							}

						}

						if( $acc == "CCL" ){
							
							$continuar_accion = true;
							/*
							$time_cancelado = get_post_meta($servicio["id_reserva"], 'confirmado_a', true);
							if( $time_cancelado != false && $time_cancelado > 0 ){
								$time_cancelado = $time_cancelado - time();
								if( $time_cancelado <= 600 ){
									$continuar_accion = false;
								}
							}
							*/

							if( $continuar_accion ){

								if( $CONFIRMACION == "YES" ){
									update_post_meta($servicio["id_reserva"], 'cancelado_a', time() );
									include(__DIR__."/cancelacion.php");
								}else{ // Bloque Confirmacion
									$CONTENIDO .= "
									<div class='msg_acciones'>
						                <strong>Esta seguro de CANCELAR la reserva #".$servicio["id_reserva"].".</strong>
						            </div>";
								}

							}else{
								$CONTENIDO .= "
								<div class='msg_acciones'>
					                <strong>¡Lo sentimos!</strong><br>
					                La reserva #".$servicio["id_reserva"]." ha sido confirmada previamente, si desea cancelarla, por favor esperar unos 5 minutos antes de intentar nuevamente.
					            </div>";
							}
						}
					}


				}
			
			}


		}
	}


?>