<?php
	$sql = "DELETE FROM  {$vlzpf}{$mod} WHERE id = '{$id}'";
	$res = $wpdb->query( $sql );
?>