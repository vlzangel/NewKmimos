<?php
	error_reporting(0);

	session_start();

	include 'init.php';
	include 'funciones.php';

	$modulo = "cuidador";

	$desde = $_SESSION[ "desde_".$modulo ];
	$hasta = $_SESSION[ "hasta_".$modulo ];

	$condicion = "";

	if( $desde != "" && $hasta != "" ){
		$condicion = " WHERE registro >= '{$desde} 00:00:00' AND registro <= '{$hasta} 00:00:00'";
	}
	
	$sql = "SELECT * FROM cuidadores_bp {$condicion}";

	$cuidadores = $db->get_results( $sql );

	$data["data"] = [];

	if( $cuidadores != null ){

		foreach( $cuidadores as $key => $r ){ 

			$link_login = md5($r->user_id);

		    $_status = '';
		    if( $r->status == "activo" ){
			    $_status = '<span id="user_'.$r->user_id.'" class="enlace" onclick="change_status( jQuery(this) )" data-id="'.$r->user_id.'" data-status="inactivo">Desactivar</span>';
		    }else{
		    	$_status = '<span id="user_'.$r->user_id.'" class="enlace" onclick="change_status( jQuery(this) )" data-id="'.$r->user_id.'" data-status="activo">Activar</span>';
		    } 

		    $data["data"][] = [
		    	$r->user_id,
		    	$r->flash,
		    	date('Y-m-d', strtotime($r->registro)),
		    	$r->nacimiento,
				utf8_encode( $r->full_name ),
				utf8_encode( $r->nombre ),
				utf8_encode( $r->apellido ),
				utf8_encode( '<a target="_blank" href="'.$_GET['home'].'/wp-admin/post.php?post='.$r->post_id.'&action=edit"> '.$r->cuidador.' </a>'),
				utf8_encode( '<a target="_blank" href="'.$_GET['home']."/?i=".$link_login.'"> '.$r->email.' </a>'),
				utf8_encode( $r->estado ),
				utf8_encode( $r->municipio),
				utf8_encode( $r->direccion),
				$r->telefono,
				$r->nos_conocio,
				$_status
		    ];

		}
	}
	echo json_encode( $data );
?>