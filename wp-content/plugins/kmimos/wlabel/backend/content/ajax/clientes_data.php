<?php
	$kmimos_load=dirname(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))))).'/wp-load.php';
	if(file_exists($kmimos_load)){
	    include_once($kmimos_load);
	}
    date_default_timezone_set('America/Mexico_City');

	global $wpdb;

    $condicion_referido = "( usermeta.meta_value LIKE '%{$_SESSION["label"]->wlabel}%' OR usermeta_2.meta_value LIKE '%{$_SESSION["label"]->wlabel}%' )";
/*    if( $_SESSION["label"]->wlabel == "petco" ){
        $condicion_referido = "
        ( 
            usermeta.meta_value = '{$_SESSION["label"]->wlabel}' OR 
            usermeta_2.meta_value = '{$_SESSION["label"]->wlabel}' OR 
            usermeta_2.meta_value = 'CC-Petco' 
        )";
    }*/

	$SQL = "
		SELECT 
			*
		FROM 
			{$wpdb->prefix}users AS usuarios
        LEFT JOIN wp_usermeta AS usermeta ON (usermeta.user_id=usuarios.ID AND usermeta.meta_key='_wlabel')
        LEFT JOIN wp_usermeta AS usermeta_2 ON (usermeta_2.user_id=usuarios.ID AND usermeta_2.meta_key='user_referred')
		WHERE
			{$condicion_referido} AND
			usuarios.user_registered >= '2018-09-01 00:00:00'
		GROUP BY usuarios.ID DESC";

	$usuarios = $wpdb->get_results($SQL);

	$razas = get_razas();	

	$_data["data"] = [];
	foreach ($usuarios as $usuario) {
		$metas = get_user_meta($usuario->ID);

		$estado = $wpdb->get_row('SELECT name FROM states WHERE id = '.$metas["billing_state"][0]);
		
		$mascotas = getMascotas($usuario->ID);
		$pets_nombre = array();
  		$pets_razas  = array();
  		$pets_edad	 = array();
  		if( count($mascotas) > 0 ){
			foreach( $mascotas as $pet_id => $pet) { 
				$pets_nombre[] = $pet['nombre'];
				$pets_razas[] = $razas[ $pet['raza'] ];
				$pets_edad[] = $pet['edad'];
			} 
  		}
  		$pets_nombre = implode("<br>", $pets_nombre);
  		$pets_razas  = implode("<br>", $pets_razas);
  		$pets_edad	 = implode("<br>", $pets_edad);


		$rol = strrpos($metas["wp_capabilities"][0], "subscriber");

		if( $rol !== false ){
			$conocio = "WL Petco";
			$color = "#6194e6";
			if( strtolower($metas["_wlabel"][0]) == "" ){
				if( strtolower($metas["user_referred"][0]) == "petco" ){
					$conocio = 'Kmimos Petco';
					$color = "#e455a8";
				}
				if( strtolower($metas["user_referred"][0]) == "cc-petco" ){
					$conocio =  "CC Petco";
					$color = "#67e661";
				}
			}

		    $registrado_desde = get_user_meta($usuario->ID, 'registrado_desde', true);
		    $registrado_desde = ( empty($registrado_desde) ) ? 'App' : 'Página';

	        $_data["data"][] = [
	            $usuario->ID,  
	            ( date("Y-m-d", strtotime( $usuario->user_registered ) ) ),
	            $registrado_desde,
	            $metas["first_name"][0]." ".$metas["last_name"][0],
	            $usuario->user_email,
	            $metas["user_mobile"][0],
	            "<div  style='background:".$color."; color: #FFF; font-weight: 600; padding: 5px;'>".$conocio."</div >",
	            ucfirst($metas["user_gender"][0]),
	            $metas["user_age"][0],
	            $pets_nombre,
	            $pets_razas,
	            $pets_edad
	        ];
		}
	}

	echo json_encode( $_data );

	function getMascotas($user_id){
		if(!$user_id>0){ return []; }

		global $wpdb;
		$mascotas_cliente = $wpdb->get_results("SELECT * FROM wp_posts WHERE post_author = '{$user_id}' AND post_type='pets' AND post_status = 'publish'");
	    $mascotas = array();
	    foreach ($mascotas_cliente as $key => $mascota) {
	        $metas = get_post_meta($mascota->ID);

	        $anio = $metas["birthdate_pet"][0];
	        $anio = str_replace("/", "-", $anio);
	        $anio = strtotime($anio);
	        $edad_time = time()-$anio;

	        $edad = '';
	        if( (date("Y", $edad_time)-1970) > 0 ){
		        $edad = (date("Y", $edad_time)-1970)." año(s) ";
	        }
	        $edad .= date("m", $edad_time)." mes(es)";
	 
	        $mascotas[] = array(
	            "nombre" => $mascota->post_title,
	            "raza" => $metas["breed_pet"][0],
	            "edad" => $edad
	        );
	    }
		return $mascotas;
	}

	function get_razas(){
		global $wpdb;
		$sql = "SELECT * FROM razas ";
		$result = $wpdb->get_results($sql);
		$razas = [];
		foreach ($result as $raza) {
			$razas[$raza->id] = $raza->nombre;
		}
		return $razas;
	}