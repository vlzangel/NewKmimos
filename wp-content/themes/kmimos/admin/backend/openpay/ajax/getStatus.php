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
            // Openpay::setProductionMode( ($OPENPAY_PRUEBAS == 0) );

            $_openpay_id = $db->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = {$reserva->post_parent} AND meta_key LIKE '%_openpay_customer_id%'");

            $customer = $openpay->customers->get( $_openpay_id );

            $limite = date("Y-m-d", strtotime("-1 day"));

            $findDataRequest = array(
                'creation[gte]' => $limite
            );

            $chargeList = $customer->charges->getList($findDataRequest);

            $resp = [];
            foreach ($chargeList as $key => $value) {
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
            }  

            echo '
                <div class="contenedor" >
                    <div>
                        <h2>Datos del pago</h2>
                        <div>
                            <label>ID:</label> '.$value->id.'<br>
                            <label>Status:</label> '.$value->status.'<br>
                            <label>ID ORDEN:</label> '.$value->order_id.'<br>
                        </div>  
                    </div>
                    <div>
                        <h2>Datos del cliente</h2>
                        <div>
                            <label>Nombre:</label> '.$customer->name.' '.$customer->last_name.'<br>
                            <label>Email:</label> '.$customer->email.'<br>
                        </div>  
                    </div>
                    <div>
                        <h2>Datos de la tarjeta</h2>
                        <div>
                            <label>Titular:</label> '.$value->card->holder_name.'<br>
                            <label>Número:</label> '.$value->card->card_number.'<br>
                            <label>Vencimiento:</label> '.$value->card->expiration_month.' / '.$value->card->expiration_year.'<br>
                            <label>Tipo:</label> '.$value->card->type.' - '.$value->card->brand.'<br>
                            <label>Banco:</label> '.$value->card->bank_name.'<br>
                        </div>  
                    </div>
                </div>
                <div style="">
                    <label>Razón del fallo:</label> '.$value->error_message.'
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