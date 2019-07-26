<?php
	error_reporting(0);
	include dirname(__DIR__).'/campaing/db.php';
	$info = (array) json_decode(base64_decode( $_GET['info']));
	extract($info);

	$campaing = $db->get_row("SELECT * FROM vlz_campaing WHERE id = {$id}");
	$d = (array) json_decode( utf8_encode($campaing->data) );

	if( is_array($d['vistos']) ){
		$vistos = [];
		foreach ($d['vistos'] as $key => $value) { $vistos[] = $value->email; }
		if( !in_array($email, $vistos) ){ 
			$d['vistos'][] = [ "fecha" => time(), "email" => $email ]; 
		}
	}else{
		$d['vistos'] = [ [ "fecha" => time(), "email" => $email ] ];
	}

	$_data = utf8_decode( json_encode($d, JSON_UNESCAPED_UNICODE) );
	$sql = "UPDATE vlz_campaing SET data = '{$_data}' WHERE id = ".$id;
	$db->query( $sql );	

	echo "<pre>";
		print_r( $sql );
	echo "</pre>";
	
	exit();

	header("Content-Type: image/png");
	echo file_get_contents("img.png");
?>