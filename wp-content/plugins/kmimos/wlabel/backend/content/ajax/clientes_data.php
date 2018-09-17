<?php
	$kmimos_load=dirname(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))))).'/wp-load.php';
	if(file_exists($kmimos_load)){
	    include_once($kmimos_load);
	}
    date_default_timezone_set('America/Mexico_City');

	global $wpdb;

    $condicion_referido = "( usermeta.meta_value = '{$_SESSION["label"]->wlabel}' OR usermeta_2.meta_value = '{$_SESSION["label"]->wlabel}' )";
    if( $_SESSION["label"]->wlabel == "petco" ){
        $condicion_referido = "
        ( 
            usermeta.meta_value = '{$_SESSION["label"]->wlabel}' OR 
            usermeta_2.meta_value = '{$_SESSION["label"]->wlabel}' OR 
            usermeta_2.meta_value = 'CC-Petco' 
        )";
    }

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

	$_data["data"] = [];
	foreach ($usuarios as $usuario) {
		$metas = get_user_meta($usuario->ID);

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
	        $_data["data"][] = [
	            $usuario->ID,  
	            ( date("Y-m-d", strtotime( $usuario->user_registered ) ) ),
	            $metas["first_name"][0]." ".$metas["last_name"][0],
	            $usuario->user_email,
	            $metas["user_mobile"][0],
	            "<div  style='background:".$color."; color: #FFF; font-weight: 600; padding: 5px;'>".$conocio."</div >",
	            ucfirst($metas["user_gender"][0]),
	            $metas["user_age"][0]
	        ];
		}
	}

	echo json_encode( $_data );
?>