<?php
	include("Requests/Requests.php");

	extract($_POST);
	
	$options = array(
        $campo => $email,
    );
    
    Requests::register_autoloader();

    $request = Requests::post($lista, array(), $options );
?>