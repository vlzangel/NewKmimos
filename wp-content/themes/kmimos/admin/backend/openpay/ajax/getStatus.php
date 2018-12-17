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
        

        echo "Orden: ".$reserva->post_parent;

        $openpay = Openpay::getInstance($MERCHANT_ID, $OPENPAY_KEY_SECRET);
        // Openpay::setProductionMode( ($OPENPAY_PRUEBAS == 0) );

        $_openpay_id = $db->get_var("SELECT meta_value FROM wp_usermeta WHERE user_id = {$reserva->post_author} AND meta_key LIKE '%open%'");

        $customer = $openpay->customers->get( $_openpay_id );

        $limite = date("Y-m-d", strtotime("-1 day"));

        $findDataRequest = array(
            'creation[gte]' => $limite
        );

        $chargeList = $customer->charges->getList($findDataRequest);

        $resp = [];
        foreach ($chargeList as $key => $value) {
            $resp[] = [
                $value->id,
                $value->method,
                $value->status,
                $value->order_id,
                $value->error_message,
            ];
        }

        echo "<pre>";
            print_r( $resp );
        echo "</pre>";

    }
    
	exit;
?>