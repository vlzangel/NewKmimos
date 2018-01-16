<?php

    session_start();
    
    require('../wp-load.php');

    date_default_timezone_set('America/Bogota');

    global $wpdb;

    $hora_actual = strtotime("now");
    $xhora_actual = date("H", $hora_actual);

    $periodo_sql = "subio_12 = '0' ";
    $periodo = 1;
    if( $xhora_actual == "17" ){
        $periodo_sql = "subio_06 = '0' ";
        $periodo = 2;
    }

    $hoy = date("Y-m-d");

    $SQL = "SELECT * FROM fotos WHERE {$periodo_sql} AND fecha = '{$hoy}'";
    $enviar_recordatorio = $wpdb->get_results( $SQL );

    $PATH = dirname(__DIR__)."/wp-content/uploads/fotos/";

    foreach ($enviar_recordatorio as $key => $value) {
        $email_cuidador = $wpdb->get_var("SELECT user_email FROM wp_users WHERE ID = {$value->cuidador} ");

        $nombre_cuidador = $wpdb->get_var("SELECT post_title FROM wp_posts WHERE post_author = {$value->cuidador} AND post_type = 'petsitters' ");

        $recordatorio = dirname(__DIR__).'/wp-content/themes/kmimos/template/mail/fotos/recordatorio.php';
        $recordatorio = file_get_contents($recordatorio);

        $temp = "ma&ntilde;ana";
        if( $periodo == 2 ){
            $temp = "tarde";
        }

        $recordatorio = str_replace('[ID_RESERVA]', $value->reserva, $recordatorio);
        $recordatorio = str_replace('[CUIDADOR]', $nombre_cuidador, $recordatorio);
        $recordatorio = str_replace('[PERIODO]', $temp, $recordatorio);

        $recordatorio = get_email_html($recordatorio);

        wp_mail( $email_cuidador, "Recordatorio de carga de fotos", $recordatorio);
    }

?>