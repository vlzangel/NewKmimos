<?php
	include("Requests/Requests.php");

	extract($_POST);
	
	$options = array(
        'cm-vydldr-vydldr' => $email,
    );
    
    Requests::register_autoloader();

    $request = Requests::post('http://kmimos.intaface.com/t/j/s/vydldr/', array(), $options );

/*    print_r($_POST);
    print_r($options);
    print_r($request);*/
?>