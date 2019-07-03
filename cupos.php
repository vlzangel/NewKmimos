<?php
	
	error_reporting( 0 );

	date_default_timezone_set('America/Mexico_City');
	
	class db{
		private $conn;
		function db($con){ $this->conn = $con; }
		function query($sql){ return $this->conn->query($sql); }
		function get_var($sql, $campo = ""){ $result = $this->query($sql); if( $result->num_rows > 0 ){ if($campo == ""){ $temp = $result->fetch_array(MYSQLI_NUM); return $temp[0]; }else{ $temp = $result->fetch_assoc(); return $temp[$campo]; } }else{ return false; } }
		function get_row($sql){ $result = $this->query($sql); if( $result->num_rows > 0 ){ return (object) $result->fetch_assoc(); }else{ return false; } }
		function get_results($sql){ $result = $this->query($sql); if( $result->num_rows > 0 ){ $resultados = array(); while ( $f = $result->fetch_assoc() ) { $resultados[] = (object) $f; } return $resultados; }else{ return false; } }
		function insert_id(){ return $this->conn->insert_id; }
		function multi_query($sql){ return $this->conn->multi_query($sql); }
	}

	include("vlz_config.php");

	$db = new db( new mysqli($host, $user, $pass, $db) );

	$actual = date( 'YmdHis', time() );
	$hoy = date( 'Y-m-d', time() );

	$sql = "
		SELECT 
			reserva.ID 				 AS id, 
			servicio.post_author 	 AS autor, 
			servicio.ID 		 	 AS servicio_id, 
			tipo.slug 		 	 	 AS servicio_tipo, 
			servicio.post_name 		 AS servicio, 
			DATE_FORMAT(startmeta.meta_value,'%d-%m-%Y') AS inicio, 
			DATE_FORMAT(endmeta.meta_value,'%d-%m-%Y') AS fin,
			acepta.meta_value		 AS acepta,
			mascotas.meta_value 	 AS mascotas,
			reserva.post_status		 AS status

		FROM wp_posts AS reserva

		LEFT JOIN wp_postmeta as startmeta     ON ( reserva.ID 		= startmeta.post_id 		)
		LEFT JOIN wp_postmeta as endmeta   	   ON ( reserva.ID 		= endmeta.post_id 			)
		LEFT JOIN wp_postmeta as mascotas  	   ON ( reserva.ID 		= mascotas.post_id 			)
		LEFT JOIN wp_postmeta as servicio_id   ON ( reserva.ID 		= servicio_id.post_id 		)
		LEFT JOIN wp_posts    as servicio  	   ON ( servicio.ID 	= servicio_id.meta_value 	)
		LEFT JOIN wp_postmeta as acepta  	   ON ( acepta.post_id 	= servicio.ID 				)

		LEFT JOIN wp_term_relationships as relacion ON ( relacion.object_id = servicio.ID )
		LEFT JOIN wp_terms as tipo ON ( tipo.term_id = relacion.term_taxonomy_id )

		WHERE 
			reserva.post_type  		= 'wc_booking' 				AND 
			startmeta.meta_key   	= '_booking_start' 			AND 
			endmeta.meta_key   		= '_booking_end' 			AND 
			servicio_id.meta_key   	= '_booking_product_id' 	AND 
			acepta.meta_key   		= '_wc_booking_qty' 		AND 
			mascotas.meta_key  		= '_booking_persons' 		AND 
			(
				reserva.post_status NOT LIKE '%cancelled%' 	AND
				reserva.post_status NOT LIKE '%cart%'  		AND
				reserva.post_status NOT LIKE '%modified%'   AND
				reserva.post_status NOT LIKE '%unpaid%' 
			) AND  (
				endmeta.meta_value >= '{$actual}'
			) AND
			relacion.term_taxonomy_id != 28
	";


	$resultados = $db->get_results($sql);

	$data_cupos = [];

	foreach ($resultados as $key => $reserva) {
		$mascotas = 0;
		$temp = unserialize( $reserva->mascotas);
		foreach ($temp as $cant) {
			$mascotas += $cant;
		}

		$ini = strtotime( $reserva->inicio );
		$fin = strtotime( $reserva->fin );

		for ($i=$ini; $i < $fin; $i += 86400 ) { 
			$data_cupos[ $reserva->servicio_id ][ date("Y-m-d", $i) ] += $mascotas;
		}
	}
	
	/*
	echo "<pre>";
		print_r($data_cupos);
	echo "</pre>";
	*/

	// $db->query("UPDATE cupos SET cupos = 0");

	$sql = '';
	$temp_cupos = [];
	foreach ($data_cupos as $key => $fechas) {
		foreach ($fechas as $fecha => $cupos) {
			$temp_cupos[ $cupos ][ $key ][] = $fecha;
		}
	}

	$sql = '';
	foreach ($temp_cupos as $cupos => $servicios) {
		$_servicios = [];
		$_fechas = [];

		$sql .= "UPDATE cupos SET cupos = '{$cupos}' WHERE ";
		$subgrupos = [];
		foreach ($servicios as $servicio => $fechas) {
			$subgrupos[] = "( servicio = '{$servicio}' AND fecha IN ('".implode("', '", $fechas)."') )";
		}
		$sql .= implode(" OR ", $subgrupos).";";
	}

	$db->multi_query( $sql );

	$db->query("UPDATE cupos SET cupos = 0");


	$db->query("UPDATE cupos SET full = 1 WHERE cupos >= acepta");
	$db->query("UPDATE cupos SET full = 0 WHERE cupos < acepta");
	
	// echo $sql;

	/*
	echo "<pre>";
		print_r($temp_cupos);
	echo "</pre>";
	*/
	

	exit();
?>