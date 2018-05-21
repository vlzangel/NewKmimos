<?php
require_once( dirname(dirname(__DIR__)).'/vlz_config.php' );

class db {

	protected $cnn;

	public function __construct(){
		global $host, $pass, $user, $db;
				
			$mysqli = new \mysqli(
				$host,
				$user,
				$pass,
				$db,
				3306
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
	public function query($query="", $insert_id=false){

		$result = null;
		if(!empty($query)){
			$result = $this->cnn->query( $query );
		}
		$id = ($insert_id)? $this->cnn->insert_id : 0 ;
		return ($insert_id)? $id : $result ;
	}

	public function escape( $str ){
		return mysqli_real_escape_string( $this->cnn, htmlentities($str) );
	}

	public function close(){
		//Mysqli_close($this->cnn);
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
				// $result = $result[0];
			}
		}
		$this->close();
		return $result;
	}

	public function insert($query=""){
		$id = self::query( $query );
		$this->close();
		return $id;
	}

	public function delete($query=""){
		return self::query( $query );
	}

	public function update($query=""){
		return self::query( $query );
	}

}