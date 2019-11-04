<?php
	global $MODULOS_ADMIN_2;

	$MODULOS_ADMIN_2[] = array(
        'parent'        =>  'campaing',
        'title'         =>  __('Listas'),
        'short-title'   =>  __('Listas'),
        'access'        =>  'manage_options',
        'slug'          =>  'listas',
        'modulo'        =>  function(){
        	init_page( 'listas' );
        },
        'level' => 2
    );

    function get_listas_form($data, $action = 'insert'){
		global $wpdb;

		$config = json_decode($data->config);

		extract($config);

		$listas_news = '';
		$news = $wpdb->get_results("SELECT DISTINCT source FROM wp_kmimos_subscribe ORDER BY source ASC");
		foreach ($news as $key => $value) {
			if( ucfirst($value->source) != '' ){
				$listas_news .= '<option value="'.$value->source.'" '.selected($value->source, $config->newsletter, false).'>'.ucfirst($value->source).'</option>';
			}
		}

		$wlabes = '';
		$wlabes_cuidadores = '';
		$news = $wpdb->get_results("SELECT * FROM wp_kmimos_wlabel ORDER BY wlabel ASC");

		$wlabes .= '<option value="kmimos" '.selected("kmimos", $config->wlabel, false).'>Kmimos</option>';
		$wlabes_cuidadores .= '<option value="kmimos" '.selected("kmimos", $config->cuidadores, false).'>Kmimos</option>';

		foreach ($news as $key => $value) {
			if( trim($value->title) != '' ){
				$wlabes .= '<option value="'.$value->wlabel.'" '.selected($value->wlabel, $config->wlabel, false).'>'.ucfirst($value->title).'</option>';
				$wlabes_cuidadores .= '<option value="'.$value->wlabel.'" '.selected($value->wlabel, $config->cuidadores, false).'>'.ucfirst($value->title).'</option>';
			}
		}

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
					<input type="text" class="form-control" id="titulo" name="titulo" value="'.$data->titulo.'" placeholder="Titulo de la Lista">
				</div>
				<div class="form-group">
					<label for="importar">Importar Clientes</label>
					<input type="file" class="form-control" id="importar" name="importar" accept=".csv" />
					<input type="hidden" id="importaciones" name="importaciones" />
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="titulo">Listas Newsletter</label>
							<select id="newsletter" name="newsletter" class="form-control" >
								<option value="">Seleccione...</option>
								'.$listas_news.'
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="titulo">Clientes Registrados</label>
							<select id="wlabel" name="wlabel" class="form-control" >
								<option value="">Seleccione...</option>
								'.$wlabes.'
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="titulo">Cuidadores Registrados</label>
							<select id="cuidadores" name="cuidadores" class="form-control" >
								<option value="">Seleccione...</option>
								'.$wlabes_cuidadores.'
							</select>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="titulo">Desde</label>
							<input type="date" name="desde" value="'.$config->desde.'" class="form-control" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="titulo">Hasta</label>
							<input type="date" name="hasta" value="'.$config->hasta.'" class="form-control" />
						</div>
					</div>
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

	add_action( 'wp_ajax_vlz_listas_insert', function() {
		extract($_POST);
		global $wpdb;

    	// include dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))).'/test/list_campaing.php';

		$desde = ( $desde != "" ) ? date("Y-m-d", strtotime( str_replace("/", "-", $desde) ) ) : '';
		$hasta = ( $hasta != "" ) ? date("Y-m-d", strtotime( str_replace("/", "-", $hasta) ) ) : '';

		$existe = $wpdb->get_var("SELECT id FROM vlz_listas WHERE data LIKE '%\"titulo\":\"{$titulo}\"%' ");
		if( empty($existe) ){
			$suscriptores = [];
			$suscriptores_manuales = [];
			$suscriptores_no_repeat = [];
			if( !empty($importaciones) ){
				$importaciones = preg_replace("/[\r\n|\n|\r]+/", "|", $importaciones);
				$importaciones = explode("|", $importaciones);
				foreach ($importaciones as $key => $value) {
					$suscriptor = explode(",", $value);
					if( !in_array($suscriptor[1], $suscriptores_no_repeat)){
						if(false !== filter_var($suscriptor[1], FILTER_VALIDATE_EMAIL)){
							// if( in_array($suscriptor[1], $emails_validos) ){
								$suscriptores[] = [
									$suscriptor[0],
									$suscriptor[1]
								];
							// }
							$suscriptores_no_repeat[] = $suscriptor[1];
						}
					}
				}
			}

			$suscriptores_manuales = json_encode($suscriptores);

			if( $newsletter != "" ){

				$fechas = ( $desde != "" ) ? " AND time >= '{$desde}' " : '';
				$fechas .= ( $hasta != "" ) ? " AND time <= '{$hasta}' " : '';
				$suscritos = $wpdb->get_results("SELECT * FROM wp_kmimos_subscribe WHERE source = '{$newsletter}' {$fechas} ");
				foreach ($suscritos as $key => $suscrito) {
					// if( in_array($suscrito->email, $emails_validos) ){
						$suscriptores[] = [
							$suscrito->email,
							$suscrito->email
						];
					// }
				}

			}

			if( $wlabel != "" ){

				if( $wlabel == 'kmimos' ){
					$fechas = ""; 
					if( $desde != "" ) {
						$fechas = " AND u.user_registered >= '{$desde}' ";
					}
					if( $hasta != "" ) {
						$fechas = " AND u.user_registered <= '{$hasta}' ";
					}

					$sql =  "
					SELECT DISTINCT (u.user_email), u.ID
					FROM wp_usermeta AS m 
					INNER JOIN wp_users AS u ON ( u.ID = m.user_id ) 
					INNER JOIN wp_usermeta AS c ON ( u.ID = c.user_id AND c.meta_key = 'wp_capabilities' )
					WHERE NOT EXISTS
					    (
					        SELECT  null 
					        FROM wp_usermeta AS w
					        WHERE w.user_id = u.ID AND w.meta_key = '_wlabel'
					    ) 
					    AND c.meta_value LIKE '%subscriber%'
					    {$fechas}";

					$suscritos = $wpdb->get_results($sql);
					$cont = 0;
					foreach ($suscritos as $key => $suscrito) {
						$cont++;
						$first = get_user_meta($suscrito->ID, 'first_name', true);
						$first = str_replace('"', '', $first);

						// if( in_array($suscrito->user_email, $emails_validos) ){
							$suscriptores[] = [
								$first,
								$suscrito->user_email
							];
						// }
					}

				}else{
					$fechas = ""; 
					if( $desde != "" ) {
						$fechas = " AND u.user_registered >= '{$desde}' ";
					}
					if( $hasta != "" ) {
						$fechas = " AND u.user_registered <= '{$hasta}' ";
					}

					$sql =  "
					SELECT u.user_email AS email, n.meta_value AS name 
					FROM wp_usermeta AS m 
					INNER JOIN wp_users AS u ON ( u.ID = m.user_id ) 
					INNER JOIN wp_usermeta AS n ON ( u.ID = n.user_id AND n.meta_key = 'first_name' )
					INNER JOIN wp_usermeta AS c ON ( u.ID = c.user_id AND c.meta_key = 'wp_capabilities' )
					WHERE  (  m.meta_key = '_wlabel' OR  m.meta_key = 'user_referred' ) AND m.meta_value LIKE '%{$wlabel}%' AND c.meta_value LIKE '%subscriber%' {$fechas}";

					$suscritos = $wpdb->get_results($sql);

					foreach ($suscritos as $key => $suscrito) {
						// if( in_array($suscrito->email, $emails_validos) ){
							$suscriptores[] = [
								$suscrito->name,
								$suscrito->email
							];
						// }
					}
				}

			}

			if( $cuidadores != "" ){

				if( $cuidadores == 'kmimos' ){
					$fechas = ""; 
					if( $desde != "" ) {
						$fechas = " AND u.user_registered >= '{$desde}' ";
					}
					if( $hasta != "" ) {
						$fechas = " AND u.user_registered <= '{$hasta}' ";
					}

					$sql =  "
					SELECT DISTINCT (u.user_email), u.ID
					FROM wp_usermeta AS m 
					INNER JOIN wp_users AS u ON ( u.ID = m.user_id ) 
					INNER JOIN wp_usermeta AS c ON ( u.ID = c.user_id AND c.meta_key = 'wp_capabilities' )
					WHERE NOT EXISTS
					    (
					        SELECT  null 
					        FROM wp_usermeta AS w
					        WHERE w.user_id = u.ID AND w.meta_key = '_wlabel'
					    ) 
					    AND c.meta_value LIKE '%vendor%'
					    {$fechas}";

					$suscritos = $wpdb->get_results($sql);
					$cont = 0;
					foreach ($suscritos as $key => $suscrito) {
						$cont++;
						$first = get_user_meta($suscrito->ID, 'first_name', true);
						$first = str_replace('"', '', $first);

						// if( in_array($suscrito->user_email, $emails_validos) ){
							$suscriptores[] = [
								$first,
								$suscrito->user_email
							];
						// }
					}

				}else{
					$fechas = ""; 
					if( $desde != "" ) {
						$fechas = " AND u.user_registered >= '{$desde}' ";
					}
					if( $hasta != "" ) {
						$fechas = " AND u.user_registered <= '{$hasta}' ";
					}

					$sql =  "
					SELECT u.user_email AS email, n.meta_value AS name 
					FROM wp_usermeta AS m 
					INNER JOIN wp_users AS u ON ( u.ID = m.user_id ) 
					INNER JOIN wp_usermeta AS n ON ( u.ID = n.user_id AND n.meta_key = 'first_name' )
					INNER JOIN wp_usermeta AS c ON ( u.ID = c.user_id AND c.meta_key = 'wp_capabilities' )
					WHERE  (  m.meta_key = '_wlabel' OR  m.meta_key = 'user_referred' ) AND m.meta_value LIKE '%{$cuidadores}%' AND c.meta_value LIKE '%vendor%' {$fechas}";

					$suscritos = $wpdb->get_results($sql);

					foreach ($suscritos as $key => $suscrito) {
						// if( in_array($suscrito->email, $emails_validos) ){
							$suscriptores[] = [
								$suscrito->name,
								$suscrito->email
							];
						// }
					}
				}
			}

			$info = [
				"titulo" => $titulo,
				"suscriptores" => $suscriptores,
			];
			$data = json_encode($info, JSON_UNESCAPED_UNICODE);

			$config = [
				"newsletter" => $newsletter,
				"wlabel" => $wlabel,
				"cuidadores" => $cuidadores,
				"desde" => $desde,
				"hasta" => $hasta
			];
			$config = json_encode($config, JSON_UNESCAPED_UNICODE);

			$wpdb->query("INSERT INTO vlz_listas VALUES (NULL, '{$data}', '{$config}', '{$suscriptores_manuales}', NOW())");
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
					<input type="text" class="form-control" id="titulo" value="'.$titulo.'" readonly />
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

	/*
	add_action( 'wp_ajax_vlz_listas_crear', function() {
		extract($_POST);
		global $wpdb;

		$desde = ( $desde != "" ) ? date("Y-m-d", strtotime( str_replace("/", "-", $desde) ) ) : '';
		$hasta = ( $hasta != "" ) ? date("Y-m-d", strtotime( str_replace("/", "-", $hasta) ) ) : '';

		$existe = $wpdb->get_var("SELECT id FROM vlz_listas WHERE data LIKE '%\"titulo\":\"{$titulo}\"%' ");
		if( empty($existe) ){
			$suscriptores = [];
			
			// Busqueda de emails

			if( $newsletter != "" ){

				$fechas = ( $desde != "" && $hasta != "" ) ? " AND ( time >= '{$desde}' AND time <= '{$hasta}' ) " : '';
				$suscritos = $wpdb->get_results("SELECT * FROM wp_kmimos_subscribe WHERE source = '{$newsletter}' {$fechas} ");
				foreach ($suscritos as $key => $suscrito) {
					$suscriptores[] = [
						$suscrito->email,
						$suscrito->email
					];
				}

			}

			$info = [
				"titulo" => $titulo,
				"suscriptores" => $suscriptores,
			];
			$info = json_encode($info);
			$wpdb->query("INSERT INTO vlz_listas VALUES (NULL, '{$info}', '{}', '{}', NOW())");

			echo json_encode([ "error" => "", "msg" => "Lista Creada Exitosamente" ]);
		}else{
			echo json_encode([ "error" => "Ya existe una listas con este nombre", "msg" => "" ]);
		}
	   	die();
	} );
	*/

	add_action( 'wp_ajax_vlz_listas_update', function() {

    	// include dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))).'/test/list_campaing.php';

		extract($_POST);
		global $wpdb;

		$desde = ( $desde != "" ) ? date("Y-m-d", strtotime( str_replace("/", "-", $desde) ) ) : '';
		$hasta = ( $hasta != "" ) ? date("Y-m-d", strtotime( str_replace("/", "-", $hasta) ) ) : '';

		$existe = $wpdb->get_var("SELECT id FROM vlz_listas WHERE data LIKE '%\"titulo\":\"{$titulo}\"%' AND id != '{$id}' ");
		if( empty($existe) ){

			$campaing = $wpdb->get_row("SELECT * FROM vlz_listas WHERE id = '{$id}' ");

			$suscriptores = json_decode($campaing->manuales);
			$suscriptores_manuales = $suscriptores;

			$suscriptores_no_repeat = [];
			foreach ($suscriptores as $key => $suscriptor) {
				$suscriptores_no_repeat[] = $suscriptor[1];
			}

			if( !empty($importaciones) ){
				$importaciones = preg_replace("/[\r\n|\n|\r]+/", "|", $importaciones);
				$importaciones = explode("|", $importaciones);
				foreach ($importaciones as $key => $value) {
					$suscriptor = explode(",", $value);
					if( !in_array($suscriptor[1], $suscriptores_no_repeat)){
						if(false !== filter_var($suscriptor[1], FILTER_VALIDATE_EMAIL)){
							// if( in_array($suscriptor[1], $emails_validos) ){
								$suscriptores[] = [
									$suscriptor[0],
									$suscriptor[1]
								];
							// }
							$suscriptores_no_repeat[] = $suscriptor[1];
						}
					}
				}
			}

			$suscriptores_manuales = json_encode($suscriptores);

			if( $newsletter != "" ){

				$fechas = ( $desde != "" ) ? " AND time >= '{$desde}' " : '';
				$fechas .= ( $hasta != "" ) ? " AND time <= '{$hasta}' " : '';
				$sql_nl = "SELECT * FROM wp_kmimos_subscribe WHERE source = '{$newsletter}' {$fechas} ";
				$suscritos = $wpdb->get_results($sql_nl);
				foreach ($suscritos as $key => $suscrito) {
					// if( in_array($suscrito->email, $emails_validos) ){
						$suscriptores[] = [
							$suscrito->email,
							$suscrito->email
						];
					// }
				}

			}

			if( $wlabel != "" ){

				if( $wlabel == 'kmimos' ){
					$fechas = ""; 
					if( $desde != "" ) {
						$fechas = " AND u.user_registered >= '{$desde}' ";
					}
					if( $hasta != "" ) {
						$fechas = " AND u.user_registered <= '{$hasta}' ";
					}

					$sql =  "
					SELECT DISTINCT (u.user_email), u.ID
					FROM wp_usermeta AS m 
					INNER JOIN wp_users AS u ON ( u.ID = m.user_id ) 
					INNER JOIN wp_usermeta AS c ON ( u.ID = c.user_id AND c.meta_key = 'wp_capabilities' )
					WHERE NOT EXISTS
					    (
					        SELECT  null 
					        FROM wp_usermeta AS w
					        WHERE w.user_id = u.ID AND w.meta_key = '_wlabel'
					    ) 
					    AND c.meta_value LIKE '%subscriber%'
					    {$fechas}";

					$suscritos = $wpdb->get_results($sql);
					$cont = 0;
					foreach ($suscritos as $key => $suscrito) {
						$cont++;
						$first = get_user_meta($suscrito->ID, 'first_name', true);
						$first = str_replace('"', '', $first);

						// if( in_array($suscrito->user_email, $emails_validos) ){
							$suscriptores[] = [
								$first,
								$suscrito->user_email
							];
						// }
					}

				}else{
					$fechas = ""; 
					if( $desde != "" ) {
						$fechas = " AND u.user_registered >= '{$desde}' ";
					}
					if( $hasta != "" ) {
						$fechas = " AND u.user_registered <= '{$hasta}' ";
					}

					$sql =  "
					SELECT u.user_email AS email, n.meta_value AS name 
					FROM wp_usermeta AS m 
					INNER JOIN wp_users AS u ON ( u.ID = m.user_id ) 
					INNER JOIN wp_usermeta AS n ON ( u.ID = n.user_id AND n.meta_key = 'first_name' )
					INNER JOIN wp_usermeta AS c ON ( u.ID = c.user_id AND c.meta_key = 'wp_capabilities' )
					WHERE  (  m.meta_key = '_wlabel' OR  m.meta_key = 'user_referred' ) AND m.meta_value LIKE '%{$wlabel}%' AND c.meta_value LIKE '%subscriber%' {$fechas}";

					$suscritos = $wpdb->get_results($sql);

					foreach ($suscritos as $key => $suscrito) {
						// if( in_array($suscrito->email, $emails_validos) ){
							$suscriptores[] = [
								$suscrito->name,
								$suscrito->email
							];
						// }
					}
				}

			}

			if( $cuidadores != "" ){

				if( $cuidadores == 'kmimos' ){
					$fechas = ""; 
					if( $desde != "" ) {
						$fechas = " AND u.user_registered >= '{$desde}' ";
					}
					if( $hasta != "" ) {
						$fechas = " AND u.user_registered <= '{$hasta}' ";
					}

					$sql =  "
					SELECT DISTINCT (u.user_email), u.ID
					FROM wp_usermeta AS m 
					INNER JOIN wp_users AS u ON ( u.ID = m.user_id ) 
					INNER JOIN wp_usermeta AS c ON ( u.ID = c.user_id AND c.meta_key = 'wp_capabilities' )
					WHERE NOT EXISTS
					    (
					        SELECT  null 
					        FROM wp_usermeta AS w
					        WHERE w.user_id = u.ID AND w.meta_key = '_wlabel'
					    ) 
					    AND c.meta_value LIKE '%vendor%'
					    {$fechas}";

					$suscritos = $wpdb->get_results($sql);
					$cont = 0;
					foreach ($suscritos as $key => $suscrito) {
						$cont++;
						$first = get_user_meta($suscrito->ID, 'first_name', true);
						$first = str_replace('"', '', $first);

						// if( in_array($suscrito->user_email, $emails_validos) ){
							$suscriptores[] = [
								$first,
								$suscrito->user_email
							];
						// }
					}

				}else{
					$fechas = ""; 
					if( $desde != "" ) {
						$fechas = " AND u.user_registered >= '{$desde}' ";
					}
					if( $hasta != "" ) {
						$fechas = " AND u.user_registered <= '{$hasta}' ";
					}

					$sql =  "
					SELECT u.user_email AS email, n.meta_value AS name 
					FROM wp_usermeta AS m 
					INNER JOIN wp_users AS u ON ( u.ID = m.user_id ) 
					INNER JOIN wp_usermeta AS n ON ( u.ID = n.user_id AND n.meta_key = 'first_name' )
					INNER JOIN wp_usermeta AS c ON ( u.ID = c.user_id AND c.meta_key = 'wp_capabilities' )
					WHERE  (  m.meta_key = '_wlabel' OR  m.meta_key = 'user_referred' ) AND m.meta_value LIKE '%{$cuidadores}%' AND c.meta_value LIKE '%vendor%' {$fechas}";

					$suscritos = $wpdb->get_results($sql);

					foreach ($suscritos as $key => $suscrito) {
						// if( in_array($suscrito->email, $emails_validos) ){
							$suscriptores[] = [
								$suscrito->name,
								$suscrito->email
							];
						// }
					}
				}
			}

			$info = [
				"titulo" => $titulo,
				"suscriptores" => $suscriptores,
			];
			$data = json_encode($info, JSON_UNESCAPED_UNICODE);

			$config = [
				"newsletter" => $newsletter,
				"wlabel" => $wlabel,
				"cuidadores" => $cuidadores,
				"desde" => $desde,
				"hasta" => $hasta
			];
			$config = json_encode($config, JSON_UNESCAPED_UNICODE);

			$sql = "UPDATE vlz_listas SET data = '{$data}', config = '{$config}', manuales = '{$suscriptores_manuales}' WHERE id = ".$id;
			$wpdb->query( $sql );
			echo json_encode([
				"error" => "",
				"msg" => "Lista Actualizada Exitosamente",
				"sql_nl" => $sql_nl,
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