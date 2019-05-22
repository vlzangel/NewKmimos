<?php

    date_default_timezone_set('America/Mexico_City');

    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/wp-load.php");

    global $wpdb;

    extract($_POST);

    $info = $wpdb->get_row("SELECT * reporte_reserva WHERE id = {$id}");

    // $valor = ( $campo == 'comentarios' ) ? $info->comentarios.$valor."\n\n" : $valor;

    $SQL = "
        UPDATE 
            reporte_reserva 
        SET 
            {$campo} = '{$valor}'
        WHERE 
            id = {$id}
    ";

    $wpdb->query( $SQL );

    echo json_encode([
        "SQL" => $SQL
    ]);

?>