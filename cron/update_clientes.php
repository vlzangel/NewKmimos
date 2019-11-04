<?php
    session_start();
    require('../wp-load.php');
    date_default_timezone_set('America/Mexico_City');
    global $wpdb;

    require_once('../wp-content/plugins/kmimos/dashboard/core/ControllerClientes.php');

	$_registros = $wpdb->get_results("SELECT user_id FROM clientes_bp");
	$registros = [];

	if( is_array($_registros) && count($_registros) > 0 ){
		foreach ($_registros as $key => $value) {
			$registros[] = $value->user_id;
		}
	}

    $landing = '';
	$date = getdate();
	$desde = '';
	$hasta = '';


	$mostrar_total_reserva = (!empty($_POST['mostrar_total_reserva']))? true : false;
	if(	!empty($_POST['desde']) && !empty($_POST['hasta']) ){
		$desde = (!empty($_POST['desde']))? $_POST['desde']: "";
		$hasta = (!empty($_POST['hasta']))? $_POST['hasta']: "";
	}
	// Buscar Reservas
	$razas = get_razas();
	$users = getUsers($desde, $hasta);

	global $current_user;
	$user_id = $current_user->ID;


	$data = [];

	foreach( $users['rows'] as $key => $row ){ 
		$usermeta = getmetaUser( $row['ID'] );

		if( $usermeta['user_age'] == "" ){
			$usermeta['user_age'] = "25-35 A&ntilde;os";
		}else{
			$usermeta['user_age'] .= " A&ntilde;os";
		}
		if( $usermeta['phone'] == "" ){
			if( $usermeta['user_referred'] != "Petco-CPF" ){
				$usermeta['user_referred'] = "CPF";
			}
		}

		$link_login = get_home_url()."/?i=".md5($row['ID']);

		$name = "{$usermeta['first_name']}";
		$lastname = "{$usermeta['last_name']}";
		if(empty( trim($name)) ){
		 	$name = $usermeta['nickname'];
		}

			$cant_reservas = 0;
        if( $mostrar_total_reserva ){ 
  			$cant_reservas = getCountReservas( $row['ID'] );
  		}

		$reserva_15 = '';
		$_reserva_15 = '';
		$p_reserva = get_primera_reservas(  $row['ID'] );
		$dif = null;
		if( isset($p_reserva['rows'][0]['post_date_gmt']) ){
			$dif = diferenciaDias($row['user_registered'], $p_reserva['rows'][0]['post_date_gmt']);
			if( $dif['dia'] >= 0 && $dif['dia'] <= 15 ){
				$reserva_15 = '15 Dias';
			}else if( $dif['dia'] >= 16 && $dif['dia'] <= 30 ){
				$reserva_15 = '30 Dias';
			}else if( $dif['dia'] >= 16 && $dif['dia'] <= 45 ){
				$reserva_15 = '45 Dias';
			}else if( $dif['dia'] >= 16 && $dif['dia'] <= 60 ){
				$reserva_15 = '60 Dias';
			}else {
				$reserva_15 = '+60 Dias';
			}
			$_reserva_15 = $dif['dia'];
		}

		$conocer_15 = '';
		$_conocer_15 = '';
		$p_conocer = get_primera_conocer(  $row['ID'] );
		$dif_conocer = null;
		if( isset($p_conocer['rows'][0]['post_date_gmt']) ){

			$dif_conocer = diferenciaDias($row['user_registered'], $p_conocer['rows'][0]['post_date_gmt']);
			if( $dif_conocer['dia'] >= 0 && $dif_conocer['dia'] <= 15 ){
				$conocer_15 = '15 Dias';
			}else if( $dif_conocer['dia'] >= 16 && $dif_conocer['dia'] <= 30 ){
				$conocer_15 = '30 Dias';
			}else if( $dif_conocer['dia'] >= 16 && $dif_conocer['dia'] <= 45 ){
				$conocer_15 = '45 Dias';
			}else if( $dif_conocer['dia'] >= 16 && $dif_conocer['dia'] <= 60 ){
				$conocer_15 = '60 Dias';
			}else {
				$conocer_15 = '+60 Dias';
			}
			$_conocer_15 = $dif_conocer['dia'];

		}

		$_cant_reservas = '';
		if( $mostrar_total_reserva ){
			$_cant_reservas = $_cant_reservas['rows'][0]['cant'];
	    }

	    $_status = 'Activo';
	    if( $usermeta['status_user'] == 'inactivo' ){
	    	$_status = 'Inactivar';
	    }
	    $_status = $_status;

		$referido_por = (!empty($usermeta['user_referred'])) ? $usermeta['user_referred'] : 'Otros' ;

		$data[] = [
	    	$row['ID'],
			date_convert($row['user_registered'], 'Y-m-d'),
			$name,
			$lastname,
			$row['user_email'],
			$usermeta['phone'],
			$referido_por,
			$usermeta['user_gender'],
			$usermeta['user_age'],
			$conocer_15,
			$reserva_15,
			$_status
		];
	}

	foreach ($data as $key => $cliente) {
		if( in_array($cliente[0], $registros ){
			$sql = "
				UPDATE 
					clientes_bp
				SET
					nombre = '{$cliente[2]}',
					apellido = '{$cliente[3]}',
					telefono = '{$cliente[5]}',
					donde_nos_conocio = '{$cliente[6]}',
					sexo = '{$cliente[7]}',
					edad = '{$cliente[8]}',
					primera_solicitud = '{$cliente[9]}',
					primera_reserva = '{$cliente[10]}',
					status = '{$cliente[11]}'
				WHERE
					user_id = '{$cliente[0]}'
			";
		}else{
			$sql = "
				INSERT INTO
					clientes_bp
				VALUES
				(
					NULL,
					'{$cliente[0]}',
					'{$cliente[1]}',
					'{$cliente[2]}',
					'{$cliente[3]}',
					'{$cliente[4]}',
					'{$cliente[5]}',
					'{$cliente[6]}',
					'{$cliente[7]}',
					'{$cliente[8]}',
					'{$cliente[9]}',
					'{$cliente[10]}',
					'{$cliente[11]}'
				)
			";
		}

		$wpdb->query($sql);
	}

?>