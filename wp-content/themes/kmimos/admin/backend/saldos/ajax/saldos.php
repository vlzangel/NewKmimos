<?php
    date_default_timezone_set('America/Mexico_City');
    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/wp-load.php");
    global $wpdb;
    $data["data"] = [];
    $registros = $wpdb->get_results("SELECT * FROM cambios_saldos ORDER BY id DESC");
    foreach ($registros as $registro) {
        $metas_cliente = get_user_meta($registro->cliente);
        $metas_admin = get_user_meta($registro->admin);
        $data["data"][] = [
            $registro->id,
            ($metas_cliente["first_name"][0]." ".$metas_cliente["last_name"][0]),
            "$".number_format($registro->saldo_anterior, 2, ',', '.'),
            "$".number_format($registro->saldo_siguiente, 2, ',', '.'),
            ($metas_admin["first_name"][0]." ".$metas_admin["last_name"][0]),
            date("d/m/Y h:i:s a", strtotime($registro->fecha) )
        ];
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    die();
?>