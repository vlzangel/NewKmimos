<?php
	include_once dirname(dirname(__DIR__))."/wp-load.php";
	include 'funciones.php';
	global $wpdb;

    $ini = time();

    $wlabels = $wpdb->get_results("SELECT * FROM wlabel_ventas");

    foreach ($wlabels as $key => $_wlabel) {
    	$wlabel = $_wlabel->wlabel;
        $inicio_wlabel = $_wlabel->inicio_wlabel;
    	$sql = "
            SELECT
                posts.*
            FROM
                wp_posts AS posts
                LEFT JOIN wp_postmeta AS postmeta ON (postmeta.post_id=posts.post_parent AND postmeta.meta_key='_wlabel')
                LEFT JOIN wp_usermeta AS usermeta ON (usermeta.user_id=posts.post_author AND (usermeta.meta_key='_wlabel' OR usermeta.meta_key='user_referred') AND usermeta.meta_value LIKE '%{$wlabel}%' )
            WHERE
                (
                    (posts.post_type = 'wc_booking' AND usermeta.meta_value LIKE '%{$wlabel}%') OR
                    (posts.post_type = 'wc_booking' AND postmeta.meta_value = '{$wlabel}')
                ) AND 
                posts.post_status in ( 'confirmed', 'completed', 'cancelled'  ) AND
                posts.post_date >= '{$inicio_wlabel} 00:00:00'
            ORDER BY
                posts.ID DESC
        ";
        $bookings = $wpdb->get_results($sql);
        $BUILDbookings = array();
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
            $_meta_WCorder_line_total = $wpdb->get_var("SELECT meta_value FROM wp_woocommerce_order_itemmeta WHERE order_item_id ='$IDorder_item' AND meta_key = '_line_subtotal' ");
            $_metas_customer = get_user_meta($customer);
            $_customer_name = $_metas_customer['first_name'][0] . " " . $_metas_customer['last_name'][0];
            $product = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID ='$IDproduct'");
            $BUILDbookings[$ID] = array();
            $BUILDbookings[$ID]['booking'] = $ID;
            $BUILDbookings[$ID]['order'] = $order;
            $BUILDbookings[$ID]['date'] = $date;
            $BUILDbookings[$ID]['customer'] = $customer;
            $BUILDbookings[$ID]['status'] = $status;
            $BUILDbookings[$ID]['status_name'] = $status_name;
            $BUILDbookings[$ID]['metas_booking'] = $_metas_booking;
            $BUILDbookings[$ID]['metas_order'] = $_metas_order;
            $BUILDbookings[$ID]['WCorder_line_total'] = $_meta_WCorder_line_total*1;
        }

        $day_init = strtotime(date('m/d/Y',  strtotime($inicio_wlabel) ));
        $day_last=strtotime( date("Y")."-".(date('m')+1)."-01" );
        $day_more = (24*60*60);

        $inits = [
            "day_init" => $day_init,
            "day_last" => $day_last,
            "day_more" => $day_more
        ];

        $data["reservas_canceladas"] = loop_ventas($BUILDbookings, $inits, "reservas_canceladas");
        $data["total_reservas"] = loop_ventas($BUILDbookings, $inits, "total_reservas");
        $data["total_reservas_canceladas"] = loop_ventas($BUILDbookings, $inits, "total_reservas_canceladas");


        $sql = "
            SELECT
                posts.*
            FROM
                wp_posts AS posts
                LEFT JOIN wp_postmeta AS postmeta ON (postmeta.post_id=posts.post_parent AND postmeta.meta_key='_wlabel')
                LEFT JOIN wp_usermeta AS usermeta ON (usermeta.user_id=posts.post_author AND (usermeta.meta_key='_wlabel' OR usermeta.meta_key='user_referred') AND usermeta.meta_value LIKE '%{$wlabel}%' )
            WHERE
                (
                    ( posts.post_type = 'wc_booking' AND usermeta.meta_value LIKE '%{$wlabel}%' )
                    OR
                    ( posts.post_type = 'wc_booking' AND postmeta.meta_value = '{$wlabel}' )
                ) AND 
                posts.post_status in ( 'unpaid'  ) AND
                posts.post_date >= '2018-09-01 00:00:00'
            GROUP BY
                posts.ID DESC
        ";
        $bookings = $wpdb->get_results($sql);
        $_BUILDbookings = array();
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
            $_meta_WCorder_line_total = $wpdb->get_var("SELECT meta_value FROM wp_woocommerce_order_itemmeta WHERE order_item_id ='$IDorder_item' AND meta_key = '_line_subtotal' ");
            $_metas_customer = get_user_meta($customer);
            $_customer_name = $_metas_customer['first_name'][0] . " " . $_metas_customer['last_name'][0];
            $product = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID ='$IDproduct'");
            $_BUILDbookings[$ID] = array();
            $_BUILDbookings[$ID]['booking'] = $ID;
            $_BUILDbookings[$ID]['order'] = $order;
            $_BUILDbookings[$ID]['date'] = $date;
            $_BUILDbookings[$ID]['customer'] = $customer;
            $_BUILDbookings[$ID]['status'] = $status;
            $_BUILDbookings[$ID]['status_name'] = $status_name;
            $_BUILDbookings[$ID]['metas_booking'] = $_metas_booking;
            $_BUILDbookings[$ID]['metas_order'] = $_metas_order;
            $_BUILDbookings[$ID]['WCorder_line_total'] = $_meta_WCorder_line_total*1;
        }

        $data["monto_por_pagar"] = loop_ventas($_BUILDbookings, $inits, "monto_por_pagar");
        $data["monto_comision"] = loop_ventas($_BUILDbookings, $inits, "monto_comision");

        /*
        echo "<pre>";   
            print_r( $data );
        echo "</pre>";
        */

        $json = json_encode($data);
        $wpdb->query("UPDATE wlabel_ventas SET data = '{$json}', actualizado = NOW() WHERE id = ".$_wlabel->id);

    }

    $fin = time();

    echo "Tiempo: ".($fin-$ini);
?>