<?php
	$sql = "DELETE FROM  {$pf}{$mod} WHERE id = '{$id}'";
	$res = $wpdb->query( $sql );
?>