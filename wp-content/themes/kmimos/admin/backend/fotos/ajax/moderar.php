<?php
    include_once(dirname(dirname(dirname(__DIR__)))."/recursos/conexion.php");

    $MODERADAS = array();
    foreach ($_POST as $key => $value) {
    	if( strpos($key, "foto") > -1 ){
    		$MODERADAS[$PERIODO][] = $value;
    	}
    }

    $db->query( "UPDATE fotos SET moderacion = '".serialize($MODERADAS)."' WHERE reserva = ".$ID_RESERVA." AND fecha = '".date("Y-m-d")."'" );

    $imgx = explode(',', $COLLAGE);
	$img = end($imgx);
    $sImagen = base64_decode($img);

    $DIR = path_base()."/wp-content/uploads/fotos/".$ID_RESERVA."/".date("Y-m-d")."_".$PERIODO."/";

    procesar_img("collage", $PERIODO, $DIR, $sImagen, true);

	exit;
?>