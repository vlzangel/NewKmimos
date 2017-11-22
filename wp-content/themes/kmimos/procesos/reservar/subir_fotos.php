<?php
	$raiz = dirname(dirname(dirname(dirname(dirname(__DIR__)))));

	include_once($raiz."/vlz_config.php");
	include_once("../funciones/db.php");

	$db = new db( new mysqli($host, $user, $pass, $db) );

	extract($_POST);

	function procesar_img($id, $periodo, $dir, $sImagen, $es_collage = false){
		$name = $id.".png";
	    $path = $dir.$name;

	    @file_put_contents($path, $sImagen);

	    $sExt = @mime_content_type( $path );

	    switch( $sExt ) {
	        case 'image/jpeg':
	            $aImage = @imageCreateFromJpeg( $path );
	        break;
	        case 'image/gif':
	            $aImage = @imageCreateFromGif( $path );
	        break;
	        case 'image/png':
	            $aImage = @imageCreateFromPng( $path );
	        break;
	        case 'image/wbmp':
	            $aImage = @imageCreateFromWbmp( $path );
	        break;
	    }

	    if( $es_collage ){
		    $nWidth  = 600;
		    $nHeight = 495;
	    }else{
		    $nWidth  = 270;
		    $nHeight = 190;

	    }

	    $aSize = @getImageSize( $path );

	    if( $aSize[0] > $aSize[1] ){
	        $nHeight = round( ( $aSize[1] * $nWidth ) / $aSize[0] );
	    }else{
	        $nWidth = round( ( $aSize[0] * $nHeight ) / $aSize[1] );
	    }

	    $aThumb = @imageCreateTrueColor( $nWidth, $nHeight );

	    @imageCopyResampled( $aThumb, $aImage, 0, 0, 0, 0, $nWidth, $nHeight,
	    $aSize[0], $aSize[1] );

	    @imagepng( $aThumb, $path );

	    @imageDestroy( $aImage ); @imageDestroy( $aThumb );

	    return $name;
	}

    $dir = (dirname(dirname(dirname(dirname(__DIR__)))))."/uploads/fotos/";
    if( !file_exists($dir) ){ @mkdir($dir); }

    $dir = (dirname(dirname(dirname(dirname(__DIR__)))))."/uploads/fotos/".$id_reserva."/";
    if( !file_exists($dir) ){ @mkdir($dir); }

    $dir = (dirname(dirname(dirname(dirname(__DIR__)))))."/uploads/fotos/".$id_reserva."/".$periodo."/";
    if( !file_exists($dir) ){ @mkdir($dir); }

    $RUTAS = array();
    foreach ($imgs as $id => $img) {
	    $imgx = explode(',', $img);
		$img = end($imgx);
	    $sImagen = base64_decode($img);
    	
    	$RUTAS[] = $id_reserva."/".$periodo."/".procesar_img($id, $periodo, $dir, $sImagen);
    }

    $imgx = explode(',', $collage);
	$img = end($imgx);
    $sImagen = base64_decode($img);

    $RUTAS[] = $id_reserva."/".$periodo."/".procesar_img("collage", $periodo, $dir, $sImagen, true);

    $info = $db->get_row("SELECT * FROM fotos WHERE reserva = $id_reserva");

    $hoy = date("Y-m-d");

    if( $periodo == $hoy."_1" ){
    	$db->query("UPDATE fotos SET subio_12 = 1 WHERE reserva = $id_reserva AND fecha = '{$hoy}';");
    }else{
    	$db->query("UPDATE fotos SET subio_06 = 1 WHERE reserva = $id_reserva AND fecha = '{$hoy}';");
    }
    
	exit;

?>