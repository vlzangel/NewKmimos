<?php
	if( $motivo == 'otro' ){ $motivo = $otro_motivo; }

	$res = $wpdb->query("UPDATE wp_kmivet_reservas SET status = 4, observaciones = '{$motivo}' WHERE cita_id = '{$cita_id}' ");

	// if( $res != false ){
		$rcs = change_status($cita_id, [ "status" => 4, "description" => $motivo ]);

		if( $rcs['status'] == 'ok' ){

			    function set_format_slug($cadena){
			        // $cadena = utf8_encode( $cadena );
			        $originales = [ 'Á','É','Í','Ó','Ú' ];
			        $modificadas = [ 'a','e','i','o','u' ];
			        foreach ($originales as $key => $value) {
			            $cadena = str_replace($value, $modificadas[ $key ], $cadena);
			        }
			        return strtolower($cadena);
			    }

			    function set_format_name($cadena){
			        $originales = [ 'Á','É','Í','Ó','Ú', 'Ñ' ];
			        $modificadas = [ '&aacute;','&eacute;','&iacute;','&oacute;','&uacute;','&ntilde;' ];
			        foreach ($originales as $key => $value) {
			            $cadena = str_replace($value, $modificadas[ $key ], $cadena);
			        }
			        return mb_strtolower($cadena, 'UTF-8');
			    }

			    function set_format_precio($price){
			        $temp = explode('.', $price);
			        if( !isset($temp[1]) ){ $temp[1] = '00'; }
			        return '<span>MXN$</span> <strong style="font-size: 25px;">'.$temp[0].',</strong><span>'.$temp[1].'</span>';
			    }

			    function set_format_ranking($img, $ranking){
			        $ranking += 0;
			        if( $ranking > 5 ){ $ranking = 5; }
			        if( $ranking < 1 ){ $ranking = 1; }
			        $_ranking = '';
			        for ($i=1; $i <= $ranking; $i++) {  $_ranking .= '<img src="'.$img.'/CONSULTA/CANCELACION/hueso_full.png" style="width: 20px; margin-right: 5px;" />'; }
			        if( $ranking < 5 ){ for ($i=$ranking; $i < 5; $i++) {  $_ranking .= '<img src="'.$img.'/CONSULTA/CANCELACION/hueso_vacio.png" style="width: 20px; margin-right: 5px;" />'; } }
			        return $_ranking;
			    }

			    $cita_id = 68;

			    global $wpdb;

			    $info_email = $wpdb->get_var("SELECT info_email FROM wp_kmivet_reservas WHERE id = '{$cita_id}' ");
			    $INFORMACION = (array) json_decode( $info_email );

			    $medicos = get_medics(
			        // $INFORMACION['SPE_MEDIC'],
			        'e41ed3d30309496a845c611dfd8f2e3d',
			        $INFORMACION['LAT_MEDIC'],
			        $INFORMACION['LNG_MEDIC']
			    );

			    $RECOMENDACIONES = ''; $cont = 0;
			    foreach ($medicos["res"]->objects as $key => $medico) {
			        $img = ( ( $medico->profilePic ) != "" ) ? $medico->profilePic : 'http://www.psi-software.com/wp-content/uploads/2015/07/silhouette-250x250.png';
			        $RECOMENDACIONES .= buildEmailTemplate('KMIVET/partes/recomendacion', [
			            'IMG' => $img,
			            'NAME' => set_format_name( $medico->firstName.' '.$medico->lastName),
			            'RATE' => set_format_ranking( getTema().'/KMIVET/img', $medico->rating),
			            'PRECIO' => set_format_precio($medico->price)
			        ]);
			        $cont++;
			        if( $cont > 2 ){
			            break;
			        }
			    }

			    $INFORMACION['RECOMENDACIONES'] = $RECOMENDACIONES;
			    $INFORMACION['KV_URL_IMGS'] = getTema().'/KMIVET/img';

			    $mensaje = kv_get_email_html(
			        'KMIVET/reservas/cancelacion_cliente', 
			        $INFORMACION
			    );
		        wp_mail($INFORMACION['CORREO_CLIENTE'], 'Kmivet - Consulta Cancelada', $mensaje);

			    $mensaje = kv_get_email_html(
			        'KMIVET/reservas/cancelacion_veterinario', 
			        $INFORMACION
			    );
		        wp_mail($INFORMACION['CORREO_VETERINARIO'], 'Kmivet - Consulta Cancelada', $mensaje);

			    $mensaje = kv_get_email_html(
			        'KMIVET/reservas/cancelacion_admin', 
			        $INFORMACION
			    );
		        wp_mail('soporte.kmimos@gmail.com', 'Kmivet - Consulta Cancelada', $mensaje);


			die( json_encode([ 'status' => true ]) );
		}else{
			die( json_encode([ 'status' => $res, 'error' => 'Error cambiando el estatus en el API' ]) );
		}
		/*
	}else{
		die( json_encode([ 'status' => false, 'error' => 'Error cambiando el estatus en kmivet', 'extra' => $res, 'x' => "UPDATE wp_kmivet_reservas SET status = 4, observaciones = '{$motivo}' WHERE cita_id = '{$cita_id}' " ]) );
	} */

	die( json_encode([ 'status' => false, 'error' => 'Error inesperado' ]) );
?>