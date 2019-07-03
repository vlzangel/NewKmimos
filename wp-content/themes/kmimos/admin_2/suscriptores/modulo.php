<?php
	/*
	add_action( 'init', function() {
	    register_post_type( 'listas', get_attrs_cpt('Lista', [
	    	"secciones" => [ 'title' ],
	    	"genero" => 'a'
	    ]) );
	});

	add_action( 'admin_enqueue_scripts', function() {
		global $vlz_globals;
		$modulo = "listas";
	    wp_enqueue_style( 'vlz_suscriptores', $vlz_globals['base'].'/'.$modulo.'/css.css', array(), "1.0.0" );
	    wp_enqueue_script( 'vlz_suscriptores-js', $vlz_globals['base'].'/'.$modulo.'/js.js', array('jquery'), "1.0.0" );
	} );

	add_action( 'add_meta_boxes', function(){  
		/*
		add_meta_box( 
			'vlz_metabox_suscriptores', 
			'Datos de la campaña', 
			function($post){
				$values = get_post_custom( $post->ID );
				$vlz_cliente_testimonio = isset( $values['vlz_cliente_testimonio'] ) ? esc_attr( $values['vlz_cliente_testimonio'][0] ) : '';
				$vlz_cargo_testimonio = isset( $values['vlz_cargo_testimonio'] ) ? esc_attr( $values['vlz_cargo_testimonio'][0] ) : '';
				wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
				echo '
				    <div class="form-group">
						<label for="vlz_cliente_testimonio" style="font-weight: 600;" >Cliente</label>
						<input type="text" class="form-control" style="width: 100%; margin: 5px 0px 10px;" name="vlz_cliente_testimonio" id="vlz_cliente_testimonio" value="'.$vlz_cliente_testimonio.'" />
				    </div>
				    <div class="form-group">
						<label for="vlz_cargo_testimonio" style="font-weight: 600;" >Cargo</label>
						<input type="text" class="form-control" style="width: 100%; margin: 5px 0px 10px;" name="vlz_cargo_testimonio" id="vlz_cargo_testimonio" value="'.$vlz_cargo_testimonio.'" />
				    </div>
				';
			},
			'listas'
		);  
	});
		*/
	
?>