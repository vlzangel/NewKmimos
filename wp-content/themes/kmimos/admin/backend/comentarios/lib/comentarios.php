<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE );
date_default_timezone_set('America/Mexico_City');

$comentarios = new comentarios();

class comentarios {
	
	public $db;
	
	public function comentarios(){
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

	public function get_cuidadores( $user_id=0 ){
		$sql = "SELECT * FROM cuidadores";
		return $this->db->get_results($sql);
	}

	public function get_criterio_valoracion( $user_id, $criterio ){
		$criterio = strtolower($criterio);
		$sql = "
			SELECT  	
				c.user_id,
				CASE 
					WHEN max( cm.meta_value ) > 0 THEN max( cm.meta_value ) ELSE 0
				END as maximo,
				CASE 
					WHEN min( cm.meta_value ) > 0 THEN min( cm.meta_value ) ELSE 0
				END as minimo,
				CASE 
					WHEN ( SUM(meta_value) / COUNT( META_key ) ) > 0 THEN ( SUM(meta_value) / COUNT( META_key ) ) ELSE 0
				END as promedio
			 FROM wp_comments as c
				left join wp_commentmeta as cm ON cm.comment_id = c.comment_ID and cm.meta_key = '{$criterio}'
			 WHERE cm.meta_value > 0 AND c.user_id = {$user_id}
		";
		return $this->db->get_row($sql);
	}

	public function get_criterio_general( $user_id ){
		$sql = "
			SELECT  	
				c.user_id,
				CASE 
					WHEN max( cm.meta_value ) > 0 THEN max( cm.meta_value ) ELSE 0
				END as maximo,
				CASE 
					WHEN min( cm.meta_value ) > 0 THEN min( cm.meta_value ) ELSE 0
				END as minimo,
				CASE 
					WHEN ( SUM(meta_value) / COUNT( META_key ) ) > 0 THEN ( SUM(meta_value) / COUNT( META_key ) ) ELSE 0
				END as promedio
			 FROM wp_comments as c
				left join wp_commentmeta as cm ON cm.comment_id = c.comment_ID 
			 WHERE cm.meta_value > 0 AND c.user_id = {$user_id}
		";
		return $this->db->get_row($sql);
	}
}
