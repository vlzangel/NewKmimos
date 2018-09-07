<?php
global $wpdb;
$kmimos_load=dirname(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))))).'/wp-load.php';
if(file_exists($kmimos_load)){
    include_once($kmimos_load);
}

include dirname(dirname(dirname(dirname(__DIR__)))).'/dashboard/core/ControllerReservas.php';

function number_round($number){
    $number=(round($number*100))/100;
    $number=number_format($number, 2, ',', '.');
    return $number;
}

$wlabel=$_wlabel_user->wlabel;
$WLcommission=$_wlabel_user->wlabel_Commission();
/*
$_wlabel_user->wlabel_Options('booking');
$_wlabel_user->wLabel_Filter(array('trdate'));
$_wlabel_user->wlabel_Export('booking','RESERVAS','table');*/

$wlabel = $_SESSION["label"]->wlabel; ?>

<div class="module_title">
    RESERVAS
</div>

<!-- <div class="module_data">
    <div class="item" id="user_filter">Personas reservando en el periodo seleccionado: <span></span></div>
</div> -->

<div class="section">
    <div class="tables">
    <table cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th>#</th>
                <th># Reserva</th>
                <th>Flash</th>
                <th>Estatus</th>
                <th>Fecha Reservacion</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Noches</th>
                <th># Mascotas</th>
                <th># Noches Totales</th>
                <th>Cliente</th>
                <th>Correo Cliente</th>
                <th>Tel&eacute;fono Cliente</th>
                <th>Recompra (1Mes)</th>
                <th>Recompra (3Meses)</th>
                <th>Recompra (6Meses)</th>
                <th>Recompra (12Meses)</th>
                <th>Donde nos conocio?</th>
                <th>Mascotas</th>
                <th>Razas</th>
                <th>Edad</th>
                <th>Cuidador</th>
                <th>Correo Cuidador</th>
                <th>Tel&eacute;fono Cuidador</th>
                <th>Servicio Principal</th> 
                <th>Servicios Especiales</th> <!-- Servicios adicionales -->
                <th>Estado</th>
                <th>Municipio</th>
                <th>Forma de Pago</th>
                <th>Tipo de Pago</th>
                <th>Total a pagar ($)</th>
                <th>Monto Pagado ($)</th>
                <th>Monto Remanente ($)</th>
                <th># Pedido</th>
                <th>Observaci&oacute;n</th>
            </tr>
        </thead>

        <tbody> <?php
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
                            wlabel_cliente.meta_value = '{$wlabel}'
                        )

                    LEFT JOIN wp_postmeta as wlabel_reserva ON 
                        ( 
                            wlabel_reserva.post_id = r.ID AND 
                            wlabel_reserva.meta_key = '_wlabel' AND
                            wlabel_reserva.meta_value = '{$wlabel}'
                        )

                WHERE r.post_type = 'wc_booking' 

                    and not r.post_status like '%cart%' 
                    and cl.ID > 0 
                    and p.ID > 0
                    and (
                        wlabel_cliente.meta_value = '{$wlabel}' OR
                        wlabel_reserva.meta_value = '{$wlabel}'
                    )

                ORDER BY r.ID desc
                ;";

            $reservas = $wpdb->get_results($sql);


            /*echo "<pre>";
                print_r( $reservas );
            echo "</pre>";

            (
                [nro_reserva] => 201835
                [fecha_solicitud] => 2018-09-04
                [estatus_reserva] => paid
                [nro_pedido] => 201834
                [estatus_pago] => wc-completed
                [producto_title] => Hospedaje - Pedro P.
                [producto_name] => hospedaje-8631-pedro-p
                [nro_noches] => 0
                [nro_mascotas] => 1
                [total_noches] => 0
                [producto_id] => 150448
                [post_name] => hospedaje-8631-pedro-p
                [cuidador_id] => 8631
                [cliente_id] => 367
            )
            */

            $_reservas = [];
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

                $_reservas[ $reserva->nro_reserva ] = [
                    $reserva->nro_reserva,
                    $flash,
                    $estatus['sts_corto'],
                    $reserva->fecha_solicitud,
                    date_convert($meta_reserva['_booking_start'], 'Y-m-d', true),
                    date_convert($meta_reserva['_booking_end'], 'Y-m-d', true),
                    $nro_noches . $Day,
                    $reserva->nro_mascotas,
                    $nro_noches * $reserva->nro_mascotas,
                    "<a href='".get_home_url()."/?i=".md5($reserva->cliente_id)."'>".$cliente['first_name'].' '.$cliente['last_name'],
                    $wpdb->get_var("SELECT user_email FROM wp_users WHERE ID = ".$reserva->cliente_id),
                    implode(", ", $telf_cliente),
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
            }

            $cont = 1;
            foreach ($_reservas as $key => $valor) { ?>
                <tr> <?php
                    echo "<td>{$cont}</td>";
                    foreach ($valor as $key => $value) {
                        echo "<td>{$value}</td>";
                    }
                    $cont++; ?>
                </tr> <?php
            } ?>

        </tbody>
    </table>
