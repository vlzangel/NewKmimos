<?php

	if( !function_exists('get_home_url')){
		function get_home_url(){ return '/'; }
	}
	$path = dirname(__DIR__).'/cron/lib/payu/PayU.php';
	require ( $path ); 

/*
	if( !empty($_GET) ){
		extract($_GET);
	}

	if( isset( $o ) ){
		switch ($o) {
			case 'procesar':
				if( isset( $order_id ) ){
					$r = fopen('data.txt', "a+");
					fwrite($r, date("Y-m-d H:i:s ") );
					fwrite($r, " || ".$order_id."\r\n" );
					fclose($r);
					echo 'completado';
				}else{
					echo 'NO completado';
				}

				break;			
			case 'init':
				$response = get_reservas();
				echo '<pre>';
				print_r( $response );
				echo '</pre>';
				break;
		}	
	}
*/

$response = get_reservas();
echo '<pre>';
print_r( $response );
echo '</pre>';


	// ************************
	// Funciones 
	// ************************
	function get_reservas(){
	
		$payu = new PayU();
 
		$sql = "
			SELECT
	 			r.post_parent as 'pedido'
			FROM wp_posts as r
				LEFT JOIN wp_postmeta as rm ON rm.post_id = r.post_parent and rm.meta_key = '_payment_method' 			 
			WHERE r.post_type = 'wc_booking' 
				and not r.post_status like '%cart%' 
				and r.post_status in ('unpaid','wc-pending')
				and rm.meta_value = 'payulatam'
			ORDER BY r.ID DESC
		";

		$r = get_fetch_assoc($sql);
		$reservas = ( array_key_exists('rows', $r) )? $r['rows'] : [] ;


		foreach( $reservas as $reserva ){
			$response[] = $payu->getByOrderID( intval( $reserva['pedido'] ) );
			
		}

		return $response;
	}

	function get_status( $state ){

		switch ($state) {
			case "APPROVED":
				break;
			case "DECLINED":
				break;
			case "ERROR":
				break;
			case "EXPIRED":
				break;	
			case "PENDING":
				break;
			case "SUBMITTED":
				break;
		}
	}

	function get_fetch_assoc($sql){

		include ( realpath( dirname(__DIR__).'/vlz_config.php') );

		$cnn = new mysqli($host, $user, $pass, $db);
		$data = [];
		if($cnn){
			$rows = $cnn->query( $sql );
			if(isset($rows->num_rows)){
				if( $rows->num_rows > 0){
					$data['info'] = $rows;
					$data['rows'] = mysqli_fetch_all($rows,MYSQLI_ASSOC);
				}
			}
		}
		return $data;
	}

?>
