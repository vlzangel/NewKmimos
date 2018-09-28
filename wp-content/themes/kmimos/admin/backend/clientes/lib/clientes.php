<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE );
date_default_timezone_set('America/Mexico_City');

$Clientes = new Clientes();

class Clientes {
	
	public $db;
	
	public function Clientes(){
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

	public function get_clientes( $desde, $hasta ){
		$sql = 'SELECT * FROM clientes';
		if( !empty($desde) && !empty($hasta) ){
			$sql .= " WHERE fecha_registro >= '{$desde} 00:00:00' and fecha_registro <= '{$hasta} 23:59:59' ";
		}
		return $this->db->get_results($sql);
	}

	public function get_mascotas( $user_id ){
		return $this->db->get_results("SELECT * FROM mascotas WHERE user_id = {$user_id}");
	}
}