</div>

<style type="text/css">
    table th {
        white-space: nowrap;
    }
    table td {
        text-align: center;
        white-space: nowrap;
        padding: 10px;
    }
</style>

<?php
/*
   $sql = "
            SELECT
                posts.*,
                posts.ID AS ID,
                posts.post_type AS ptype,
                posts.post_status AS status,
                posts.post_parent AS porder,
                posts.post_author AS customer
            FROM
                wp_posts AS posts
                LEFT JOIN wp_postmeta AS postmeta ON (postmeta.post_id=posts.post_parent AND postmeta.meta_key='_wlabel')
                LEFT JOIN wp_usermeta AS usermeta ON (usermeta.user_id=posts.post_author AND usermeta.meta_key='_wlabel')
            WHERE
                posts.post_type = 'wc_booking' AND
                (
                    usermeta.meta_value = '{$wlabel}' OR
                    postmeta.meta_value = '{$wlabel}'
                )
                AND NOT
                posts.post_status LIKE '%cart%'
            ORDER BY
              posts.ID DESC
     ";

   $bookings = $wpdb->get_results($sql);

   foreach($bookings as $key => $booking){
        $ID=$booking->ID;
        $date=strtotime($booking->post_date);
        $customer=$booking->post_author;
        $status=$booking->post_status;
        $order=$booking->post_parent;
        $status_name=$status;

        $_metas_booking = get_post_meta($ID);
        $_metas_order = get_post_meta($order);
        $IDproduct=$_metas_booking['_booking_product_id'][0];
        $IDcustomer=$_metas_booking['_booking_customer_id'][0];
        $IDorder_item=$_metas_booking['_booking_order_item_id'][0];

        $_metas_booking_start=strtotime($_metas_booking['_booking_start'][0]);
        $_metas_booking_end=strtotime($_metas_booking['_booking_end'][0]);
        $duration = floor(($_metas_booking_end-$_metas_booking_start) / (60 * 60 * 24));

        $_meta_WCorder = wc_get_order_item_meta($IDorder_item,'');
       // $_meta_WCorder_line_total = wc_get_order_item_meta($IDorder_item,'_line_total');
        $_meta_WCorder_line_total = wc_get_order_item_meta($IDorder_item,'_line_subtotal');
        $_meta_WCorder_duration = wc_get_order_item_meta($IDorder_item,'Duración');
        $_meta_WCorder_caregiver = wc_get_order_item_meta($IDorder_item,'Ofrecido por');

        //SERVICES
        $post = get_post($IDproduct);
        $services = $post->post_name;
        $services=explode('-',$services);
        if(count($services)>0){
           $services=trim($services[0]);
        }else{
           $services='';
        }

       //DURATION
       $period = 1;
       if(strpos($duration, 'semana') !== false){
           $period = 7;
       }else if(strpos($duration, 'mes') !== false){
           $period = 30;
       }

       $duration=str_replace(array('días','día','dias','dia','day', 'semana', 'semanas', 'mes'),'',$duration);
       $duration=trim($duration);
       $duration_text=' Dia(s)';

       if($services=='hospedaje'){
           $duration=(int)$duration;//-1
           $duration_text=' Noche(s)';
       }

       if($duration<=0){
           $duration=(int)$duration+1;
       }

       $duration_text= $duration.$duration_text;
       $duration_text.='<br>'.date('d/m/Y',(int) strtolower($_metas_booking_start));
       $duration_text.='<br>'.date('d/m/Y',(int) strtolower($_metas_booking_end));

       $_meta_WCorder_services_additional=array();
        foreach($_meta_WCorder as $meta=>$value){
            if(strpos($meta,'Servicios Adicionales') !== false){
                $_meta_WCorder_services_additional[]=str_replace('(precio por mascota)','',$value[0]);
            }
        }
        $_meta_WCorder_services_additional=implode(',',$_meta_WCorder_services_additional);

        //CUSTOMER
        $_metas_customer = get_user_meta($customer);
        $_customer_name = $_metas_customer['first_name'][0] . " " . $_metas_customer['last_name'][0];

       //CAREGIVER
        $caregiver = $post->post_author;
        $_metas_caregiver = get_user_meta($caregiver);
        $_caregiver_name = $_metas_caregiver['first_name'][0] . " " . $_metas_caregiver['last_name'][0];

        $product = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID ='$IDproduct'");

        $html='
        <tr class="trshow" data-day="'.date('d',$date).'" data-month="'.date('n',$date).'" data-year="'.date('Y',$date).'" data-status="'.$status.'">
            <td>'.$booking->ID.'</td>
            <td>'.date('d/m/Y',$date).'</td>
            <td class="user" data-user="'.$customer.'">'.$_customer_name.'</td>
            <td>'.$_caregiver_name.'</td>
            <td>'.$services.'</td>
            <td class="status">'.$status_name.'</td>
            <td class="duration" data-user="'.$customer.'" data-count="'.$duration.'">'.$duration_text.'</td>
            <td class="duration_total" data-user="'.$customer.'"></td>
            <td>'.$_meta_WCorder_services_additional.'</td>
            <td>MXN '.number_round($_meta_WCorder_line_total).'</td>';

        if( $wlabel == "volaris"){
            $html .= '
                <td>'.number_round($_meta_WCorder_line_total*0.20).'</td>
                <td>'.number_round($_meta_WCorder_line_total*0.20*($WLcommission/100)).'</td>
            ';
        }

        $html .= '</tr>';


        echo $html;
    }


 ?>
        </tbody>
    </table>
    </div>
</div>
*/ ?>

