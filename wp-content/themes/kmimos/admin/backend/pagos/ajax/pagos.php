<?php
    session_start();
    date_default_timezone_set('America/Mexico_City');

    include_once('../lib/pagos.php');

    $data = array(
        "data" => array()
    );

    $actual = time();

    extract( $_POST );

    if( $tipo == 'nuevo' ){
        $pagos_lists = $pagos->getPagoCuidador( $desde, $hasta );    
    }else{
        $pagos_lists = $pagos->getPagoGenerados( $desde, $hasta );
    }
 
    $estatus=[
        "" => "",
        "por_autorizar" => "Por autorizar",   
        "autorizado" => "Autorizado",   
        "negado" => "Negado",  
        "in_progress" => "En progreso",  
        "cancelled" => "Cancelado",  
        "completed" => "Completado",  
        "failed" => "Error",
    ];

//print_r($pagos_lists);

    $_SESSION['pago_cuidador'] = [];

    if( $pagos_lists != false ){
        $i = 0;
        foreach ($pagos_lists as $pago) {

            $_SESSION['pago_cuidador'][ $pago->user_id ] = $pago;

            // Datos del cuidador
                $cuidador = $pagos->db->get_row("SELECT user_id, nombre, apellido, banco FROM cuidadores WHERE user_id = {$pago->user_id}");

            $botones = '';

            
            // Validar si el cuidador tiene datos bancarios
                $token = md5(serialize($pago->detalle));
                $checkbox = "<input type='checkbox' data-type='item_selected' name='item_selected[]' data-token='".$token."' value='".$pago->user_id."'>";
                if( !empty($cuidador->banco) ){
                    //$botones .= "<button style='padding:5px;'><i class='fa fa-money'></i> Generar Solicitud de pago</button>"; 
                }else{
                    $botones .= "No posee datos bancarios"; 
                    $checkbox = "<input type='checkbox' class='disabled' data-action='error' title='".$cuidador->nombre." ".$cuidador->apellido." no posee datos bancarios'>";
                }

            if( $tipo == 'generados' ){
                // Detalle
                    $pago->detalle = (!empty($pago->detalle))? unserialize($pago->detalle) : [] ;

                // Opciones
                    $botones = ""; 
            }

            // Agregar salto de linea a detalle
                $detalle = '';
                $count= 0;
                foreach ($pago->detalle as $item) {
                    $count++;

                    // Cargar cupones 
                    $cupon_sql = "SELECT items.order_item_name as name, meta.meta_value as monto  FROM `wp_woocommerce_order_items` as items 
                    INNER JOIN wp_woocommerce_order_itemmeta as meta ON meta.order_item_id = items.order_item_id
                    INNER JOIN wp_posts as p ON p.ID = ".$item['reserva']." and p.post_type = 'wc_booking' 
                    WHERE 
                    meta.meta_key = 'discount_amount'
                    and items.`order_id` = p.post_parent";
                    $cupones = $pagos->db->get_results($cupon_sql);

                    $info = '';
                    if( !empty($cupones) ){                    
                        foreach ($cupones as $cupon) {
                            if( $cupon->monto > 0 ){
                                $info .= " [ ".$cupon->name .": " .$cupon->monto . " ] ";
                            }
                        }
                    }

                    $info = (!empty($info))? 'data-toggle="tooltip" data-placement="top" title="'.$info.'"' : '' ;

                    $detalle .= '<small class="items-span" '.$info.' >'.$item['reserva'].' <span class="badge">$'.number_format($item['monto'], 2, ",", ".").'</span></small>';

                    if( $count == 7 ){ 
                        $detalle .= "<br>"; 
                        $count = 0;
                    }
                }


            // Agregar descripcion de los Autorizados
                $autorizado_por = '';
                $comentarios = '';
                if( !empty($pago->autorizado) ){
                    $autorizar = unserialize($pago->autorizado);
                    foreach ($autorizar as $key => $value) {
                        $nombre = $pagos->db->get_var("SELECT meta_value FROM wp_usermeta WHERE meta_key='first_name' and user_id = {$key}", 'meta_value');
                        $apellido = $pagos->db->get_var("SELECT meta_value FROM wp_usermeta WHERE meta_key='last_name' and user_id = {$key}", 'meta_value');
                        $color_class = ( $value['accion'] == 'negado' )? 'item-danger' : 'item-success';
                        $autorizado_por .= "<div class='items-span {$color_class}'>".utf8_encode($nombre)." ".utf8_encode($apellido)."<span class='badge'>{$value['accion']}</span></div>";

                        $comentarios = $value['comentario'];
                    }
                }

            // Agregar boton de comentarios    
                if( !empty($comentarios) ){
                    $botones .= "<button class='btn btn-default' style='padding:5px;margin:5px;' data-titulo='Comentarios' data-modal='comentarios' data-id='".$pago->id."'><i class='fa fa-comments-o' aria-hidden='true'></i></button>";
                } 

            $data["data"][] = array(
                $checkbox,
                date('Y-m-d',strtotime($pago->fecha_creacion)),
                strtoupper("<strong>".$estatus[$pago->estatus]."</strong>"),
                $pago->user_id,
                utf8_encode($cuidador->nombre),
                utf8_encode($cuidador->apellido),
                '$ '.number_format($pago->total, 2, ",", "."),
                $pago->cantidad,
                $detalle,
                $autorizado_por,
                $botones,
                '',
            );

        }
    }

    echo json_encode($data, JSON_UNESCAPED_UNICODE);

?>