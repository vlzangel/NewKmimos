<?php

	session_start();

	if( !function_exists('get_ayuda_secciones') ){
		include ( '../../../../../wp-load.php' );
	}

	if( $_POST && isset($_POST['nombre']) && !empty($_POST['nombre']) ){
		global $wpdb;

/*		$ignore_keywords = ["a","ante","bajo","cabe","con","contra","de","desde","durante","en","entre","hacia","hasta","mediante","para","por","según","sin","so","sobre","tras","versus","vía", "la"];
		$keywords = explode(" ", $_POST['nombre']);
		$where = '';
		foreach( $keywords as $keyword ){
			if( !in_array($keyword, $ignore_keywords) ){
				$condicional = ( !empty($where) )? ' or ' : '' ;
				$where .= $condicional . " post_title like '%$keyword%'";
			}
		}
*/
		$where = " post_title like '%{$_POST['nombre']}%'";
		$sql = " select * from wp_posts where post_type = 'faq' and ( $where )";
 
 		$result = $wpdb->get_results($sql);

		$_SESSION['ayuda']['resultado'] = $result;
		$_SESSION['ayuda']['terminos'] = $_POST['nombre'];
		unset($_SESSION['ayuda']['filtro'] );

	}else{
		if( isset($_SESSION['ayuda']['filtro']) && !empty($_SESSION['ayuda']['filtro']) ){

		}else{
			$secciones = get_ayuda_secciones() ;
			$_SESSION['ayuda']['filtro'] = $secciones;
		}
	}
	if( !isset($redirect) || !$redirect ) {
		header("location: ".get_home_url()."/ayuda-general/");
	}