<?php

global $wpdb;
$kmimos_load=dirname(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))))).'/wp-load.php';
if(file_exists($kmimos_load)){
    include_once($kmimos_load);
}
    date_default_timezone_set('America/Mexico_City');

include dirname(dirname(dirname(dirname(__DIR__)))).'/dashboard/core/ControllerReservas.php';

function number_round($number){
    $number=(round($number*100))/100;
    $number=number_format($number, 2, ',', '.');
    return $number;
}

$wlabel=$_wlabel_user->wlabel;
$WLcommission=$_wlabel_user->wlabel_Commission();


$sql = "
SELECT 
    r.ID as 'nro_reserva',
    DATE_FORMAT(r.post_date_gmt,'%Y-%m-%d') as 'fecha_solicitud',
    r.post_status as 'estatus_reserva',
    p.ID as 'nro_pedido',
    p.post_status as 'estatus_pago',            
    pr.post_title as 'producto_title',
    pr.post_name as 'producto_name',            
    (du.meta_value -1) as  'nro_noches',
    (IFNULL(mpe.meta_value,0) + IFNULL(mme.meta_value,0) + IFNULL(mgr.meta_value,0) + IFNULL(mgi.meta_value,0)) as nro_mascotas,
    ((du.meta_value -1) * ( IFNULL(mpe.meta_value,0) + IFNULL(mme.meta_value,0) + IFNULL(mgr.meta_value,0) + IFNULL(mgi.meta_value,0) )) as 'total_noches',

    pr.ID as producto_id,
    pr.post_name as post_name,
    us.user_id as cuidador_id,
    cl.ID as cliente_id

from wp_posts as r
    LEFT JOIN wp_postmeta as rm ON rm.post_id = r.ID and rm.meta_key = '_booking_order_item_id' 
    LEFT JOIN wp_posts as p ON p.ID = r.post_parent

    LEFT JOIN wp_woocommerce_order_itemmeta as fe  ON (fe.order_item_id  = rm.meta_value and fe.meta_key  = 'Fecha de Reserva')
    LEFT JOIN wp_woocommerce_order_itemmeta as du  ON (du.order_item_id  = rm.meta_value and du.meta_key  = 'Duración')
    LEFT JOIN wp_woocommerce_order_itemmeta as mpe ON mpe.order_item_id = rm.meta_value and (mpe.meta_key = 'Mascotas Pequeños' or mpe.meta_key = 'Mascotas Pequeñas')
    LEFT JOIN wp_woocommerce_order_itemmeta as mme ON mme.order_item_id = rm.meta_value and (mme.meta_key = 'Mascotas Medianos' or mme.meta_key = 'Mascotas Medianas')
    LEFT JOIN wp_woocommerce_order_itemmeta as mgr ON (mgr.order_item_id = rm.meta_value and mgr.meta_key = 'Mascotas Grandes')
    LEFT JOIN wp_woocommerce_order_itemmeta as mgi ON (mgi.order_item_id = rm.meta_value and mgi.meta_key = 'Mascotas Gigantes')
    LEFT JOIN wp_woocommerce_order_itemmeta as pri ON (pri.order_item_id = rm.meta_value and pri.meta_key = '_product_id')
    LEFT JOIN wp_posts as pr ON pr.ID = pri.meta_value
    LEFT JOIN cuidadores as us ON us.user_id = pr.post_author
    LEFT JOIN wp_users as cl ON cl.ID = r.post_author

    LEFT JOIN wp_usermeta as wlabel_cliente ON 
        ( 
            wlabel_cliente.user_id = r.post_author AND 
            (
                wlabel_cliente.meta_key = 'user_referred' OR
                wlabel_cliente.meta_key = '_wlabel' 
            ) AND
            wlabel_cliente.meta_value LIKE '%{$wlabel}%'
        )

    LEFT JOIN wp_postmeta as wlabel_reserva ON 
        ( 
            wlabel_reserva.post_id = r.ID AND 
            wlabel_reserva.meta_key = '_wlabel' AND
            wlabel_reserva.meta_value = '{$wlabel}'
        )

WHERE 

    (
        r.post_type = 'wc_booking' AND 
        not r.post_status like '%cart%' AND 
        cl.ID > 0 AND 
        p.ID > 0 AND (
            wlabel_cliente.meta_value = '{$wlabel}' OR
            wlabel_reserva.meta_value = '{$wlabel}'
        )
    ) AND
    r.post_date >= '2018-09-01 00:00:00' 

GROUP BY r.ID
ORDER BY r.ID desc;";

$reservas = $wpdb->get_results($sql);

