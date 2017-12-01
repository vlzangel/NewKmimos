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

	$cupones = $db->get_results("SELECT * FROM wp_posts WHERE post_type LIKE 'shop_coupon'");
	foreach ($cupones as $cupon) {
		
		$usos = $db->get_var("SELECT COUNT(*) AS total FROM wp_woocommerce_order_items WHERE order_item_name LIKE '".$cupon->post_name."'");

		$usage_count = $db->get_var("SELECT * FROM wp_postmeta WHERE post_id = {$cupon->ID} AND meta_key LIKE 'usage_count'");
		if( $usage_count != false ){
			$db->query("UPDATE wp_postmeta SET meta_value = '{$usos}' WHERE post_id = {$cupon->ID} AND meta_key LIKE 'usage_count'");
			echo ("UPDATE wp_postmeta SET meta_value = '{$usos}' WHERE post_id = {$cupon->ID} AND meta_key LIKE 'usage_count'; <br>");
		}else{
			$db->query("INSERT INTO wp_postmeta VALUES (NULL, '{$cupon->ID}', 'usage_count', '{$usos}');");
			echo ("INSERT INTO wp_postmeta VALUES (NULL, '{$cupon->ID}', 'usage_count', '{$usos}'); <br>");
		}

	}
?>