<?php
	$_sqls = [];
	$agenda = [];

	$sql = "SELECT agenda FROM {$pf}veterinarios WHERE user_id = '{$user_id}'"; $_sqls[] = $sql;
	$_agenda = $wpdb->get_var($sql);
	if( $_agenda != null ){
		$agenda = (array) json_decode($_agenda);
	}

	$id = explode("_", $id);
	unset($agenda[ $id[0] ][ $id[1] ]);
	if( count($agenda[ $id[0] ]) == 0 ){ unset($agenda[ $id[0] ]); }
	$agenda = json_encode($agenda);

	
	$sql = "UPDATE {$pf}veterinarios SET agenda = '{$agenda}' WHERE user_id = '{$user_id}'"; $_sqls[] = $sql;
	$r = $wpdb->query($sql);
	if( $r ){
		die( json_encode([
			'status' => true,
		] ) );
	}else{
		die( json_encode([
			'status' => false,
			'error' => 'Error borrando el horario',
			'agenda' => $agenda,
		] ) );
	}
?>