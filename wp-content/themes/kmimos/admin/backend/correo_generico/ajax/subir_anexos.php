<?php
    include(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))))."/wp-load.php");

    global $wpdb;
    extract($_POST);

    $path_galeria = dirname(__DIR__)."/anexos/";

    if( count($pre_anexos) > 0 ){
        foreach ($pre_anexos as $key => $value) {
            unlink( $path_galeria."/".$value );
        }
    }

    if( !file_exists( $path_galeria ) ){
    	mkdir( $path_galeria );
    }

    $path_fotos = [];
    if( count($anexos) > 0 ){
	    foreach ($anexos as $key => $_img) {
	    	$imgx = explode(',', $_img[1]);
			$img = end($imgx);
		    $sImagen = base64_decode($img);
		    $name = time()."_".$key.".jpg";
    		$path = $path_galeria."/".$name;
    		$path_fotos[] = $name;
    		@file_put_contents($path, $sImagen);
	    }
    }

	echo json_encode( 
		$path_fotos
    );
?>