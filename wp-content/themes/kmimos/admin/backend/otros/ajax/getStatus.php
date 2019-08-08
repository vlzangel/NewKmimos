<?php
    date_default_timezone_set('America/Mexico_City');
    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/wp-load.php");
    global $wpdb;

    extract($_POST);

    $saldo += 0;
    echo "SELECT * FROM wp_posts WHERE ID = '{$reserva}' AND post_type='wc_booking' ";
    
    $_reserva = $wpdb->get_row("SELECT * FROM wp_posts WHERE ID = '{$reserva}' AND post_type='wc_booking' ");
    if( $_reserva == null ){
        echo "El ID no pertenece a una reserva - {$reserva} -";
    }else{
        $orden = $wpdb->get_row("SELECT * FROM wp_posts WHERE ID = '{$_reserva->post_parent}'");
        $_saldo += 0;
        echo "
            <input type='hidden' id='orden' name='orden' value='{$reserva->post_parent}' />
            <div><label class='info_label'>Reserva: </label> <span>{$reserva->post_status}</span></div>
            <div><label class='info_label'>Orden: </label> <span>{$orden->post_status}</span></div>
            <div>
                <label class='info_label'>Acci&oacute;n a realizar: </label>
                <span>
                    <select id='status' name='status' >
                        <option value=''>Seleccione una opci&oacute;n</option>
                        <option value='pagado'>Pagado</option>
                        <option value='pagado_email'>Pagado y enviar email</option>
                        <option value='confirmado'>Confirmado</option>
                        <option value='confirmado_email'>Confirmado y enviar email</option>
                        <option value='cancelado'>Cancelado</option>
                        <option value='cancelado_email'>Cancelado y enviar email</option>
                    </select>
                </span>
            </div>
        ";
    }
    
	exit;
?>