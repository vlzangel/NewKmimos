<?php
	function init_modal($id = 'mymodal'){
		global $vlz;
		
		$lenguajes = '';
		echo '
			<div class="modal fade" id="'.$id.'" tabindex="-1" role="dialog" aria-labelledby="'.$id.'Label" aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="modal_title">Titulo</h5>
							'.$lenguajes.'
							<button id="close_modal" type="button" class="close" data-dismiss="modal" aria-label="Cerrar"> <span aria-hidden="true">&times;</span> </button>
						</div>
						<form id="form_modal" class="was-validated">
							<input type="hidden" id="modal_accion" />
							<input type="hidden" id="modal_type" />
							<div class="conten_modal_container">
								<div class="spinner_container"> <div class="spinner_label"> <i class="fas fa-cog fa-spin"></i> Procesando...</div> </div>
								<div id="modal_content" class="modal-body"></div>
								<div class="modal-body" style="padding: 0px 15px;"></div>
							</div>
							<div class="modal-footer">
								<span class="modal_msg"></span>
								<button id="submit_btn_accion" type="submit" class="btn btn-primary">Guardar</button>
							</div>
						</form>
					</div>
				</div>
				<div class="vlz_bg_close"></div>
			</div>
		';
	}

	function init_page($data, $globales){
		global $m;
		extract($data);
		extract($globales);
		extract($m);
		$columnas = '';
		foreach ($cls as $key => $c) {
			$columnas .= '<th style="'.$c['s'].'" >'.$c['t'].'</th>';
		}
		if( isset($bts) && count($bts) > 0 ){
			$botones = '';
			foreach ($bts as $key => $b) {
				$botones .= '<span onclick="_'.$b['act'].'()" id="vlz_'.$b['id'].'" class="vlz_boton"> <i class="'.$b['ico'].'"></i> </span>';
			}
		}
		$nombre_boton = ( $btn != '' ) ? $btn : 'Guardar' ;
		$botones_base = '<span onclick="_new()" id="vlz_add" data-boton="'.$nombre_boton.'" class="vlz_boton"> <i class="fas fa-plus"></i> </span>';
		if( isset($hide_bts) && $hide_bts == true ){
			$botones_base = '';
		}

		$attrs_data = '';
		if( isset($dat) ){
			foreach ($dat as $key => $value) {
				$attrs_data .= ' data-'.$key.' = "'.$value.'" ';
			}
		}
		echo '
			<div class="vlz_container_top">
				<div class="vlz_col vlz_title">'.$plu.'</div>
				<div class="vlz_col vlz_botones">
					'.$botones.'
					'.$botones_base.'
			        <span id="vlz_excel" class="vlz_boton"> <i class="far fa-file-excel"></i> </span>
			        <!-- <span id="vlz_pdf" class="vlz_boton"> <i class="fas fa-file-pdf"></i> </span> -->
			        <input type="text" id="search" />  
			        <i class="fas fa-search"></i>
				</div>
			</div>
			<div class="vlz_container_bottom">
				<div class="vlz_col vlz_tabla" '.$attrs_data.' > 
					<table id="example" class="table table-striped table-bordered nowrap dataTable no-footer" style="width:100%" >
				        <thead>
				            <tr>
				                <th style="width: 40px;">ID</th>
				                <th style="width: 120px;">Acciones</th>
				                '.$columnas.'
				            </tr>
				        </thead>
				        <tbody></tbody>
				    </table>
				</div>
			</div>
		';
		$s = DIRECTORY_SEPARATOR;
		if( !file_exists( dirname(__DIR__).$s.'modulos'.$s.$m['mod'].$s.'ajax'.$s.'back'.$s.'list.php' ) ){
			echo "El archivo [ list.php ] no se encuentra en la carpeta [ modulos/".$m['mod']."/ajax/back/ ], los datos de la tabla se cargan desde este archivo";
		}
	}

	function FF($fecha, $format = 'db' ){
		if( $format == 'db' ){
			return date("Y-m-d H:i:s", strtotime( str_replace("/", "-", $fecha) ) );
		}else{
			return date("d/m/Y", strtotime( $fecha ) );
		}
	}

	function FH($fecha ){
		return date("H:i", strtotime( $fecha ) );
	}

	function listo($info){
		echo json_encode($info);
	}

	function debug($info){
		echo '<pre>';
			print_r($info);
		echo '</pre>';
	}

	function upload($base64, $name){
		if( $base64 != '' ){
			$base64 = explode(',', $base64);
			if( count($base64) > 1 ){
				$base64 = end($base64);
			    $file = base64_decode($base64);
			    $path = dirname(__DIR__)."/files/".$name;
			    @file_put_contents($path, $file);
			    return $name;
			}else{
				return $base64[0];
			}
		}
	    return '';
	}

	function get_extra( $tipo ){
		global $wpdb;
		global $vlz; extract($vlz);
		$items = $wpdb->get_results("SELECT * FROM {$vlzpf}{$tipo} ORDER BY id ASC");
		$res = [];
		foreach ($items as $key => $item) {
			$res[ $item->id ] = $item;
		}
		return $res;
	}

	function updateGenerico($campos_db){
		unset($_POST[ 'id' ]);
		$datos = [];
		foreach ($campos_db as $key => $campo) { $valor = ( is_array( $_POST[$campo] ) ) ? json_encode( $_POST[$campo] ) : $_POST[$campo]; $datos[] = " {$campo} = '{$valor}' "; }
		$data = json_encode( $_POST );
		if(	count($datos) > 0 ){ $datos = ', '.implode(", ", $datos); }else{ $datos = ''; }
		return [
			'data' => $data,
			'datos' => $datos
		];
	}

	function initSession(){
		if( !isset($_SESSION) ){ session_start(); }
	}

	function initGlobales(){
		initSession();
		global $wpdb;
		global $vlz;
		$res = [
			"wpdb" => $wpdb
		];
		foreach ($vlz as $key => $value) {
			$res[ $key ] = $value;
		}
		return $res;
	}

?>