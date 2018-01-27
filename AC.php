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

	function dateFormat($fecha){
		return strtotime( str_replace("/", "-", $fecha) );
	}

	include("vlz_config.php"); $db = new db( new mysqli($host, $user, $pass, $db) );

	$db->query("DELETE FROM cupos WHERE id > 1;");
	$db->query("DELETE FROM disponibilidad WHERE id > 1;");

	$rangos = array();
	$cupos = $db->get_results("SELECT * FROM wp_postmeta WHERE meta_key = '_wc_booking_availability' AND meta_value != 'a:0:{}' AND meta_value != 's:6:\"a:0:{}\";' AND post_id != 0 ");

	echo "<pre>";
		foreach ($cupos as $key => $value) {
			$_rangos = unserialize( $value->meta_value );
			foreach ($_rangos as $_rango) {
				if( $_rango["from"] != "" ){
					$rangos[ $value->post_id ][] = array(
						$_rango["from"],
						$_rango["to"]
					);
				}
			}
		}
		
		// print_r( $rangos );

		foreach ($rangos as $servicio_id => $_rangos) {
			foreach ($_rangos as $rango) {
				$rango[0] = date( "Y-m-d", strtotime( str_replace("/", "-", $rango[0]) ) );
				$rango[1] = date( "Y-m-d", strtotime( str_replace("/", "-", $rango[1]) ) );
				$existe = $db->get_var("SELECT * FROM disponibilidad WHERE servicio_id = {$servicio_id} AND desde = '{$rango[0]}' AND hasta = '{$rango[1]}' ");
				if( $existe === false ){
					$user_id = $db->get_var("SELECT post_author FROM wp_posts WHERE ID = {$servicio_id} ");
					$tipo = $db->get_var(
	                    "
	                        SELECT
	                            tipo_servicio.slug AS tipo
	                        FROM 
	                            wp_term_relationships AS relacion
	                        LEFT JOIN wp_terms as tipo_servicio ON ( tipo_servicio.term_id = relacion.term_taxonomy_id )
	                        WHERE 
	                            relacion.object_id = '{$servicio_id}' AND
	                            relacion.term_taxonomy_id != 28
	                    "
	                );
					$db->query("INSERT INTO disponibilidad VALUES ( NULL, {$user_id}, {$servicio_id}, '{$tipo}', '{$rango[0]}', '{$rango[1]}' ) ");
				}
			}
		}

		$db->query("UPDATE cupos SET no_disponible = '0' WHERE no_disponible = '1';");
		$no_disponibilidades = $db->get_results("SELECT * FROM disponibilidad");

		foreach ($no_disponibilidades as $data) {
			$desde = strtotime( $data->desde );
			$hasta = strtotime( $data->hasta );
			for ($i=$desde; $i <= $hasta; $i+=86400) { 
				$fecha = date("Y-m-d", $i);
				$existe = $db->get_row("SELECT * FROM cupos WHERE cuidador = {$data->user_id} AND servicio = '{$data->servicio_id}' AND fecha = '{$fecha}'");
				if( $existe !== false ){
					$db->query("UPDATE cupos SET no_disponible = 1 WHERE id = {$existe->id};");
				}else{
					$db->query("
						INSERT INTO cupos VALUES (
							NULL,
							'{$data->user_id}',
							'{$data->servicio_id}',
							'{$data->servicio_str}',
							'{$fecha}',
							'0',
							'0',
							'0',
							'1'
						)
					");
				}
			}
			$acepta = $db->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = '{$data->servicio_id}' AND meta_key = '_wc_booking_qty' ");
			$db->query("UPDATE cupos SET acepta = '{$acepta}' WHERE servicio = '{$data->servicio_id}';");
		}

		$actual = date( 'YmdHis', time() );

		$sql = "
			SELECT 
				reserva.ID 				 AS id, 
				servicio.post_author 	 AS autor, 
				servicio.ID 		 	 AS servicio_id, 
				tipo.slug 		 	 	 AS servicio_tipo, 
				servicio.post_name 		 AS servicio, 
				DATE_FORMAT(startmeta.meta_value,'%Y-%m-%d') AS inicio, 
				DATE_FORMAT(endmeta.meta_value,'%Y-%m-%d') AS fin,
				acepta.meta_value		 AS acepta,
				mascotas.meta_value 	 AS mascotas,
				reserva.post_status		 AS status

			FROM wp_posts AS reserva

			INNER JOIN wp_postmeta as startmeta     ON ( reserva.ID 		= startmeta.post_id 		)
			INNER JOIN wp_postmeta as endmeta   	   ON ( reserva.ID 		= endmeta.post_id 			)
			INNER JOIN wp_postmeta as mascotas  	   ON ( reserva.ID 		= mascotas.post_id 			)
			INNER JOIN wp_postmeta as servicio_id   ON ( reserva.ID 		= servicio_id.post_id 		)
			INNER JOIN wp_posts    as servicio  	   ON ( servicio.ID 	= servicio_id.meta_value 	)
			INNER JOIN wp_postmeta as acepta  	   ON ( acepta.post_id 	= servicio.ID 				)

			INNER JOIN wp_term_relationships as relacion ON ( relacion.object_id = servicio.ID )
			INNER JOIN wp_terms as tipo ON ( tipo.term_id = relacion.term_taxonomy_id )

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

		print_r($cupos);

		foreach ( $cupos as $cupo ) {

			$ini = dateFormat( $cupo->inicio );
			$fin = dateFormat( $cupo->fin );

			$nuevos_cupos = 0; $mascotas = unserialize($cupo->mascotas);
			foreach ($mascotas as $cantidad) {
				$nuevos_cupos += $cantidad;
			}

			for ($i=$ini; $i <= $fin; $i+=86400) { 

				if( $i == $fin && $cupo->servicio_tipo == "hospedaje" ){}else{

					$fecha = date("Y-m-d", $i);
					$existe = $db->get_var("SELECT * FROM cupos WHERE cuidador = {$cupo->autor} AND servicio = '{$cupo->servicio_id }' AND fecha = '{$fecha}'");
					if( $existe != false ){
						$total = $existe->cupos+$nuevos_cupos;
						$db->query("UPDATE cupos SET cupos = {$total} WHERE id = {$existe->id};");
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
		}
	    $db->query("UPDATE cupos SET full = 1 WHERE cupos >= acepta");


    echo "</pre>";

?>