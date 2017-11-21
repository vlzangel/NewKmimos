<?php
	extract($_POST);

	function procesar_img($id, $id_orden, $periodo, $dir, $sImagen, $es_collage = false){
		$name = time()."_".$id.".jpg";
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
		    $nHeight = 430;
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

	    @imagejpeg( $aThumb, $path );

	    @imageDestroy( $aImage ); @imageDestroy( $aThumb );

	    return $name;
	}

    $dir = (dirname(dirname(dirname(dirname(__DIR__)))))."/uploads/fotos/";
    if( !file_exists($dir) ){ @mkdir($dir); }

    $dir = (dirname(dirname(dirname(dirname(__DIR__)))))."/uploads/fotos/".$id_orden."/";
    if( !file_exists($dir) ){ @mkdir($dir); }

    $dir = (dirname(dirname(dirname(dirname(__DIR__)))))."/uploads/fotos/".$id_orden."/".$periodo."/";
    if( !file_exists($dir) ){ @mkdir($dir); }

    $RUTAS = array();
    foreach ($imgs as $id => $img) {
	    $imgx = explode(',', $img);
		$img = end($imgx);
	    $sImagen = base64_decode($img);
    	
    	$RUTAS[] = $id_orden."/".$periodo."/".procesar_img($id, $id_orden, $periodo, $dir, $sImagen);
    }

    $imgx = explode(',', $collage);
	$img = end($imgx);
    $sImagen = base64_decode($img);

    $RUTAS[] = $id_orden."/".$periodo."/".procesar_img("collage", $id_orden, $periodo, $dir, $sImagen, true);

    echo json_encode( $RUTAS );

?>