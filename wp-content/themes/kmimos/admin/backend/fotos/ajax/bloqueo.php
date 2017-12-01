<?php
    include_once(dirname(dirname(dirname(__DIR__)))."/recursos/conexion.php");

    $db->query( "UPDATE fotos SET bloqueo = '{$bloquear}' WHERE reserva = ".$ID_RESERVA." AND fecha = '".date("Y-m-d")."'" );

	exit;
?>