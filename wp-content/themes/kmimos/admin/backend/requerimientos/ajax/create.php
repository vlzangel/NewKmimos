<?php
    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/wp-load.php");
    date_default_timezone_set('America/Mexico_City');
    global $wpdb;
    extract($_POST);

    $slq_crear = "
        INSERT INTO 
            requerimientos
        VALUES (
            NULL,
            NOW(),
            '{$medio}',
            '{$nombre} {$apellido}',
            '{$email}',
            '{$telf}',
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
    ]);
?>