<?php
	$_sqls = [];
	$agenda = [];

	$sql = "SELECT agenda FROM {$pf}veterinarios WHERE user_id = '{$id}'"; $_sqls[] = $sql;
	$_agenda = $wpdb->get_var($sql);
	if( $_agenda != null ){
		$agenda = (array) json_decode($_agenda);
	}

	if( !isset($agenda[ $dia ]) ){ $agenda[ $dia ] = []; }
	$agenda[ set_format_slug( $dia ) ][] = [
		"ini" => $ini,
		"fin" => $fin
	];
	$agenda = json_encode($agenda);

	$sql = "UPDATE {$pf}veterinarios SET agenda = '{$agenda}' WHERE user_id = '{$id}'"; $_sqls[] = $sql;
	$r = $wpdb->query($sql);
	if( $r ){
		die( json_encode([
			'status' => true,
			'error' => 'Nuevo horario agregado exitosamente!',
			'extra' => $_POST,
			'sql' => $_sqls,
			'agenda' => $agenda,
		] ) );
	}else{
		die( json_encode([
			'status' => false,
			'error' => 'Error agregando el nuevo horario',
			'extra' => $_POST,
			'sql' => $_sqls,
			'agenda' => $agenda,
		] ) );
	}
?>