$_reservas["data"] = []; $i = 1;
foreach ($reservas as $key => $reserva) {
    // *************************************
    // Cargar Metadatos
    // *************************************
    # MetaDatos del Cuidador
    $meta_cuidador = getMetaCuidador($reserva->cuidador_id);
    # MetaDatos del Cliente
    $cliente = getMetaCliente($reserva->cliente_id);

    # Recompra 12 Meses
    $cliente_n_reserva = getCountReservas($reserva->cliente_id, "12");
    if(array_key_exists('rows', $cliente_n_reserva)){
        foreach ($cliente_n_reserva["rows"] as $value) {
            $recompra_12M = ($value['cant']>1)? "SI" : "NO" ;
        }
    }
    # Recompra 1 Meses
    $cliente_n_reserva = getCountReservas($reserva->cliente_id, "1");
    if(array_key_exists('rows', $cliente_n_reserva)){
        foreach ($cliente_n_reserva["rows"] as $value) {
            $recompra_1M = ($value['cant']>1)? "SI" : "NO" ;
        }
    }
    # Recompra 3 Meses
    $cliente_n_reserva = getCountReservas($reserva->cliente_id, "3");
    if(array_key_exists('rows', $cliente_n_reserva)){
        foreach ($cliente_n_reserva["rows"] as $value) {
            $recompra_3M = ($value['cant']>1)? "SI" : "NO" ;
        }
    }
    # Recompra 6 Meses
    $cliente_n_reserva = getCountReservas($reserva->cliente_id, "6");
    if(array_key_exists('rows', $cliente_n_reserva)){
        foreach ($cliente_n_reserva["rows"] as $value) {
            $recompra_6M = ($value['cant']>1)? "SI" : "NO" ;
        }
    }

    # MetaDatos del Reserva
    $meta_reserva = getMetaReserva($reserva->nro_reserva);
    # MetaDatos del Pedido
    $meta_Pedido = getMetaPedido($reserva->nro_pedido);
    # Mascotas del Cliente
    $mypets = getMascotas($reserva->cliente_id); 
    # Estado y Municipio del cuidador
    $ubicacion = get_ubicacion_cuidador($reserva->cuidador_id);
    # Servicios de la Reserva
    $services = getServices($reserva->nro_reserva);
    # Status
    $estatus = get_status(
        $reserva->estatus_reserva, 
        $reserva->estatus_pago, 
        $meta_Pedido['_payment_method'],
        $reserva->nro_reserva // Modificacion Ángel Veloz
    );

    if($estatus['addTotal'] == 1){
        $total_a_pagar += currency_format($meta_reserva['_booking_cost'], "");
        $total_pagado += currency_format($meta_Pedido['_order_total'], "", "", ".");
        $total_remanente += currency_format($meta_Pedido['_wc_deposits_remaining'], "", "", ".");
    }

    $pets_nombre = array();
    $pets_razas  = array();
    $pets_edad   = array();

    foreach( $mypets as $pet_id => $pet) { 
        $pets_nombre[] = $pet['nombre'];
        $pets_razas[] = $razas[ $pet['raza'] ];
        $pets_edad[] = $pet['edad'];
    } 

    $pets_nombre = implode("<br>", $pets_nombre);
    $pets_razas  = implode("<br>", $pets_razas);
    $pets_edad   = implode("<br>", $pets_edad);

    $nro_noches = dias_transcurridos(
            date_convert($meta_reserva['_booking_end'], 'd-m-Y'), 
            date_convert($meta_reserva['_booking_start'], 'd-m-Y') 
        );                  
    if(!in_array('hospedaje', explode("-", $reserva->post_name))){
        $nro_noches += 1;
    }


    $Day = "";
    $list_service = [ 'hospedaje' ]; // Excluir los servicios del Signo "D"
    $temp_option = explode("-", $reserva->producto_name);
    if( count($temp_option) > 0 ){
        $key = strtolower($temp_option[0]);
        if( !in_array($key, $list_service) ){
            $Day = "-D";



        }
    }

    $flash = "";
    if( $meta_reserva['_booking_flash'] == "SI" ){
        $flash = '
            Flash
        ';
    }

    if( isset($meta_reserva["modificacion_de"]) || isset($meta_reserva["reserva_modificada"]) ){
        switch ( $estatus['sts_corto'] ) {
            case 'Modificado':
                if( $meta_reserva["modificacion_de"] != "" && $meta_reserva["reserva_modificada"] != "" ){
                    $estatus['sts_corto'] = 'Modificada-I';
                }else{
                    if( $meta_reserva["reserva_modificada"] != "" ){
                        $estatus['sts_corto'] = 'Modificada-O';
                    }
                    if( $meta_reserva["modificacion_de"] != "" ){
                        $estatus['sts_corto'] = 'Modificada-F';
                    }
                }
            break;
            case 'Confirmado':
                if( $meta_reserva["modificacion_de"] != "" ){
                    // $estatus['sts_corto'] = 'Modificada-F';
                }
            break;
        }
    }

    $telf_cliente = array();
    if( $cliente["user_mobile"] != "" ){ $telf_cliente[] = $cliente["user_mobile"]; }
    if( $cliente["user_phone"] != "" ){ $telf_cliente[] = $cliente["user_phone"]; }

    $telf_cuidador = array();
    if( $meta_cuidador["user_mobile"] != "" ){ $telf_cuidador[] = $meta_cuidador["user_mobile"]; }
    if( $meta_cuidador["user_phone"] != "" ){ $telf_cuidador[] = $meta_cuidador["user_phone"]; }

    $adicionales = "";
    foreach( $services as $service ){ 
        $__servicio = $service->descripcion . $service->servicio;
        $__servicio = str_replace("(precio por mascota)", "", $__servicio); 
        $__servicio = str_replace("(precio por grupo)", "", $__servicio); 
        $__servicio = str_replace("Servicios Adicionales", "", $__servicio); 
        $__servicio = str_replace("Servicios de Transportación", "", $__servicio); 
        $adicionales .= $__servicio."<br>";
    }

    $tipo_pago = "";
    if( !empty($meta_Pedido['_payment_method_title']) ){
        $tipo_pago = $meta_Pedido['_payment_method_title']; 
    }else{
        if( !empty($meta_reserva['modificacion_de']) ){
            $tipo_pago = 'Saldo a favor' ; 
        }else{
            $tipo_pago = 'Saldo a favor y/o cupones'; 
        }
    }

    $forma_pago = "";
    $deposito = $wpdb->get_var("SELECT meta_value FROM wp_woocommerce_order_itemmeta WHERE order_item_id = {$meta_reserva['_booking_order_item_id']} AND meta_key = '_wc_deposit_meta' ");
    $deposito = unserialize($deposito);
    if( $deposito["enable"] == "yes" ){
        $forma_pago = "Pago 20%";
    }else{
        $forma_pago = "Pago Total";
    }

    $eventos = $wpdb->get_var("SELECT COUNT(*) FROM wp_posts WHERE post_author = {$reserva->cliente_id} AND post_type = 'wc_booking' AND post_date >= '2018-09-01 00:00:00' ");

    $_reservas["data"][] = [
        $i,
        $reserva->nro_reserva,
        $flash,
        $estatus['sts_corto'],
        $reserva->fecha_solicitud,
        date_convert($meta_reserva['_booking_start'], 'Y-m-d', true),
        date_convert($meta_reserva['_booking_end'], 'Y-m-d', true),
        $nro_noches . $Day,
        $reserva->nro_mascotas,
        $nro_noches * $reserva->nro_mascotas,
        "<a href='".get_home_url()."/?i=".md5($reserva->cliente_id)."'>".$cliente['first_name'].' '.$cliente['last_name']."</a>",
        $wpdb->get_var("SELECT user_email FROM wp_users WHERE ID = ".$reserva->cliente_id),
        implode(", ", $telf_cliente),
        $eventos,
        '<div id="'.$reserva->cliente_id.'" class="mostrarInfo" onclick="mostrarEvento('.$reserva->cliente_id.')">Mostrar</div>',
        $recompra_1M,
        $recompra_3M,
        $recompra_6M,
        $recompra_12M,
        (empty($cliente['user_referred']))? 'Otros' : $cliente['user_referred'],
        $pets_nombre,
        $pets_razas,
        $pets_edad,
        $meta_cuidador['first_name'] . ' ' . $meta_cuidador['last_name'],
        $wpdb->get_var("SELECT user_email FROM wp_users WHERE ID = ".$reserva->cuidador_id),
        implode(", ", $telf_cuidador),
        $reserva->producto_title,
        $adicionales,
        utf8_decode( $ubicacion['estado'] ),
        utf8_decode( $ubicacion['municipio'] ),
        $tipo_pago,
        $forma_pago,
        currency_format($meta_reserva['_booking_cost'], "", "","."),
        currency_format($meta_Pedido['_order_total'], "", "","."),
        currency_format($meta_Pedido['_wc_deposits_remaining'], "", "","."),
        $reserva->nro_pedido,
        $estatus['sts_largo']                  
    ];

    $i++;

}

echo json_encode( $_reservas );

?>



