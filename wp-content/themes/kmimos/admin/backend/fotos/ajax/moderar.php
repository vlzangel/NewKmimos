<?php
    include_once(dirname(dirname(dirname(__DIR__)))."/recursos/conexion.php");

    $MODERADAS = $db->get_var( "SELECT moderacion FROM fotos WHERE reserva = ".$ID_RESERVA." AND fecha = '".date("Y-m-d")."'" );
    if( $MODERADAS == false ){ $MODERADAS = array(); }else{
        $MODERADAS = unserialize($MODERADAS);
    }
    $MODERADAS[$PERIODO] = array();
    foreach ($_POST as $key => $value) {
    	if( strpos($key, "foto") > -1 ){
    		$MODERADAS[$PERIODO][$key] = $value;
    	}
    }
    $db->query( "UPDATE fotos SET moderacion = '".serialize($MODERADAS)."' WHERE reserva = ".$ID_RESERVA." AND fecha = '".date("Y-m-d")."'" );

	exit;
?>