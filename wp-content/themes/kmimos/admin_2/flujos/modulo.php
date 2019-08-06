<?php
	global $MODULOS_ADMIN_2;

	$MODULOS_ADMIN_2[] = array(
        'parent'        =>  'campaing',
        'title'         =>  __('Flujos'),
        'short-title'   =>  __('Flujos'),
        'access'        =>  'manage_options',
        'slug'          =>  'flujos',
        'modulo'        =>  function(){
        	init_page( 'flujos' );
        }
    );

    function get_flujos_form($data, $action = 'insert'){
		global $wpdb;

		$campaings = $wpdb->get_results("SELECT * FROM vlz_campaing ORDER BY creada ASC");
		$flujos = [];
		foreach ($campaings as $key => $campaing) {
			$data = json_decode($campaing->data);
			if( $data->hacer_despues+0 == 0 ){
				$flujos[ $campaing->id ] = [
					$data->data->titulo
				];
			}else{
				$data = json_decode($campaing->data);
				$flujos[ $campaing->id ] = [
					$data->data->titulo,
					$data->campaing_anterior,
					$data->campaing_despues_no_abre,
				];
			}
		}
		$grafo = '';
		foreach ($flujos as $key => $data) {
			if( count($data) == 1 ){
				$grafo .= '{ key: '.$key.', text: "'.$data[0].'", fill: "#ccc", stroke: "#4d90fe" },';
			}else{
				if( $data[2] == "si" ){
					$grafo .= '{ key: "'.$key.'_condicional", text: "SI", fill: "#ccc", stroke: "#4d90fe", parent: '.$data[1].' },';
					$grafo .= '{ key: '.$key.', text: "'.$data[0].'", fill: "#ccc", stroke: "#4d90fe", parent: "'.$key.'_condicional" },';
				}else{
					$grafo .= '{ key: "'.$key.'_condicional", text: "NO", fill: "#ccc", stroke: "#4d90fe", parent: '.$data[1].' },';
					$grafo .= '{ key: '.$key.', text: "'.$data[0].'", fill: "#ccc", stroke: "#4d90fe", parent: "'.$key.'_condicional" },';
				}
			}
		}

		echo '
			<div id="myDiagramDiv" style="border: solid 1px black; width:100%; height:500px"></div>
			<script id="code">
				function init() {
					if (window.goSamples) goSamples();
					var $ = go.GraphObject.make;
					myDiagram =
					$(go.Diagram, "myDiagramDiv",
						{
							allowCopy: false,
							allowDelete: false,
							allowMove: false,
							initialAutoScale: go.Diagram.Uniform,
							layout:
							$(FlatTreeLayout,
								{
									angle: 90,
									compaction: go.TreeLayout.CompactionNone
								}
							),
							"undoManager.isEnabled": false
						}
					);

					myDiagram.nodeTemplate =
					$(
						go.Node, 
						"Vertical",
						{ selectionObjectName: "BODY" },
						$(go.Panel, "Auto", { name: "BODY" },
						$(
							go.Shape, 
							"RoundedRectangle",
							new go.Binding("fill"),
							new go.Binding("stroke")
						),
						$(
							go.TextBlock,
							{ font: "bold 12pt Arial, sans-serif", margin: new go.Margin(4, 2, 2, 2) },
							new go.Binding("text"))
						),
						$(
							go.Panel,
							{ height: 17 },
							$("TreeExpanderButton")
						)
					);

					myDiagram.linkTemplate =
					$(
						go.Link,
						$( go.Shape, { strokeWidth: 1.5 } )
					);

					var nodeDataArray = [
						'.$grafo.'
					];

					myDiagram.model = $(go.TreeModel, { nodeDataArray: nodeDataArray }); 
				}

				function FlatTreeLayout() {
					go.TreeLayout.call(this);
				}
				go.Diagram.inherit(FlatTreeLayout, go.TreeLayout);

				FlatTreeLayout.prototype.commitLayout = function() {
					go.TreeLayout.prototype.commitLayout.call(this);
					var y = -Infinity;
					this.network.vertexes.each(function(v) {
						y = Math.max(y, v.node.position.y);
					});
					this.network.vertexes.each(function(v) {
						if (v.destinationEdges.count === 0) {
							v.node.position = new go.Point(v.node.position.x, y);
							v.node.toEndSegmentLength = Math.abs(v.centerY - y);
						} else {
							v.node.toEndSegmentLength = 10;
						}
					});
				};
			</script>
			<script>
				jQuery(document).ready(function() {
				    _flujo( '.$data->id.' );
				    init();
				});
			</script>
		';
    }

	add_action( 'wp_ajax_vlz_flujos_list', function() {
		extract($_POST);
		global $wpdb;
		$data["data"] = [];
		$info = $wpdb->get_results("SELECT * FROM vlz_campaing WHERE data LIKE '%hacer_despues\":\"0%' ORDER BY creada DESC");
		foreach ($info as $key => $value) {
			$d = json_decode($value->data);
			$data["data"][] = [
				$value->id,
				$d->data->titulo,
				'<span class="btn btn-primary btn-s" onclick="_modal( jQuery(this) )" data-id="'.$value->id.'" data-modal="flujos_edit" data-titulo="Ver Flujo" >Ver Flujo</span>'
			];
		}
		echo json_encode($data);
	   	die();
	} );

	add_action( 'wp_ajax_vlz_flujos_new', function() {
		extract($_POST);
		global $wpdb;
		get_flujos_form([]);
	   	die();
	} );

	add_action( 'wp_ajax_vlz_flujos_edit', function() {
		extract($_POST);
		global $wpdb;
		$data = $wpdb->get_row("SELECT * FROM vlz_flujos WHERE id = ".$ID);
		get_flujos_form($data, 'update');
	   	die();
	} );

	function get_flujos_form_cliente($data, $action = 'update'){
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
			<form id="flujos_form_cliente" data-modulo="flujos" >
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
				_'.$action.'("flujos_form_cliente");
			</script>
		';
	}

	add_action( 'wp_ajax_vlz_flujos_edit_cliente', function() {
		extract($_POST);
		global $wpdb;
		$ID = explode("|", $ID);
		$data = $wpdb->get_row("SELECT * FROM vlz_flujos WHERE id = ".$ID[0]);
		$data->correo = $ID[1];
		get_flujos_form_cliente($data, 'update');
	   	die();
	} );

	add_action( 'wp_ajax_vlz_flujos_del_form', function() {
		extract($_POST);
		global $wpdb;
		$data = $wpdb->get_row("SELECT * FROM vlz_flujos WHERE id = ".$ID);
		$data = (array)  json_decode($data->data);
		extract($data);

		echo '
			<form id="flujos_form" data-modulo="flujos" >
				<input type="hidden" name="id" value="'.$ID.'" />
				<div class="form-group">
					<label for="titulo">¿Esta seguro de eliminar esta Lista?</label>
					<input type="text" class="form-control" id="titulo" value="'.$data->titulo.'" readonly />
				</div>
				<div class="text-right">
					<button id="btn_submit_modal" type="submit" class="btn btn-primary">Eliminar</button>
				</div>
			</form>
			<script>_delete("flujos_form");</script>
		';

	   	die();
	} );

	add_action( 'wp_ajax_vlz_flujos_del_modal', function() {
		extract($_POST);
		global $wpdb;
		$_ID = explode("|", $ID);
		$data = $wpdb->get_row("SELECT * FROM vlz_flujos WHERE id = ".$_ID[0]);
		
		echo '
			<form id="flujos_form" data-modulo="flujos" >
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
			<script>_delete("flujos_form");</script>
		';
	   	die();
	} );

	add_action( 'wp_ajax_vlz_flujos_insert', function() {
		extract($_POST);
		global $wpdb;
		$titulo = $data["titulo"];
		$existe = $wpdb->get_var("SELECT id FROM vlz_flujos WHERE data LIKE '%\"titulo\":\"{$titulo}\"%' ");
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
			$wpdb->query("INSERT INTO vlz_flujos VALUES (NULL, '{$data}', NOW())");
			echo json_encode([
				"error" => "",
				"msg" => "Lista Creada Exitosamente",
			]);
		}else{
			echo json_encode([
				"error" => "Ya existe una flujos con este nombre",
				"msg" => "",
			]);
		}
	   	die();
	} );

	add_action( 'wp_ajax_vlz_flujos_update', function() {
		extract($_POST);
		global $wpdb;
		if( $form == "cliente" ){
			$lista = $wpdb->get_row("SELECT * FROM vlz_flujos WHERE id = ".$id);
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
			$sql = "UPDATE vlz_flujos SET data = '{$data}' WHERE id = ".$id;
			$wpdb->query( $sql );
			echo json_encode([
				"error" => "",
				"msg" => "Cliente Actualizado Exitosamente",
			]);
		}else{
			$titulo = $data["titulo"];
			$existe = $wpdb->get_var("SELECT id FROM vlz_flujos WHERE data LIKE '%\"titulo\":\"{$titulo}\"%' AND id != ".$id);
			if( empty($existe) ){

				$lista = $wpdb->get_row("SELECT * FROM vlz_flujos WHERE id = ".$id);
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
				unset($_POST["table_flujos_length"]);
				unset($_POST["form"]);
				$_POST["suscriptores"] = $suscriptores;
				$data = json_encode($_POST);
				$sql = "UPDATE vlz_flujos SET data = '{$data}' WHERE id = ".$id;
				$wpdb->query( $sql );
				echo json_encode([
					"error" => "",
					"msg" => "Lista Actualizada Exitosamente",
				]);
			}else{
				echo json_encode([
					"error" => "Ya existe una flujos con este nombre",
					"msg" => "",
				]);
			}
		}
	   	die();
	} );

	add_action( 'wp_ajax_vlz_flujos_delete', function() {
		extract($_POST);
		global $wpdb;
		if( $form == "modal" ){
			$_ID = explode("|", $id);
			$lista = $wpdb->get_row("SELECT * FROM vlz_flujos WHERE id = ".$_ID[0]);
			$info = json_decode($lista->data);

			$suscriptores = [];
			foreach ($info->suscriptores as $key => $suscriptor) {
				if( $_ID[2] != $suscriptor[1] ){
					$suscriptores[] = $suscriptor;
				}
			}
			$info->suscriptores = $suscriptores;
			$data = json_encode($info);
			$sql = "UPDATE vlz_flujos SET data = '{$data}' WHERE id = ".$_ID[0];
			$wpdb->query($sql);
			
			echo json_encode([
				"error" => "",
				"msg" => "Cliente Eliminado Exitosamente",
			]);
		}else{
			$wpdb->query("DELETE FROM vlz_flujos WHERE id = ".$id);
			echo json_encode([
				"error" => "",
				"msg" => "Lista Eliminada Exitosamente",
			]);
		}
	   	die();
	} );

?>