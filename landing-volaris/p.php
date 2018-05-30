<?php
	include("Requests/Requests.php");

	$email = 'prueba@maiil.com';

	$options = array( 'cm-gjkkui-gjkkui' => $email );
    Requests::register_autoloader();
    $request = Requests::post('http://kmimos.intaface.com/t/j/s/gjkkui', array(), $options );

    echo "<pre>";
    	print_r( $request->body );
    echo "</pre>";
?>