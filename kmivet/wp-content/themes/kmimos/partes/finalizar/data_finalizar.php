<?php
	global $wpdb;

    $orden_id = vlz_get_page(); 

    $info = '
        <div class="desglose_box">
            <div>
                <div class="sub_titulo sub_titulo_top">RESERVA</div>
                <span>'.$data_reserva["servicio"]["id_reserva"].'</span>
            </div>
            <div>
                <div class="sub_titulo sub_titulo_top">MÉTODO DE PAGO</div>
                <span>Pago por '.$data_reserva["servicio"]["metodo_pago"].'</span>
            </div>
        </div>
        <div class="desglose_box datos_cuidador">
            
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

    $numero_servicios = 1;
    $nombre_servicios = $data_reserva["servicio"]["tipo"];

    $adicionales = "";
    if( count($data_reserva["servicio"]["adicionales"]) > 0 ){
        foreach ($data_reserva["servicio"]["adicionales"] as $value) {
            $adicionales .= '
                <div class="item">
                    <div>'.$value[0].' - '.$value[1].' x $'.$value[2].'</div>
                    <span>$'.$value[3].'</span>
                </div>
            ';
            $numero_servicios++;
            $nombre_servicios .= " - ".$value[0];
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
            $numero_servicios++;
            $nombre_servicios .= " - ".$value[0];
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
        $descuento .= "
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
                    <span>".number_format( $data_reserva["servicio"]["desglose"]["deposit"], 2, ',', '.')."</span>
                </div>
                ".$descuento."
                <div class='item total'>
                    <div>Total</div>
                    <span>".number_format( $data_reserva["servicio"]["desglose"]["total"], 2, ',', '.')."</span>
                </div>
            </div>
        ";
    }

    $CONTENIDO .= 
        "
        <div class='desglose_container'>".
            $info.
            $variaciones.
            $adicionales.
            $transporte.
            $totales.
        "</div>"
    ;

    $que_hacer = "
    <div class='que_debo_hacer que_debo_hacer_2'>
		<div style='text-align: left;'>¿QUÉ DEBO HACER AHORA?</div>
		<ul>
			<li><span>Revisa tu correo. Te enviaremos la Confirmación o Rechazo de tu Reserva en unos momentos (puede durar desde 30 min a 4 horas).</span></li>
			<li><span>Luego de aceptada la Reserva, el cuidador seleccionado y/o Atención al Cliente de Kmimos se pondrán en contacto contigo para alinear la logística de entrega.</span></li>
			<li><span>En caso de dudas, puedes contactarte con nuestro equipo de atención al cliente al teléfono 01 (55) 8526 1162, Whatsapp +52 1 (33) 1261 41 86, o al correo contactomex@kmimos.la</span></li>
		</ul>
	</div>";
    if( $data_reserva["metodo_pago"] == "Tienda" ){
	    $que_hacer = "
	    <div class='que_debo_hacer que_debo_hacer_2'>
			<div>¿QUÉ DEBO HACER AHORA?</div>
			<ul>
				<li><span>Pícale al botón con las Instrucciones para pagar en la Tienda de Conveniencia que elijas.</span></li>
				<li><span>Recuerda que tienes 48 horas para hacer el pago.</span></li>
				<li><span><strong>El Cuidador que seleccionaste no recibirá tu solicitud de Reserva sino hasta que hayas hecho el pago en la tienda.</strong></span></li>
				<li><span>Una vez que pagues en la Tienda de Conveniencia, el cuidador seleccionado y/o Atención al Cliente de Kmimos se pondrán en contacto contigo dentro de las siguientes 1 a 4 horas para alinear la logística de entrega.</span></li>
				<li><span>En caso de dudas, puedes contactarte con nuestro equipo de atención al cliente al teléfono 01 (55) 8526 1162, Whatsapp +52 1 (33) 1261 41 86, o al correo contactomex@kmimos.la</span></li>

			</ul>

		</div>";
    }

?>