<?php

	$__desde ='2017-01-01';

	$__hasta = '2018-03-22';


	$hoy = $__desde;
	for ($i=0; $hoy <= $__hasta ; $i++) { 

		 
			echo '<pre>';
			echo $hoy.":<br>";
			try{
				echo '<br>http://localhost/mx.kmimos.new/monitor/cron/nutriheroes/ventas.php?d='.$hoy;
				request('http://localhost/mx.kmimos.new/monitor/cron/nutriheroes/ventas.php?d='.$hoy);
				request('http://localhost/mx.kmimos.new/monitor/cron/nutriheroes/usuarios.php?d='.$hoy);
			}catch(Exception $e){}
			echo '</pre>';
		 


		$hoy = date( "Y-m-d", strtotime( "$hoy +1 day" ) );
	}


	function request( $url ){

		if( !class_exists('Requests') ){
			require_once('recursos/Requests/Requests.php');
			Requests::register_autoloader();
		}
		$headers = Array(
			'Content-Type'=> 'application/json; charset=UTF-8',	
			'Accept'=>'application/json'
		);
		$request = Requests::get($url, array('Accept' => 'application/json'));;

		return $request;
	}