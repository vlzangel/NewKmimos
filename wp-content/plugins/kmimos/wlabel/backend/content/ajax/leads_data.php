<?php
	$kmimos_load=dirname(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))))).'/wp-load.php';
	if(file_exists($kmimos_load)){
	    include_once($kmimos_load);
	}

	global $wpdb;
	$PAGE = $_GET["page"]+0;
	$PAGE *= 50;
	$SQL = "SELECT * FROM `wp_kmimos_subscribe` WHERE source = '{$_SESSION["label"]->wlabel}' AND time >= '2018-09-01 00:00:00' ";
	$usuarios = $wpdb->get_results($SQL);
	$registros = "";

    $_data["data"] = []; $i = 1;
	foreach ($usuarios as $usuario) {
		$conocio = "WL";
		$color = "#6194e6";
		if( strtolower($usuario->source) == "cc-petco" ){
			$conocio =  "CC Petco";
			$color = "#67e661";
		}
		if( strtolower($usuario->source) == "petco" ){
			$conocio = 'WL Petco';
			$color = "#e455a8";
		}
        $_data["data"][] = [
            $i,  
            date("Y-m-d", strtotime( $usuario->time ) ),
            $usuario->email,
            "<div style='background: {$color};padding: 5px;color: #FFF;font-weight: 600;'>".$conocio."</div>"     
        ];
        $i++;
	}

	echo json_encode( $_data );
?>