<?php
	include_once dirname(__DIR__).'/wp-load.php';

	global $wpdb;
	
	$estados_txt = [];
	$estados = [];

	$states = $wpdb->get_results("SELECT * FROM states WHERE country_id = 1 ORDER BY id ASC");
	foreach ($states as $key => $value) {
		$estados_txt[ $value->id ] = utf8_decode($value->name);
	}

	$locations = $wpdb->get_results("SELECT * FROM locations ORDER BY state_id ASC");
	foreach ($locations as $key => $value) {
		$estados[ $value->state_id ]++;
	}

	echo "<pre>";
		print_r( $estados_txt );
		print_r( $estados );
	echo "</pre>";
?>