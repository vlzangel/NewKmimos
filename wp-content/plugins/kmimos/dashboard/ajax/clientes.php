<?php
	error_reporting(0);

	session_start();

	include 'init.php';
	include 'funciones.php';

	$modulo = "cliente";

	$desde = $_SESSION[ "desde_".$modulo ];
	$hasta = $_SESSION[ "hasta_".$modulo ];
	$total_reservas_cliente = $_SESSION[ "total_reservas_".$modulo ];

	$condicion = "";

	if( $desde != "" && $hasta != "" ){
		$condicion = " WHERE registro >= '{$desde} 00:00:00' AND registro <= '{$hasta} 00:00:00'";
	}
	
	$sql = "SELECT * FROM clientes_bp {$condicion}";

	$users = $db->get_results( $sql );

	$data["data"] = [];

	if( $users != null ){

		foreach( $users as $key => $r ){ 

			$link_login = md5($r->user_id);

		    $_status = '';
		    if( $r->status == "activo" ){
			    $_status = '<span id="user_'.$r->user_id.'" class="enlace" onclick="change_status( jQuery(this) )" data-id="'.$r->user_id.'" data-status="inactivo">Desactivar</span>';
		    }else{
		    	$_status = '<span id="user_'.$r->user_id.'" class="enlace" onclick="change_status( jQuery(this) )" data-id="'.$r->user_id.'" data-status="activo">Activar</span>';
		    } 

		    $_cant_reservas = "N/A";

		    if( $total_reservas_cliente == "YES" ){
		    	$_cant_reservas = getCountReservas($r->user_id, $desde, $hasta);
		    }

		    $r->primera_solicitud = ( $r->primera_solicitud == "" ) ? "-" : $r->primera_solicitud;
		    $r->primera_reserva = ( $r->primera_reserva == "" ) ? "-" : $r->primera_reserva;

		    $registrado_desde = $db->get_var("SELECT meta_value FROM wp_usermeta WHERE user_id = '{$r->user_id}' AND meta_key = 'registrado_desde' ");
		    $registrado_desde = ( $registrado_desde === false ) ? 'App' : 'Página';

		    $user_nicename = $db->get_var("SELECT user_nicename FROM wp_users WHERE ID = '{$r->user_id}' ");

		    $data["data"][] = [
		    	$r->user_id,
		    	date('Y-m-d', strtotime($r->registro)),
				$registrado_desde,
				utf8_encode( $r->nombre ),
				utf8_encode( $r->apellido ),
				utf8_encode( '<a href="'.$_GET['home']."/?i=".$link_login.'"> '.$r->email.' </a>'),
				$user_nicename,
				utf8_encode( $r->telefono ),
				utf8_encode( $r->donde_nos_conocio),
				$r->sexo,
				$r->edad,
				$_cant_reservas,
				$r->primera_solicitud,
				$r->primera_reserva,
				$_status
		    ];

		}
	}
	echo json_encode( $data );
?>