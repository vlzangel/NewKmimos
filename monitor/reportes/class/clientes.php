<?php

require_once (dirname(__DIR__)."../conf/database.php");

class clientes extends db{

	public function get_clientes( $id ){
		return $this->select("select * from configuracion where id = {$id}");
	}

}