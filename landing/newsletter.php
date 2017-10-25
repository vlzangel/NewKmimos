<?php
	// $sts = 0;

	// if(isset($_GET['email']))
	// {
	// 	$file = fopen("newsletter.csv", "a+");
	// 	fwrite($file, $_GET['email'].";" . PHP_EOL);
	// 	fclose($file);
	// 	$sts=1;
	// }

	// return $sts;


	header('Access-Control-Allow-Origin: *');
	require_once("../vlz_config.php");
	$sts = 0;

	if(!empty($_GET) ){
		if( !isset($_GET['source'])) { return; }
		if( empty($_GET['source'])) { return; }
		
		if( !isset($_GET['email'])) { return; }
		if( empty($_GET['email'])) { return; }

		// Validar Email
		if(preg_match("/[\+]{1,}/", $_GET['email'])){ return; }
		$result = filter_var($_GET['email'], FILTER_VALIDATE_EMAIL);

		if( $result ){
			$cnn = new mysqli($host, $user, $pass, $db);
			if($cnn){

				$rows = "SELECT * FROM wp_kmimos_subscribe WHERE email = '".$_GET['email']."'";
				$r = $cnn->query( $rows );
				if( $r->num_rows == 0){
					# Insertar registro
					$sql = "
						insert into wp_kmimos_subscribe 
							( `name`, `source`, `email`, `time` )
					 	value 
							( '', '".$_GET['source']."', '".$_GET['email']."', now() )
					";
					if( $cnn->query( $sql ) ){
						$sts = 1;
					}
				}else{
					$sts = 3;
				}
			}
		}else{
			$sts = 2;
		}
	}
	print_r($sts);

