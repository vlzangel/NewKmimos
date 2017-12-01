<?php

    $rangos = $db->get_var(" SELECT meta_value FROM wp_postmeta WHERE post_id = '{$servicio}' AND meta_key = '_wc_booking_availability' ");
    $rangos = unserialize($rangos);

    $inicio = date("Y-m-d", strtotime( str_replace("/", "-", $inicio)));
    $fin = date("Y-m-d", strtotime( str_replace("/", "-", $fin)));

    $db->query("UPDATE cupos SET no_disponible = 0 WHERE servicio = '{$servicio}' AND fecha >= '{$inicio}' AND fecha <= '{$fin}'");

    $autor = $db->get_var("SELECT post_author FROM wp_posts WHERE ID = '{$servicio}' ");

    $db->query("UPDATE cupos SET cuidador = '{$autor}' WHERE servicio = '{$servicio}'");

    $rangos_2 = array();

    foreach ($rangos as $key => $value) {

        $formato = explode("/", $value["from"]);
        if( count($formato) > 0 ){
            $value["from"] = date("Y-m-d", strtotime( str_replace("/", "-", $value["from"])));
            $value["to"] = date("Y-m-d", strtotime( str_replace("/", "-", $value["to"])));
        }

        if( $value["from"] == $inicio && $value["to"] == $fin ){ }else{

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
    
    $rangos = serialize($rangos_2);
    $db->query(" UPDATE wp_postmeta SET meta_value = '{$rangos}' WHERE post_id = '{$servicio}' AND meta_key = '_wc_booking_availability' ");
?>