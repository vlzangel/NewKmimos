<?php

	header('Content-type: application/vnd.ms-excel;charset=iso-8859-15');
	header('Content-Disposition: attachment; filename=nombre_archivo.xls');

	include 'wp-load.php';

	global $wpdb;

	$sql = "
		SELECT 
			u.ID,
			DATE_FORMAT(inicio.meta_value,'%d-%m-%Y') AS inicio,
			DATE_FORMAT(fin.meta_value,'%d-%m-%Y') AS fin,

			cliente.ID AS cliente,
			cliente_nombre.meta_value AS cliente_nombre,
			cliente_apellido.meta_value AS cliente_apellido,
			cliente.user_email AS email_cliente,
			CONCAT( cliente_movil.meta_value, ' / ', cliente_phone.meta_value ) AS telefonos_cliente,
			cliente_conocio.meta_value AS cliente_conocio,


			cuidador.ID AS cuidador,
			cuidador_nombre.meta_value AS cuidador_nombre,
			cuidador_apellido.meta_value AS cuidador_apellido,
			post_producto.post_title AS servicio,
			cuidador.user_email AS email_cuidador,
			CONCAT( cuidador_movil.meta_value, ' / ', cuidador_phone.meta_value ) AS telefonos_cuidador,
			cuidador_conocio.meta_value AS cuidador_conocio
		FROM
			wp_posts as u
		INNER JOIN wp_postmeta AS inicio ON ( u.ID = inicio.post_id AND inicio.meta_key = '_booking_start' )
		INNER JOIN wp_postmeta AS fin ON ( u.ID = fin.post_id AND fin.meta_key = '_booking_end' )
		INNER JOIN wp_users AS cliente ON ( cliente.ID = u.post_author )
		INNER JOIN wp_postmeta AS producto ON ( u.ID = producto.post_id AND producto.meta_key = '_booking_product_id' )
		INNER JOIN wp_posts AS post_producto ON ( producto.meta_value = post_producto.ID )
		INNER JOIN wp_users AS cuidador ON ( cuidador.ID = post_producto.post_author )


		INNER JOIN wp_usermeta AS cliente_nombre ON ( cliente.ID = cliente_nombre.user_id AND cliente_nombre.meta_key = 'first_name' )
		INNER JOIN wp_usermeta AS cliente_apellido ON ( cliente.ID = cliente_apellido.user_id AND cliente_apellido.meta_key = 'last_name' )
		INNER JOIN wp_usermeta AS cliente_movil ON ( cliente.ID = cliente_movil.user_id AND cliente_movil.meta_key = 'user_mobile' )
		INNER JOIN wp_usermeta AS cliente_phone ON ( cliente.ID = cliente_phone.user_id AND cliente_phone.meta_key = 'user_phone' )
		INNER JOIN wp_usermeta AS cliente_conocio ON ( cliente.ID = cliente_conocio.user_id AND cliente_conocio.meta_key = 'user_referred' )

		INNER JOIN wp_usermeta AS cuidador_nombre ON ( cuidador.ID = cuidador_nombre.user_id AND cuidador_nombre.meta_key = 'first_name' )
		INNER JOIN wp_usermeta AS cuidador_apellido ON ( cuidador.ID = cuidador_apellido.user_id AND cuidador_apellido.meta_key = 'last_name' )
		INNER JOIN wp_usermeta AS cuidador_movil ON ( cuidador.ID = cuidador_movil.user_id AND cuidador_movil.meta_key = 'user_mobile' )
		INNER JOIN wp_usermeta AS cuidador_phone ON ( cuidador.ID = cuidador_phone.user_id AND cuidador_phone.meta_key = 'user_phone' )
		INNER JOIN wp_usermeta AS cuidador_conocio ON ( cuidador.ID = cuidador_conocio.user_id AND cuidador_conocio.meta_key = 'user_referred' )

		WHERE
			u.post_type = 'wc_booking' AND
			u.post_status = 'confirmed' AND
		    inicio.meta_value > NOW() AND
		    DATE_FORMAT(inicio.meta_value,'%m-%d-%Y') < DATE_FORMAT('20181105000000','%m-%d-%Y')
		GROUP BY u.ID
	";

	$re = $wpdb->get_results($sql);

/*	echo "<pre>";
		print_r($re);
	echo "</pre>";*/

	$filas = '';
	foreach ($re as $key => $value) {
		$filas .= '
			<tr>
		        <td>'.$value->ID.'</td>
		        <td>'.$value->inicio.'</td>
		        <td>'.$value->fin.'</td>

		        <td>'.$value->cliente.'</td>
		        <td>'.$value->cliente_nombre.'</td>
		        <td>'.$value->cliente_apellido.'</td>
		        <td>'.$value->email_cliente.'</td>
		        <td>'.$value->telefonos_cliente.'</td>
		        <td>'.$value->cliente_conocio.'</td>

		        <td>'.$value->cuidador.'</td>
		        <td>'.$value->cuidador_nombre.'</td>
		        <td>'.$value->cuidador_apellido.'</td>
		        <td>'.$value->servicio.'</td>
		        <td>'.$value->email_cuidador.'</td>
		        <td>'.$value->telefonos_cuidador.'</td>
		        <td>'.$value->cuidador_conocio.'</td>
			</tr>
		';
	}

	echo '
		<table border="1" cellpadding="2" cellspacing="0" width="100%">
		    <caption>Reservas</caption>
		    <tr>
		        <td># Reserva</td>
		        <td>Inicio</td>
		        <td>Fin</td>

		        <td>Cliente ID</td>
		        <td>Cliente Nombre</td>
		        <td>Cliente Apellido</td>
		        <td>Cliente Email</td>
		        <td>Cliente Teléfonos</td>
		        <td>Cliente Nos conocio por:</td>

		        <td>Cuidador ID</td>
		        <td>Cuidador Nombre</td>
		        <td>Cuidador Apellido</td>
		        <td>Cuidador Servicio</td>
		        <td>Cuidador Email</td>
		        <td>Cuidador Teléfonos</td>
		        <td>Cuidador Nos conocio por:</td>
		    </tr>
		    '.$filas.'
		</table>
	';
?>
