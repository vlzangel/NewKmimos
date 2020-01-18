<?php
	updateGenerico($campos_db);
	$sql = "UPDATE  {$vlzpf}{$mod} SET data = '{$data}' {$datos} WHERE id = '{$id}' ";
	$res = $wpdb->query( $sql );
?>