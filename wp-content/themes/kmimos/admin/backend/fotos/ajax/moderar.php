<?php
    include_once(dirname(dirname(dirname(__DIR__)))."/recursos/conexion.php");

    $MODERADAS = array();
    foreach ($_POST as $key => $value) {
    	if( strpos($key, "foto") > -1 ){
    		$MODERADAS[] = $value;
    	}
    }

    $db->get_var( "UPDATE fotos SET moderacion = '".serialize($MODERADAS)."' WHERE reserva = ".$ID_RESERVA." AND fecha = '".date("Y-m-d")."'" );
?>