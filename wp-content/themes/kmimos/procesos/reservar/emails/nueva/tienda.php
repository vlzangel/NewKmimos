<?php

	/* Administrador */

		// kmimos_mails_administradores_new("Solicitud de reserva #".$reserva_id, $mensaje_admin);

	/* Correo Cliente */


		$cuidador_file = dirname(dirname(dirname(dirname(__DIR__)))).'/template/mail/reservar/cliente_tienda.php';
        $mensaje_cliente = file_get_contents($cuidador_file);

        $fin = strtotime( str_replace("/", "-", $_POST['service_end']) );

        $mensaje_cliente = str_replace('[mascotas]', $mascotas, $mensaje_cliente);
        $mensaje_cliente = str_replace('[desglose]', $desglose, $mensaje_cliente);
        
        $mensaje_cliente = str_replace('[ADICIONALES]', $adicionales, $mensaje_cliente);
       	$mensaje_cliente = str_replace('[TRANSPORTE]', $transporte, $mensaje_cliente);
        

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

		//echo $mensaje_cliente = get_email_html($mensaje_cliente);

		wp_mail( $cliente["email"], "Solicitud de reserva", $mensaje_cliente);
?>