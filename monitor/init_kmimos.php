<?php
	echo '<pre>';

		$__desde ='2017-01-01';

		$__hasta = '2018-06-01';


		$hoy = $__desde;
		for ($i=0; $hoy <= $__hasta ; $i++) { 

			 
				try{
					echo '<br>'.$hoy;

					print_r( 
						request('http://mx.kmimos.la/monitor/cron/kmimos/historico_reservas.php?d='.$hoy) 
					);

					print_r( 
						request('http://mx.kmimos.la/monitor/cron/kmimos/reservas.php?d='.$hoy)
					);

					// request('http://localhost/mx.kmimos.new/monitor/cron/kmimos/usuarios.php?d='.$hoy);

				}catch(Exception $e){}
			 


			$hoy = date( "Y-m-d", strtotime( "$hoy +1 day" ) );
		}

	echo '</pre>';


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