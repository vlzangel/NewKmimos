<?php
    error_reporting(0);

    date_default_timezone_set('America/Mexico_City');
	
	extract($_POST);
    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/wp-load.php");

    global $wpdb;

    $superAdmin = "YES";

    $id_orden = $ORDEN_ID;

    $orden_status = $wpdb->get_var("SELECT post_status FROM wp_posts WHERE ID = {$id_orden}");
    $reserva = $wpdb->get_row("SELECT * FROM wp_posts WHERE post_parent = {$id_orden} AND post_type LIKE 'wc_booking'");
    $reserva_status = $reserva->post_status;

    $id_reserva = $reserva->ID;

    $cliente_id = $reserva->post_author;

    $cliente_email = $wpdb->get_var("SELECT user_email FROM wp_users WHERE ID = {$cliente_id}");
    $metas_cliente = get_user_meta($cliente_id);

    $producto = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID = '".get_post_meta($reserva->ID, '_booking_product_id', true)."'");

    $cuidador_email = $wpdb->get_var("SELECT user_email FROM wp_users WHERE ID = {$producto->post_author}");
    $metas_cuidador = get_user_meta($producto->post_author);

    $new_status = "";

    switch ( $status ) {
        
        case 'pagado':

            $new_status = "Pagado";

            $id_reserva = $wpdb->get_var("SELECT ID FROM wp_posts WHERE post_parent = {$id_orden} AND post_type LIKE 'wc_booking'");

            $id_item = $wpdb->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = {$id_reserva} AND meta_key = '_booking_order_item_id' ");
            $remanente = $wpdb->get_var("SELECT meta_value FROM wp_woocommerce_order_itemmeta WHERE order_item_id = {$id_item} AND meta_key = '_wc_deposit_meta' ");

            $hora_actual = date("Y-m-d H:i:s");
            $wpdb->query("UPDATE wp_posts SET post_date = '{$hora_actual}' WHERE ID = {$id_reserva};");
            $wpdb->query("UPDATE wp_posts SET post_date = '{$hora_actual}' WHERE ID = {$id_orden};");

            $remanente = unserialize($remanente);

            if( $remanente["enable"] != 'no' ){
                $wpdb->query("UPDATE wp_posts SET post_status = 'wc-partially-paid' WHERE ID = $id_orden;");
                $wpdb->query("UPDATE wp_posts SET post_status = 'unpaid' WHERE ID = '$id_reserva';");
            }else{
                $wpdb->query("UPDATE wp_posts SET post_status = 'wc-completed' WHERE ID = $id_orden;");
                $wpdb->query("UPDATE wp_posts SET post_status = 'paid' WHERE ID = '$id_reserva';");
            }

        break;

        case 'pagado_email':

            $CONFIRMACION_ENVIO_DOBLE = $ENVIO_DOBLE;

            $new_status = "Pagado con env&iacute;o de correo";

            $id_reserva = $wpdb->get_var("SELECT ID FROM wp_posts WHERE post_parent = {$id_orden} AND post_type LIKE 'wc_booking'");

            $id_item = $wpdb->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = {$id_reserva} AND meta_key = '_booking_order_item_id' ");
            $remanente = $wpdb->get_var("SELECT meta_value FROM wp_woocommerce_order_itemmeta WHERE order_item_id = {$id_item} AND meta_key = '_wc_deposit_meta' ");

            $hora_actual = date("Y-m-d H:i:s");
            $wpdb->query("UPDATE wp_posts SET post_date = '{$hora_actual}' WHERE ID = {$id_reserva};");
            $wpdb->query("UPDATE wp_posts SET post_date = '{$hora_actual}' WHERE ID = {$id_orden};");

            $remanente = unserialize($remanente);

            if( $remanente["enable"] != 'no' ){
                $wpdb->query("UPDATE wp_posts SET post_status = 'wc-partially-paid' WHERE ID = $id_orden;");
                $wpdb->query("UPDATE wp_posts SET post_status = 'unpaid' WHERE ID = '$id_reserva';");
            }else{
                $wpdb->query("UPDATE wp_posts SET post_status = 'wc-completed' WHERE ID = $id_orden;");
                $wpdb->query("UPDATE wp_posts SET post_status = 'paid' WHERE ID = '$id_reserva';");
            }

            include( $raiz."/wp-content/themes/kmimos/procesos/reservar/emails/index.php");

        break;

        case 'confirmado':
            $new_status = "Confirmado";
            $acc = "CFM"; $usu = "CUI"; $NO_ENVIAR = "YES";
            $_GET['u'] = 'sistema_cambio_status';
            $_GET['CONFIRMACION_BACK'] = 'YES';
            include( $raiz."/wp-content/themes/kmimos/procesos/reservar/emails/index.php");
        break;

        case 'confirmado_email':
            $new_status = "Confirmado con env&iacute;o de correo";
            $acc = "CFM"; $usu = "CUI";
            $_GET['u'] = 'sistema_cambio_status';
            $_GET['CONFIRMACION_BACK'] = 'YES';
            include( $raiz."/wp-content/themes/kmimos/procesos/reservar/emails/index.php");
        break;

        case 'cancelado':
            $new_status = "Cancelado";
            $acc = "CCL"; $usu = "CUI"; $NO_ENVIAR = "YES";
            $_GET['CONFIRMACION_BACK'] = 'YES';
            include( $raiz."/wp-content/themes/kmimos/procesos/reservar/emails/index.php");
        break;

        case 'cancelado_email':
            $new_status = "Cancelado con env&iacute;o de correo";
            $acc = "CCL"; $usu = "CUI";
            $_GET['CONFIRMACION_BACK'] = 'YES';
            include( $raiz."/wp-content/themes/kmimos/procesos/reservar/emails/index.php");
        break;
    
    }

    function getStatusTxt($reserva, $orden){
        switch ( $reserva."-".$orden ) {

            case "wc-confirmed-confirmed":
                return "Confirmado";
            break;

            case "modified-modified":
                return "Modificado";
            break;

            case "wc-cancelled-cancelled":
                return "Cancelado";
            break;

            case "paid-wc-completed":
                return "Pendiente por Confirmar";
            break;

            case "wc-on-hold-unpaid":
                return "Pendiente por pago en tienda";
            break;

            case "wc-pending-unpaid":
                return "Pendiente por pago en tienda";
            break;
            
            default:
                return "Otro Status: ( ".$reserva."-".$orden." )";
            break;

        }
    }

    $status_actual = getStatusTxt( $orden_status, $reserva_status );

    if( $new_status != "" ){

        $current_user = wp_get_current_user();
        $admin_user_id = $current_user->ID;

        $data = [
            "admin" => $admin_user_id,
            "status_a" => $status_actual,
            "status_n" => $new_status,
            "hora" => date("H:i:s"),
            "fecha" => date("d-m-Y")
        ];

        $data = json_encode($data);

        add_post_meta($id_reserva, 'status_change', $data);

        $admin_email = $wpdb->get_var("SELECT user_email FROM wp_users WHERE ID = {$admin_user_id}");
        $metas_admin = get_user_meta($admin_user_id);

        $file = $PATH_TEMPLATE.'/template/mail/status/admin.php';
        $mensaje = file_get_contents($file);

        $mensaje = str_replace('[RESERVA]', $reserva->ID, $mensaje);

        $mensaje = str_replace('[ADMIN]', $metas_admin["first_name"][0]." ".$metas_admin["last_name"][0], $mensaje);
        $mensaje = str_replace('[ADMIN_EMAIL]', $admin_email, $mensaje);

        $mensaje = str_replace('[CLIENTE]', $metas_cliente["first_name"][0]." ".$metas_cliente["last_name"][0], $mensaje);
        $mensaje = str_replace('[CLIENTE_EMAIL]', $cliente_email, $mensaje);

        $mensaje = str_replace('[CUIDADOR]', $metas_cuidador["first_name"][0]." ".$metas_cuidador["last_name"][0], $mensaje);
        $mensaje = str_replace('[CUIDADOR_EMAIL]', $cuidador_email, $mensaje);

        $mensaje = str_replace('[ORIGINAL]', $status_actual, $mensaje);
        $mensaje = str_replace('[FINAL]', $new_status, $mensaje);
        $mensaje = str_replace('[FECHA]', date("d/m/Y H:i a") , $mensaje);
        
        $mensaje = get_email_html($mensaje);

        // wp_mail( "a.veloz@kmimos.la", "Actualización de Status", $mensaje);
        // wp_mail( "chaudaryy@gmail.com", "Actualización de Status", $mensaje);
    }

	exit;
?>