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
			$listas[ $lista->id ] = $d->titulo;
		}
		foreach ($info as $key => $value) {
			$d = json_decode($value->data);
			if( isset($d->data_listas) ){
				$temp = [];
				foreach ($d->data_listas as $_key => $_value) {
					$temp[] = $listas[ $_value ];
				}
				$temp = implode("<br>", $temp);
			}else{
				$cam = $wpdb->get_row("SELECT * FROM vlz_campaing WHERE id = ".$d->campaing_anterior);
				$_d = json_decode($cam->data);
				$se_abre = ( $d->campaing_despues_no_abre == 'si' ) ? 'Se abre el correo' : 'No se abre el correo';
				$temp = "Enviar después de la campaña: <b>[ {$_d->data->titulo} ]</b> en <b>[ {$d->campaing_despues_delay} ]</b> días si <b>[ {$se_abre} ]</b>";
			}
			$data["data"][] = [
				$value->id,
				$d->data->titulo,
				$temp,
				'
					<span class="btn btn-primary btn-s" onclick="_modal( jQuery(this) )" data-id="'.$value->id.'" data-modal="campaing_test" data-titulo="Envio de Prueba" >Test</span> &nbsp;
					<span class="btn btn-primary btn-s" onclick="_modal( jQuery(this) )" data-id="'.$value->id.'" data-modal="campaing_edit" data-titulo="Editar Campaña" >Editar</span> &nbsp;
					<span class="btn btn-danger  btn-s" onclick="_modal( jQuery(this) )" data-id="'.$value->id.'" data-modal="campaing_del_form" data-titulo="Eliminar Campaña" >Eliminar</span>
				'
			];
		}
		echo json_encode($data);
	   	die();
	} );

	function get_despues(){
		return [
			0 => "No hacer nada",
			1 => "Enviar otra campaña",
		];
	}

	function get_hacer_despues(){
		return [
			0 => "Campaña padre (Inicio de Flujo)",
			1 => "Enviar después de otra campaña",
		];
	}

	add_action( 'wp_ajax_vlz_campaing_test', function() {
		extract($_POST);
		global $wpdb;
		
		echo '
			<form id="listas_form" data-modulo="campaing" >
				<input type="hidden" name="form" value="modal" />
				<input type="hidden" name="id" value="'.$ID.'" />
				<div class="form-group">
					<label for="email">Email</label>
					<input type="text" class="form-control" id="email" name="email" />
				</div>
				<div class="text-right">
					<button id="btn_submit_modal" type="submit" class="btn btn-primary">Enviar</button>
				</div>
			</form>
			<script>_test("listas_form");</script>
		';
	   	die();
	} );

	add_action( 'wp_ajax_vlz_campaing_test_send', function() {
		extract($_POST);
		global $wpdb;
		$campaing = $wpdb->get_row("SELECT * FROM vlz_campaing WHERE id = ".$id);
		$d = json_decode($campaing->data);
		
		$info_validacion = base64_encode( json_encode( [
			"id" => $campaing->id,
			"type" => "img",
			"format" => "png",
			"email" => $email
		] ) );

		$d->data->plantilla = str_replace('<p data-f-id="pbf" style="text-align: center; font-size: 14px; margin-top: 30px; opacity: 0.65; font-family: sans-serif;">Powered by <a href="https://www.froala.com/wysiwyg-editor?pb=1" title=""></a></p>', '', $d->data->plantilla);
		$mensaje = $d->data->plantilla.'<img src="'.get_home_url().'/campaing_2/'.$info_validacion.'/'.md5($info_validacion).'.png" />';
		wp_mail( trim($email) , $d->data->asunto, $mensaje);

		echo json_encode([
			"error" => "",
			"msg" => "Mensaje Enviado Exitosamente!",
		]);

	   	die();
	} );

    function get_campaing_form($info, $action = 'insert'){
		global $wpdb;
		$btn = 'Crear'; $ID = 0;
		$data_listas = [];
		if( $action == 'update' ){
			$ID = $info->id;
			$input_id = '<input type="hidden" name="id" value="'.$info->id.'" />';
			$info = (array) json_decode($info->data);
			extract($info);
			$btn = 'Actualizar';
		}
		$_listas = $wpdb->get_results("SELECT * FROM vlz_listas ORDER BY creada DESC");
		$listas = '';
		$listas_despues = '';
		if( isset($data_listas) ){
			foreach ($_listas as $key => $lista) {
				$d = json_decode($lista->data);
				if( $action == 'update' ){
					$selected = ( in_array($lista->id, $data_listas) ) ? 'selected' : '';
				}else{
					$selected = '';
				}
				$listas .= '<option value="'.$lista->id.'" '.$selected.' >'.$d->titulo.'</option>';
			}
		}


		$_campaings = $wpdb->get_results("SELECT * FROM vlz_campaing ORDER BY creada DESC");
		$_campaings_options = '<option value="" >No enviar nada</option>';
		foreach ($_campaings as $key => $cam) {
			$d = json_decode($cam->data);
			$selected_despues = ( $cam->id == $info["campaing_anterior"] ) ? 'selected' : '';
			if( $action == 'update' && $cam->id == $ID ){}else{
				$_campaings_options .= '<option value="'.$cam->id.'" '.$selected_despues.' >'.$d->data->titulo.'</option>';
			}
		}

		$enviar_otra = ( $info["hacer_despues"]+0 == 1 ) ? '' : 'campaing_despues_hidden' ;
		$show_listas = ( $info["hacer_despues"]+0 == 1 ) ? 'campaing_despues_hidden' : '' ;
		
		$hacer_despues = ( !isset($hacer_despues) ) ? 0 : $hacer_despues;
		$opciones = get_hacer_despues();
		$_hacer_despues = '';
		foreach ($opciones as $key => $opcion) {
			$_hacer_despues .= '<option value="'.$key.'" '.selected($key, $hacer_despues, false).'>'.$opcion.'</option>';
		}

		$_vistos = ( empty($vistos) ) ? '{}' : json_encode($vistos, JSON_UNESCAPED_UNICODE);

		$_vistos = str_replace('"', '\"', $_vistos);

		echo '
			<form id="campaing_form" data-modulo="campaing" >
				'.$input_id.'

				<input type="hidden" id="vistos" name="vistos" value=\''.$_vistos.'\' />

				<div class="form-group">
					<label for="titulo">Nombre de la Campaña</label>
					<input type="text" class="form-control" id="titulo" name="data[titulo]" placeholder="Titulo de la Campaña" value="'.$data->titulo.'" required />
				</div>
				<div class="form-group">
					<label for="asunto">Asunto del Email</label>
					<input type="text" class="form-control" id="asunto" name="data[asunto]" placeholder="Asunto del Email" value="'.$data->asunto.'" required />
				</div>
				<div class="form-group">
					<label for="plantilla">Plantilla</label>
					<textarea id="contenido" name="data[plantilla]" class="form-control" placeholder="Contenido de Email">'.$data->plantilla.'</textarea>
				</div>

				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label for="hacer_despues">Hacer después de:</label>
							<select id="hacer_despues" name="hacer_despues" class="form-control" onchange="_hacer_despues( jQuery(this) )" required >
								'.$_hacer_despues.'
							</select>
						</div>
					</div>
					<div id="campaing_hacer_despues_div" class="col-md-12 '.$enviar_otra.'">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="campaing_anterior">Campaña:</label>
									<select id="campaing_anterior" name="campaing_anterior" class="form-control" >
										'.$_campaings_options.'
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="campaing_despues_delay">Esperar cuantos días:</label>
									<input type="number" id="campaing_despues_delay" name="campaing_despues_delay" class="form-control" value="'.$info["campaing_despues_delay"].'" />
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="campaing_despues_no_abre">Enviar si:</label>
									<select id="campaing_despues_no_abre" name="campaing_despues_no_abre" class="form-control" >
										<option value="" '.selected('', $info["campaing_despues_no_abre"], false).'>No hacer nada</option>
										<option value="si" '.selected('si', $info["campaing_despues_no_abre"], false).'>Se abre la campaña anterior</option>
										<option value="no" '.selected('no', $info["campaing_despues_no_abre"], false).'>NO  se abre la campaña anterior</option>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div id="listas_div" class="'.$show_listas.'">

					<div class="form-group">
						<label for="listas">Listas</label>
						<select id="listas" name="data_listas[]" data-name="data_listas[]" data-required="true" multiple class="form-control" required >
							'.$listas.'
						</select>
					</div>

					<div class="row">
						<div class="col-md-12">
							<label>Desde</label>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<input type="date" id="fecha" name="data[fecha]" data-name="data[fecha]" data-required="true" class="form-control" value="'.$data->fecha.'" required >
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<input type="time" id="hora" name="data[hora]" data-name="data[hora]" data-required="true" class="form-control" value="'.$data->hora.'" required >
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<label>Hasta</label>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<input type="date" id="fecha_fin" name="data[fecha_fin]" data-name="data[fecha_fin]" data-required="false" class="form-control" value="'.$data->fecha_fin.'" >
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<input type="time" id="hora_fin" name="data[hora_fin]" data-name="data[hora_fin]" data-required="false" class="form-control" value="'.$data->hora_fin.'" >
							</div>
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
	                heightMin: 400,
	                heightMax: 410,
	                imageManagerLoadURL: ADMIN_AJAX+"?action=vlz_campaing_image_manager",
	                imageManagerDeleteURL: ADMIN_AJAX+"?action=vlz_campaing_image_delete",
	                imageManagerDeleteMethod: "POST",
	                imageUploadURL: ADMIN_AJAX+"?action=vlz_campaing_uploads",
	                imageUploadParams: {
	                    id: "contenido"
	                },
					imageUploadMethod: "POST",
					imageMaxSize: 5 * 1024 * 1024,
					imageAllowedTypes: ["jpeg", "jpg", "png", "gif"]
	            });
	            _verificar_names();
			</script>
		';
    }

	add_action( 'wp_ajax_vlz_campaing_new', function() {
		extract($_POST);
		global $wpdb;
		get_campaing_form([]);
	   	die();
	} );
	
	add_action( 'wp_ajax_vlz_campaing_image_manager', function() {
		extract($_POST);
		global $wpdb;
		$_directorio = [];
		$uploadFileDir = __DIR__.'/files/';
		$directorio = opendir( $uploadFileDir );
		while ($archivo = readdir($directorio)) {
		    if (is_dir($archivo)) { } else {
		        if( $archivo != "mini" ){
			        $_directorio[] = [
					    "url" => getTema()."/admin_2/campaing/files/".$archivo,
					    "thumb" => getTema()."/admin_2/campaing/files/mini/".$archivo,
					    "tag" => ''
			        ];
		        }
		    }
		}
		echo json_encode( $_directorio );
	   	die();
	} );
	
	add_action( 'wp_ajax_vlz_campaing_image_delete', function() {
		extract($_POST);
		global $wpdb;

		$url = getTema()."/admin_2/campaing/files/mini/";
		$url = str_replace("/", "%2F", $url);
		$url = str_replace(":", "%3A", $url);

		$_POST["src"] = str_replace($url, "", $_POST["src"]);

		extract( $_POST );

		$path = __DIR__.'/files/';

		unlink( $path.$src );
		unlink( $path."mini/".$src );

		echo json_encode( $_POST );
	   	die();
	} );
	
	add_action( 'wp_ajax_vlz_campaing_uploads', function() {

		extract($_POST);
		global $wpdb;


		$fileTmpPath = $_FILES['file']['tmp_name'];
		$fileName = $_FILES['file']['name'];
		$fileSize = $_FILES['file']['size'];
		$fileType = $_FILES['file']['type'];
		$fileNameCmps = explode(".", $fileName);
		$fileExtension = strtolower(end($fileNameCmps));

		$newFileName = md5( $fileName ).".".$fileExtension;

		$uploadFileDir = __DIR__.'/files/';
		$dest_path = $uploadFileDir.$newFileName;
		$dest_path_thumb = $uploadFileDir."mini/".$newFileName;

		if(move_uploaded_file($fileTmpPath, $dest_path)){

			$sExt = @mime_content_type( $dest_path );
		    switch( $sExt ) {
		        case 'image/jpeg':
		            $aImage = @imageCreateFromJpeg( $dest_path );
		        break;
		        case 'image/gif':
		            $aImage = @imageCreateFromGif( $dest_path );
		        break;
		        case 'image/png':
		            $aImage = @imageCreateFromPng( $dest_path );
		        break;
		        case 'image/wbmp':
		            $aImage = @imageCreateFromWbmp( $dest_path );
		        break;
		    }
		    $nWidth  = 400;
		    $nHeight = 300;
		    $aSize = @getImageSize( $dest_path );
		    if( $aSize[0] > $aSize[1] ){
		        $nHeight = round( ( $aSize[1] * $nWidth ) / $aSize[0] );
		    }else{
		        $nWidth = round( ( $aSize[0] * $nHeight ) / $aSize[1] );
		    }
		    $aThumb = @imageCreateTrueColor( $nWidth, $nHeight );
		    @imageCopyResampled( 
		        $aThumb, $aImage, 
		        0, 0, 
		        0, 0, 
		        $nWidth, $nHeight, 
		        $aSize[0], $aSize[1] 
		    );

		    @imagejpeg( $aThumb, $dest_path_thumb );
		    @imageDestroy( $aImage ); 
		    @imageDestroy( $aThumb );

			echo json_encode([
				"link" => getTema()."/admin_2/campaing/files/".$newFileName,
			]);
		} else {
			echo json_encode([
				"error" => "Error subiendo la imagen",
			]);
		}

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
			$_POST["vistos"] = json_decode( $_POST["vistos"] );
			$_POST["data"]["plantilla"] = preg_replace("/[\r\n|\n|\r]+/", " ", $_POST["data"]["plantilla"]);
			$_POST["data"]["plantilla"] = preg_replace('#<p data(.*?)/p>#', '', $_POST["data"]["plantilla"]);
			$data = json_encode($_POST, JSON_UNESCAPED_UNICODE);
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

			// $_POST["vistos"] = str_replace("[", "{", $_POST["vistos"]);
			// $_POST["vistos"] = str_replace("]", "}", $_POST["vistos"]);
			$_POST["vistos"] = ( $_POST["vistos"] );

			$_POST["data"]["plantilla"] = preg_replace("/[\r\n|\n|\r]+/", " ", $_POST["data"]["plantilla"]);
			$_POST["data"]["plantilla"] = str_replace("Froala Editor", "", $_POST["data"]["plantilla"]);

			$_POST["data"]["plantilla"] = preg_replace('#<p data(.*?)/p>#', '', $_POST["data"]["plantilla"]);
			
			$data = json_encode($_POST, JSON_UNESCAPED_UNICODE);
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