<?php
	global $MODULOS_ADMIN_2;

	$MODULOS_ADMIN_2[] = array(
        'parent'        =>  '',
        'title'         =>  __('Campaing'),
        'short-title'   =>  __('Campaing'),
        'access'        =>  'manage_options',
        'slug'          =>  'campaing',
        'modulo'        =>  function(){
        	init_page( 'campaing' );
        },
        'icon'          =>  '',
        'position'      =>  4,
    );

	add_action( 'wp_ajax_vlz_campaing_list', function() {
		extract($_POST);
		global $wpdb;
		
		$data["data"] = [];
		
		$info = $wpdb->get_results("SELECT * FROM vlz_campaing ORDER BY creada DESC");

		$_listas = $wpdb->get_results("SELECT * FROM vlz_listas ORDER BY creada DESC");
		$listas = [];
		foreach ($_listas as $key => $lista) {
			$d = json_decode($lista->data);
			$listas[ $lista->id ] = $d->data->titulo;
		}
		
		foreach ($info as $key => $value) {
			$d = json_decode($value->data);

			$temp = [];
			foreach ($d->data_listas as $_key => $_value) {
				$temp[] = $listas[ $_value ];
			}

			$data["data"][] = [
				$value->id,
				$d->data->titulo,
				implode("<br>", $temp),
				'
					<span class="btn btn-primary btn-s" onclick="_edit( jQuery(this) )" data-id="'.$value->id.'" data-modal="campaing_edit" data-titulo="Editar Campaña" >Editar</span> &nbsp;
					<span class="btn btn-danger btn-s" onclick="_del_form( jQuery(this) )" data-id="'.$value->id.'" data-modal="campaing_del_form" data-titulo="Eliminar Campaña" >Eliminar</span>
				'
			];
		}

		echo json_encode($data);

	   	die();
	} );

    function get_campaing_form($info, $action = 'insert'){
		global $wpdb;
		$btn = 'Crear';
		if( $action == 'update' ){
			$input_id = '<input type="hidden" name="id" value="'.$info->id.'" />';
			$info = (array)  json_decode($info->data);
			extract($info);
			$btn = 'Actualizar';
		}

		$_listas = $wpdb->get_results("SELECT * FROM vlz_listas ORDER BY creada DESC");
		$listas = '';
		foreach ($_listas as $key => $lista) {
			$d = json_decode($lista->data);
			if( $action == 'update' ){
				$selected = ( in_array($lista->id, $data_listas) ) ? 'selected' : '';
			}else{
				$selected = '';
			}
			$listas .= '<option value="'.$lista->id.'" '.$selected.' >'.$d->data->titulo.'</option>';
		}
		/*
		echo "<pre>";
			print_r($info);
		echo "</pre>";
		*/
		echo '
			<form id="campaing_form" data-modulo="campaing" >
				'.$input_id.'
				<div class="form-group">
					<label for="titulo">Nombre de la Campaña</label>
					<input type="text" class="form-control" id="titulo" name="data[titulo]" placeholder="Titulo de la Campaña" value="'.$data->titulo.'" />
				</div>
				<div class="form-group">
					<label for="plantilla">Plantilla</label>
					<textarea id="contenido" name="data[plantilla]" class="form-control" placeholder="Contenido de Email">'.$data->plantilla.'</textarea>
				</div>
				<div class="form-group">
					<label for="listas">Listas</label>
					<select id="listas" name="data_listas[]" multiple class="form-control">
						'.$listas.'
					</select>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="fecha">Fecha</label>
							<input type="date" id="fecha" name="data[fecha]" class="form-control" >
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="hora">Hora</label>
							<input type="time" id="hora" name="data[hora]" class="form-control" >
						</div>
					</div>
				</div>
				<div class="text-right">
					<button id="btn_submit_modal" type="submit" class="btn btn-primary">'.$btn.'</button>
				</div>
			</form>
			<script>
				_'.$action.'("campaing_form");
				if (editor) { editor.destroy(); }
	            var editor = new FroalaEditor("#contenido", {
	                heightMin: 200,
	                heightMax: 200,
	                imageUploadURL: ADMIN_AJAX+"?action=vlz_campaing_uploads_list",
	                imageUploadParams: {
	                    id: "contenido"
	                },

					imageUploadMethod: "POST",
					imageMaxSize: 5 * 1024 * 1024,
					imageAllowedTypes: ["jpeg", "jpg", "png"],

					events: {
						"image.beforeUpload": function (images) {
							// Return false if you want to stop the image upload.
						},
						"image.uploaded": function (response) {
							// Image was uploaded to the server.
						},
						"image.inserted": function ($img, response) {
							// Image was inserted in the editor.
						},
						"image.replaced": function ($img, response) {
							// Image was replaced in the editor.
						},
						"image.error": function (error, response) {
							// Bad link.
						if (error.code == 1) { ... }

							// No link in upload response.
						else if (error.code == 2) { ... }

							// Error during image upload.
						else if (error.code == 3) { ... }

							// Parsing response failed.
						else if (error.code == 4) { ... }

							// Image too text-large.
						else if (error.code == 5) { ... }

							// Invalid image type.
						else if (error.code == 6) { ... }

							// Image can be uploaded only to same domain in IE 8 and IE 9.
						else if (error.code == 7) { ... }

							// Response contains the original server response to the request if available.
						}
					}
	            });
			</script>
		';
    }

	add_action( 'wp_ajax_vlz_campaing_new', function() {
		extract($_POST);
		global $wpdb;
		get_campaing_form([]);
	   	die();
	} );

	add_action( 'wp_ajax_vlz_campaing_uploads_list', function() {
		extract($_POST);
		global $wpdb;


		echo json_encode([
			"POST" => $_POST,
			"FILES" => $_FILES,
		]);
	   	die();
	} );

	add_action( 'wp_ajax_vlz_campaing_edit', function() {
		extract($_POST);
		global $wpdb;
		$data = $wpdb->get_row("SELECT * FROM vlz_campaing WHERE id = ".$ID);
		get_campaing_form($data, 'update');
	   	die();
	} );

	add_action( 'wp_ajax_vlz_campaing_del_form', function() {
		extract($_POST);
		global $wpdb;
		$data = $wpdb->get_row("SELECT * FROM vlz_campaing WHERE id = ".$ID);
		$data = (array)  json_decode($data->data);
		extract($data);
		echo '
			<form id="campaing_form" data-modulo="campaing" >
				<input type="hidden" name="id" value="'.$ID.'" />
				<div class="form-group">
					<label for="titulo">¿Esta seguro de eliminar esta Campaña?</label>
					<input type="text" class="form-control" id="titulo" value="'.$data->titulo.'" readonly />
				</div>
				<div class="text-right">
					<button id="btn_submit_modal" type="submit" class="btn btn-primary">Eliminar</button>
				</div>
			</form>
			<script>_delete("campaing_form");</script>
		';
	   	die();
	} );

	add_action( 'wp_ajax_vlz_campaing_insert', function() {
		extract($_POST);
		global $wpdb;
		$titulo = $data["titulo"];
		$existe = $wpdb->get_var("SELECT id FROM vlz_campaing WHERE data LIKE '%\"titulo\":\"{$titulo}\"%' ");
		if( empty($existe) ){
			$data = json_encode($_POST);
			$_POST["data"]["plantilla"] = preg_replace("/[\r\n|\n|\r]+/", " ", $_POST["data"]["plantilla"]);
			$wpdb->query("INSERT INTO vlz_campaing VALUES (NULL, '{$data}', NOW())");
			echo json_encode([
				"error" => "",
				"msg" => "Campaña Creada Exitosamente",
			]);
		}else{
			echo json_encode([
				"error" => "Ya existe una campaña con este nombre",
			]);
		}
	   	die();
	} );

	add_action( 'wp_ajax_vlz_campaing_update', function() {
		extract($_POST);
		global $wpdb;
		$titulo = $data["titulo"];
		$existe = $wpdb->get_var("SELECT id FROM vlz_campaing WHERE data LIKE '%\"titulo\":\"{$titulo}\"%' AND id != ".$id);
		if( empty($existe) ){
			$_POST["data"]["plantilla"] = preg_replace("/[\r\n|\n|\r]+/", " ", $_POST["data"]["plantilla"]);
			
			$data = json_encode($_POST);
			$sql = "UPDATE vlz_campaing SET data = '{$data}' WHERE id = ".$id;
			$wpdb->query( $sql );
			echo json_encode([
				"error" => "",
				"msg" => "Campaña Actualizada Exitosamente",
			]);
		}else{
			echo json_encode([
				"error" => "Ya existe una campaña con este nombre",
				"msg" => "",
			]);
		}
	   	die();
	} );

	add_action( 'wp_ajax_vlz_campaing_delete', function() {
		extract($_POST);
		global $wpdb;
		$wpdb->query("DELETE FROM vlz_campaing WHERE id = ".$id);
		echo json_encode([
			"error" => "",
			"msg" => "Campaña Eliminada Exitosamente",
		]);
	   	die();
	} );















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

















	/*

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
	*/
	
?>