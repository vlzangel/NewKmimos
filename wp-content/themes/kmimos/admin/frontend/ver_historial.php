<?php
    
    global $wpdb;

    $orden = vlz_get_page();

    $datos_generales = kmimos_datos_generales_desglose($orden, false, false);

    $detalles_cliente = $datos_generales["cliente"];
    $detalles_cuidador = $datos_generales["cuidador"];
    $detalles_mascotas = $datos_generales["mascotas"];

    $cliente_email  = $datos_generales["cliente_email"];
    $cuidador_email = $datos_generales["cuidador_email"];

    /* Detalles del servicio */

    $detalles = kmimos_desglose_reserva($orden);

    $msg_id_reserva = $detalles["msg_id_reserva"];
    $aceptar_rechazar = $detalles["aceptar_rechazar"];
    $detalles_servicio = $detalles["detalles_servicio"];
    $detalles_factura = $detalles["detalles_factura"];

    $data_reserva = kmimos_desglose_reserva_data($orden);

/*    echo "<pre>";
        print_r( $data_reserva );
    echo "</pre>";*/


/*    $email = "<strong>Email: </strong>".$wpdb->get_var("SELECT user_email FROM wp_users WHERE ID='{$data_reserva["cuidador"]}'");
    $telefonos = "<strong>Tel&eacute;fono: </strong>".get_user_meta($data_reserva["cuidador"], "user_phone", true)."<br>";
    $telefonos .= "<strong>M&oacute;vil: </strong>".get_user_meta($data_reserva["cuidador"], "user_mobile", true);
*/
    $info = '
        <div class="desglose_box">
            <div>
                <div class="sub_titulo">CUIDADOR SELECCIONADO</div>
                <span>
                    '.$wpdb->get_var("SELECT post_title FROM wp_posts WHERE post_author='{$data_reserva["cuidador"]}' AND post_type = 'petsitters'").'
                </span>
            </div>
            <div>
                <div class="sub_titulo">MEDIO DE PAGO</div>
                <span>Pago por '.$data_reserva["metodo_pago"].'</span>
            </div>
        </div>
    ';

    $variaciones = "";
    foreach ($data_reserva["variaciones"] as $value) {
        $variaciones .= '
            <div class="item">
                <div>'.$value[0].' '.$value[1].' x '.$value[2].' x $'.$value[3].'</div>
                <span>$'.$value[4].'</span>
            </div>
        ';
    }
    $variaciones = "
        <div class='desglose_box'>
            <strong>Servicio</strong>
            <div class='item'>
                <div>".$data_reserva["servicio_titulo"]."</div>
                <span>
                    <span>".$data_reserva["inicio"]."</span>
                        &nbsp; &gt; &nbsp;
                    <span>".$data_reserva["fin"]."</span>
                </span>
            </div>
        </div>
        <div class='desglose_box'>
            <strong>Mascotas</strong>
            ".$variaciones."
        </div>
    ";

    $adicionales = "";
    if( count($data_reserva["transporte"]) > 0 ){
        foreach ($data_reserva["adicionales"] as $value) {
            $adicionales .= '
                <div class="item">
                    <div>'.$value[0].' - '.$value[1].' x $'.$value[2].'</div>
                    <span>$'.$value[3].'</span>
                </div>
            ';
        }
        $adicionales = "
            <div class='desglose_box'>
                <strong>Servicios Adicionales</strong>
                ".$adicionales."
            </div>
        ";
    }

    $transporte = "";
    if( count($data_reserva["transporte"]) > 0 ){
        foreach ($data_reserva["transporte"] as $value) {
            $transporte .= '
                <div class="item">
                    <div>'.$value[0].'</div>
                    <span>$'.$value[2].'</span>
                </div>
            ';
        }
        $transporte = "
            <div class='desglose_box'>
                <strong>Transportaci&oacute;n</strong>
                ".$transporte."
            </div>
        ";
    }

    $totales = ""; $descuento = "";

    if( $data_reserva["descuento"]+0 > 0 ){
        $descuento = "
            <div class='item'>
                <div>Descuento</div>
                <span>".number_format( $data_reserva["descuento"], 2, ',', '.')."</span>
            </div>
        ";
    }

    if( $data_reserva["desglose"]["enable"] == "yes" ){
        
        $totales = "
            <div class='desglose_box totales'>
                <strong>Totales</strong>
                <div class='item'>
                    <div class='pago_en_efectivo'>Monto a pagar en EFECTIVO al cuidador</div>
                    <span>".number_format( ($data_reserva["desglose"]["remaining"]-$data_reserva["descuento"]), 2, ',', '.')."</span>
                </div>
                <div class='item'>
                    <div>Pagado</div>
                    <span>".number_format( $data_reserva["desglose"]["deposit"], 2, ',', '.')."</span>
                </div>
                ".$descuento."
                <div class='item total'>
                    <div>Total</div>
                    <span>".number_format( $data_reserva["desglose"]["total"], 2, ',', '.')."</span>
                </div>
            </div>
        ";
    }else{
        
        $totales = "
            <div class='desglose_box totales'>
                <strong>Totales</strong>
                <div class='item'>
                    <div>Pagado</div>
                    <span>".number_format( $data_reserva["desglose"]["deposit"]-$data_reserva["descuento"], 2, ',', '.')."</span>
                </div>
                ".$descuento."
                <div class='item total'>
                    <div>Total</div>
                    <span>".number_format( $data_reserva["desglose"]["deposit"], 2, ',', '.')."</span>
                </div>
            </div>
        ";
    }

    $CONTENIDO .= 
        "<div class='desglose_container'>".
            $info.
            $variaciones.
            $adicionales.
            $transporte.
            $totales.
        "</div>"
    ;

?>