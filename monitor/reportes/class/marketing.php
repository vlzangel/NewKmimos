<?php
	
include_once('general.php');

class marketing extends general{
	
	public function get_datos( $where='' ){
		return $this->select( "SELECT * FROM monitor_marketing $where " );
	}

}