<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE );
date_default_timezone_set('America/Mexico_City');

$Procesos = new Procesos();

class Procesos {
	
	public $db;
	
	public function Procesos(){
		$this->raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
		if( !isset($db) || is_string( $db ) ){
			include($this->raiz.'/vlz_config.php');
			if( !class_exists('db') ){
				include($this->raiz.'/wp-content/themes/kmimos/procesos/funciones/db.php');
			}
		    $db = new db( new mysqli($host, $user, $pass, $db) );
		}
		$this->db = $db;
	}

	public function get_usos( $desde, $hasta ){
		$sql = 'SELECT * FROM usos_banner';
		if( !empty($desde) && !empty($hasta) ){
			$sql .= " WHERE fecha >= '{$desde} 00:00:00' and fecha <= '{$hasta} ' ";
		}
		return $this->db->get_results($sql);
	}

	public function get_cliente( $user_id ){
		$sql = "SELECT * FROM wp_usermeta WHERE user_id = '{$user_id}' AND ( meta_key = 'first_name' OR meta_key = 'last_name' )";
		$r = $this->db->get_results($sql);
		$info = [];
		foreach ($r as $key => $v) {
			$info[ $v->meta_key ] = $v->meta_value;
		}
		return $info["first_name"]." ".$info["last_name"];
	}
}