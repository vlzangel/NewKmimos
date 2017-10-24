<?php
	include("Requests/Requests.php");

	extract($_POST);
	
	$options = array(
        'cm-vydliu-vydliu' => $email
    );
    
    Requests::register_autoloader();

    $request = Requests::post('http://kmimos.intaface.com/t/j/s/vydliu/', array(), $options );

?>