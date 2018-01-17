<?php

    $respuesta = $_POST;

    $ini = kmimos_dateFormat($inicio, "Y-m-d");
    $fin = kmimos_dateFormat($fin, "Y-m-d");
    
    if( $servicio != 'todos' ){

        $existe = $db->get_row("
            SELECT 
                id
            FROM 
                disponibilidad 
            WHERE 
                user_id = {$user_id} AND 
                servicio_id = '{$servicio}' AND 
                servicio_str = '{$tipo}' AND 
                desde = '{$ini}' AND 
                hasta = '{$fin}'
        ");

        if( $existe === false ){
            $db->query("
                INSERT INTO disponibilidad VALUES (
                    NULL,
                    '{$user_id}',
                    '{$servicio}',
                    '{$tipo}',
                    '{$ini}',
                    '{$fin}'
                )
            ");
        }

    }else{
    
        $mis_servicios = $db->get_results("SELECT ID FROM wp_posts WHERE post_author = '{$user_id}' AND post_type = 'product' ");
        foreach ($mis_servicios as $servicio) {
            $tipo = $db->get_var("
                SELECT
                    tipo_servicio.slug AS tipo
                FROM 
                    wp_term_relationships AS relacion
                LEFT JOIN wp_terms as tipo_servicio ON ( tipo_servicio.term_id = relacion.term_taxonomy_id )
                WHERE 
                    relacion.object_id = '{$servicio->ID}' AND
                    relacion.term_taxonomy_id != 28
            ");

            $existe = $db->get_row("
                SELECT 
                    id
                FROM 
                    disponibilidad 
                WHERE 
                    user_id = {$user_id} AND 
                    servicio_id = '{$servicio->ID}' AND 
                    servicio_str = '{$tipo}' AND 
                    desde = '{$ini}' AND 
                    hasta = '{$fin}'
            ");

            if( $existe === false ){
                $db->query("
                    INSERT INTO disponibilidad VALUES (
                        NULL,
                        '{$user_id}',
                        '{$servicio->ID}',
                        '{$tipo}',
                        '{$ini}',
                        '{$fin}'
                    )
                ");
            }
        }

    }

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