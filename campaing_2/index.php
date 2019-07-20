<?php
	error_reporting(0);

	include dirname(__DIR__).'/campaing/db.php';

	$info = (array) json_decode(base64_decode( $_GET['info']));
	extract($info);
	
	/*
	echo "<pre>";
		// print_r($_GET);
		print_r( $id );
		echo "<br>";
		print_r( $info );
	echo "</pre>";
	echo "<pre>";
		print_r( $info );
	echo "</pre>";
	*/

	$campaing = $db->get_row("SELECT * FROM vlz_campaing WHERE id = {$id}");

	$d = (array) json_decode($campaing->data);

	if( is_array($d['vistos']) ){
		if( !in_array($email, $d['vistos']) ){
			$d['vistos'][] = $email;
		}
	}else{
		$d['vistos'] = [
			$email
		];
	}
	
	$data = json_encode($d, JSON_UNESCAPED_UNICODE);
	$data = str_replace('<p data-f-id=\"pbf\" style=\"text-align: center; font-size: 14px; margin-top: 30px; opacity: 0.65; font-family: sans-serif;\">Powered by <a href=\"https:\/\/www.froala.com\/wysiwyg-editor?pb=1\" title=\"\"><\/a><\/p>', '', $data);

	$sql = "UPDATE vlz_campaing SET data = '{$data}' WHERE id = ".$id;
	$db->query( $sql );

	/*
	echo base64_encode( json_encode( [
		"id" => 12,
		"type" => "img",
		"format" => "png",
		"email" => "vlzangel91@gmail.com"
	] ) );

	echo base64_encode( json_encode( [
		"id" => 12,
		"type" => "img",
		"format" => "png",
		"email" => "a.veloz@kmimos.la"
	] ) );
	*/
	/*
	echo "<pre>";
		print_r( $data );
	echo "</pre>";
	*/
	



	
	header("Content-Type: image/png");
	echo file_get_contents("img.png");
	
?>