<?php
	$cita_id = $id;
	$rcs = change_status($cita_id, [ "status" => 3, "description" => "Cita finalizada" ]);
	if( $rcs['status'] == 'ok' ){ 
		// $r = $wpdb->query("UPDATE {$pf}reservas SET status = 3 WHERE cita_id = '{$cita_id}' ");

		$reserva = $wpdb->get_row("SELECT * FROM {$pf}reservas WHERE cita_id = '{$cita_id}' ");

		$veterinario = $wpdb->get_row("SELECT * FROM {$pf}veterinarios WHERE veterinario_id = '{$reserva->veterinario_id}' ");

		$INFORMACION = (array) json_decode( $reserva->info_email );

		$appointment = get_appointment($cita_id);

		$INFORMACION["AVATAR_URL"] = kmimos_get_foto($veterinario->user_id);
    	$INFORMACION["DIAGNOSTICO"] = $appointment['result']->diagnostic->diagnostic->title;
	    $INFORMACION["DIAGNOSTICO_NOTA"] = $appointment['result']->diagnostic->notes;
	    $INFORMACION["TRATAMIENTO"] = $appointment['result']->treatment;

	    
        include dirname(dirname(__DIR__)).'/lib/dompdf/lib/html5lib/Parser.php';
	    include dirname(dirname(__DIR__)).'/lib/dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
	    include dirname(dirname(__DIR__)).'/lib/dompdf/lib/php-svg-lib/src/autoload.php';
	    include dirname(dirname(__DIR__)).'/lib/dompdf/src/Autoloader.php';

	    Dompdf\Autoloader::register();
	    use Dompdf\Dompdf;
	    $dompdf = new Dompdf\Dompdf();
	    ob_start();
	        require_once ( __DIR__.'/template/recipe.php');
	    $html = ob_get_clean();

	    $_INFORMACION = [
	        "VETERINARIO" => $INFORMACION["NAME_VETERINARIO"],
	        "CEDULA" => $INFORMACION["CEDULA_VETERINARIO"],
	        "PACIENTE" => $INFORMACION["NAME_CLIENTE"],
	        "EDAD" => $INFORMACION["EDAD_CLIENTE"],
	        "TRATAMIENTO" => $INFORMACION["TRATAMIENTO"],
	    ];

	    foreach ($_INFORMACION as $key => $value) {
	        $html = str_replace('['.$key.']', $value, $html);
	    }

	    $path = dirname(dirname(dirname(dirname(__DIR__))))."/uploads/recipes/".$cita_id;
	    if( !file_exists($path) ){
	    	mkdir( $path );
	    }

	    $dompdf->loadHtml( $html );
	    $dompdf->setPaper('A4', 'portrait');
	    $dompdf->render();
	    $output = $dompdf->output();
	    file_put_contents( $path.'/recipe.pdf', $output);

	    $INFORMACION["PDF"] = get_home_url().'/wp-content/uploads/recipes/'.$cita_id.'/recipe.pdf';

	    /*
		$mensaje = kv_get_email_html(
	        'KMIVET/reservas/confirmacion_cliente', 
	        $INFORMACION
	    );
	    wp_mail($INFORMACION['CORREO_CLIENTE'], 'Kmivet - Consulta Completada', $mensaje);

		$mensaje = kv_get_email_html(
	        'KMIVET/reservas/confirmacion_cliente', 
	        $INFORMACION
	    );
	    wp_mail($INFORMACION['CORREO_VETERINARIO'], 'Kmivet - Consulta Completada', $mensaje);
	    
		$mensaje = kv_get_email_html(
	        'KMIVET/reservas/confirmacion_cliente', 
	        $INFORMACION
	    );
	    $admins = get_admins();
	    wp_mail($admins['admin'], 'Kmivet - Consulta Completada', $mensaje, $admins['otros']);
		*/

		if( $r ){
			die( json_encode([
				'status' => true
			] ) );
		}else{
			die( json_encode([
				'status' => false,
				'error' => 'Error cambiando el estatus en kmivet' 
			] ) );
		}
	}else{
        die( json_encode([ 
        	'status' => $res, 
        	'error' => 'Error cambiando el estatus en el API' 
        ]));
    }
?>