<?php

    $enviar_code = true;
    if( $CODE == $_SESSION["CODE"] ){
        $enviar_code = false;
    }else{
        $_SESSION["CODE"] = $CODE;
    }

    $orden_sin_pagar = false;
    $status = $wpdb->get_var("SELECT post_status FROM wp_posts WHERE ID = $id;");
    $_payment_method = get_post_meta($id, '_payment_method', true);
    if($status == 'wc-on-hold' && $_payment_method == 'tienda'){
        $orden_sin_pagar = true;
    }

    $cfdi = false;
    if( $superAdmin == "" && $status == "modified" ){
        
    }else{
        $cfdi = kmimos_set_kmisaldo($cliente["id"], $id, $servicio["id_reserva"], $usu);
    }
    
    $wpdb->query("UPDATE wp_posts SET post_status = 'wc-cancelled' WHERE ID = $id;");
    $wpdb->query("UPDATE wp_posts SET post_status = 'cancelled' WHERE ID = '{$servicio["id_reserva"]}';");

    update_cupos( $id, "-");

	$cuidador_info = $wpdb->get_row("SELECT * FROM cuidadores WHERE user_id = ".$cuidador["id"]);

	$sql = "
        SELECT 
            DISTINCT id,
            ROUND ( ( 6371 * acos( cos( radians({$cuidador_info->latitud}) ) * cos( radians(latitud) ) * cos( radians(longitud) - radians({$cuidador_info->longitud}) ) + sin( radians({$cuidador_info->latitud}) ) * sin( radians(latitud) ) ) ), 2 ) as DISTANCIA,
            id_post,
            user_id,
            hospedaje_desde,
            adicionales,
            experiencia
        FROM 
            cuidadores
        WHERE
            id_post != {$cuidador_info->id_post} AND 
            activo = 1 AND
            user_id != 8631
        ORDER BY DISTANCIA ASC
        LIMIT 0, 4
    ";

    $sugeridos = $wpdb->get_results($sql);

    $str_sugeridos = "";

    $file_plantilla = $PATH_TEMPLATE.'/template/mail/reservar/partes/cuidadores.php';
    $plantilla_cuidador = file_get_contents($file_plantilla);

    foreach ($sugeridos as $valor) {
    	$nombre = $wpdb->get_row("SELECT post_title, post_name FROM wp_posts WHERE ID = ".$valor->id_post);
    	$rating = kmimos_petsitter_rating($valor->id_post, true); $rating_txt = "";
    	foreach ($rating as $key => $value) {
    		if( $value == 1 ){ $rating_txt .= "<img style='width: 15px; padding: 0px 1px;' src='[URL_IMGS]/new/huesito.png' >";
    		}else{ $rating_txt .= "<img style='width: 15px; padding: 0px 1px;' src='[URL_IMGS]/new/huesito_vacio.png' >"; }
    	}
    	$servicios = vlz_servicios($valor->adicionales, true);
    	$servicios_txt = "";
        if( count($servicios)+0 > 0 && $servicios != "" ){
            foreach ($servicios as $key => $value) {
                //$servicios_txt .= "<img style='margin: 0px 3px 0px 0px;' src='[URL_IMGS]/servicios/".str_replace('.svg', '.png', $value["img"])."' height='100%' align='middle' >";
                $servicios_txt .= "<img style='margin: 0px 3px 0px 0px;' src='[URL_IMGS]/servicios/".str_replace('.svg', '_.png', $value["img"])."' height='100%' align='middle' >";
            }
        }

        if( $valor->experiencia > 1900 ){
            $valor->experiencia = $valor->experiencia;
        }else{
            $valor->experiencia = date("Y")-$valor->experiencia;
        }

        $monto = explode(",", number_format( ($valor->hospedaje_desde*getComision()), 2, ',', '.') );
        $temp = str_replace("[EXPERIENCIA]", $valor->experiencia, $plantilla_cuidador);

        $temp = str_replace("[MONTO]", $monto[0], $temp);
        $temp = str_replace("[MONTO_DECIMALES]", ",".$monto[1], $temp);
    	$temp = str_replace("[AVATAR]", kmimos_get_foto($valor->user_id), $temp);
    	$temp = str_replace("[NAME_CUIDADOR]", $nombre->post_title, $temp);
    	$temp = str_replace("[HUESOS]", $rating_txt, $temp);
    	$temp = str_replace("[SERVICIOS]", $servicios_txt, $temp);
    	$temp = str_replace('[LIKS]', get_home_url()."/petsitters/".$nombre->post_name."/", $temp);
    	$str_sugeridos .= $temp;
    }

    $file_plantilla = $PATH_TEMPLATE.'/template/mail/reservar/partes/sugeridos.php';
    $plantilla_sugeridos = file_get_contents($file_plantilla);
    $plantilla_sugeridos = str_replace("[CUIDADORES]", $str_sugeridos, $plantilla_sugeridos);

    $msg_cliente = "";
    $msg_cuidador = "";

    $URL_IMGS = get_home_url()."/wp-content/themes/kmimos/images/emails";

    if( $usu == "STM" ){
        $msg_cliente = "Te notificamos que el sistema ha <span style='font-size: 20px; color: #7d1696; font-weight: 600; text-transform: uppercase;'>cancelado</span> la reserva con el cuidador <strong>[name_cuidador]</strong> debido a que se venció el plazo de confirmación.";
        $msg_cuidador = "Te notificamos que el sistema ha <span style='font-size: 20px; color: #7d1696; font-weight: 600; text-transform: uppercase;'>cancelado</span> la reserva realizada por <strong>[name_cliente]</strong> debido a que se venció el plazo de confirmación.";
        $msg_administrador = "Te notificamos que el sistema ha <span style='font-size: 20px; color: #7d1696; font-weight: 600; text-transform: uppercase;'>cancelado</span> la reserva realizada por <strong>[name_cliente]</strong> al cuidador <strong>[name_cuidador]</strong> debido a que se venció el plazo de confirmación.";
        
        $CANCELADO_POR = "reservas/sistema";
    }else{
        if( $usu == "CLI" ){
            $msg_cliente = "Te notificamos que la reserva ha sido <span style='font-size: 20px; color: #7d1696; font-weight: 600; text-transform: uppercase;'>cancelada</span> exitosamente.";
            $msg_cuidador = "Te notificamos que el cliente <strong>[name_cliente]</strong> ha <span style='font-size: 20px; color: #7d1696; font-weight: 600; text-transform: uppercase;'>cancelado</span> la reserva.";
            $msg_administrador = "Te notificamos que el cliente <strong>[name_cliente]</strong> ha <span style='font-size: 20px; color: #7d1696; font-weight: 600; text-transform: uppercase;'>cancelado</span> la reserva.";

            $CANCELADO_POR = "reservas/cliente";
        }else{
            if( $usu == "OPENPAY" ){
                $msg_cliente = "Te notificamos que se ha <span style='font-size: 20px; color: #7d1696; font-weight: 600; text-transform: uppercase;'>cancelado</span> la reserva por vencimiento de pago en tienda.";
                $msg_administrador = "Te notificamos que se ha <span style='font-size: 20px; color: #7d1696; font-weight: 600; text-transform: uppercase;'>cancelado</span> la reserva por vencimiento de pago en tienda.";

                $CANCELADO_POR = "reservas/openpay";
            }else{
                $msg_cliente = "Te notificamos que el cuidador <strong>[name_cuidador]</strong> ha <span style='font-size: 20px; color: #7d1696; font-weight: 600; text-transform: uppercase;'>cancelado</span> la reserva.";
                $msg_cuidador = "Te notificamos que la reserva ha sido <span style='font-size: 20px; color: #7d1696; font-weight: 600; text-transform: uppercase;'>cancelada</span> exitosamente.";
                $msg_administrador = "Te notificamos que el cuidador <strong>[name_cuidador]</strong> ha <span style='font-size: 20px; color: #7d1696; font-weight: 600; text-transform: uppercase;'>cancelado</span> la reserva.";

                $CANCELADO_POR = "reservas/cuidador";
            }
        }
    }

    switch ( $usu ) {
        case 'STM':
            $titulo_cancelacion = "Solicitud Cancelada por el Sistema";
            revertir_saldo_conocer($cliente["id"]);
        break;
        case 'CUI':
            $titulo_cancelacion = "Solicitud Cancelada por el Cuidador";
            revertir_saldo_conocer($cliente["id"]);
        break;
        case 'CLI':
            $titulo_cancelacion = "Solicitud Cancelada por el Cliente";
        break;
        case 'OPENPAY':
            revertir_saldo_conocer($cliente["id"]);
            $titulo_cancelacion = "Cancelación de reserva automática por vencimiento de pago en tienda";
        break;
        
        default:
            $titulo_cancelacion = "Solicitud Cancelada por el Sistema";
        break;
    }

    if( $usu == "CLI" || $usu == "OPENPAY" ){
        $str_sugeridos = "";
        $plantilla_sugeridos = "";
    }

    /* CORREO CLIENTE */
        $file = $PATH_TEMPLATE.'/template/mail/reservar/cliente/cancelar.php';
        $mensaje_cliente = file_get_contents($file);

        $mensaje_cliente = str_replace('[MODIFICACION]', $modificacion, $mensaje_cliente);
        $mensaje_cliente = str_replace("[TITULO_CANCELACION]", $titulo_cancelacion, $mensaje_cliente);
        $mensaje_cliente = str_replace('[mensaje]', $msg_cliente, $mensaje_cliente);
        $mensaje_cliente = str_replace('[name_cliente]', "<strong style='text-transform: uppercase;'>".$cliente["nombre"]."</strong>", $mensaje_cliente);
        $mensaje_cliente = str_replace('[name_cuidador]', $cuidador["nombre"], $mensaje_cliente);
        $mensaje_cliente = str_replace('[id_reserva]', $servicio["id_reserva"], $mensaje_cliente);
        $mensaje_cliente = str_replace('[SUGERIDOS]', $plantilla_sugeridos, $mensaje_cliente);

        $mensaje_cliente = str_replace('[URL_IMGS]', $URL_IMGS, $mensaje_cliente);
        $mensaje_cliente = str_replace('[CANCELADO_POR]', $CANCELADO_POR, $mensaje_cliente);
    	
        if( $usu == "CLI" || $usu == "OPENPAY" ){
            $mensaje_cliente = get_email_html($mensaje_cliente, true, true, $cliente["id"], false, true);	
        }else{
            $mensaje_cliente = get_email_html($mensaje_cliente, true, true, $cliente["id"], false); 
        }

        if( $NO_ENVIAR != "" ){
            echo $mensaje_cliente;
            if( $enviar_code ){
                wp_mail( "vlzangel91@gmail.com", "Cancelación de Reserva", $mensaje_cliente);
            }
        }else{
           wp_mail( $cliente["email"], "Cancelación de Reserva", $mensaje_cliente);
        }

    /* CORREO CUIDADOR */
        $file = $PATH_TEMPLATE.'/template/mail/reservar/cuidador/cancelar.php';
        $mensaje_cuidador = file_get_contents($file);

        $mensaje_cuidador = str_replace('[MODIFICACION]', $modificacion, $mensaje_cuidador);
        $mensaje_cuidador = str_replace("[TITULO_CANCELACION]", $titulo_cancelacion, $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[mensaje]', $msg_cuidador, $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[name_cliente]', $cliente["nombre"], $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[name_cuidador]', $cuidador["nombre"], $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[id_reserva]', $servicio["id_reserva"], $mensaje_cuidador);

        $mensaje_cuidador = str_replace('[URL_IMGS]', $URL_IMGS, $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[CANCELADO_POR]', $CANCELADO_POR, $mensaje_cuidador);

        $mensaje_cuidador = get_email_html($mensaje_cuidador, true, true, $cliente["id"], false, true);

        if( $NO_ENVIAR != "" ){
            echo $mensaje_cuidador;
        }else{
            if( $orden_sin_pagar ){}else{

                if( $usu != "OPENPAY" ){
                    wp_mail( $cuidador["email"], "Cancelación de Reserva", $mensaje_cuidador);
                }
                
            }
        }

        $file = $PATH_TEMPLATE.'/template/mail/reservar/admin/cancelar.php';
        $mensaje_admin = file_get_contents($file);

        if( $str_sugeridos == "" ){
            $str_sugeridos = "No se enviaron sugerencias";
        }

        $mensaje_admin = str_replace('[MODIFICACION]', $modificacion, $mensaje_admin);
        $mensaje_admin = str_replace("[TITULO_CANCELACION]", $titulo_cancelacion, $mensaje_admin);
        $mensaje_admin = str_replace('[mensaje]', $msg_administrador, $mensaje_admin);
        $mensaje_admin = str_replace('[name_cliente]', $cliente["nombre"], $mensaje_admin);
        $mensaje_admin = str_replace('[name_cuidador]', $cuidador["nombre"], $mensaje_admin);
        $mensaje_admin = str_replace('[id_reserva]', $servicio["id_reserva"], $mensaje_admin);

        if( $usu == "CLI" || $usu == "OPENPAY" ){
            $mensaje_admin = str_replace('[SUGERENCIAS]', "", $mensaje_admin);
        }else{
            $mensaje_admin = str_replace('[SUGERENCIAS]', "<div style='background-color: #efefef; font-family: Verdana; font-size: 16px; line-height: 1.07; letter-spacing: 0.3px; color: #000000; padding: 30px 30px 20px;'>
                <strong>Sugerencias enviadas al cliente:</strong>
            </div>

            <div style='background-color: #efefef; text-align: center; padding: 0px 3px 30px;'>
                [CUIDADORES]
            </div>", $mensaje_admin);
            $mensaje_admin = str_replace('[CUIDADORES]', $str_sugeridos, $mensaje_admin);
        }

        $mensaje_admin = str_replace('[CUIDADORES]', $str_sugeridos, $mensaje_admin);

        $mensaje_admin = str_replace('[URL_IMGS]', $URL_IMGS, $mensaje_admin);
        $mensaje_admin = str_replace('[CANCELADO_POR]', $CANCELADO_POR, $mensaje_admin);

        if( $usu == "CLI" || $usu == "OPENPAY" ){
            $mensaje_admin = get_email_html($mensaje_admin, true, true, $cliente["id"], false, true); 
        }else{
            $mensaje_admin = get_email_html($mensaje_admin, true, true, $cliente["id"], false);
        }

        if( $NO_ENVIAR != "" ){
            echo $mensaje_admin;
        }else{
           kmimos_mails_administradores_new("Cancelación de Reserva", $mensaje_admin);
        }

        
        $style = ($cfdi)? '' : 'msg_acciones';

        $CONTENIDO .= '
            <h1 style="margin: 10px 0px 5px 0px; padding: 0px; text-align:left;">
                <div class="'.$style.'">
                    Te notificamos que la reserva <strong>#'.$servicio["id_reserva"].'</strong>, ha sido cancelada exitosamente.
                </div>
            </h1>
        ';

        $factura = $wpdb->get_row( "select * from facturas where reserva_id = {$servicio["id_reserva"]}");
        if( isset($factura->id) && $factura->id > 0 ){

            $CONTENIDO .= '
                <style type="text/css">
                    .volver_msg{
                        display: none;
                    }
                </style>
                <input type="hidden" id="id_orden" name="id_orden" value="'.$factura->pedido_id.'" />
                <section id="descargar-factura">
                    <label class="lbl-text" style="font-style:italic;">
                        El Comprobante Fiscal Digital fue emitido satisfactoriamente
                    </label>
                    <hr style="margin: 5px 0px 15px;">
                    <div class="col-sm-6 col-md-3 btn-factura" >
                        <a href="'.get_home_url()."/consultar-factura/".$servicio["id_reserva"].'" target="_blank" class="km-btn-primary">Consultar</a>
                    </div>
                    <div class="col-sm-6 col-md-3 btn-factura">
                        <a href="javascript:;" data-pdfxml="'."{$servicio["id_reserva"]}_{$factura->numeroReferencia}".'" class="km-btn-primary">Descargar PDF y XML</a>
                    </div>
                    <div class="col-sm-6 col-md-3 btn-factura">
                        <a href="javascript:;" id="btn_facturar_sendmail" class="km-btn-primary">Enviar por Email</a>
                    </div>
                </section>
                <div class="clear"></div>
                <section class="col-sm-12 col-md-12" style="margin-top: 20px;">
                    <div class="perfil_cargando" style="width: 100%; background-image: url('.getTema().'/images/cargando.gif);" ></div>
                    <br>
                    <a href="'.get_home_url().'/perfil-usuario/historial">
                        <i class="fa fa-angle-double-left" aria-hidden="true"></i> Volver 
                    </a>
                </section>
            ';

        }
?>