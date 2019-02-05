<?php
	include dirname(__DIR__).'/wp-load.php';
	include 'lib/Paypal_lib.php';

	extract($_GET);

    switch ($accion) {
    	case 'value':
    		
    	break;
    	
    	default:    
    		$paypalURL = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		    $paypalID = 'vlzangel91-facilitator@gmail.com';

		    $returnURL = get_home_url() . '/paypal/?accion=success&id='.$pedido_id;
		    $cancelURL = get_home_url() . '/paypal/?accion=cancel&id='.$pedido_id;
		    $notifyURL = get_home_url() . '/paypal/?accion=ipn';

		    $logo = get_home_url()."/paypal/lib/paypal.png";

		    $paypal->add_field('business', $paypalID);
		    $paypal->add_field('return', $returnURL);
		    $paypal->add_field('cancel_return', $cancelURL);
		    $paypal->add_field('notify_url', $notifyURL);

		    $paypal->add_field('item_name', "Hospedaje - Pedro P.");
		    $paypal->add_field('custom', 367);
		    $paypal->add_field('item_number', 204354);
		    $paypal->add_field('amount', 2500);
		    $paypal->image($logo);

		    $paypal->paypal_auto_form();
    	break;
    }
?>