<?php

	/* Administrador */

		// kmimos_mails_administradores_new("Solicitud de reserva #".$reserva_id, $mensaje_admin);

	/* Correo Cliente */


		$cuidador_file = realpath('../../../../template/mail/reservar/cliente.php');
        $mensaje_cliente = file_get_contents($cuidador_file);

        $fin = strtotime( str_replace("/", "-", $_POST['service_end']) );

        $mensaje_cliente = str_replace('[mascotas]', $mascotas, $mensaje_cliente);
        $mensaje_cliente = str_replace('[desglose]', $desglose, $mensaje_cliente);

        $mensaje_cliente = str_replace('[URL_IMGS]', get_home_url()."/wp-content/themes/kmimos/images/emails", $mensaje_cliente);

        $mensaje_cliente = str_replace('[tipo_servicio]', trim($servicio["tipo"]), $mensaje_cliente);
        $mensaje_cliente = str_replace('[id_reserva]', $servicio["id_reserva"], $mensaje_cliente);

        $mensaje_cliente = str_replace('[inicio]', date("d/m", $servicio["inicio"]), $mensaje_cliente);
        $mensaje_cliente = str_replace('[fin]', date("d/m", $servicio["fin"]), $mensaje_cliente);
        $mensaje_cliente = str_replace('[anio]', date("Y", $servicio["fin"]), $mensaje_cliente);
        $mensaje_cliente = str_replace('[tiempo]', $servicio["duracion"], $mensaje_cliente);
        $mensaje_cliente = str_replace('[tipo_pago]', $servicio["metodo_pago"], $mensaje_cliente);

        $mensaje_cliente = str_replace('[name_cliente]', $cliente["nombre"], $mensaje_cliente);

        $mensaje_cliente = str_replace('[name_cuidador]', $cuidador["nombre"], $mensaje_cliente);
        $mensaje_cliente = str_replace('[avatar]', kmimos_get_foto($cuidador["id"]), $mensaje_cliente);
        $mensaje_cliente = str_replace('[telefonos_cuidador]', $cuidador["telefono"], $mensaje_cliente);
        $mensaje_cliente = str_replace('[correo_cuidador]', $cuidador["email"], $mensaje_cliente);
        $mensaje_cliente = str_replace('[direccion_cuidador]', $cuidador["direccion"], $mensaje_cliente);

        $mensaje_cliente = str_replace('[TOTALES]', $totales_plantilla, $mensaje_cliente);

		$mensaje_cliente = get_email_html($mensaje_cliente);

		wp_mail( $cliente["email"], "Solicitud de reserva", $mensaje_cliente);

	/*
		Correo Cuidador
	*/

		$cuidador_file = realpath('../../../../template/mail/reservar/cuidador.php');
        $mensaje_cuidador = file_get_contents($cuidador_file);

        $fin = strtotime( str_replace("/", "-", $_POST['service_end']) );

        $mensaje_cuidador = str_replace('[mascotas]', $mascotas, $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[desglose]', $desglose, $mensaje_cuidador);

        $mensaje_cuidador = str_replace('[URL_IMGS]', get_home_url()."/wp-content/themes/kmimos/images/emails", $mensaje_cuidador);

        $mensaje_cuidador = str_replace('[tipo_servicio]', $servicio["tipo"], $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[id_reserva]', $servicio["id_reserva"], $mensaje_cuidador);

        $mensaje_cuidador = str_replace('[inicio]', date("d/m", $servicio["inicio"]), $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[fin]', date("d/m", $servicio["fin"]), $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[anio]', date("Y", $servicio["fin"]), $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[tiempo]', $servicio["duracion"], $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[tipo_pago]', $servicio["metodo_pago"], $mensaje_cuidador);

        $mensaje_cuidador = str_replace('[name_cliente]', $cliente["nombre"], $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[avatar]', kmimos_get_foto($cliente["id"]), $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[telefonos_cliente]', $cliente["telefono"], $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[correo_cliente]', $cliente["email"], $mensaje_cuidador);

        $mensaje_cuidador = str_replace('[name_cuidador]', $cuidador["nombre"], $mensaje_cuidador);

        $mensaje_cuidador = str_replace('[TOTALES]', $totales_plantilla, $mensaje_cuidador);

		$mensaje_cuidador = get_email_html($mensaje_cuidador, false);

		wp_mail( $cuidador["email"], 'Nueva Reserva - '.$servicio["tipo"].' por: '.$cliente["nombre"], $mensaje_cuidador);
?>