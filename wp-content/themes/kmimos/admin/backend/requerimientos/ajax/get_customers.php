<?php
    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/wp-load.php");
    date_default_timezone_set('America/Mexico_City');
    global $wpdb;
    extract($_POST);

    $_claves = explode(" ", $clave);

    $SQL = "
        SELECT 
            c.ID,
            c.user_email,
            n.meta_value AS nombre,
            a.meta_value AS apellido
        FROM 
            wp_users AS c
        INNER JOIN wp_usermeta AS n ON ( c.ID = n.user_id )
        INNER JOIN wp_usermeta AS a ON ( c.ID = a.user_id )
        INNER JOIN wp_usermeta AS t ON ( c.ID = t.user_id AND t.meta_key = 'wp_capabilities' )
        WHERE
            n.meta_key = 'first_name' AND
            a.meta_key = 'last_name' AND
            t.meta_value LIKE '%subscriber%' AND
            (
                n.meta_value LIKE '%{$nombre}%' AND
                a.meta_value LIKE '%{$apellido}%' AND
                c.user_email LIKE '%{$email}%'
            ) AND
            (
                n.meta_value != '' AND
                a.meta_value != ''
            )
        ORDER BY nombre ASC
        LIMIT 1, 20
    ";

    $_clientes = $wpdb->get_results($SQL);

    $clientes = [];
    foreach ($_clientes as $key => $value) {
        $nombre = utf8_encode($value->nombre);
        $apellido = utf8_encode($value->apellido);
        if( $nombre != "" ){
            $clientes[] = [
                $value->ID,
                $nombre,
                $apellido,
                $value->user_email
            ];
        }
    }

    echo json_encode([
        "clientes" => $clientes,
        "SQL" => $SQL,
    ]);
?>