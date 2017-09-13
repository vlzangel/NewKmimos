<?php
	include("../wp-load.php");

	global $wpdb;

    date_default_timezone_set('America/Mexico_City');

    $limite = date("Y-m-d", strtotime("-4 day"));

	echo "
		<style>
			*{
				font-size: 12px;
			}
		</style>
	";

	$ordenes_pendientes = $wpdb->get_results("
		SELECT 
			p.ID AS orden,
			p.post_date AS fecha,
			p.post_status AS status_orden,
			reserva.ID AS reserva,
			reserva.post_status AS status_reserva,
			remanente.meta_value AS remanente
		FROM 
			wp_posts AS p
		INNER JOIN wp_posts 						AS reserva 		ON ( p.ID 					= reserva.post_parent																										) 
		INNER JOIN wp_postmeta 						AS mts 	 		ON ( p.ID 					= mts.post_id 					) 
		INNER JOIN wp_postmeta 						AS mts_reserva 	ON ( reserva.ID 			= mts_reserva.post_id 		AND mts_reserva.meta_key 	= '_booking_order_item_id'											) 
		INNER JOIN wp_woocommerce_order_itemmeta 	AS remanente 	ON ( mts_reserva.meta_value = remanente.order_item_id 	AND remanente.meta_key 		= '_wc_deposit_meta'												) 
		WHERE 
			1=1 
			AND mts.meta_key = '_payment_method' AND mts.meta_value = 'openpay_stores'
			AND p.post_type = 'shop_order' AND p.post_status = 'wc-on-hold' AND p.post_date >= '{$limite}'
			AND reserva.post_status = 'unpaid'
		GROUP BY 
			p.ID DESC
	");

	if( count($ordenes_pendientes) > 0){

		$ordenes = array();
		foreach ($ordenes_pendientes as $f) {
			$ordenes[] = $f->orden;
		}

		// Modo Producción
		$openpay = Openpay::getInstance('mbagfbv0xahlop5kxrui', 'sk_b485a174f8d34df3b52e05c7a9d8cb22');
		Openpay::setProductionMode(true);

		 // Modo Pruebas
		// $openpay = Openpay::getInstance('mej4n9f1fsisxcpiyfsz', 'sk_684a7f8598784911a42ce52fb9df936f');

		$findDataRequest = array(
		    'creation[gte]' => $limite,
		    'offset' => 0,
		    'limit' => 10000
	    );

		$chargeList = $openpay->charges->getList($findDataRequest);

		foreach ($chargeList as $key => $value) {

			if( in_array($value->order_id, $ordenes) ){

				switch ($value->status) {

					case 'cancelled':
						
						$id_orden = $value->order_id;
						$id_reserva = $wpdb->get_var("SELECT ID FROM wp_posts WHERE post_parent = {$id_orden} AND post_type LIKE 'wc_booking'");

						$booking = new WC_Booking( $id_reserva );
				 		$order   = new WC_Order($id_orden );
					
						$order->update_status('wc-cancelled');
						$booking->update_status('cancelled');

					break;

					case 'completed':
						
						$id_orden = $value->order_id;
						$id_reserva = $wpdb->get_var("SELECT ID FROM wp_posts WHERE post_parent = {$id_orden} AND post_type LIKE 'wc_booking'");

						$id_item = $wpdb->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = {$id_reserva} AND meta_key = '_booking_order_item_id' ");

						$remanente = $wpdb->get_var("SELECT meta_value FROM wp_woocommerce_order_itemmeta WHERE order_item_id = {$id_item} AND meta_key = '_wc_deposit_meta' ");

						$booking = new WC_Booking( $id_reserva );
   						$order   = new WC_Order($id_orden);

   						$hora_actual = date("Y-m-d H:i:s");
   						$wpdb->query("UPDATE wp_posts SET post_date = '{$hora_actual}', post_date_gmt = '{$hora_actual}', post_modified = '{$hora_actual}', post_modified_gmt = '{$hora_actual}' WHERE ID = {$id_reserva};");
   						$wpdb->query("UPDATE wp_posts SET post_date = '{$hora_actual}', post_date_gmt = '{$hora_actual}', post_modified = '{$hora_actual}', post_modified_gmt = '{$hora_actual}' WHERE ID = {$id_orden};");

					 	if( $remanente != 'a:1:{s:6:"enable";s:2:"no";}' ){
							$booking->update_status('unpaid');
							$order->update_status('wc-partially-paid');
						}else{
							$booking->update_status('paid');
							$order->update_status('wc-completed');
						}

						$info = kmimos_get_info_syte();

					    add_filter( 'wp_mail_from_name', function( $name ) {
					        global $info;
					        return $info["titulo"];
					    });
					    add_filter( 'wp_mail_from', function( $email ) {
					        global $info;
					        return $info["email"]; 
					    });

					    $email_admin = $info["email"];

						$datos_generales = kmimos_datos_generales_desglose($id_orden, true, true);

						$detalles_cliente = $datos_generales["cliente"];
						$detalles_cuidador = $datos_generales["cuidador"];
						$detalles_mascotas = $datos_generales["mascotas"];

						$cliente_email  = $datos_generales["cliente_email"];
						$cuidador_email = $datos_generales["cuidador_email"];

					    $orden_id = $datos_generales["orden"];
					    $reserva_id = $datos_generales["booking"];

					    $order = new WC_Order($orden_id);
						$booking = new WC_Booking($reserva_id);

					    $nom_cliente  = $datos_generales["nombre_cliente"];
					    $nom_cuidador = $datos_generales["nombre_cuidador"];

					    $producto  = $datos_generales["producto_name"];
					    $tipo_servicio = $datos_generales["tipo_servicio"];

						/* Detalles del servicio */

						$detalles = kmimos_desglose_reserva($id_orden, true);

						$msg_id_reserva = $detalles["msg_id_reserva"];
						$aceptar_rechazar = $detalles["aceptar_rechazar"];
						$detalles_servicio = $detalles["detalles_servicio"];
						$detalles_servicio_cuidador = $detalles["detalles_servicio_cuidador"];

						$metodo_pago = $detalles["metodo_pago"];

						$aceptar_rechazar = '
							<center>
								<p><strong>¿ACEPTAS ESTA RESERVA?</strong></p>
								<table> <tr> <td>
									<a href="'.get_home_url().'/wp-content/plugins/kmimos/order.php?o='.$orden_id.'&s=1&t=1" style="text-decoration: none; padding: 7px 0px; background: #00d2b7; color: #FFF; font-size: 16px; font-weight: 500; border-radius: 5px; width: 100px; display: inline-block; text-align: center;">
										Aceptar
									</a> </td> <td>
									 <a href="'.get_home_url().'/wp-content/plugins/kmimos/order.php?o='.$orden_id.'&s=0&t=1" style="text-decoration: none; padding: 7px 0px; background: #dc2222; color: #FFF; font-size: 16px; font-weight: 500; border-radius: 5px; width: 100px; display: inline-block; text-align: center;">
									 	Rechazar
									 </a> </td> </tr>
								</table>
							</center>
						';

						$dudas = '<p align="justify">Para cualquier duda y/o comentario puedes contactar al Staff Kmimos a los teléfonos '.$info["telefono"].', o al correo '.$info["email"].'</p>';

						/* Cliente */

							$msg_id_reserva ='<p>Solicitud de reserva de servicio <strong>(N° '.$reserva_id.')</strong> </p>';

							$saludo = '
								<center style="font-size: 16px; font-weight: 600;">¡Gracias '.$nom_cliente.'!</center>
								<p>Recibimos tu solicitud de reserva de <strong>'.$tipo_servicio.'</strong>, para que <strong>'.$nom_cuidador.'</strong> atienda a tu(s) peludo(s).</p>
								<p align="justify">
									Dentro de las próximas 12 horas el cuidador confirmará la disponibilidad para tu solicitud. 
									Posterior a que el cuidador confirme, te llegará un correo indicando que está <u>confirmada</u> tu reserva (el período de 12 horas es referencial, 
									normalmente los cuidadores contactan al clientes con mucha anterioridad).
								</p>
								<p align="justify">
									Si no puedes o decides no esperar las 12 horas no te preocupes, puedes contactar directamente al cuidador a través del teléfono o correo mostrados más abajo.
								</p>
								<p align="justify">
									En caso de que el cuidador no esté disponible, recibirás un correo de notificación con instrucciones para que puedas solicitar el reembolso del importe realizado.
								</p>
								<h2>Detalles de tu reservación pendiente a confirmar:</h2>
							';

							$mensaje_cliente = 	
								$msg_id_reserva.
								$saludo.
								$detalles_cuidador.
								$detalles_mascotas.
								$detalles_servicio.
						  		$dudas
							;

							if( $modificacion == "" ){
								$titulo_mail = 'Solicitud de reserva';
							}else{
								$titulo_mail = $modificacion;
							}
							
							$mensaje_cliente = kmimos_get_email_html($titulo_mail, $mensaje_cliente, 'Solicitud de reserva', true, true);

						/* Administrador */

							$saludo = "<p>Hola <strong>Administrador</strong>,</p>";
							$msg_id_reserva ='<p>Se ha pagado la Reserva <strong># '.$reserva_id.'</strong> </p>';
							
							$mensaje_admin 	= 
								$saludo.
						  		$msg_id_reserva.
						  		"<br>".$aceptar_rechazar.
					  			$detalles_cliente.
						  		$detalles_cuidador.
						  		$detalles_mascotas.
						  		$detalles_servicio_cuidador.
						  		"<br>".$aceptar_rechazar
							;

							if( $modificacion == "" ){
								$titulo_mail = 'Nueva Reserva - '.$producto;
							}else{
								$titulo_mail = $modificacion;
							}

							$mensaje_admin = kmimos_get_email_html($titulo_mail, $mensaje_admin, 'Nueva Reserva - '.$producto, true, true);

						/* Cuidador */

							$msg_id_reserva ='<p>Reserva #: <strong>'.$reserva_id.'</strong> </p>';

							$saludo = '
								<p>Hola <strong>'.$nom_cuidador.'</strong>,</p>
								<p>El cliente <strong>'.$nom_cliente.'</strong> te ha enviado una solicitud de Reserva.</p>
								'.$aceptar_rechazar.'
								<h2>Detalles de la solicitud:</h2>
							';

							$siguientes_pasos = '
								<br><p align="center"><strong><u>SIGUIENTES PASOS - Importante</u></strong></p>
								<ul>
									<li> <p align="justify">Es necesario que en las 8 horas a partir de que recibiste esta solicitud confirmes si aceptas el servicio.  En caso de no llevarlo a cabo, el sistema cancelará esta solicitud y enviará automáticamente una recomendación al cliente sobre otros cuidadores sugeridos para atenderlo.</p> </li>
									<li> <p align="justify">Si existe algún cambio en la reserva relacionada a horarios, precios o servicios adicionales por favor asegúrate que el cliente está enterado y que está de acuerdo con los cambios,  posteriormente contacta al Staff Kmimos a la brevedad para realizar los ajustes.</p> </li>
								</ul>
								<br>
								<p align="justify"><strong><u>Puntos a considerar para tu reservación</u></strong></p>
								<br>
								<ul>
									<li><p align="justify">Preséntate con el cliente de una manera cordial, formal y cuidando tu imagen. (Vestimenta casual).</p></li>
									<li><p align="justify">Coméntale la experiencia que tienes cuidando perros y cuál es la rutina que utilizas.</p></li>
									<li><p align="justify">Verifica que el perro del dueño tenga sus vacunas y te compartan su cartilla de vacunación.</p></li>
									<br>
									<li><p align="justify"><strong>IMPORTANTE: </strong>Sin cartilla de vacunación, no estarán amparados ni tú ni el perro ante los beneficios veterinarios de Kmimos.</p></li>
									<br>
									<li><p align="justify">En caso de no conocerse personalmente, asegúrate de que te envíen fotos del perro que llegará a tu casa para confirmar que el tamaño se adecúa a lo declarado por el cliente.</p></li>
								</ul>
								<br>
								<p align="justify">Recuerda que cada perro tiene un comportamiento diferente, por lo que deberás tener la mayor información posible sobre los mismos:</p>
								<ul>
									<li><p>¿Cómo es su rutina diaria? </p>
										<ul> <li> <p>¿Sale a pasear?, ¿A qué hora come y hace del baño?, etc.</p> </li> </ul>
									</li>
									<li><p>¿Cómo se comporta con otros seres vivos? </p>
										<ul> <li> <p>¿Cómo interactúa con otros perros y personas?, ¿Cómo reacciona con un extraño?, etc.</p> </li> </ul>
									</li>
									<li><p>¿Cómo identifica su animó?  </p>
										<ul> <li> <p>¿Cómo se comporta cuando esta triste o estresado?, ¿Qué hace su dueño cuando esta triste o estresado?, etc.</p> </li> </ul>
									</li>
								</ul>
							'; 

							$mensaje_cuidador  	= 	
								$saludo.
								$msg_id_reserva.
						  		$detalles_cuidador.
						  		$detalles_cliente.
						  		$detalles_mascotas.
						  		$detalles_servicio_cuidador.
						  		"<br>".$aceptar_rechazar.
						  		$siguientes_pasos.
						  		$dudas
							;

							if( $modificacion == "" ){
								$titulo_mail = 'Nueva Reserva - '.$tipo_servicio.' por: '.$nom_cliente;
							}else{
								$titulo_mail = $modificacion;
							}
							
							$mensaje_cuidador = kmimos_get_email_html($titulo_mail, $mensaje_cuidador, 'Nueva Reserva - '.$tipo_servicio.' por: '.$nom_cliente, true, true);

						wp_mail( $cliente_email, 'Pago Recibido Exitosamente!', $mensaje_cliente);
						wp_mail( $email_admin, "Solicitud de reserva #".$reserva_id, $mensaje_admin, kmimos_mails_administradores() );
						wp_mail( $cuidador_email, 'Nueva Reserva - '.$tipo_servicio.' por: '.$nom_cliente, $mensaje_cuidador, kmimos_mails_administradores());

					break;
				
				}
				
			}
		}

	}

?>