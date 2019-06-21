<?php    

    session_start();
    
    function phpmailer_init_vlz() {
        
        return [
            "email" => "reservacionesmx@kmimos.la",
            "clave" => "Roberto2019",
            "From" => "reservacionesmx@kmimos.la",
            "FromName" => "Kmimos México",
        ];
    }

    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include($raiz.'/wp-load.php');

    date_default_timezone_set('America/Mexico_City');
    error_reporting(E_ERROR | E_WARNING | E_PARSE );

    global $wpdb;

    extract( $_POST );
    $sql = "SELECT * FROM wp_posts WHERE ID = {$id}";
    $es_reserva = $wpdb->get_row($sql);
    if( $es_reserva->post_type != "wc_booking" ){

        echo json_encode([
            "error" => "No es un ID de reserva valido",
            "sql" => $sql
        ]); die();

    }else{

        $metas = get_post_meta($id);
        $metas_cliente = get_user_meta($metas["_booking_customer_id"][0]);

        $cliente_email = $wpdb->get_var("SELECT user_email FROM wp_users WHERE ID = {$metas["_booking_customer_id"][0]}");

        $producto_id = $metas["_booking_product_id"][0];
        $cuidador = $wpdb->get_var("SELECT post_author FROM wp_posts WHERE ID = {$producto_id}");

        $metas_cuidador = get_user_meta($cuidador);

        $cliente_name = $metas_cliente["first_name"][0]." ".$metas_cliente["last_name"][0];
        $cuidador_name = $metas_cuidador["first_name"][0]." ".$metas_cuidador["last_name"][0];

        $INFORMACION = [
            "CLIENTE" => $cliente_name,
            "RESRVA_ID" => $id,
            "CUIDADOR_NAME" => $cuidador_name,
        ];

        $wpdb->query("INSERT INTO correos_disponibilidad VALUES (NULL, '{$id}', NOW());");

        $mensaje = buildEmailTemplate(
            'correos/confirma_disponibilidad', 
            $INFORMACION
        );

        $mensaje = buildEmailHtml(
            $mensaje, 
            [
                'user_id' => 367, 
                'barras_ayuda' => false,
                'test' => false,
                'dudas' => false,
                'beneficios' => false,
            ]
        );

        // kmimos_mails_administradores_new('Confirmación de Disponibilidad Reserva #'.$id, $mensaje);

        wp_mail( $cliente_email, 'Confirmación de Disponibilidad Reserva #'.$id, $mensaje);

        echo json_encode([
            "error" => "",
            "respuesta" => "Correo Enviado Exitosamente!",
        ]); die();
    }
?>

