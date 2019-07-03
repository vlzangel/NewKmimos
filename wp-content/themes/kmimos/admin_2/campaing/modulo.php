<?php

	add_action( 'init', function() {
	    register_post_type( 
	    	'campaing', 
	    	get_attrs_cpt(
	    		'Campaña',
	    		[ 
	    			"genero" => 'a', 
	    			"secciones" => [ 'title', 'editor' ], 
	    		]
	    	) 
	    );

	    registrar_taxonomia('campaing', no_especiales('Lista') );
	});

	add_action( 'admin_enqueue_scripts', function() {
		global $vlz_globals;
		$modulo = "campaing";
	    wp_enqueue_style( 'vlz_campaing', $vlz_globals['base'].'/'.$modulo.'/css.css', array(), "1.0.0" );
	    wp_enqueue_script( 'vlz_campaing-js', $vlz_globals['base'].'/'.$modulo.'/js.js', array('jquery'), "1.0.0" );
	} );

	add_action( 'add_meta_boxes', function(){  
		add_meta_box( 
			'vlz_metabox_campaing', 
			'Email de Purebas', 
			function($post){
				$values = get_post_custom( $post->ID );
				$vlz_email_test = isset( $values['vlz_email_test'] ) ? esc_attr( $values['vlz_email_test'][0] ) : '';
				wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
				echo '
					<div id="sub_form_test">
						<input type="hidden" id="ID" name="ID" value="'.$post->ID.'" />
						<div class="">
							<label for="vlz_email_test" style="font-weight: 600;" >Email de prueba</label>
							<input type="text" class="form-control" style="width: 100%; margin: 5px 0px 10px;" name="vlz_email_test" id="vlz_email_test" value="'.$vlz_email_test.'" />
					    </div>
						<div class="btn_container">
							<input type="button" class="button button-primary button-large btn-right" value="Enviar" onClick="sendTest()" />
					    </div>
				    </div>
				    <script>
				    	function sendTest(){
				    		var campos = [];
				    		jQuery("#sub_form_test input").each(function(i, v){
				    			var temp = jQuery(this).attr("id");
				    			if( temp != undefined ){
				    				campos.push( [ temp, jQuery(this).val() ] );
				    			}
				    		});
				    		

				    		jQuery.post(
				    			"'.admin_url('admin-ajax.php').'?action=vlz_campaing_test_email",
				    			{
				    				info: campos
				    			},
				    			function(data){
				    				console.log( data );
				    				}, "json"
				    		);
				    	}
				    </script>
				';
			},
			'campaing',
			'side'
		);  
	});
	
	add_action( 'wp_ajax_vlz_campaing_test_email', function() {
		extract($_POST);
		global $wpdb;

		$data = [];
		foreach ($info as $key => $value) {
			$data[ $value[0] ] = $value[1];
		}
		
		
		$post = $wpdb->get_row("SELECT * FROM wp_posts WHERE ID = ".$data[ "ID" ]);

		$email_html = "<div style='font-family: Verdana; width: 600px; margin: 0px auto;'>".$post->post_content."</div>";
		
		wp_mail( $data[ "vlz_email_test" ], $post->post_title, $email_html );
		

	    echo json_encode([
	    	"_POST" => $_POST,
	    	"data" => $data,
	    ]);
	    
	   	die();
	} );
	
?>