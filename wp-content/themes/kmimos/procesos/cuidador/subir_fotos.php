<?php
    include(dirname(dirname(dirname(dirname(dirname(__DIR__)))))."/wp-load.php");

    global $wpdb;

    extract($_POST);

    $cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE email = '{$email}' ");

    $galeria_id = $cuidador->id - 5000;

    $path_galeria = dirname(dirname(dirname(dirname(__DIR__))))."/uploads/cuidadores/galerias/".$galeria_id;

    if( !file_exists( $path_galeria ) ){
    	mkdir( $path_galeria );
    }

    $path_fotos = [];
    if( count($fotos) > 0 ){
	    foreach ($fotos as $key => $_img) {
	    	$imgx = explode(',', $_img[1]);
			$img = end($imgx);
		    $sImagen = base64_decode($img);

		    $name = time()."_".$key.".jpg";
    		$path = $path_galeria."/".$name;

    		$path_fotos[] = $name;

    		@file_put_contents($path, $sImagen);
	    }
    }

	echo json_encode( [
		$_POST,
		$path_fotos
	] );
?>