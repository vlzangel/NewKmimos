<?php
	include 'wp-load.php';

	global $wpdb;

	$cantidad = $wpdb->get_results("
		SELECT 
			COUNT(*)
		FROM 
			wp_posts AS p
		INNER JOIN wp_term_relationships AS m ON ( m.object_id = p.ID AND m.term_taxonomy_id = '2605' )
		WHERE 
			post_type = 'pets' AND 
			post_status = 'publish' AND
			post_date >= '2016-01-01 00:00:00'

	");

	echo "<pre>";
		print_r( count($cantidad) );
	echo "</pre>";

	/*
		Kmimos México

			14.776 Perros
			   333 Gatos

		Kmimos Colombia

			210 Perros
			 34 Gatos

		Kmimos Perú

			363 Perros
			  9 Gatos
	*/
?>

