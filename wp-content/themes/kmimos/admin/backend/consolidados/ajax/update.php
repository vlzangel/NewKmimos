<?php
    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/wp-load.php");
    date_default_timezone_set('America/Mexico_City');
    global $wpdb;
    extract($_POST);
    $info = $wpdb->get_row("SELECT * reporte_reserva_new WHERE id = {$id}");
    $SQL = "
        UPDATE 
            reporte_reserva_new 
        SET 
            {$campo} = '{$valor}'
        WHERE 
            id = {$id}
    ";
    $wpdb->query( $SQL );
    echo json_encode([
        "SQL" => $SQL,
        "status" => "ok"
    ]);
?>