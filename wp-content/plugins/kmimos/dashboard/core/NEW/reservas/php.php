<?php
	include dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))))).'/wp-load.php';

	include __DIR__.'/funciones.php';
	
	global $wpdb;
	extract($_GET);

	switch ( $action ) {
		case 'list':
			
			$reservas = $wpdb->get_results("SELECT * FROM reporte_reserva_new ORDER BY reserva_id DESC LIMIT 0, 10");

			$data['data'] = [];
			foreach ($reservas as $key => $reserva) {
				$cupones = get_cupones_reserva( $reserva->reserva_id );
				$data['data'][] = [
					$reserva->id,
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
					$reserva->razas,
					$reserva->edad,
					$reserva->cuidador,
					$reserva->correo_cuidador,
					$reserva->telefono_cuidador,
					$reserva->servicio_principal,
					$reserva->servicios_especiales,
					$reserva->estado,
					$reserva->municipio,
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