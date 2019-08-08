<?php
    date_default_timezone_set('America/Mexico_City');
    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/wp-load.php");
    global $wpdb;
    $data["data"] = [];
    $registros = $wpdb->get_results("SELECT post_id, meta_value FROM wp_postmeta WHERE meta_key = 'status_change' ORDER BY post_id DESC");
    foreach ($registros as $registro) {
        $meta_reserva = get_user_meta( $registro->post_id );
        $info = json_decode( $registro->meta_value );
            
        $cliente_id = $meta_reserva["_booking_customer_id"][0];

        $metas_cliente = get_user_meta( $cliente_id );
        $metas_admin = get_user_meta($info->admin);
        $correo_cliente = $wpdb->get_var("SELECT user_email FROM wp_users WHERE ID = ".$cliente_id);

        $data["data"][] = [
            $registro->post_id,
            ($metas_cliente["first_name"][0]." ".$metas_cliente["last_name"][0])." (".$correo_cliente.")",
            $info->status_a,
            $info->status_n,
            ($metas_admin["first_name"][0]." ".$metas_admin["last_name"][0]),
            date("d/m/Y h:i:s a", strtotime($info->fecha." ".$info->hora) )
        ];
        

    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    die();
?>