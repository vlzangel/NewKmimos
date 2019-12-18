<?php
	session_start();

	extract($_POST);

	function getCoordinates($address){
	    $address = urlencode($address);
	    $url = "http://maps.google.com/maps/api/geocode/json?sensor=false&address=" . $address ."&key=AIzaSyCLvX3VwG4eb4KjiCqKgYx1NfBTAuhVHmY";
	    $response = file_get_contents($url);
	    $json = json_decode($response, true);

	    $lat = $json['results'][0]['geometry']['location']['lat'];
	    $lng = $json['results'][0]['geometry']['location']['lng'];

	    return array($lat, $lng, $url);
	}


	$coords = getCoordinates( $address );
	print_r( json_encode( $coords ) );

	die();
?>