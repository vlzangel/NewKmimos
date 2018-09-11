<?php
	$kmimos_load=dirname(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))))).'/wp-load.php';
	if(file_exists($kmimos_load)){
	    include_once($kmimos_load);
	}

	global $wpdb;

	$PAGE = $_GET["page"]+0;

	$PAGE *= 50;

	$SQL = "
		SELECT 
			SQL_CALC_FOUND_ROWS *
		FROM 
			{$wpdb->prefix}users AS usuarios
		INNER JOIN {$wpdb->prefix}usermeta AS m ON ( m.user_id = usuarios.ID )
		WHERE
			(
				m.meta_key = 'user_referred' OR
				m.meta_key = '_wlabel' 
			) AND
			m.meta_value = '{$_SESSION["label"]->wlabel}' AND
			usuarios.user_registered >= '2018-09-01 00:00:00'
		GROUP BY usuarios.ID DESC
		LIMIT {$PAGE}, 50";

	$usuarios = $wpdb->get_results($SQL);

	$foundRows = $wpdb->get_var("SELECT FOUND_ROWS() as foundRows");

	$_data["data"] = [];
	foreach ($usuarios as $usuario) {
		$metas = get_user_meta($usuario->ID);

		$conocio = "WL Petco";
		$color = "#6194e6";
		if( strtolower($metas["user_referred"][0]) == "cc-petco" ){
			$conocio =  "CC Petco";
			$color = "#67e661";
		}
		if( strtolower($metas["user_referred"][0]) == "petco" ){
			$conocio = 'Kmimos Petco';
			$color = "#e455a8";
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

	echo json_encode( $_data );
?>