<?php

	function redi($path, $name){
		$_path = $path."/mini/";
		if( !file_exists($_path) ){
	        @mkdir($_path);
	    }
		$path .= $name;
	    $aSize = getImageSize( $path );
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
	    $temp_name = explode(".", $name);
		$new_path = $_path.$temp_name[0].".jpg";
	    $nWidth  = 400;
	    $nHeight = 250;
	    if( $aSize[0] > $aSize[1] ){
	        $nHeight = round( ( $aSize[1] * $nWidth ) / $aSize[0] );
	    }else{
	        $nWidth = round( ( $aSize[0] * $nHeight ) / $aSize[1] );
	    }
	    $aThumb = @imageCreateTrueColor( $nWidth, $nHeight );
	    @imageCopyResampled( 
	    	$aThumb, $aImage, 
	    	0, 0, 0, 0, 
	    	$nWidth, $nHeight,
	    	$aSize[0], $aSize[1] 
	    );
	    @imagejpeg( $aThumb, $new_path );
	    @imageDestroy( $aImage ); @imageDestroy( $aThumb );
	}
	
	$imagenes = [];
	$eliminar = [];

	$path_galeria = __DIR__."/wp-content/uploads/cuidadores/galerias/";
	function listar_directorios_ruta($ruta, $nivel = 0, $sub_path = '/'){ 
	   	global $imagenes;
	   	global $eliminar;

	   	if (is_dir($ruta)) { 
	      	if ($dh = opendir($ruta)) {

	      		if( $nivel <= 1 ){
		      		$imagenes[ md5("$ruta") ] = [
	               		"path" => $ruta,
	               		"sub_path" => $sub_path,
	               		"imgs" => []
	               	];
               	}else{
		      		$eliminar[ md5("$ruta") ] = [
	               		"path" => $ruta,
	               		"sub_path" => $sub_path,
	               		"imgs" => []
	               	];
               	}
	         	while (($file = readdir($dh)) !== false) { 
		            if (is_dir($ruta . $file) && $file!="." && $file!=".."){ 
		               	// echo "<br>Directorio: $ruta$file"; 
		               	// if( $nivel <= 1 ){
		               		listar_directorios_ruta($ruta . $file . "/", $nivel+1,  $file); 
		               	// }
		            }else{
		            	if ( $file!="." && $file!=".."){ 
		            		if( $nivel <= 1 ){
		            			$imagenes[ md5("$ruta") ]["imgs"][] = "$file";
		            		}else{
		            			$eliminar[ md5("$ruta") ]["imgs"][] = "$file";
		            		}
		            		// echo "<div style='padding-left: 20px;'>$ruta$file</div>";
		            	}
		            }
	         	} 
	      		closedir($dh); 
	      	} 
	   	}else {
	      	echo "<br>No es ruta valida"; 
	   	}
	}

	listar_directorios_ruta($path_galeria);

	// foreach ($imagenes as $key => $value) {
	// 	if( count($value["imgs"]) > 0 ){
	// 		foreach ($value["imgs"] as $_key => $_value) {
	// 			redi( $value["path"], $_value );
	// 		}
	// 	}
	// }

/*	foreach ($eliminar as $key => $value) {
		foreach ($value["imgs"] as $_key => $_value) {
			$temp = str_replace("C:\\", "", $value["path"].$_value);
			echo "del /F /Q '".str_replace("/", "\\", $temp)."'<br>";
		}
		unlink( $value["path"] );
		// $temp = str_replace("C:\\", "", $value["path"]);
		// echo "rd /S /Q '".str_replace("/", "\\", $temp)."'<br>";
	}*/

	echo "<pre>";
		// print_r($eliminar);
		print_r($imagenes);
	echo "</pre>";
?>