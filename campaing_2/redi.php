<?php
	error_reporting(0);
	include dirname(__DIR__).'/campaing/db.php';
	$info = (array) json_decode(base64_decode( $_GET['info']));
	extract($info);

	$link = $db->get_row("SELECT * FROM vlz_seguimiento_links WHERE campaing = '{$info['id']}' AND email = '{$info['email']}' AND link = '{$info['url']}' ");
	if( $link !== false ){
		$_metas = json_decode($link->metadata);
		$_metas->clicks += 1;
		$_metas = json_encode($_metas);
		/*
		echo "<pre>";
			print_r($_metas);
		echo "</pre>";
		*/
		$db->query("UPDATE vlz_seguimiento_links SET actualizacion = NOW(), metadata = '{$_metas}' WHERE id = '{$link->id}';");
	}else{
		$metas = json_encode([
			"clicks" => 1
		]);
		$db->query("INSERT INTO vlz_seguimiento_links VALUES (
			NULL,
			'{$info['id']}',
			'{$info['email']}',
			'{$info['url']}',
			'{$metas}',
			NOW(),
			NOW()
		);");
	}

	/*
	echo "<pre>";
		print_r( $info );
		print_r( $link );
	echo "</pre>";
	*/
	
	header("location: ".$info['url']);
?>