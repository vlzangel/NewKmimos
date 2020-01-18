<?php
	global $vlz; $S = DIRECTORY_SEPARATOR;
	$path_functions = dirname(__DIR__).$S."modulos";
	$directorio = opendir( $path_functions );
	while ($archivo = readdir($directorio)) {
		$excluir = ['.', '..'];
		if( !in_array($archivo, $excluir) ){
	        $ruta = $path_functions.$S.$archivo.$S."config.php";
	        extract( get_data_modulo( $archivo ) );
		    if ( file_exists($ruta) ) { include $ruta; }
		    include dirname(__DIR__).$S.'base.php';
		}
	}

	function get_data_modulo($modulo_name, $params = []){
		$S = DIRECTORY_SEPARATOR;
		$dir_modulo = dirname(__DIR__).$S.'modulos'.$S.$modulo_name.$S;
		$name_modulo = $modulo_name;
		$sub_modulo = "";
		$titulo_modulo = ucfirst($name_modulo);
		$titulo_modulo_singular = substr($titulo_modulo, 0, -1);
		$genero = substr($titulo_modulo, -2, -1);
		extract($params);
		return [ 'dir' => $dir_modulo, 'mod' => $name_modulo, 'sub' => $sub_modulo, 'plu' => $titulo_modulo, 'sin' => $titulo_modulo_singular, 'gen' => $genero, ];
	}

	function get_campos_db($tabla){
		global $wpdb; $sql = "SELECT COLUMN_NAME AS campo, DATA_TYPE AS tipo FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '".$tabla."' AND ( COLUMN_NAME NOT IN ( 'id', 'data', 'creado' ) ) ";
		$info = $wpdb->get_results($sql); $columnas = [];
		foreach ($info as $key => $value) { $columnas[] = $value->campo; }
		return $columnas;
	}

	function add_ajax($a, $params, $is_login = true){
		extract($params);
		if( $is_login ){
			_add_ajax($a, $params);
		}else{
			_add_ajax($a, $params); _add_ajax($a, $params, false);
		}
	}

	function _add_ajax($a, $params, $is_login = true){
		extract($params);
		$nopriv = ( $is_login ) ? '' : 'nopriv_';
		$dir = str_replace("\\", "/", $dir);
		eval('add_action( "wp_ajax_'.$nopriv.'vlz_'.$mod.'_ajax_'.$a.'", function(){
			global $wpdb; global $vlz;
			extract($vlz); extract($_GET); extract($_POST); 
			$m = [ "gen" => "'.$gen.'", "mod" => "'.$mod.'", "sub" => "'.$sub.'", "plu" => "'.$plu.'", "sin" => "'.$sin.'" ];
			extract($m);
			$tabla_name = "{$vlzpf}{$mod}"; 
			$campos_db = get_campos_db( $tabla_name );
			if( file_exists(  "'.$dir.'ajax/front/{$a}.php" ) ){
				include "'.$dir.'ajax/front/{$a}.php";
			}else{
				echo "El archivo [ {$a}.php ] no se encuentra en la carpeta [ '.$dir.'modulos/'.$mod.'/ajax/front ] ";
			}
			die();
		} );');
	}

	function initMenu($params){
		foreach ($params as $key => $menu) {
			add_menu_page( $menu['tit'], $menu['tit'], 'manage_options', 'vlz_'.$menu['mod'].'_page', 'vlz_'.$menu['mod'].'_page', $menu['ico'], 4);
			foreach ($menu['sub'] as $key => $submenu) {
				add_submenu_page( 'vlz_'.$menu['mod'].'_page', $submenu['tit'], $submenu['tit'], 'manage_options', 'vlz_'.$submenu['mod'].'_page', 'vlz_'.$submenu['mod'].'_page');
				// add_submenu_page( 'edit.php?post_type=page', 'wp-menu-separator', '', 'vlz_'.$menu['mod'].'_page', '11', '' );
			}
		}
	}
?>