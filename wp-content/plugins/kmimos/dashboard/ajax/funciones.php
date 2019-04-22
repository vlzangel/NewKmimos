<?php
	function getCountReservas( $author_id=0, $desde="", $hasta="" ){

		global $db;

		$filtro_adicional = "";
		if( !empty($landing) ){
			$filtro_adicional = " source = '{$landing}'";
		}
		if( !empty($desde) && !empty($hasta) ){
			$filtro_adicional .= " 
				AND post_date_gmt >= '{$desde} 00:00:00' AND post_date_gmt <= '{$hasta} 00:00:00'
			";
		}

		$result = [];

		$sql = "
			SELECT 
				count(ID) as cant
			FROM 
				wp_posts
			WHERE 
				post_type = 'wc_booking' 
				AND not post_status like '%cart%'
				AND post_status = 'confirmed' 
				AND post_author = {$author_id}
				{$filtro_adicional}
		";

		$result = $db->get_var($sql);

		return $result;
	}
?>