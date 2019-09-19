<?php
	include dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))))).'/wp-load.php';

	include __DIR__.'/funciones.php';

	if( !isset($_SESSION)){ session_start(); }
	
    $ini = $_SESSION["reservas_ini"];
    $fin = $_SESSION["reservas_fin"];
	
	global $wpdb;
	extract($_GET);

	switch ( $action ) {
		case 'list':
			
			$reservas = $wpdb->get_results("SELECT * FROM reporte_reserva_new WHERE fecha_reservacion >= '{$ini}' AND fecha_reservacion <= '{$fin}' ORDER BY reserva_id DESC");
			
			$data['data'] = [];
			$result = $wpdb->get_results("SELECT * FROM razas");
			$razas = [];
			foreach ($result as $raza) {
				$razas[$raza->id] = $raza->nombre;
			}
			foreach ($reservas as $key => $reserva) {
				$cupones = get_cupones_reserva( $reserva->reserva_id );
				$origen = ( empty( get_post_meta($reserva->pedido, 'verification_code', true) ) ) ? 'Página' : 'App';
				if( $reserva->status == 'Pendiente' && $reserva->forma_de_pago != 'Tienda' ){

				}else{

					$r = $wpdb->get_row("SELECT * FROM wp_posts WHERE ID = ".$reserva->reserva_id);

					$creacion = get_post_meta($reserva->pedido, '_booking_creacion', true);
					if( empty($creacion) ){
						$creacion = $r->post_date_gmt;
					}

					$pago = get_post_meta($reserva->pedido, '_booking_pago', true);
					if( empty($pago) ){
						$pago = $r->post_date;
					}

					$pago = date("d/m/Y h:i:s a", strtotime($pago) );

					$pago = ( $reserva->status == 'Pendiente' ) ? '---' : $pago;

					$user_id = $wpdb->get_var("SELECT ID FROM wp_users WHERE user_email = '{$reserva->correo_cliente}' ");
					$mascotas_cliente = $wpdb->get_results("SELECT * FROM wp_posts WHERE post_author = '{$user_id}' AND post_type='pets' AND post_status = 'publish'");
				    $mascotas = array();
				    foreach ($mascotas_cliente as $key => $mascota) {
				        $mascotas[] = $razas[ get_post_meta($mascota->ID, "breed_pet", true) ];
				    }

					if( !empty($r) ){

						$data['data'][] = [
							$reserva->id,
							$origen,
							$reserva->reserva_id,
							$reserva->flash,
							$reserva->status,
							$reserva->fecha_reservacion,
							$reserva->check_in,
							$reserva->check_out,
							$reserva->noches,
							$reserva->num_mascotas,
							$reserva->num_noches_totales,
							$reserva->cliente,
							$reserva->correo_cliente,
							$reserva->telefono_cliente,
							$reserva->recompra_1_mes,
							$reserva->recompra_3_meses,
							$reserva->recompra_6_meses,
							$reserva->recompra_12_meses,
							$reserva->donde_nos_conocio,
							$reserva->mascotas,
							implode("<br>", $mascotas),
							$reserva->edad,
							$reserva->cuidador,
							$reserva->correo_cuidador,
							$reserva->telefono_cuidador,
							$reserva->servicio_principal,
							$reserva->servicios_especiales,
							$reserva->estado,
							$reserva->municipio,

							date("d/m/Y h:i:s a", strtotime($creacion) ),
							$pago,

							$reserva->forma_de_pago,
							$reserva->tipo_de_pago,

							number_format($reserva->total_a_pagar, 2, '.', ','),
							number_format($reserva->monto_pagado, 2, '.', ','),
							number_format($reserva->monto_remanente, 2, '.', ','),
							
							num_for( $cupones[ 'info' ]['saldo']+0 ),

							( is_array($cupones[ 'info' ]['promo']['kmimos']['cupones']) && count($cupones[ 'info' ]['promo']['kmimos']['cupones']) > 0 ) ? implode(', ', $cupones[ 'info' ]['promo']['kmimos']['cupones']) : '-',
							num_for( $cupones[ 'info' ]['promo']['kmimos']['total'] ),

							( is_array($cupones[ 'info' ]['promo']['cuidador']['cupones']) && count($cupones[ 'info' ]['promo']['cuidador']['cupones']) > 0 ) ? implode(', ', $cupones[ 'info' ]['promo']['cuidador']['cupones']) : '-',
							num_for( $cupones[ 'info' ]['promo']['cuidador']['total'] ),

							$reserva->pedido,
							$reserva->observacion,
						];
					}
				}
			}

			echo json_encode($data);
		break;
		
		default:
			
		break;
	}

	/*
id) Incremento automático	
reserva_id)	
flash
status
fecha_reservacion
check_in
check_out
noches
num_mascotas
num_noches_totales
cliente
correo_cliente
telefono_cliente
recompra_1_mes
recompra_3_meses
recompra_6_meses
recompra_12_meses
donde_nos_conocio
mascotas
razas
edad
cuidador
correo_cuidador
telefono_cuidador
servicio_principal
servicios_especiales
estado
municipio
forma_de_pago
tipo_de_pago
total_a_pagar
monto_pagado
monto_remanente
cupones_kmimos
cupones_cuidador
total_cupones
pedido
observacion
comentarios
ult_contacto	
atendido_por
	*/
?>