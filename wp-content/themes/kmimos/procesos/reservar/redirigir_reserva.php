<?php

	extract($_POST);

	session_start();

	$busqueda = ($_SESSION["busqueda"]);

	$busqueda["checkin"] = $checkin;
    $busqueda["checkout"] = $checkout;

    $_SESSION["busqueda"] = ( $busqueda );

	header("location: ".$redirigir);
?>