<?php
	global $MODULOS_ADMIN_2;

	$MODULOS_ADMIN_2[] = array(
        'parent'        =>  'campaing',
        'title'         =>  __('listas'),
        'short-title'   =>  __('listas'),
        'access'        =>  'manage_options',
        'slug'          =>  'listas',
        'modulo'        =>  function(){
        	init_page( 'listas' );
        }
    );

    function get_listas_form($data, $action = 'insert'){
		global $wpdb;
		$btn = 'Crear';
		if( $action == 'update' ){
			$input_id = '<input type="hidden" name="id" value="'.$data->id.'" />';
			$data = (array)  json_decode($data->data);
			extract($data);
			$data->suscriptores = str_replace(",", "\n", $data->suscriptores);
			$btn = 'Actualizar';
		}
		echo '
			<form id="listas_form" data-modulo="listas" >
				'.$input_id.'
				<div class="form-group">
					<label for="titulo">Nombre de la lista</label>
					<input type="text" class="form-control" id="titulo" name="data[titulo]" value="'.$data->titulo.'" placeholder="Titulo de la Campaña">
				</div>
				<div class="form-group">
					<label for="suscriptores">Suscriptores</label>
					<textarea id="suscriptores" name="data[suscriptores]" class="form-control" placeholder="listas de emails suscritos">'.$data->suscriptores.'</textarea>
				</div>
				<div class="text-right">
					<button id="btn_submit_modal" type="submit" class="btn btn-primary">'.$btn.'</button>
				</div>
			</form>
			<script>_'.$action.'("listas_form");</script>
		';
    }

	add_action( 'wp_ajax_vlz_listas_list', function() {
		extract($_POST);
		global $wpdb;
		$data["data"] = [];
		$info = $wpdb->get_results("SELECT * FROM vlz_listas ORDER BY creada DESC");
		foreach ($info as $key => $value) {
			$d = json_decode($value->data);
			$data["data"][] = [
				$value->id,
				$d->data->titulo,
				count( explode(",", $d->data->suscriptores) ),
				'
					<span class="btn btn-primary btn-s" onclick="_edit( jQuery(this) )" data-id="'.$value->id.'" data-modal="listas_edit" data-titulo="Editar Lista" >Editar</span> &nbsp;
					<span class="btn btn-danger btn-s" onclick="_del_form( jQuery(this) )" data-id="'.$value->id.'" data-modal="listas_del_form" data-titulo="Eliminar Lista" >Eliminar</span>
				'
			];
		}
		echo json_encode($data);
	   	die();
	} );

	add_action( 'wp_ajax_vlz_listas_new', function() {
		extract($_POST);
		global $wpdb;
		get_listas_form([]);
	   	die();
	} );

	add_action( 'wp_ajax_vlz_listas_edit', function() {
		extract($_POST);
		global $wpdb;
		$data = $wpdb->get_row("SELECT * FROM vlz_listas WHERE id = ".$ID);
		get_listas_form($data, 'update');
	   	die();
	} );

	add_action( 'wp_ajax_vlz_listas_del_form', function() {
		extract($_POST);
		global $wpdb;
		$data = $wpdb->get_row("SELECT * FROM vlz_listas WHERE id = ".$ID);
		$data = (array)  json_decode($data->data);
		extract($data);

		echo '
			<form id="listas_form" data-modulo="listas" >
				<input type="hidden" name="id" value="'.$ID.'" />
				<div class="form-group">
					<label for="titulo">¿Esta seguro de eliminar esta Lista?</label>
					<input type="text" class="form-control" id="titulo" value="'.$data->titulo.'" readonly />
				</div>
				<div class="text-right">
					<button id="btn_submit_modal" type="submit" class="btn btn-primary">Eliminar</button>
				</div>
			</form>
			<script>_delete("listas_form");</script>
		';

	   	die();
	} );

	add_action( 'wp_ajax_vlz_listas_insert', function() {
		extract($_POST);
		global $wpdb;
		$titulo = $data["titulo"];
		$existe = $wpdb->get_var("SELECT id FROM vlz_listas WHERE data LIKE '%\"titulo\":\"{$titulo}\"%' ");
		if( empty($existe) ){
			$_POST["data"]["suscriptores"] = preg_replace("/[\r\n|\n|\r]+/", ",", $_POST["data"]["suscriptores"]);
			$_temp = explode(",", $_POST["data"]["suscriptores"]);
			$suscriptores = [];
			foreach ($_temp as $suscriptor) {
				if( !in_array($suscriptor, $suscriptores)){
					if(false !== filter_var($suscriptor, FILTER_VALIDATE_EMAIL)){
						$suscriptores[] = $suscriptor;
					}
				}
			}
			$_POST["data"]["suscriptores"] = implode(",", $suscriptores);
			$data = json_encode($_POST);
			$wpdb->query("INSERT INTO vlz_listas VALUES (NULL, '{$data}', NOW())");
			echo json_encode([
				"error" => "",
				"msg" => "Lista Creada Exitosamente",
			]);
		}else{
			echo json_encode([
				"error" => "Ya existe una listas con este nombre",
				"msg" => "",
			]);
		}
	   	die();
	} );

	add_action( 'wp_ajax_vlz_listas_update', function() {
		extract($_POST);
		global $wpdb;
		$titulo = $data["titulo"];
		$existe = $wpdb->get_var("SELECT id FROM vlz_listas WHERE data LIKE '%\"titulo\":\"{$titulo}\"%' AND id != ".$id);
		if( empty($existe) ){
			$_POST["data"]["suscriptores"] = preg_replace("/[\r\n|\n|\r]+/", ",", $_POST["data"]["suscriptores"]);
			$_temp = explode(",", $_POST["data"]["suscriptores"]);
			$suscriptores = [];
			foreach ($_temp as $suscriptor) {
				if( !in_array($suscriptor, $suscriptores)){
					if(false !== filter_var($suscriptor, FILTER_VALIDATE_EMAIL)){
						$suscriptores[] = $suscriptor;
					}
				}
			}
			$_POST["data"]["suscriptores"] = implode(",", $suscriptores);
			$data = json_encode($_POST);
			$sql = "UPDATE vlz_listas SET data = '{$data}' WHERE id = ".$id;
			$wpdb->query( $sql );
			echo json_encode([
				"error" => "",
				"msg" => "Lista Actualizada Exitosamente",
			]);
		}else{
			echo json_encode([
				"error" => "Ya existe una listas con este nombre",
				"msg" => "",
			]);
		}
	   	die();
	} );

	add_action( 'wp_ajax_vlz_listas_delete', function() {
		extract($_POST);
		global $wpdb;
		$wpdb->query("DELETE FROM vlz_listas WHERE id = ".$id);
		echo json_encode([
			"error" => "",
			"msg" => "Lista Eliminada Exitosamente",
		]);
	   	die();
	} );


	/*

sdfsdf
sdfsdf@dsfsdf.sdfsdf
sdfsdf@dsfsdf.es
sdfsdf@dsfsdf.sdfsdf
sdfsdf@dsfsdf.com

	add_action( 'wp_ajax_vlz_listas_list', function() {
		extract($_POST);
		global $wpdb;
		
		echo '
			<table id="table_listass" class="table table-striped table-bordered nowrap" cellspacing="0" style="min-width: 100%;">
	            <thead>
	                <tr>
	                    <th>#</th>
	                    <th>Nombre</th>
	                    <th>listass</th>
	                    <th>Acciones</th>
	                </tr>
	            </thead>
	            <tbody></tbody>
	        </table>
		';

	   	die();
	} );

	add_action( 'wp_ajax_vlz_listas_list', function() {
		extract($_POST);
		global $wpdb;
		
		echo 'listass';

	   	die();
	} );
	*/

?>