<?php
    error_reporting(0);
    
    extract($_POST);

    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/vlz_config.php");


    include($raiz."/openpay/openpay/Openpay.php");
    include($raiz."/wp-content/themes/kmimos/procesos/funciones/config.php");


    $tema = (dirname(dirname(dirname(dirname(__DIR__)))));
    include_once($tema."/procesos/funciones/db.php");
    include_once($tema."/procesos/funciones/generales.php");

    $db = new db( new mysqli($host, $user, $pass, $db) );

    $saldo += 0;

    $reserva = $db->get_row("SELECT * FROM wp_posts WHERE ID = '{$reserva}' AND post_type='wc_booking' ");

    if( $reserva == null ){
        echo "El ID no pertenece a una reserva";
    }else{
        $orden = $db->get_row("SELECT * FROM wp_posts WHERE ID = '{$reserva->post_parent}'");

        try {            
            $openpay = Openpay::getInstance($MERCHANT_ID, $OPENPAY_KEY_SECRET);
            Openpay::setProductionMode( ($OPENPAY_PRUEBAS == 0) );

            $_openpay_id = $db->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = {$reserva->post_parent} AND meta_key LIKE '%_openpay_customer_id%'");
            
            if( $_openpay_id == false ){
                $_openpay_id = $db->get_var("SELECT meta_value FROM wp_usermeta WHERE user_id = {$reserva->post_author} AND meta_key LIKE '%openpay%'");
            }

            $customer = $openpay->customers->get( $_openpay_id );
            $limite = date("Y-m-d", strtotime("-1 day"));
            $findDataRequest = array(
                'creation[gte]' => $limite
            );
            $chargeList = $customer->charges->getList($findDataRequest);
            $resp = [];
            foreach ($chargeList as $key => $value) {
                if( $value->order_id == $reserva->post_parent && $value->method == 'card' ){
                    $resp[] = [
                        "id" => $value->id,
                        "metodo" => $value->method,
                        "status" => $value->status,
                        "order_id" => $value->order_id,
                        "error_message" => $value->error_message,
                        "cliente" => [
                            "id" => $_openpay_id,
                            "nombre" => $customer->name.' '.$customer->last_name,
                            "email" => $customer->email,
                            "direccion" => 'México, '.$customer->address->state.', '.$customer->address->city.' - '.$customer->address->postal_code,
                            "creacion" => $customer->creation_date,
                        ],
                        "tarjeta" => [
                            "titular" => $value->card->holder_name,
                            "numero" => $value->card->brand." ".$value->card->card_number,
                            "expiracion" => $value->card->expiration_month.' / '.$value->card->expiration_year,
                            "banco" => $value->card->bank_name,
                            "tipo" => $value->card->type,
                            "brand" => $value->card->brand,
                        ]
                    ];
                    $falla = $value->error_message;
                }
            }             
            
            $_metas_reserva = $db->get_results("SELECT * FROM wp_postmeta WHERE post_id = {$reserva->ID}");
            $metas_reserva = [];
            foreach ($_metas_reserva as $key => $value) { $metas_reserva[ $value->meta_key ] = $value->meta_value; }

            $_metas_clientes = $db->get_results("SELECT * FROM wp_usermeta WHERE user_id = {$metas_reserva[ '_booking_customer_id' ]}");
            $metas_clientes = [];
            foreach ($_metas_clientes as $key => $value) { $metas_clientes[ $value->meta_key ] = $value->meta_value; }

            $servicio = $db->get_row("SELECT * FROM wp_posts WHERE ID = {$metas_reserva[ '_booking_product_id' ]}");

            $db->query("INSERT INTO solicitudes_openpay VALUES (NULL, '{$user_id}', '{$reserva->ID}', NOW(), 'Consulta');");


            echo utf8_encode('
                <div class="contenedor" >
                    <div>
                        <h2>Datos del cliente</h2>
                        <div>
                            <label>Nombre:</label> '.$metas_clientes["first_name"].' '.$metas_clientes["last_name"].'<br>
                            <label>Email:</label> '.$customer->email.'<br>
                            <label>Tel&eacute;fonos:</label> '.$metas_clientes["user_phone"].' / '.$metas_clientes["user_mobile"].'<br>
                        </div>  
                    </div>
            ');

            echo '
                    <div>
                        <h2>Datos de la reserva</h2>
                        <div>
                            <label>Reserva:</label> '.$reserva->ID.'<br>
                            <label>Fecha:</label> '.date("d/m/Y", strtotime($reserva->post_date) ).'<br>
                            <label>Servicio:</label> '.$servicio->post_title.'<br>
                            <label>Desde:</label> '.date("d/m/Y", strtotime($metas_reserva[ '_booking_start' ]) ).' <label>Hasta:</label> '.date("d/m/Y", strtotime($metas_reserva[ '_booking_end' ]) ).'<br>
                        </div>  
                    </div>
                </div>
                <div style="">
                    <label>Razón del fallo:</label> '.$falla.'
                </div>
            ';

            echo "==========================";
            echo json_encode( $resp );      

        } catch (Exception $e) {
            echo "Error: ".$e->getErrorCode();
        }

    }
    
    exit;
?>