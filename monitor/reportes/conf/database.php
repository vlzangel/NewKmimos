<?php

class db {

	protected $cnn;

	protected $host = 'localhost';
	protected $username = 'root';
	protected $password = '';
	protected $dbname = 'kmimos_monitor';
	protected $port = '3306';

	public function __construct(){
		$mysqli = new \mysqli(
			$this->host,
			$this->username,
			$this->password,
			$this->dbname,
			$this->port
		);
		mysqli_query($mysqli, "SET NAMES 'utf8'");
		if(mysqli_connect_errno()){
			echo 'Conexion Fallida : ', mysqli_connect_error();
			exit();
		}
		$this->cnn = $mysqli;
	}

	// --------------------------------------
	// Execute Query
	// --------------------------------------
	private function query($query=""){

		$result = null;
		if(!empty($query)){
			$result = $this->cnn->query( $query );
		}
		return $result;
	}

	public function escape( $str ){
		return mysqli_real_escape_string( $this->cnn, htmlentities($str) );
	}

	// --------------------------------------
	// CRUD
	// --------------------------------------
	public function select($query=""){

		$result = null;
		$datos = self::query( $query );
		if( isset($datos->num_rows) && $datos->num_rows > 0 ){
			/* while ( $temp = $datos->fetch_assoc() ) { $result[] = (object) $temp; } */
			$result = mysqli_fetch_all($datos, MYSQLI_ASSOC);
			if( count($result) == 1 ){
				$result = $result[0];
			}
		}

		return $result;
	}

	public function insert($query=""){
		self::query( $query );
		return $this->cnn->insert_id;
	}

	public function delete($query=""){

		return self::query( $query );
	}

	public function update($query=""){

		return self::query( $query );
	}

}