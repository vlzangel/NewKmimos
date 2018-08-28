<?php
	session_start();

    date_default_timezone_set('America/Mexico_City');

    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/vlz_config.php");

    $tema = (dirname(dirname(dirname(dirname(__DIR__)))));
    include_once($tema."/procesos/funciones/db.php");
    include_once($tema."/procesos/funciones/generales.php");

    $db = new db( new mysqli($host, $user, $pass, $db) );

    $solicitudes = $_POST['users'];
    $admin_id = $_POST['ID'];

    $pagos = $_SESSION['pago_cuidador'];

    foreach ($solicitudes as $item) {
    	if( array_key_exists($item['user_id'], $pagos) ){
    		$pago = $pagos[ $item['user_id'] ];

    		// Metadatos
	    		$cuidador = $db->get_row("SELECT user_id, nombre, apellido, banco FROM cuidadores WHERE user_id = {$pago->user_id}");
	    		$banco = unserialize($cuidador->banco);
	    		$detalle = serialize($pago->detalle);
			
	    	// validar si la solicitud se genero anteriormente
	    	$where = '';
	    	foreach( $pago->detalle as $row ){		
	    		$logica = ( $where != '' )? ' or ' : '' ;
	    		$str = 's:7:"reserva";s:'.strlen($row['reserva']).':"'.$row['reserva'].'";';
	    		$where .= " {$logica} detalle like '%{$str}%' ";
	    	}
	    	if( !empty($where) ){
				$reserva_procesada = $db->get_results("SELECT * FROM cuidadores_pagos WHERE {$where}" );
				if( $reserva_procesada ){
					$item['token'] = '';
				}
	    	}

			// Validar token    		
	    		if( md5($detalle) == $item['token'] ){
		    		$sql = "INSERT INTO cuidadores_pagos (
			    			admin_id,
			    			user_id,
			    			total,
			    			cantidad,
			    			detalle,
			    			estatus,
			    			cuenta,
			    			titular,
			    			banco
			    		) VALUES (
			    			{$admin_id},
			    			".$pago->user_id.",
			    			'".$pago->total."',
			    			'".$pago->cantidad."',
			    			'{$detalle}',
			    			'por autorizar',
			    			'".$banco['cuenta']."',
			    			'".$banco['titular']."',
			    			'".$banco['banco']."'
						);";
					$db->query($sql);
					print_r($sql);
	    		}else{
		    		print_r('no valido');
	    		}
    	}
    }