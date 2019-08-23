<?php
	global $MODULOS_ADMIN_2;

	$MODULOS_ADMIN_2[] = array(
        'parent'        =>  'campaing',
        'title'         =>  __('Seguimiento'),
        'short-title'   =>  __('Seguimiento'),
        'access'        =>  'manage_options',
        'slug'          =>  'seguimiento',
        'modulo'        =>  function(){
        	init_page( 'seguimiento' );
        },
        'level' => 4
    );

    function get_seguimiento_form($data, $action = 'insert'){
		global $wpdb;

		$links = $wpdb->get_results("SELECT * FROM vlz_seguimiento_links WHERE campaing = ".$data["id"]);
		$_data = [];
		foreach ($links as $value) {
			if( isset($_data[ $value->link ]) ){
				$_data[ $value->link ] = 1;
			}else{
				$_data[ $value->link ] += 1;
			}
		}

		echo '<table id="links" class="table table-striped table-bordered nowrap" style="margin-top: 15px;">
				<thead><tr>
					<th>Link</th>
					<th>Clicks</th>
				</tr></thead><tbody>';
				foreach ($_data as $link => $count) {
					echo "<tr>";
						echo "
							<td>".$link."</td>
							<td>".$count."</td>
						";
					echo "</tr>";
				}
		echo '</tbody></table>';
    }

    /*
	add_action( 'wp_ajax_vlz_seguimiento_list_form', function() {
		extract($_POST);
		extract($_GET);
		global $wpdb;
		$_data["data"] = [];
		$info = $wpdb->get_row("SELECT * FROM vlz_seguimiento_links WHERE id = ".$ID);
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
						<span class="btn btn-primary btn-s" onclick="_modal( jQuery(this) )" data-id="'.$ID."|".$suscriptor[1].'" data-modal="seguimiento_edit_cliente" data-titulo="Editar Cliente" >Editar</span> &nbsp;
						<span class="btn btn-danger  btn-s" onclick="_modal( jQuery(this) )" data-id="'.$ID."|".base64_encode($suscriptor[0])."|".$suscriptor[1].'" data-modal="seguimiento_del_modal" data-titulo="Eliminar Cliente" >Eliminar</span>
					</div>'
				];
			}
		}
		echo json_encode($_data);
	   	die();
	} );
	*/

	add_action( 'wp_ajax_vlz_seguimiento_list', function() {
		extract($_POST);
		global $wpdb;

		$_campaings = $wpdb->get_results("SELECT * FROM vlz_campaing");
		$campaings = [];
		foreach ($_campaings as $campaing) {
			$d = json_decode($campaing->data);
			$campaings[ $campaing->id ] = $d->data->titulo;
		}

		$data["data"] = [];
		$links = $wpdb->get_results("SELECT * FROM vlz_seguimiento_links ORDER BY creacion DESC");
		$_data = [];
		foreach ($links as $value) {
			if( isset($_data[ $value->campaing ][ $value->link ]) ){
				$_data[ $value->campaing ][ $value->link ] = 1;
			}else{
				$_data[ $value->campaing ][ $value->link ] += 1;
			}
		}
		
		
		$cont = 1;
		foreach ($_data as $campaing => $_links) {
			$links = count($_links);
			$clicks = 0;
			foreach ($_links as $key => $_link) {
				$clicks += $_link;
			}

			$data["data"][] = [
				$cont,
				$campaings[ $campaing ],
				$links,
				$clicks,'
				<span class="btn btn-primary btn-s" onclick="_modal( jQuery(this) )" data-id="'.$campaing.'" data-modal="seguimiento_show" data-titulo="Información de [ '.$campaings[ $campaing ].' ]" >Ver</span>'
			];
			$cont++;
		}
		
		echo json_encode($data);
	   	die();
	} );

	add_action( 'wp_ajax_vlz_seguimiento_new', function() {
		extract($_POST);
		global $wpdb;
		get_seguimiento_form([]);
	   	die();
	} );

	add_action( 'wp_ajax_vlz_seguimiento_edit', function() {
		extract($_POST);
		global $wpdb;
		$data = $wpdb->get_row("SELECT * FROM vlz_seguimiento_links WHERE id = ".$ID);
		get_seguimiento_form($data, 'update');
	   	die();
	} );

	add_action( 'wp_ajax_vlz_seguimiento_show', function() {
		extract($_POST);
		global $wpdb;
		get_seguimiento_form(["id" => $ID], 'show');
	   	die();
	} );

	function get_seguimiento_form_cliente($data, $action = 'update'){
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
			<form id="seguimiento_form_cliente" data-modulo="seguimiento" >
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
				_'.$action.'("seguimiento_form_cliente");
			</script>
		';
	}

	add_action( 'wp_ajax_vlz_seguimiento_edit_cliente', function() {
		extract($_POST);
		global $wpdb;
		$ID = explode("|", $ID);
		$data = $wpdb->get_row("SELECT * FROM vlz_seguimiento_links WHERE id = ".$ID[0]);
		$data->correo = $ID[1];
		get_seguimiento_form_cliente($data, 'update');
	   	die();
	} );

	add_action( 'wp_ajax_vlz_seguimiento_del_form', function() {
		extract($_POST);
		global $wpdb;
		$data = $wpdb->get_row("SELECT * FROM vlz_seguimiento_links WHERE id = ".$ID);
		$data = (array)  json_decode($data->data);
		extract($data);

		echo '
			<form id="seguimiento_form" data-modulo="seguimiento" >
				<input type="hidden" name="id" value="'.$ID.'" />
				<div class="form-group">
					<label for="titulo">¿Esta seguro de eliminar esta Lista?</label>
					<input type="text" class="form-control" id="titulo" value="'.$data->titulo.'" readonly />
				</div>
				<div class="text-right">
					<button id="btn_submit_modal" type="submit" class="btn btn-primary">Eliminar</button>
				</div>
			</form>
			<script>_delete("seguimiento_form");</script>
		';

	   	die();
	} );

	add_action( 'wp_ajax_vlz_seguimiento_del_modal', function() {
		extract($_POST);
		global $wpdb;
		$_ID = explode("|", $ID);
		$data = $wpdb->get_row("SELECT * FROM vlz_seguimiento_links WHERE id = ".$_ID[0]);
		
		echo '
			<form id="seguimiento_form" data-modulo="seguimiento" >
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
			<script>_delete("seguimiento_form");</script>
		';
	   	die();
	} );

	add_action( 'wp_ajax_vlz_seguimiento_insert', function() {
		extract($_POST);
		global $wpdb;
		$titulo = $data["titulo"];
		$existe = $wpdb->get_var("SELECT id FROM vlz_seguimiento_links WHERE data LIKE '%\"titulo\":\"{$titulo}\"%' ");
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
			$wpdb->query("INSERT INTO vlz_seguimiento_links VALUES (NULL, '{$data}', NOW())");
			echo json_encode([
				"error" => "",
				"msg" => "Lista Creada Exitosamente",
			]);
		}else{
			echo json_encode([
				"error" => "Ya existe una seguimiento con este nombre",
				"msg" => "",
			]);
		}
	   	die();
	} );

	add_action( 'wp_ajax_vlz_seguimiento_update', function() {
		extract($_POST);
		global $wpdb;
		if( $form == "cliente" ){
			$lista = $wpdb->get_row("SELECT * FROM vlz_seguimiento_links WHERE id = ".$id);
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
			$sql = "UPDATE vlz_seguimiento_links SET data = '{$data}' WHERE id = ".$id;
			$wpdb->query( $sql );
			echo json_encode([
				"error" => "",
				"msg" => "Cliente Actualizado Exitosamente",
			]);
		}else{
			$titulo = $data["titulo"];
			$existe = $wpdb->get_var("SELECT id FROM vlz_seguimiento_links WHERE data LIKE '%\"titulo\":\"{$titulo}\"%' AND id != ".$id);
			if( empty($existe) ){

				$lista = $wpdb->get_row("SELECT * FROM vlz_seguimiento_links WHERE id = ".$id);
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
				unset($_POST["table_seguimiento_length"]);
				unset($_POST["form"]);
				$_POST["suscriptores"] = $suscriptores;
				$data = json_encode($_POST);
				$sql = "UPDATE vlz_seguimiento_links SET data = '{$data}' WHERE id = ".$id;
				$wpdb->query( $sql );
				echo json_encode([
					"error" => "",
					"msg" => "Lista Actualizada Exitosamente",
				]);
			}else{
				echo json_encode([
					"error" => "Ya existe una seguimiento con este nombre",
					"msg" => "",
				]);
			}
		}
	   	die();
	} );

	add_action( 'wp_ajax_vlz_seguimiento_delete', function() {
		extract($_POST);
		global $wpdb;
		if( $form == "modal" ){
			$_ID = explode("|", $id);
			$lista = $wpdb->get_row("SELECT * FROM vlz_seguimiento_links WHERE id = ".$_ID[0]);
			$info = json_decode($lista->data);

			$suscriptores = [];
			foreach ($info->suscriptores as $key => $suscriptor) {
				if( $_ID[2] != $suscriptor[1] ){
					$suscriptores[] = $suscriptor;
				}
			}
			$info->suscriptores = $suscriptores;
			$data = json_encode($info);
			$sql = "UPDATE vlz_seguimiento_links SET data = '{$data}' WHERE id = ".$_ID[0];
			$wpdb->query($sql);
			
			echo json_encode([
				"error" => "",
				"msg" => "Cliente Eliminado Exitosamente",
			]);
		}else{
			$wpdb->query("DELETE FROM vlz_seguimiento_links WHERE id = ".$id);
			echo json_encode([
				"error" => "",
				"msg" => "Lista Eliminada Exitosamente",
			]);
		}
	   	die();
	} );

?>