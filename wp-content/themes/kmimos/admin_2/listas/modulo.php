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
			$ID = $data->id;
			$data = json_decode($data->data);
			$btn = 'Actualizar';
		}

		$FORM = '';
		if( $action != 'insert' ){
			$FORM = '
				<div class="form-group">
					<label for="suscriptores">Suscriptores</label>
					<table id="table_listas" class="table table-striped table-bordered nowrap" cellspacing="0" style="min-width: 100%;">
			            <thead>
			                <tr>
			                    <th>#</th>
			                    <th>Nombre</th>
			                    <th>Correo</th>
			                    <th>Acciones</th>
			                </tr>
			            </thead>
			            <tbody></tbody>
			        </table>
				</div>
			';
		}

		echo '
			<form id="listas_form" data-modulo="listas" >
				'.$input_id.'
				<input type="hidden" name="form" value="lista" />
				<div class="form-group">
					<label for="titulo">Nombre de la lista</label>
					<input type="text" class="form-control" id="titulo" name="titulo" value="'.$data->titulo.'" placeholder="Titulo de la Campaña">
				</div>
				<div class="form-group">
					<label for="importar">Importar Clientes</label>
					<input type="file" class="form-control" id="importar" name="importar" accept=".csv" />
					<input type="hidden" id="importaciones" name="importaciones" />
				</div>
				'.$FORM.'
				<div class="text-right">
					<button id="btn_submit_modal" type="submit" class="btn btn-primary">'.$btn.'</button>
				</div>
			</form>
			<script>
				_'.$action.'("listas_form");
				loadTabla("table_listas", "list_form&ID='.$ID.'");

				jQuery(document).ready(function() {
				    importar_csv( jQuery("#importar") );
				});

			</script>
		';
    }

	add_action( 'wp_ajax_vlz_listas_list_form', function() {
		extract($_POST);
		extract($_GET);
		global $wpdb;
		$_data["data"] = [];
		$info = $wpdb->get_row("SELECT * FROM vlz_listas WHERE id = ".$ID);
		if( !empty($info) ){
			$data = json_decode($info->data);
			$temp_suscriptores = $data->suscriptores;
			$suscriptores = '';
			foreach ($temp_suscriptores as $key => $suscriptor) {
				$_data["data"][] = [
					$key+1,
					$suscriptor[0],
					$suscriptor[1],
					'<div style="text-align: center;"> 
						<span class="btn btn-primary btn-s" onclick="_modal( jQuery(this) )" data-id="'.$ID."|".$suscriptor[1].'" data-modal="listas_edit_cliente" data-titulo="Editar Cliente" >Editar</span> &nbsp;
						<span class="btn btn-danger  btn-s" onclick="_modal( jQuery(this) )" data-id="'.$ID."|".base64_encode($suscriptor[0])."|".$suscriptor[1].'" data-modal="listas_del_modal" data-titulo="Eliminar Cliente" >Eliminar</span>
					</div>'
				];
			}
		}
		echo json_encode($_data);
	   	die();
	} );

	add_action( 'wp_ajax_vlz_listas_list', function() {
		extract($_POST);
		global $wpdb;
		$data["data"] = [];
		$info = $wpdb->get_results("SELECT * FROM vlz_listas ORDER BY creada DESC");
		foreach ($info as $key => $value) {
			$d = json_decode($value->data);
			$count = count($d->suscriptores);
			$data["data"][] = [
				$value->id,
				$d->titulo,
				$count,'
				<span class="btn btn-primary btn-s" onclick="_modal( jQuery(this) )" data-id="'.$value->id.'" data-modal="listas_edit" data-titulo="Editar Lista" >Editar</span> &nbsp;
				<span class="btn btn-danger btn-s" onclick="_modal( jQuery(this) )" data-id="'.$value->id.'" data-modal="listas_del_form" data-titulo="Eliminar Lista" >Eliminar</span>'
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

	function get_listas_form_cliente($data, $action = 'update'){
		global $wpdb;
		$input_id = '<input type="hidden" name="id" value="'.$data->id.'" />';
		$email = $data->correo;
		$_data = json_decode($data->data);
		$nombre = '';
		foreach ($_data->suscriptores as $key => $suscriptor) {
			if( !is_array($suscriptor) ){
				break;
			}else{
				if( $email == $suscriptor[1] ){
					$nombre = $suscriptor[0];
				}
			}
		}
		$btn = 'Actualizar';
		echo '
			<form id="listas_form_cliente" data-modulo="listas" >
				'.$input_id.'
				<input type="hidden" name="form" value="cliente" />
				<input type="hidden" name="email_old" value="'.$email.'" />
				<div class="form-group">
					<label for="titulo">Nombre</label>
					<input type="text" class="form-control" id="titulo" name="data[titulo]" value="'.$nombre.'" placeholder="Nombre">
				</div>
				<div class="form-group">
					<label for="email">Email</label>
					<input type="text" class="form-control" id="email" name="data[email]" value="'.$email.'" placeholder="Email">
				</div>
				<div class="text-right">
					<button id="btn_submit_modal" type="submit" class="btn btn-primary">'.$btn.'</button>
				</div>
			</form>
			<script>
				_'.$action.'("listas_form_cliente");
			</script>
		';
	}

	add_action( 'wp_ajax_vlz_listas_edit_cliente', function() {
		extract($_POST);
		global $wpdb;
		$ID = explode("|", $ID);
		$data = $wpdb->get_row("SELECT * FROM vlz_listas WHERE id = ".$ID[0]);
		$data->correo = $ID[1];
		get_listas_form_cliente($data, 'update');
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

	add_action( 'wp_ajax_vlz_listas_del_modal', function() {
		extract($_POST);
		global $wpdb;
		$_ID = explode("|", $ID);
		$data = $wpdb->get_row("SELECT * FROM vlz_listas WHERE id = ".$_ID[0]);
		
		echo '
			<form id="listas_form" data-modulo="listas" >
				<input type="hidden" name="form" value="modal" />
				<input type="hidden" name="id" value="'.$ID.'" />
				<div class="form-group">
					<label for="titulo">¿Esta seguro de eliminar este Cliente?</label>
					<input type="text" class="form-control" id="titulo" value="'.base64_decode($_ID[1]).' <'.$_ID[2].'>" readonly />
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
			$suscriptores = [];
			$suscriptores_no_repeat = [];
			if( !empty($importaciones) ){
				$importaciones = preg_replace("/[\r\n|\n|\r]+/", "|", $importaciones);
				$importaciones = explode("|", $importaciones);
				foreach ($importaciones as $key => $value) {
					$suscriptor = explode(",", $value);
					if( !in_array($suscriptor[1], $suscriptores_no_repeat)){
						if(false !== filter_var($suscriptor[1], FILTER_VALIDATE_EMAIL)){
							$suscriptores[] = [
								$suscriptor[0],
								$suscriptor[1]
							];
							$suscriptores_no_repeat[] = $suscriptor[1];
						}
					}
				}
			}
			$_POST["suscriptores"] = $suscriptores;
			unset($_POST["importaciones"]);
			unset($_POST["form"]);

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
		if( $form == "cliente" ){
			$lista = $wpdb->get_row("SELECT * FROM vlz_listas WHERE id = ".$id);
			$info = json_decode($lista->data);
			$temp_suscriptores = $info->suscriptores;
			$suscriptores = [];
			$email_old = $_POST["email_old"];
			$email = $_POST["data"]["email"];
			$nombre = $_POST["data"]["titulo"];
			foreach ($temp_suscriptores as $key => $suscriptor) {
				if( !is_array($suscriptor) ){
					$_nombre = "";
					$_email = $suscriptor;
				}else{
					$_nombre = $suscriptor[0];
					$_email = $suscriptor[1];
				}
				if( $email_old == $_email ){
					$_email = $email;
					$_nombre = $nombre;
				}
				$suscriptores[] = [ $_nombre, $_email ];
			}
			$info->suscriptores = $suscriptores;
			$data = json_encode($info);
			$sql = "UPDATE vlz_listas SET data = '{$data}' WHERE id = ".$id;
			$wpdb->query( $sql );
			echo json_encode([
				"error" => "",
				"msg" => "Cliente Actualizado Exitosamente",
			]);
		}else{
			$titulo = $data["titulo"];
			$existe = $wpdb->get_var("SELECT id FROM vlz_listas WHERE data LIKE '%\"titulo\":\"{$titulo}\"%' AND id != ".$id);
			if( empty($existe) ){

				$lista = $wpdb->get_row("SELECT * FROM vlz_listas WHERE id = ".$id);
				$info = json_decode($lista->data);

				$_POST["suscriptores"] = preg_replace("/[\r\n|\n|\r]+/", ",", $_POST["suscriptores"]);
				$_temp = explode(",", $_POST["suscriptores"]);
				
				$suscriptores = [];
				$suscriptores_no_repeat = [];

				foreach ($info->suscriptores as $key => $value) {
					$suscriptores_no_repeat[] = $value[1];
					$suscriptores[] = $value;
				}

				if( !empty($importaciones) ){
					$importaciones = preg_replace("/[\r\n|\n|\r]+/", "|", $importaciones);
					$importaciones = explode("|", $importaciones);
					foreach ($importaciones as $key => $value) {
						$suscriptor = explode(",", $value);
						if( !in_array($suscriptor[1], $suscriptores_no_repeat)){
							if(false !== filter_var($suscriptor[1], FILTER_VALIDATE_EMAIL)){
								$suscriptores[] = [
									$suscriptor[0],
									$suscriptor[1]
								];
								$suscriptores_no_repeat[] = $suscriptor[1];
							}
						}
					}
				}
				unset($_POST["importaciones"]);
				unset($_POST["table_listas_length"]);
				unset($_POST["form"]);
				$_POST["suscriptores"] = $suscriptores;
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
		}
	   	die();
	} );

	add_action( 'wp_ajax_vlz_listas_delete', function() {
		extract($_POST);
		global $wpdb;
		if( $form == "modal" ){
			$_ID = explode("|", $id);
			$lista = $wpdb->get_row("SELECT * FROM vlz_listas WHERE id = ".$_ID[0]);
			$info = json_decode($lista->data);

			$suscriptores = [];
			foreach ($info->suscriptores as $key => $suscriptor) {
				if( $_ID[2] != $suscriptor[1] ){
					$suscriptores[] = $suscriptor;
				}
			}
			$info->suscriptores = $suscriptores;
			$data = json_encode($info);
			$sql = "UPDATE vlz_listas SET data = '{$data}' WHERE id = ".$_ID[0];
			$wpdb->query($sql);
			
			echo json_encode([
				"error" => "",
				"msg" => "Cliente Eliminado Exitosamente",
			]);
		}else{
			$wpdb->query("DELETE FROM vlz_listas WHERE id = ".$id);
			echo json_encode([
				"error" => "",
				"msg" => "Lista Eliminada Exitosamente",
			]);
		}
	   	die();
	} );

?>