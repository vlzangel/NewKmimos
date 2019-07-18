<?php
    global $wpdb;
    $kmimos_load=dirname(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))))).'/wp-load.php';
    if(file_exists($kmimos_load)){
        include_once($kmimos_load);
    }
    date_default_timezone_set('America/Mexico_City');
    function number_round($number){
        $number=(round($number*100))/100;
        $number=number_format($number, 2, ',', '.');
        return $number;
    }
    $wlabel=$_wlabel_user->wlabel;
    $WLcommission=$_wlabel_user->wlabel_Commission();
    $reservas = $wpdb->get_results("SELECT * FROM reporte_reserva_new WHERE fecha_reservacion >= '2018-09-01' AND donde_nos_conocio LIKE '%{$wlabel}%' ");
    $_reservas["data"] = []; $i = 1;
    foreach ($reservas as $key => $value) {
        $cliente_id = $wpdb->get_var("SELECT ID FROM wp_users WHERE user_email = '{$value->correo_cliente}' ");
        $eventos = $wpdb->get_var("SELECT COUNT(*) FROM wp_posts WHERE post_author = {$cliente_id} AND post_type = 'wc_booking' AND post_date >= '2018-09-01 00:00:00' ");
        
        $_reservas["data"][] = [
            $i,
            $value->reserva_id,
            $value->flash,
            $value->status,
            $value->fecha_reservacion,
            $value->check_in,
            $value->check_out,
            $value->noches,
            $value->num_mascotas,
            $value->num_noches_totales,
            $value->cliente,
            $value->correo_cliente,
            $value->telefono_cliente,
            "Eventos",
            '<div id="'.$cliente_id.'" class="mostrarInfo" onclick="mostrarEvento('.$cliente_id.')">Mostrar</div>',
            $value->recompra_1_mes,
            $value->recompra_3_meses,
            $value->recompra_6_meses,
            $value->recompra_12_meses,
            $value->donde_nos_conocio,
            $value->mascotas,
            $value->razas,
            $value->edad,

            $value->apellido_cuidador,
            $value->correo_cuidador,
            $value->telefono_cuidador,

            $value->servicio_principal,
            $value->servicios_especiales,

            $value->estado,
            $value->municipio,

            $value->forma_de_pago,
            $value->tipo_de_pago,
            $value->total_a_pagar,
            $value->monto_pagado,
            $value->monto_remanente,
            $value->pedido,
            $value->observacion              
        ];
        $i++;
    }
    echo json_encode( $_reservas );
?>



