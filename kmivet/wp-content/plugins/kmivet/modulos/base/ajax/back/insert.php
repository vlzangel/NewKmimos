<?php
	$data = json_encode( $_POST );
	$sql = "INSERT INTO {$pf}{$mod} VALUES ( NULL, '{$data}', NOW() )";
	$res = $wpdb->query( $sql );
?>