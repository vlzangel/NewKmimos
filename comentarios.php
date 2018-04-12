<?php
	include "wp-load.php";

	vlz_actualizar_ratings(156113);
	
	/*global $wpdb;

	$comentarios = $wpdb->get_results("SELECT * FROM wp_comments WHERE comment_author LIKE '%@%' ");
	foreach ($comentarios as $key => $comentario) {
		if( $comentario->user_id+0 > 0 ){
			$nombre = get_user_meta($comentario->user_id, "first_name", true)." ".get_user_meta($comentario->user_id, "last_name", true);
			$sql = "UPDATE wp_comments SET comment_author = '{$nombre}' WHERE comment_ID = ".$comentario->comment_ID;
			echo $sql."<br>";
			$wpdb->query( $sql );
		}else{
			$nombre = explode("@", $comentario->comment_author);
			$sql = "UPDATE wp_comments SET comment_author = '{$nombre[0]}' WHERE comment_ID = ".$comentario->comment_ID;
			echo $sql."<br>";
			$wpdb->query( $sql );
		}
	}*/
?>