<?php

	include("Requests/Requests.php");
    Requests::register_autoloader();

	extract($_POST);

	$email = "vlz@gmail.com";

/*	$options = array( 
		"auth" => array(
			"user" => "caa35d0041b0ed8976a0552dc0ee424b",
			"pass" => "x"
		)
	);

	$url = "https://api.createsend.com/api/v3.2/clients.json?pretty=true";*/

	$headers = array('Accept' => 'application/json');
	$options = array(
		'auth' => array('73501a26bc200a72c9a6ef27894333a89ef4ebe435d4e29f', 'pass'),
		'EmailAddress' => $email,
		'ConsentToTrack' => 'Yes'
	);
	$request = Requests::post('https://api.createsend.com/api/v3.2/subscribers/16D4218496EF1631.json', $headers, $options);

    print_r( $request->body );












	
/*	if( $wlabel != "petco" ){
		$options = array( 'cm-vydliu-vydliu' => $email );
	    $request = Requests::post('http://kmimos.intaface.com/t/j/s/vydliu/', array(), $options );
	}else{
		print_r($_POST);
		$options = array( 'cm-gjkkui-gjkkui' => $email );
	    $request = Requests::post('http://kmimos.intaface.com/t/j/s/gjkkui', array(), $options );
	}
*/
?>