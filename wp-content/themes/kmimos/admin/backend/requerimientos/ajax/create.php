<?php
    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/wp-load.php");
    date_default_timezone_set('America/Mexico_City');
    global $wpdb;
    extract($_POST);

    $SQL = "
        SELECT 
            c.ID,
            c.user_email,
            n.meta_value AS nombre,
            a.meta_value AS apellido,
            t.meta_value AS telf
        FROM 
            wp_users AS c
        INNER JOIN wp_usermeta AS n ON ( c.ID = n.user_id AND n.meta_key = 'first_name' )
        INNER JOIN wp_usermeta AS a ON ( c.ID = a.user_id AND a.meta_key = 'last_name' )
        INNER JOIN wp_usermeta AS t ON ( c.ID = t.user_id AND t.meta_key = 'user_phone' )
        WHERE
            c.ID = {$cliente_id}
    ";

    $cliente = $wpdb->get_row($SQL);

    $slq_crear = "
        INSERT INTO 
            requerimientos
        VALUES (
            NULL,
            NOW(),
            '{$medio}',
            '{$cliente_id}',
            '{$cliente->nombre} {$cliente->apellido}',
            '{$cliente->user_email}',
            '{$cliente->telf}',
            '{$checkin}',
            '{$checkout}',
            '{$total_noches}',
            '{$descripcion}',
            '{$status}',
            '{$observaciones}',
            NOW(),
            '{$atendido_por}'
        )
    ";

    $wpdb->query($slq_crear);

    echo json_encode([
        "SQL" => $slq_crear,
        "POST" => $_POST,
        "cliente" => $cliente,
    ]);
?>