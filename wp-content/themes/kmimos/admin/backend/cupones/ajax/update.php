<?php

    date_default_timezone_set('America/Mexico_City');

    extract($_POST);
    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/wp-load.php");

    global $wpdb;

    $orden_status = $wpdb->query("
        DELETE FROM wp_postmeta WHERE post_id = '{$cupon_id}' AND meta_key = '_used_by' AND meta_value = '{$user_id}'
    ");

      

?>