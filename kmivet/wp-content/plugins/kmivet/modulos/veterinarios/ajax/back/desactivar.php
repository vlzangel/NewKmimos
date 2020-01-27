<?php
	$sql = "UPDATE  {$pf}{$mod} SET status = '0' WHERE user_id = '{$user_id}'";
	$res = $wpdb->query( $sql );
?>