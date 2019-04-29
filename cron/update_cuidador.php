<?php
    session_start();
    require('../wp-load.php');
    date_default_timezone_set('America/Mexico_City');
    global $wpdb;


    $_estados = $wpdb->get_results("SELECT * FROM states ORDER BY id ASC");
    $estados = [];
    foreach ($_estados as $key => $estado) {
    	$estados[ $estado->id ] = utf8_decode($estado->name);
    }

    $_mun = $wpdb->get_results("SELECT * FROM locations ORDER BY id ASC");
    $mun = [];
    foreach ($_mun as $key => $muni) {
    	$mun[ $muni->id ] = utf8_decode($muni->name);
    }

    $cuidadores = $wpdb->get_results("
    	SELECT 
    		c.*,
    		u.user_registered 
    	FROM 
    		cuidadores AS c
    	INNER JOIN wp_users AS u ON ( u.ID = c.user_id )
    ");

	$data = [];

	foreach( $cuidadores as $key => $row ){ 
		$usermeta = get_user_meta($row->user_id);

		$atributos = unserialize($row->atributos);

  	    if( !isset($atributos["nacimiento"]) ){
	        $atributos["nacimiento"] = "No disponible";
	    }

	  	$flash = "";
		if( $atributos['flash'] == 1 ){
			$flash = '
				<i 
					class="fa fa-bolt" 
					aria-hidden="true"
					style="
						padding: 2px 4px;
					    border-radius: 50%;
					    background: #00c500;
					    color: #FFF;
					    margin-right: 2px;
					"
				></i> Flash
			';
		}


		$link_login = get_home_url()."/?i=".md5($row->user_id);

		$name = "{$row->nombre} {$row->apellido}";

	    $_status = 'Activo';
	    if( $row->status == 1 ){
	    	$_status = 'Inactivo';
	    }
	    $_status = $_status;

	    $telefono = array();
	    if(  isset($usermeta["user_phone"][0]) ){
	    	$telefono[] = $usermeta["user_phone"][0];
	    }
	    if(  isset($usermeta["user_mobile"][0]) ){
	    	$telefono[] = $usermeta["user_mobile"][0];
	    }
	    
	    if( count($telefono) > 0 ){
	    	$telefono = implode(" / ", $telefono);
	    }else{
	    	$telefono = "---";
	    }

		$referido_por = (!empty($usermeta['user_referred'])) ? $usermeta['user_referred'][0] : 'Otros' ;

		$esta = explode("=", $row->estados);
		$esta = $estados[ $esta[1] ];

		$muni = explode("=", $row->municipios);
		$muni = $mun[ $muni[1] ];

		$data[] = [
	    	$row->user_id,
	    	$row->id_post,
	    	$flash,
			date('Y-m-d', strtotime($row->user_registered) )." 00:00:00",
			$atributos["nacimiento"],
			$name,
			$row->nombre,
			$row->apellido,
			$row->titulo,
			$row->email,
			$esta,
			$muni,
			$usermeta["user_address"][0],
			$telefono,
			$referido_por,
			$_status
		];

	}

/*
		echo "<pre>";
			print_r( $data );
		echo "</pre>";
*/
	
	foreach ($data as $key => $cuidador) {
		$sql = "
			INSERT INTO
				cuidadores_bp
			VALUES
			(
				NULL,
				'{$cuidador[0]}',
				'{$cuidador[1]}',
				'{$cuidador[2]}',
				'{$cuidador[3]}',
				'{$cuidador[4]}',
				'{$cuidador[5]}',
				'{$cuidador[6]}',
				'{$cuidador[7]}',
				'{$cuidador[8]}',
				'{$cuidador[9]}',
				'{$cuidador[10]}',
				'{$cuidador[11]}',
				'{$cuidador[12]}',
				'{$cuidador[13]}',
				'{$cuidador[14]}',
				'{$cuidador[15]}'
			)
		";

		$wpdb->query($sql);
	}

	echo "Listo";
?>