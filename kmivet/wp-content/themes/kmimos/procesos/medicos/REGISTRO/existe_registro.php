<?php
	$raiz = dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))));
	ob_start();
		include_once($raiz."/wp-load.php");
		$load = ob_get_contents();
	ob_end_clean();
	
	extract($_POST);
	global $wpdb;

	$filters = [];
	foreach ($_POST as $key => $value) {
		$filters[] = " {$key} = '{$value}' ";
	}

	$filters = implode(", ", $filters);

	$_registro = $wpdb->get_row( "SELECT * FROM wp_kmivet_medicos WHERE ".$filters );

	if( !isset($_registro->id) ){
		echo json_encode([
			'status' => true
		]);
	}else{
		echo json_encode([
			'status' => false
		]);
	}

	die();
?>