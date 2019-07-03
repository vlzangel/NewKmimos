<?php
	function get_attrs_cpt($name_singular, $params = []){
		extract($params);

		if( !isset($secciones) ){
			$secciones = [ 'title', 'editor', 'thumbnail' ];
		}

		if( !isset($genero) ){
			$genero = 'o';
		}

		if( !isset($name_plural) ){
			$name_plural = null;
		}

		$name_plural = ( $name_plural == null ) ? $name_singular.'s': $name_plural;
		$labels = array(
			'name' => _x( $name_plural, 'post type general name' ),
	        'singular_name' => _x( $name_singular, 'post type singular name' ),
	        'add_new' => _x( 'Añadir nuev'.$genero, 'book' ),
	        'add_new_item' => __( 'Añadir nuev'.$genero.' '.$name_singular ),
	        'edit_item' => __( 'Editar '.$name_singular ),
	        'new_item' => __( 'Nuev'.$genero.' '.$name_singular ),
	        'view_item' => __( 'Ver '.$name_singular ),
	        'search_items' => __( 'Buscar '.$name_plural ),
	        'not_found' =>  __( 'No se han encontrado '.$name_plural.'' ),
	        'not_found_in_trash' => __( 'No se han encontrado '.$name_plural.' en la papelera' ),
	        'parent_item_colon' => ''
	    );
	    $args = array( 
	    	'labels' => $labels,
	        'public' => true,
	        'publicly_queryable' => true,
	        'show_ui' => true,
	        'query_var' => true,
	        'rewrite' => true,
	        'capability_type' => 'post',
	        'hierarchical' => false,
	        'menu_position' => null,
	        'supports' => $secciones // array( 'title', 'editor', 'thumbnail', /* 'author', 'excerpt', 'comments' */ )
	    );
	    return $args;
	}

	function registrar_taxonomia($post_type, $name_singular, $name_plural = null){
		$name_plural = ( $name_plural == null ) ? $name_singular.'s': $name_plural;
		$labels = array(
			'name' => _x( $name_plural, 'taxonomy general name' ),
			'singular_name' => _x( $name_singular, 'taxonomy singular name' ),
			'search_items' =>  __( 'Buscar '.$name_plural ),
			'popular_items' => __( $name_plural.' populares' ),
			'all_items' => __( 'Todos los '.strtolower($name_plural) ),
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => __( 'Editar '.$name_singular ),
			'update_item' => __( 'Actualizar '.$name_singular ),
			'add_new_item' => __( 'Añadir nuevo '.$name_singular ),
			'new_item_name' => __( 'Nombre del nuevo '.$name_singular ),
			'separate_items_with_commas' => __( 'Separar '.$name_plural.' por comas' ),
			'add_or_remove_items' => __( 'Añadir o eliminar '.$name_plural ),
			'choose_from_most_used' => __( 'Escoger entre los '.$name_plural.' más utilizados' )
		);
		register_taxonomy( 
			strtolower($name_singular), 
			strtolower($post_type), 
			array(
				'hierarchical' => false,
				'labels' => $labels,
				'show_ui' => true,
				'query_var' => true,
				'rewrite' => array( 
					'slug' => strtolower($name_singular) 
				),
			)
		);
	}

	function no_especiales($string){
	    $string = trim($string);
	    $string = str_replace( array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string ); 
	    $string = str_replace( array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string );
	    $string = str_replace( array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string );
	    $string = str_replace( array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string );
	    $string = str_replace( array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string );
	    $string = str_replace( array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C',), $string );
	    $string = str_replace(
	        array("\\", "¨", "º", "-", "~",
	             "#", "@", "|", "!", "\"",
	             "·", "$", "%", "&", "/",
	             "(", ")", "?", "'", "¡",
	             "¿", "[", "^", "<code>", "]",
	             "+", "}", "{", "¨", "´",
	             ">", "< ", ";", ",", ":",
	             ".", " "),
	        ' ',
	        $string
	    );
		return $string;
	} 
	 
?>