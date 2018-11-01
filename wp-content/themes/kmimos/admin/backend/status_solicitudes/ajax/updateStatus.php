<?php
	
	extract($_POST);
    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/wp-load.php");

    global $wpdb;

    $superAdmin = "YES";

    $new_status = "";
    $id_orden = $id;

    switch ( $status ) {
        
        case 'pendiente':

            $new_status = "Pendiente";
            $db->query("UPDATE wp_posts SET post_status = 'pending' WHERE ID = {$id};");
            $db->query("UPDATE wp_postmeta SET meta_value = '1' WHERE post_id = {$id} AND meta_key = 'request_status';");
        break;

        case 'pendiente_email':


            $new_status = "Pendiente con env&iacute;o de correo";
            $db->query("UPDATE wp_posts SET post_status = 'pending' WHERE ID = {$id};");
            $db->query("UPDATE wp_postmeta SET meta_value = '1' WHERE post_id = {$id} AND meta_key = 'request_status';");
            include( $raiz."/wp-content/themes/kmimos/procesos/conocer/index.php");

        break;

        case 'confirmada':

            $new_status = "Confirmado";
            
            $acc = "CFM"; $usu = "CUI"; $NO_ENVIAR = "NO";

            include( $raiz."/wp-content/themes/kmimos/procesos/conocer/index.php");

        break;

        case 'confirmada_email':

            $new_status = "Confirmado con env&iacute;o de correo";
            
            $acc = "CFM"; $usu = "CUI";

            include( $raiz."/wp-content/themes/kmimos/procesos/conocer/index.php");

        break;

        case 'cancelada':

            $new_status = "Cancelado";
            
            $acc = "CCL"; $usu = "CUI"; $NO_ENVIAR = "NO";

            include( $raiz."/wp-content/themes/kmimos/procesos/conocer/index.php");

        break;

        case 'cancelada_email':

            $new_status = "Cancelado con env&iacute;o de correo";
            
            $acc = "CCL"; $usu = "CUI";

            include( $raiz."/wp-content/themes/kmimos/procesos/conocer/index.php");

        break;
    
    }

    function getStatusTxt($status_actual){
        switch ( $status_actual ) {

            case "pending":
                return "Pendiente";
            break;

            case "publish":
                return "Confirmado";
            break;

            case "drash":
                return "Cancelado";
            break;

        }
    }

    $status_actual = getStatusTxt( $status);

/*    if( $new_status != "" ){

        $current_user = wp_get_current_user();
        $admin_user_id = $current_user->ID;

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

        wp_mail( "a.veloz@kmimos.la", "Actualización de Status", $mensaje);
        wp_mail( "chaudaryy@gmail.com", "Actualización de Status", $mensaje);
    }*/

	exit;
?>