<?php
//	include("../../../../../wp-load.php");
	$raiz = dirname(dirname(dirname(dirname(dirname(__DIR__)))));

	extract($_POST);

	$param = explode(".", $_FILES[ 'file' ]['name'] );
	$ext = $param[ count($param)-1 ];

	$fichero_cargado = $raiz . '/wp-content/uploads/temp/' . md5($_FILES[ 'file' ]['name']) .".{$ext}";

	if (move_uploaded_file($_FILES[ 'file' ]['tmp_name'], $fichero_cargado)) {
		$content = file_get_contents($fichero_cargado);
		$base64 = base64_encode($content);
		$resultado['codigo'] = $base64;
		$resultado['name'] = $_FILES[ 'file' ]['name'];
	    $resultado['estatus'] = 1;
	    unlink($fichero_cargado);
	} else {
	    $resultado['estatus'] = 0;
	}

print_r( json_encode($resultado));