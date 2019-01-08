<?php


	// Construir y enviar email
		$desglose = buildEmailTemplate(
			'pagos/desglose',
			[
				'desglose_detalle' => $desglose_detalle,
				'total' => number_format($total, 2, ',', '.'),
				'transaccion_id' =>  $payout->id,
				'estatus' => "En progreso",
				'titular' => $banco['titular'],
				'cuenta' => substr($banco['cuenta'], 0, 3) ."**********". substr($banco['cuenta'], 13, 5),
			]
		);

		$mensaje = buildEmailTemplate(
			$template,
			[
				'name' => $pago->nombre.' '.$pago->apellido,
				'monto' => number_format($total, 2, ',', '.'),
				'comentarios' => $item['comentario'],
				'desglose' => $desglose,
			]
		);

		$mensaje = buildEmailHtml(
			$mensaje, 
			[]
		);
		// wp_mail( $cuidador->email, "Notificación de pago", $mensaje );
		wp_mail( 'i.cocchini@kmimos.la', "Notificación de pago", $mensaje );
