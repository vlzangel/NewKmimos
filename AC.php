<?php
	
	class db{

		private $conn;

		function db($con){
			$this->conn = $con;
		}

		function query($sql){
			return $this->conn->query($sql);
		}

		function get_var($sql, $campo = ""){
			$result = $this->query($sql);
			if( $result->num_rows > 0 ){
				if($campo == ""){
					$temp = $result->fetch_array(MYSQLI_NUM);
		            return $temp[0];
				}else{
		            $temp = $result->fetch_assoc();
		            return $temp[$campo];
				}
	        }else{
	        	return false;
	        }
		}

		function get_row($sql){
			$result = $this->query($sql);
			if( $result->num_rows > 0 ){
	            return (object) $result->fetch_assoc();
	        }else{
	        	return false;
	        }
		}

		function get_results($sql){
			$result = $this->query($sql);
			if( $result->num_rows > 0 ){
				$resultados = array();
				while ( $f = $result->fetch_assoc() ) {
					$resultados[] = (object) $f;
				}
	            return $resultados;
	        }else{
	        	return false;
	        }
		}

		function insert_id(){
			return $this->conn->insert_id;
		}
	}

	include("vlz_config.php");
	$db = new db( new mysqli($host, $user, $pass, $db) );

	$cupos = $db->get_results("SELECT * FROM wp_postmeta WHERE meta_key = '_wc_booking_availability' AND meta_value != 'a:0:{}' AND meta_value != 's:6:\"a:0:{}\";' AND post_id != 0 ");

	echo "<pre>";

		foreach ($cupos as $cupo) {
			$disponibilidades = unserialize( trim($cupo->meta_value));

			print_r($disponibilidades);

			$cuidador = $db->get_var("SELECT post_author FROM wp_posts WHERE ID = ".$cupo->post_id);
			$servicio = $cupo->post_id;
			$tipo 	  = $db->get_var(
	            "
	                SELECT
	                    tipo_servicio.slug AS tipo
	                FROM 
	                    wp_term_relationships AS relacion
	                LEFT JOIN wp_terms as tipo_servicio ON ( tipo_servicio.term_id = relacion.term_taxonomy_id )
	                WHERE 
	                    relacion.object_id = '{$servicio}' AND
	                    relacion.term_taxonomy_id != 28
	            "
	        );
	        $acepta = $db->get_var("SELECT mascotas_permitidas FROM cuidadores WHERE user_id = ".$cuidador);

			foreach ( $disponibilidades as $disponibilidad ) {
				
				if( $disponibilidad["from"] != "" && $disponibilidad["from"] != null ){

					$ini = strtotime( str_replace("/", "-", $disponibilidad["from"]) );
					$fin = strtotime( str_replace("/", "-", $disponibilidad["to"]) );

					for ($i=$ini; $i <= $fin; $i+=86400) { 

						$fecha = date("Y-m-d", $i);

						$db->query("
							INSERT INTO cupos VALUES (
								NULL,
								'{$cuidador}',
								'{$servicio}',
								'{$tipo}',
								'{$fecha}',
								'0',
								'{$acepta}',
								'0',
								'1'
							)
						");

					}

				}

			}

		}

		$actual = date( 'YmdHis', time() );

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
				reserva.post_type  		= 'wc_booking' 			AND 
				startmeta.meta_key   	= '_booking_start' 		AND 
				endmeta.meta_key   		= '_booking_end' 		AND 
				servicio_id.meta_key   	= '_booking_product_id' AND
				acepta.meta_key   		= '_wc_booking_qty' 	AND 
				mascotas.meta_key  		= '_booking_persons' 	AND 
				(
					reserva.post_status NOT LIKE '%cancelled%'  AND
					reserva.post_status NOT LIKE '%cart%' 		AND
					reserva.post_status NOT LIKE '%modified%' 
				) AND (
					endmeta.meta_value >= '{$actual}'
				) AND relacion.term_taxonomy_id != 28
		";

		$cupos = $db->get_results($sql);

		foreach ( $cupos as $cupo ) {
			$ini = strtotime( str_replace("/", "-", $cupo->inicio) );
			$fin = strtotime( str_replace("/", "-", $cupo->fin) );
			$nuevos_cupos = 0; $mascotas = unserialize($cupo->mascotas);
			foreach ($mascotas as $cantidad) {
				$nuevos_cupos += $cantidad;
			}
			for ($i=$ini; $i <= $fin; $i+=86400) { 
				$fecha = date("Y-m-d", $i);
				$existe = $db->get_var("SELECT * FROM cupos WHERE cuidador = {$cupo->autor} AND servicio = '{$cupo->servicio_id }' AND fecha = '{$fecha}'");
				if( $existe != false ){
					$total = $existe->cupos+$nuevos_cupos;
					$db->query("UPDATE cupos SET cupos = {$total} WHERE id = {$existe};");
				}else{
					$db->query("
						INSERT INTO cupos VALUES (
							NULL,
							'{$cupo->autor}',
							'{$cupo->servicio_id}',
							'{$cupo->servicio_tipo}',
							'{$fecha}',
							'{$nuevos_cupos}',
							'{$cupo->acepta}',
							'0',
							'0'
						)
					");
				}
			}
		}
	    $db->query("UPDATE cupos SET full = 1 WHERE cupos >= acepta");

    echo "</pre>";








/*	$cupos = $db->get_results("
		SELECT * FROM cupos AS C1 WHERE ( 
			SELECT 
				count(*) 
			FROM 
				cupos AS C2 
			WHERE 
				C1.cuidador = C2.cuidador AND
				C1.tipo = C2.tipo AND
				C1.fecha = C2.fecha
		) > 1
	");*/

?>