<?php
	include_once dirname(__DIR__).'/wp-load.php';
	
	global $wpdb;

	extract($_GET);

	$db = $wpdb;
	
	$colonias = [];

    $_colonias = $db->get_results("SELECT * FROM colonias ORDER BY name ASC");
    foreach ($_colonias as $key => $colonia) {
    	$hash = md5($colonia->estado."_".$colonia->municipio."_".$colonia->name);
    	$colonias[ $hash ] = $colonia->id;
    }

    // echo "DELETE FROM colonias WHERE id NOT IN ( ".implode(", ", $colonias)." )";
    echo "SELECT * FROM colonias WHERE id NOT IN ( ".implode(", ", $colonias)." )";

?>