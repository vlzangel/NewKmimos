<?php
	include "wp-load.php";
	
	global $wpdb;

	$users = $wpdb->get_results("
		SELECT 
			u.ID AS id,
			(SELECT source FROM wp_kmimos_subscribe WHERE wp_kmimos_subscribe.email = u.user_email ) AS fuente
		FROM 
			wp_users AS u
		WHERE 
			(SELECT COUNT(*) FROM wp_usermeta WHERE u.ID = wp_usermeta.user_id AND wp_usermeta.meta_key = 'user_mobile' ) = 0 AND
			(SELECT COUNT(*) FROM wp_usermeta WHERE u.ID = wp_usermeta.user_id AND wp_usermeta.meta_key = 'landing-referencia' ) > 0
	");	

	foreach ($users as $key => $value) {
		$conocer = ($value->fuente == "petco") ? "Petco-CPF" : "CPF";
		// $wpdb->query("UPDATE wp_usermeta SET meta_value = '{$conocer}' WHERE user_id = {$value->id} AND meta_key = 'user_referred';");
		echo "UPDATE wp_usermeta SET meta_value = '{$conocer}' WHERE user_id = {$value->id} AND meta_key = 'user_referred';<br>";
	}	

/*	echo "<pre>";
		print_r($users);
	echo "</pre>";*/
?>