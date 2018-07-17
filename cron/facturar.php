<?php

    session_start();

    date_default_timezone_set('America/Mexico_City');
    
    include('../wp-load.php');
    include('../wp-content/themes/kmimos/lib/enlaceFiscal/CFDI.php');

echo '<pre>';

    // buscar total de reservas del mes anterior
    $mes_anterior = strtotime("now -1 month");
    $fecha_ini = date("Y-m-01 00:00:00", $mes_anterior);
    $fecha_fin = date("Y-m-31 23:59:59", $mes_anterior);

//    $reservas = $CFDI->db->get_results("SELECT * FROM wp_posts WHERE post_date_gmt >= '{$fecha_ini}'  and post_date_gmt <= '{$fecha_fin}' and post_status like 'wc_complete%' and post_type = 'shop_order' ");

//    foreach ($reservas as $key => $value) {
        
        // cargar desglose de facturas
        $data_reserva = kmimos_desglose_reserva_data(199366, true);

	    // facturar a cuidador
        $data_reserva['receptor']['rfc'] = 'XAXX010101000';
        $r = $CFDI->generar_Cfdi_Cuidador( $data_reserva ) ;
        $d = json_decode($r['ack']);
        print_r($d->descargaArchivoPDF);
        print_r($d);

	    // validar si la factura del cliente esta generada
            // Generar factura de cliente ( publico en general )
            $rfc = 'XAXX010101000';
            $nombre = "Publico en General";
//    }