<script type="text/javascript">

    jQuery('.filters select, .filters input').change(function(e){
        setTimeout(function(){
            user_filter();
            duration_filter();
        }, 1000);

    });

    function user_filter(){
        var users=[];
        jQuery('table tbody tr:not(.noshow)').each(function(e){
            var user=jQuery(this).find('.user').data('user');
            if(jQuery.inArray(user,users)<0){
                users.push(user);
            }
        });
        //console.log(users);
        jQuery('#user_filter').find('span').html(users.length);
    }

    function duration_filter(){
        var times=[];
        jQuery('table tbody tr:not(.noshow)').each(function(e){
            var user=jQuery(this).find('.duration').data('user');
            var duration=jQuery(this).find('.duration').data('count');
            var status=jQuery(this).data('status');

            //times.push({'user':user,'duration':duration});
            //if(jQuery.inArray(user,times)<0){
            if(status!='cancelled' && status!='modified' && status!='unpaid'){
                if(times[user] == undefined){
                    times[user]=duration;
                }else{
                    times[user]=times[user]-(-duration);
                }
            }

        });

        //console.log(times);
        for(duser in times){
            jQuery('table tbody tr td.duration_total[data-user="'+duser+'"]').html(times[duser]);
        }

        /**/
    }

    user_filter();
    duration_filter();
</script>


