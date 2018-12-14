<?php
    
    global $wpdb;

    $orden = vlz_get_page();

    $data_reserva = kmimos_desglose_reserva_data($orden, true);

    $info = '
        <div class="desglose_box">
            <div>
                <div class="sub_titulo">RESERVA</div>
                <span>'.$data_reserva["servicio"]["id_reserva"].'</span>
            </div>
            <div>
                <div class="sub_titulo">MEDIO DE PAGO</div>
                <span>Pago por '.$data_reserva["servicio"]["metodo_pago"].'</span>
            </div>
        </div>
        <div class="desglose_box">
            
            <strong>CLIENTE</strong>
            <div class="item">
                <div>Nombre</div>
                <span>
                    '.$data_reserva["cliente"]["nombre"].'
                </span>
            </div>
            <div class="item">
                <div>Email</div>
                <span>
                    '.$data_reserva["cliente"]["email"].'
                </span>
            </div>
            <div class="item">
                <div>Tel&eacute;fono</div>
                <span>
                    '.$data_reserva["cliente"]["telefono"].'
                </span>
            </div>
        </div>
        <div class="desglose_box">
            
            <strong>DATOS DE MASCOTAS</strong>
            ';
        foreach ($data_reserva["cliente"]["mascotas"] as $mascota){
            $info .='
            <div class="item">
                <div>Nombre</div>
                <span>
                    '.$mascota["nombre"].'
                </span>
            </div>
            <div class="item">
                <div>Raza</div>
                <span>
                    '.$mascota["raza"].'
                </span>
            </div>
            <div class="item">
                <div>Edad</div>
                <span>
                    '.$mascota["edad"].'
                </span>
            </div>
            <div class="item">
                <div>Comportamiento</div>
                <span>
                    '.$mascota["conducta"].'
                </span>
            </div>
            <hr>
            ';}
        $info .='</div>
    ';

    $variaciones = "";
    foreach ($data_reserva["servicio"]["variaciones"] as $value) {
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
                <div>".$data_reserva["servicio"]["tipo"]."</div>
                <span>
                    <span>".date("d/m/Y", $data_reserva["servicio"]["inicio"])."</span>
                        &nbsp; &gt; &nbsp;
                    <span>".date("d/m/Y", $data_reserva["servicio"]["fin"])."</span>
                </span>
            </div>
            ".$data_reserva["servicio"]["paquete"]."
        </div>
        <div class='desglose_box'>
            <strong>Mascotas</strong>
            ".$variaciones."
        </div>
    ";

    $adicionales = "";
    if( count($data_reserva["servicio"]["adicionales"]) > 0 ){
        foreach ($data_reserva["servicio"]["adicionales"] as $value) {
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
    if( count($data_reserva["servicio"]["transporte"]) > 0 ){
        foreach ($data_reserva["servicio"]["transporte"] as $value) {
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

    if( $data_reserva["servicio"]["desglose"]["descuento"]+0 > 0 ){
        $descuento = "
            <div class='item'>
                <div>Descuento</div>
                <span>".number_format( $data_reserva["servicio"]["desglose"]["descuento"], 2, ',', '.')."</span>
            </div>
        ";
    }

    if( $data_reserva["servicio"]["desglose"]["enable"] == "yes" ){
        
        $totales = "
            <div class='desglose_box totales'>
                <strong>Totales</strong>
                <div class='item'>
                    <div class='pago_en_efectivo'>Monto a pagar en EFECTIVO al cuidador</div>
                    <span>".number_format( ($data_reserva["servicio"]["desglose"]["remaining"]), 2, ',', '.')."</span>
                </div>
                <div class='item'>
                    <div>Pagado</div>
                    <span>".number_format( $data_reserva["servicio"]["desglose"]["deposit"], 2, ',', '.')."</span>
                </div>
                ".$descuento."
                <div class='item total'>
                    <div>Total</div>
                    <span>".number_format( $data_reserva["servicio"]["desglose"]["total"], 2, ',', '.')."</span>
                </div>
            </div>
        ";
    }else{
        
        $totales = "
            <div class='desglose_box totales'>
                <strong>Totales</strong>
                <div class='item'>
                    <div>Pagado</div>
                    <span>".number_format( $data_reserva["servicio"]["desglose"]["deposit"]-$data_reserva["descuento"], 2, ',', '.')."</span>
                </div>
                ".$descuento."
                <div class='item total'>
                    <div>Total</div>
                    <span>".number_format( $data_reserva["servicio"]["desglose"]["deposit"], 2, ',', '.')."</span>
                </div>
            </div>
        ";
    }

    $CONTENIDO .= "
        <div class='volver'>
            <a href='".get_home_url()."/perfil-usuario/reservas/'>Volver</a>
        </div>
        <div class='desglose_container'>".
            $info.
            $variaciones.
            $adicionales.
            $transporte.
            $totales.
        "</div>"
    ;

?>