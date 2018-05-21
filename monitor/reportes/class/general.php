<?php

error_reporting(E_ERROR | E_WARNING | E_PARSE);

require_once (dirname(dirname(__DIR__))."/conf/database.php");

class general extends db{

	public function get_plataforma( $where='' ){
		$where = ( !empty($where) )? ' AND '.$where : '' ;
		return $this->select("select * from monitor_plataforma where estatus = 1 {$where}");
	}

	public function getMeses(){
		$meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
		return $meses;
	}

	public function countUserAttr( $list ){

		$count = [];
		foreach ($list as $val) {
			foreach ($val as $key => $item) {
				$key = strtolower($key);
				$item = strtolower(str_replace('/', '', $item));

				if( !isset($count[$key]) ){
					$count[$key] = [];
				}
				if( isset($count[$key][$item]) ){
					$count[$key][$item] += 1; 
				}else{
					$count[$key][$item] = 1; 
				}
			}
		}
		return $count;
	}


}