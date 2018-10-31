<?php

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


			cuidador.ID AS cuidador,
			cuidador_nombre.meta_value AS cuidador_nombre,
			cuidador_apellido.meta_value AS cuidador_apellido,
			post_producto.post_title AS servicio,
			cuidador.user_email AS email_cuidador,
			CONCAT( cuidador_movil.meta_value, ' / ', cuidador_phone.meta_value ) AS telefonos_cuidador
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

		INNER JOIN wp_usermeta AS cuidador_nombre ON ( cuidador.ID = cuidador_nombre.user_id AND cuidador_nombre.meta_key = 'first_name' )
		INNER JOIN wp_usermeta AS cuidador_apellido ON ( cuidador.ID = cuidador_apellido.user_id AND cuidador_apellido.meta_key = 'last_name' )
		INNER JOIN wp_usermeta AS cuidador_movil ON ( cuidador.ID = cuidador_movil.user_id AND cuidador_movil.meta_key = 'user_mobile' )
		INNER JOIN wp_usermeta AS cuidador_phone ON ( cuidador.ID = cuidador_phone.user_id AND cuidador_phone.meta_key = 'user_phone' )

		WHERE
			u.post_type = 'wc_booking' AND
			u.post_status = 'confirmed' AND
		    inicio.meta_value > NOW() AND
		    DATE_FORMAT(inicio.meta_value,'%m-%d-%Y') < DATE_FORMAT('20181105000000','%m-%d-%Y')
		GROUP BY u.ID
	";

	$re = $wpdb->get_results($sql);

	echo "<pre>";
		print_r($re);
	echo "</pre>";
?>
