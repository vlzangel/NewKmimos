<?php
	$kmimos_load=dirname(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))))).'/wp-load.php';
	if(file_exists($kmimos_load)){
	    include_once($kmimos_load);
	}

	global $wpdb;

	$wlabel=$_wlabel_user->wlabel;

	function getDataReserva($id){
		global $wpdb;

		if( $id != "" && $id != NULL ){
			$reserva = $wpdb->get_row("SELECT * FROM wp_posts WHERE ID = {$id}");

			$origen = ( empty( get_post_meta($reserva->post_parent, 'verification_code', true) ) ) ? 'Pagina' : 'App';

			$metas = get_post_meta($id);

			$_mascotas = unserialize($metas["_booking_persons"][0]);
			$mascotas = 0;
			foreach ($_mascotas as $mascota_id => $mascota_cantidad) {
				$mascotas+=$mascota_cantidad;
			}

			$servicio = $wpdb->get_row("SELECT * FROM wp_posts WHERE ID = {$metas['_booking_product_id'][0]}");

			$info = explode(" - ", $servicio->post_title);

			$status = [
				"confirmed" => "Confirmado",
				"cancelled" => "Cancelado",
			];

			$r = [
				"id" => $reserva->ID,
				"fecha" => date("Y-m-d", strtotime($reserva->post_date) ),
				"checkin" => date("Y-m-d", strtotime($metas["_booking_start"][0]) ),
				"checkout" => date("Y-m-d", strtotime($metas["_booking_end"][0]) ),
				"mascotas" => $mascotas." mascota(s)",
				"monto" => "MXN $".number_format( $metas["_booking_cost"][0], 2, ",", "." ),
				"cuidador" => $info[1],
				"servicio" => $info[0],
				"status" => $status[ $reserva->post_status ],
				"origen" => $origen
			];

			return $r;
		}
	}

	$ult_3_meses = date("Y-m-d 00:00:00", strtotime("-3 month") );

	$SQL = "
		SELECT 
			wp_users.ID AS ID,
			wp_users.user_email AS user_email,
			wp_users.user_registered AS user_registered,
			( SELECT ID FROM wp_posts WHERE wp_users.ID = wp_posts.post_author AND wp_posts.post_type = 'wc_booking' AND wp_posts.post_status IN ('confirmed', 'cancelled') ORDER BY ID ASC LIMIT 0, 1 ) AS primera_reserva,
			( SELECT ID FROM wp_posts WHERE wp_users.ID = wp_posts.post_author AND wp_posts.post_type = 'wc_booking' AND wp_posts.post_status IN ('confirmed', 'cancelled') AND wp_posts.post_date >= '{$ult_3_meses}' ORDER BY ID ASC LIMIT 0, 1 ) AS reserva_ult_3_meses
		FROM 
			wp_users
		LEFT JOIN wp_usermeta AS wlabel ON ( wp_users.ID = wlabel.user_id )
		WHERE 
			( wlabel.meta_key = 'user_referred' OR wlabel.meta_key = '_wlabel' ) AND
			( wlabel.meta_value LIKE '%{$wlabel}%' ) AND
			wp_users.user_registered >= '2018-09-01 00:00:00'
		GROUP BY wp_users.ID
	";

	$usuarios = $wpdb->get_results($SQL);
	$data["data"] = [];
	if( count($usuarios) > 0 ){
		foreach ($usuarios as $usuario) {
			$metas = get_user_meta($usuario->ID);

			$reservas = 0;

			$metas["user_referred"][0] = $wlabel;

			$_info = [
				$metas["first_name"][0]." ".$metas["last_name"][0],
				$usuario->user_email,
				( date("Y-m-d", strtotime( $usuario->user_registered ) ) ),
				$metas["user_mobile"][0],
				$metas["user_referred"][0]
			];

			if( $usuario->primera_reserva != null ){
				$info = getDataReserva($usuario->primera_reserva);

				$_info[] = $info['id'];
				$_info[] = $info['origen'];
				$_info[] = $info['fecha'];
				$_info[] = $info['checkin'];
				$_info[] = $info['checkout'];
				$_info[] = $info['mascotas'];
				$_info[] = $info['monto'];
				$_info[] = $info['cuidador'];
				$_info[] = $info['servicio'];
				$_info[] = $info['status'];

				$cancelo = ( $info['status'] == "Cancelado" ) ? "Si" : "No";
				$_info[] = $cancelo;

				if( $cancelo == "Si" ){
					$SQL = "SELECT ID FROM wp_posts WHERE post_author = {$usuario->ID} AND post_type = 'wc_booking' AND post_status = 'confirmed' ORDER BY ID ASC";
					$_nueva_reserva = $wpdb->get_var($SQL);
					if( $_nueva_reserva != null){
						$info = getDataReserva($_nueva_reserva);
						
						$_info[] = $info['id'];
						$_info[] = $info['origen'];
						$_info[] = $info['fecha'];
						$_info[] = $info['checkin'];
						$_info[] = $info['checkout'];
						$_info[] = $info['mascotas'];
						$_info[] = $info['monto'];
						$_info[] = $info['cuidador'];
						$_info[] = $info['servicio'];
						$_info[] = $info['status'];


					}else{

						$_info[] = "-";
						$_info[] = "-";
						$_info[] = "-";
						$_info[] = "-";
						$_info[] = "-";
						$_info[] = "-";
						$_info[] = "-";
						$_info[] = "-";
						$_info[] = "-";
						$_info[] = "-";
					}
				}else{

						$_info[] = "-";
						$_info[] = "-";
						$_info[] = "-";
						$_info[] = "-";
						$_info[] = "-";
						$_info[] = "-";
						$_info[] = "-";
						$_info[] = "-";
						$_info[] = "-";
						$_info[] = "-";

}

				$reservas++;
			}else{
				$_info[] = "-";
				$_info[] = "-";
				$_info[] = "-";
				$_info[] = "-";
				$_info[] = "-";
				$_info[] = "-";
				$_info[] = "-";
				$_info[] = "-";
				$_info[] = "-";
				$_info[] = "-";
				
				$_info[] = "-";

				$_info[] = "-";
				$_info[] = "-";
				$_info[] = "-";
				$_info[] = "-";
				$_info[] = "-";
				$_info[] = "-";
				$_info[] = "-";
				$_info[] = "-";
				$_info[] = "-";
				$_info[] = "-";
			}

			$_info_3_meses = "Sin reservas";
			if( $usuario->reserva_ult_3_meses != null ){
				$info = getDataReserva($usuario->reserva_ult_3_meses);

				$_info[] = $info['id'];
				$_info[] = $info['origen'];
				$_info[] = $info['fecha'];
				$_info[] = $info['checkin'];
				$_info[] = $info['checkout'];
				$_info[] = $info['mascotas'];
				$_info[] = $info['monto'];
				$_info[] = $info['cuidador'];
				$_info[] = $info['servicio'];
				$_info[] = $info['status'];

				$cancelo_3_meses = ( $info['status'] == "Cancelado" ) ? "Si" : "No";
				$_info[] = $cancelo_3_meses;

				if( $cancelo_3_meses == "Si" ){
					$SQL = "SELECT ID FROM wp_posts WHERE post_author = {$usuario->ID} AND post_type = 'wc_booking' AND post_status = 'confirmed' AND post_date >= '{$ult_3_meses}' ORDER BY ID ASC";
					$_nueva_reserva = $wpdb->get_var($SQL);
					if( $_nueva_reserva != null){
						$info = getDataReserva($_nueva_reserva);
						$_info[] = $info['id'];
						$_info[] = $info['origen'];
						$_info[] = $info['fecha'];
						$_info[] = $info['checkin'];
						$_info[] = $info['checkout'];
						$_info[] = $info['mascotas'];
						$_info[] = $info['monto'];
						$_info[] = $info['cuidador'];
						$_info[] = $info['servicio'];
						$_info[] = $info['status'];
					}else{
						$_info[] = "-";
						$_info[] = "-";
						$_info[] = "-";
						$_info[] = "-";
						$_info[] = "-";
						$_info[] = "-";
						$_info[] = "-";
						$_info[] = "-";
						$_info[] = "-";
						$_info[] = "-";
					}
				}else{
					$_info[] = "-";
					$_info[] = "-";
					$_info[] = "-";
					$_info[] = "-";
					$_info[] = "-";
					$_info[] = "-";
					$_info[] = "-";
					$_info[] = "-";
					$_info[] = "-";
					$_info[] = "-";
				}

				$reservas++;
			}else{
				$_info[] = "-";
				$_info[] = "-";
				$_info[] = "-";
				$_info[] = "-";
				$_info[] = "-";
				$_info[] = "-";
				$_info[] = "-";
				$_info[] = "-";
				$_info[] = "-";
				$_info[] = "-";
				
				$_info[] = "-";

				$_info[] = "-";
				$_info[] = "-";
				$_info[] = "-";
				$_info[] = "-";
				$_info[] = "-";
				$_info[] = "-";
				$_info[] = "-";
				$_info[] = "-";
				$_info[] = "-";
				$_info[] = "-";
			}

			if( $reservas > 0 ){

				$data["data"][] = $_info;
				
			}
			
		}
	}
	
	if( $_SESSION["CLIENTE_IP"] != '186.90.21.105' ){
		echo json_encode($data);
	}else{
		/*echo "<pre>";
			print_r( $data );
		echo "</pre>";*/
		echo json_encode($data);
	}
?>