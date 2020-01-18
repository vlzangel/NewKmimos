<?php
	$data = json_encode( $_POST );
	$sql = "INSERT INTO {$vlzpf}{$mod} VALUES ( NULL, '{$data}', NOW() )";
	$res = $wpdb->query( $sql );
?>