<?php

    $rangos = $db->get_var(" SELECT meta_value FROM wp_postmeta WHERE post_id = '{$servicio}' AND meta_key = '_wc_booking_availability' ");
    $rangos = unserialize($rangos);

    print_r( $_POST );
    print_r( $rangos );

    $inicio = date("Y-m-d", strtotime( str_replace("/", "-", $inicio)));
    $fin = date("Y-m-d", strtotime( str_replace("/", "-", $fin)));

    $db->query("UPDATE cupos SET no_disponible = 0 WHERE servicio = '{$servicio}' AND fecha >= '{$inicio}' AND fecha <= '{$fin}'");

    $rangos_2 = array();

    foreach ($rangos as $key => $value) {

        if( 
            ( $value["from"] != $inicio && $value["to"] != $fin ) || 
            ( str_replace("/", "-", $value["from"]) != $inicio && str_replace("/", "-", $value["to"]) != $fin ) ){

            $temp = array(
                "type" => "custom",
                "bookable" => "no",
                "priority" => "10",
                "from" => $value["from"],
                "to" => $value["to"]
            );

            $rangos_2[] = $temp;

        }

    }

    $db->query("UPDATE cupos SET no_disponible = 1 WHERE servicio = '{$servicio}' AND fecha >= '{$value["from"]}' AND fecha <= '{$value["to"]}'");
    
    $rangos = serialize($rangos_2);
    $db->query(" UPDATE wp_postmeta SET meta_value = '{$rangos}' WHERE post_id = '{$servicio}' AND meta_key = '_wc_booking_availability' ");
?>