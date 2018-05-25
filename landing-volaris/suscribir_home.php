<?php
	include("Requests/Requests.php");

	extract($_POST);
	
	if( $wlabel != "petco" ){
		$options = array( 'cm-vydliu-vydliu' => $email );
	    Requests::register_autoloader();
	    $request = Requests::post('http://kmimos.intaface.com/t/j/s/vydliu/', array(), $options );
	}else{
		$options = array( 'cm-gjkkui-gjkkui' => $email );
	    Requests::register_autoloader();
	    // $request = Requests::post('https://www.createsend.com/t/subscribeerror?description=', array(), $options );
	    $request = Requests::post('http://kmimos.intaface.com/t/j/s/gjkkui', array(), $options );
	}

?>