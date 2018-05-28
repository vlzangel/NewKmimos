<?php
 
require_once (dirname(dirname(__DIR__))."/conf/database.php");

class general extends db{

	// -- Enviar solicitud
	public function request( $url, $data ){

		if( !class_exists('Requests') ){
			require_once (dirname(dirname(__DIR__))."/recursos/Requests/Requests.php");
			Requests::register_autoloader();
		}
		$headers = Array(
			'Content-Type'=> 'application/json; charset=UTF-8',	
			'Accept'=>'application/json'
		);
		$request = Requests::get($url, $headers,  $data );

		return (isset($request->body))? $request->body : '' ;
	}

	public function get_plataforma( $where='' ){
		$where = ( !empty($where) )? ' AND '.$where : '' ;
		return $this->select("select * from monitor_plataforma where estatus = 1 {$where}");
	}

	public function get_html_menu_plataformas(){
		$grupo = [];
		$por_sucursal = '';
		$por_grupo = '';
		$plataformas = $this->get_plataforma();
		foreach ($plataformas as $plataforma) {
			                
			$por_sucursal .= '<li><a href="javascript:;" data-label="'.$plataforma['descripcion'].'" data-action="byname.'.$plataforma['name'].'">'.$plataforma['descripcion'].'</a></li>';

			if( !in_array($plataforma['grupo'] , $grupo) ) {
			    $por_grupo .= '<li><a href="javascript:;" data-label="'.$plataforma['grupo'].'" data-action="bygroup.'.$plataforma['grupo'].'">'.ucfirst($plataforma['grupo']).'</a></li>';
			    $grupo[] = $plataforma['grupo'];
			}

		}
		return [ 'grupo'=>$por_grupo, 'sucursal'=> $por_sucursal ];
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


	public function get_usuarios($desde="", $hasta="", $where=' c.id is null '){
		$sql = "
			SELECT 
				u.ID,
				u.user_email,
				DATE_FORMAT(u.user_registered,'%Y-%m-%d') as user_registered, 
				c.id as cuidador_id,
				m.meta_value as user_referred
			FROM wp_users as u
				LEFT JOIN cuidadores as c ON c.user_id = u.ID
				LEFT JOIN wp_usermeta as m ON m.user_id = u.ID and m.meta_key = 'user_referred'
			WHERE {$where} and (u.user_registered >= '{$desde} 00:00:00' 
					and  u.user_registered  <= '{$hasta} 23:59:59')
		";

		$result = $this->select($sql);
		return $result;
	}

	public function getMetaUsuario( $user_id ){ 
		$condicion = " AND meta_key IN ( 'user_gender', 'user_age', 'user_referred' )";
		$result = $this->get_metaUser($user_id, $condicion);
		$data = [
			'first_name' =>'', 
			'last_name' =>'', 
			'user_referred' =>'', 
		];
		if( !empty($result) ){
			foreach ($result as $row) {
				$data[$row['meta_key']] = utf8_encode( $row['meta_value'] );
				//$data['cliente_nombre'] = utf8_encode( $row['meta_value'] );
			}
		}
		$data = $this->merge_phone($data);
		return $data;
	}	

	public function get_metaUser($user_id=0, $condicion=''){
		$sql = "
			SELECT u.user_email, m.*
			FROM wp_users as u 
				INNER JOIN wp_usermeta as m ON m.user_id = u.ID
			WHERE 
				m.user_id = {$user_id} 
				{$condicion}
		";
		$result = $this->select($sql);
		return $result;	
	}

}