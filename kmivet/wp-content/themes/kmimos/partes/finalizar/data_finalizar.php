<?php
	global $wpdb;

    $cid = vlz_get_page(); 

    $pedido = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}kmivet_reservas WHERE id = ".$cid);
    $data = json_decode($pedido->data);

    $user = get_user_by('ID', $pedido->user_id);
    $metas = get_user_meta($pedido->user_id);

    $time = strtotime( $data->cita_fecha );
    $fecha = date("d/m/Y H:ia", $time);

    $CONTENIDO .= '
        <div class="desglose_container">  

            <div class="desglose_box">  
                <div>  
                    <div class="sub_titulo sub_titulo_top">CITA No</div>  
                    <span>'.$cid.'</span>  
                </div>  
                <div>  
                    <div class="sub_titulo sub_titulo_top">MÉTODO DE PAGO</div>  
                    <span>Pago por '.ucfirst($data->cita_tipo_pago).'</span>  
                </div>  
            </div>  

            <div class="desglose_box datos_cuidador">    
                <strong>CLIENTE</strong>  
                <div class="item">  
                    <div>Nombre</div>  
                    <span>'.$metas['first_name'][0].'</span> 
                </div>
                <div class="item">
                    <div>Email</div>  
                    <span>'.$user->user_email.'</span>
                </div>
                <div class="item">  
                    <div>Teléfono</div>  
                    <span>'.$metas['user_mobile'][0].'</span>
                </div>
            </div>   

            <div class="desglose_box">  
                <strong>Servicio</strong>  
                <div class="item">  
                    <div>Consulta a Domicilio</div>  
                </div>    
            </div>  

            <div class="desglose_box totales">  
                <strong>Datos del Servicio</strong>  
                <div class="item">  
                    <div>Fecha y Hora</div>  
                    <span>'.$fecha.'</span>  
                </div>  
                <div class="item">  
                    <div>Costo</div>  
                    <span>'.number_format($data->cita_precio, 2, ',', ',').'</span>  
                </div>    

                <div class="item total">  
                    <div>Total</div>  
                    <span>'.number_format($data->cita_precio, 2, ',', ',').'</span>  
                </div>  
                <div class="item">  
                    <div>Pagado</div>  
                    <span>'.number_format($data->cita_precio, 2, ',', ',').'</span>  
                </div>    
            </div>  
        </div>
    ';

    $que_hacer = "
    <div class='que_debo_hacer que_debo_hacer_2'>
		<div style='text-align: left;'>¿QUÉ DEBO HACER AHORA?</div>
		<ul>
			<li><span>El médico veterinario seleccionado y/o Atención al Cliente de Kmivet se pondrá en contacto contigo para alinear la logística.</span></li>
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