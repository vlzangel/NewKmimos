<?php
	include("Requests/Requests.php");
    Requests::register_autoloader();

	extract($_POST);
	
	if( $wlabel != "petco" ){
		$options = array( 'cm-vydliu-vydliu' => $email );
	    $request = Requests::post('http://kmimos.intaface.com/t/j/s/vydliu/', array(), $options );
	}else{
		print_r($_POST);
		$options = array( 'cm-gjkkui-gjkkui' => $email );
	    $request = Requests::post('http://kmimos.intaface.com/t/j/s/gjkkui', array(), $options );
	}

?>