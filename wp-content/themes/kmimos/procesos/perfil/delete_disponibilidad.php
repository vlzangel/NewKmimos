<?php

    $respuesta = $_POST;

    $ini = kmimos_dateFormat($inicio, "Y-m-d");
    $fin = kmimos_dateFormat($fin, "Y-m-d");


    $db->query("DELETE FROM disponibilidad WHERE user_id = '$user_id' AND servicio_id = '{$servicio}' AND desde = '$ini' AND hasta = '$fin' ");

    $db->query("UPDATE cupos SET no_disponible = '0' WHERE cuidador = '{$user_id}';");
    $no_disponibilidades = $db->get_results("SELECT * FROM disponibilidad WHERE user_id = '{$user_id}' ");

    foreach ($no_disponibilidades as $data) {
        $desde = strtotime( $data->desde );
        $hasta = strtotime( $data->hasta );
        for ($i=$desde; $i <= $hasta; $i+=86400) { 
            $fecha = date("Y-m-d", $i);
            $existe = $db->get_row("SELECT * FROM cupos WHERE cuidador = {$data->user_id} AND servicio = '{$data->servicio_id}' AND fecha = '{$fecha}'");
            if( $existe !== false ){
                $db->query("UPDATE cupos SET no_disponible = 1 WHERE id = {$existe->id};");
            }else{
                $db->query("
                    INSERT INTO cupos VALUES (
                        NULL,
                        '{$data->user_id}',
                        '{$data->servicio_id}',
                        '{$data->servicio_str}',
                        '{$fecha}',
                        '0',
                        '0',
                        '0',
                        '1'
                    )
                ");
            }
        }
        $acepta = $db->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = '{$data->servicio_id}' AND meta_key = '_wc_booking_qty' ");
        $db->query("UPDATE cupos SET acepta = '{$acepta}' WHERE servicio = '{$data->servicio_id}';");
    }
?>