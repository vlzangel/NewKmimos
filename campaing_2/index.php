<?php
	error_reporting(0);
	include dirname(__DIR__).'/campaing/db.php';
	$info = (array) json_decode(base64_decode( $_GET['info']));
	extract($info);

	$campaing = $db->get_row("SELECT * FROM vlz_campaing WHERE id = {$id}");
	$d = (array) json_decode( utf8_encode($campaing->data) );

	if( is_array($d['vistos']) ){
		if( !in_array($email, $d['vistos']) ){ $d['vistos'][] = [ "fecha" => time(), "email" => $email ]; }
	}else{
		$d['vistos'] = [ [ "fecha" => time(), "email" => $email ] ];
	}

	$_data = json_encode($d, JSON_UNESCAPED_UNICODE);
	// $_data = str_replace('<p data-f-id=\"pbf\" style=\"text-align: center; font-size: 14px; margin-top: 30px; opacity: 0.65; font-family: sans-serif;\">Powered by <a href=\"https:\/\/www.froala.com\/wysiwyg-editor?pb=1\" title=\"\"><\/a><\/p>', '', $data);

	$sql = "UPDATE vlz_campaing SET data = '{$_data}' WHERE id = ".$id;
	$db->query( $sql );	

	echo "<pre>";
		print_r( $_data );
	echo "</pre>";
	
	exit();

	header("Content-Type: image/png");
	echo file_get_contents("img.png");
?>