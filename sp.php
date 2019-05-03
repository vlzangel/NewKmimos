<?php
	include 'wp-load.php';

	global $wpdb;

	$sql = "SELECT * FROM `wp_users` WHERE `user_login` LIKE '%.%' AND user_login != user_email AND Length(user_login) > Length(user_email) AND display_name LIKE '%www%' ORDER BY `wp_users`.`ID` ASC";

	echo $sql." => ".count($r)."<br><br>";

	$r = $wpdb->get_results($sql);
	foreach ($r as $key => $p) {
		$sql_1 = "DELETE FROM wp_usermeta WHERE user_id = {$p->ID}";
		$wpdb->query($sql_1);
		$sql_2 = "DELETE FROM wp_users WHERE ID = {$p->ID}";
		$wpdb->query($sql_2);
	}
?>