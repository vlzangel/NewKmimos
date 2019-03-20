<?php
	date_default_timezone_set('America/Mexico_City');

    global $wpdb;
    global $current_user;

    $user_id = $current_user->ID;

    $servicios = array(
		"todos" => "Todos",
		"hospedaje" => "Hospedaje",
		"guarderia" => "Guardería",
		"adiestramiento-basico" => "Adiestramiento Básico",
		"adiestramiento-intermedio" => "Adiestramiento Intermedio",
		"adiestramiento-avanzado" => "Adiestramiento Avanzado",
		"paseos" => "Paseos"
	);

	$opciones = "";
	$opciones .= "<option value='todos'>Todos</option>";
	$mis_servicios = $wpdb->get_results("SELECT ID FROM wp_posts WHERE post_author = '{$user_id}' AND post_type = 'product' AND post_status = 'publish' ");
	foreach ($mis_servicios as $servicio) {
		$tipo = $wpdb->get_var("
            SELECT
                tipo_servicio.slug AS tipo
            FROM 
                wp_term_relationships AS relacion
            LEFT JOIN wp_terms as tipo_servicio ON ( tipo_servicio.term_id = relacion.term_taxonomy_id )
            WHERE 
                relacion.object_id = '{$servicio->ID}' AND
                relacion.term_taxonomy_id != 28
        ");
		$opciones .= "<option value='{$servicio->ID}' data-type='{$tipo}' >".$servicios[ $tipo ]."</option>";
	}

	$date = date("Y-m-d");

    $no_disponibilidades = $wpdb->get_results("SELECT * FROM cupos WHERE cuidador = '{$user_id}' AND no_disponible = 1 AND fecha >= '{$date}'");

    $_rangos = array();

    foreach ($no_disponibilidades as $data) {
    	$_rangos[ $data->tipo ][] = array(
    		"servicio_id" => $data->servicio_id,
    		"servicio_str" => $data->tipo,
    		"fecha" 	  => dateFormat($data->fecha)
    	);
    }

    $tabla = '<div id="lista_fechas">';
	    if( count($_rangos) > 0 ){
		    foreach ($_rangos as $servicio => $rangos) {

		    	$rangos_html = '';
		    	$rangos_del = '';

		    	foreach ($rangos as $key => $rango) {
			    	$rangos_html .= '
			    		<div>
			    			<span>
			    				'.$rango['fecha'].'
			    			</span>
			    		</div>
			    	';
		    	}

		    	$tabla .= '
		    		<div class="vlz_tabla">
		            	<div class="vlz_tabla_superior">
		            		<div class="vlz_row">
			                	<div class="vlz_tabla_cuidador vlz_celda vlz_servicio">
			                		<span>Servicio</span>
			                		<div>'.$servicios[ $servicio ].'</div>
			                	</div>
			                	<div class="vlz_tabla_cuidador vlz_celda">
			                		<span>Fecha</span>
			                		<div class="vlz_rangos">'.$rangos_html.'</div>
			                	</div>
		                	</div>
		            	</div>
		        	</div>
		    	';
		    }
		}else{
			$tabla = '<h2>No hay registros ingresados</h2>'.$tabla;
		}
    $tabla .= "</div>";

	$CONTENIDO = '
		<h1 class="theme_tite theme_table_title">No estoy disponible en:</h1>

		<input type="hidden" name="accion" value="perfil" />
        <input type="hidden" name="user_id" id="user_id" value="'.$user_id.'" />
        <input type="hidden" name="tipo" id="tipo" />

		<div class="tabla_disponibilidad_box"> 

			'.$tabla.'

			<div class="botones_container">
		        <div class="botones_box box_100">
		        	<input type="button" id="editar_disponibilidad" class="km-btn-primary" value="Editar Disponibilidad" />
		        </div>
	        </div> 
		</div>

		<div class="fechas" >
			<div class="fechas_box " >

				<div class="fechas_item">
					<select id="servicio" name="servicio">
						'.$opciones.'					
					</select>
		        </div>

				<div class="fechas_item">
					<i class="icon-calendario embebed"></i>
			        <input type="text" id="inicio" name="inicio" class="fechas" placeholder="Inicio" min="'.date("Y-m-d").'" readonly>
		        </div>

				<div class="fechas_item">
					<div class="icono"><i class="icon-calendario embebed"></i></div>
			        <input type="text" id="fin" name="fin" class="fechas" placeholder="Fin" disabled readonly>
		        </div>

				<div class="fechas_item">
					<select id="status" name="status">
						<option>Seleccione estatus</option>				
						<option value="NO">No voy a estar disponible</option>				
						<option value="SI">Si voy a estar disponible</option>				
					</select>
		        </div>
		    </div>

	        <div class="botones_container">
		        <div class="botones_box box_50">
		        	<input type="button" id="guardar_disponibilidad" class="km-btn-primary" value="Guardar" />
		        </div>
		        <div class="botones_box box_50">
		        	<input type="button" id="volver_disponibilidad" class="km-btn-primary" value="Volver" />
		        </div>
	        </div>
	    </div>

		<div class="completado" >
			<h1>Disponibilidad actualizada correctamente</h1>

	        <div class="botones_container">
		        <div class="botones_box box_50">
		        	<input type="button" id="volver_disponibilidad_2" class="km-btn-primary" value="Aceptar" />
		        </div>
	        </div>
	    </div>
	';





































/*

    $servicios = array(
		2598 => "Hospedaje",
		2599 => "Guardería",
		2602 => "Adiestramiento Básico",
		2606 => "Adiestramiento Intermedio",
		2607 => "Adiestramiento Avanzado",
		2601 => "Paseos"
	);

    $servicios = array(
		"hospedaje" => "Hospedaje",
		"guarderia" => "Guardería",
		"adiestramiento-basico" => "Adiestramiento Básico",
		"adiestramiento-intermedio" => "Adiestramiento Intermedio",
		"adiestramiento-avanzado" => "Adiestramiento Avanzado",
		"paseos" => "Paseos"
	);

    $productos 	= $wpdb->get_results("SELECT ID FROM wp_posts WHERE post_author = '{$user_id}' AND post_type = 'product'");
    $rangos = array();
    foreach ($productos as $key => $value) {
    	$temporal = $wpdb->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = '{$value->ID}' AND meta_key = '_wc_booking_availability' ");
    	$servicio = $wpdb->get_results("SELECT term_taxonomy_id FROM wp_term_relationships WHERE object_id = '{$value->ID}' ");
    	$temp = unserialize( $temporal );
    	$xrangos = "";
	    if( $temp != '' ){
	    	$xrangos = array();
		    foreach ($temp as $key2 => $value2) {
		    	if( $value2['from'] != '' && $value2['to'] != '' ){
			    	$xrangos[] = array(
			    		"from" => $value2['from'],
			    		"to" => $value2['to']
			    	);
		    	}
		    }
	    }
	    $rangos[] = array(
	    	"servicio_id" => $value->ID,
	    	"servicio" => $servicios[ $servicio[1]->term_taxonomy_id ],
	    	"rangos" => $xrangos
	    );
    }

    $tabla = '<div>';

	$opciones = "<OPTION value='Todos' >Todos</OPTION>";
	$cont = 0;
    foreach ($rangos as $value) {
    	$servicio_id = $value['servicio_id'];
    	$servicio = $value['servicio'];
    	$opciones .= "<OPTION value='{$servicio_id}' >{$servicio}</OPTION>";
    	if( $value['rangos'] != "" ){
    		foreach ($value['rangos'] as $rango) {

    			$cont++;

    			$from = explode("-", $rango['from']);
    			if( count($from) > 1 ){ $rango['from'] = $from[2]."/".$from[1]."/".$from[0]; }

    			$to = explode("-", $rango['to']);
    			if( count($to) > 1 ){ $rango['to'] = $to[2]."/".$to[1]."/".$to[0]; }

		    	$tabla .= '
		    		<div class="vlz_tabla">
	                	<div class="vlz_tabla_superior">
	                		<div class="vlz_row">
			                	<div class="vlz_tabla_cuidador vlz_celda">
			                		<span>Servicio</span>
			                		<div>'.$servicio.'</div>
			                	</div>
			                	<div class="vlz_tabla_cuidador vlz_celda">
			                		<span>Fecha</span>
			                		<div>'.$rango['from'].' <b> > </b> '.$rango['to'].'</div>
			                	</div>
		                	</div>
		                	<div class="vlz_tabla_cuidador vlz_botones vlz_celda boton_interno">
		                		<a data-id="'.$servicio_id.'" data-inicio="'.$rango['from'].'" data-fin="'.$rango['to'].'" class="vlz_accion vlz_cancelar cancelar"> <i class="fa fa-trash-o" aria-hidden="true"></i></a>
		                	</div>
	                	</div>
	                	<div class="vlz_tabla_cuidador vlz_botones vlz_celda boton_fuera">
	                		<a data-id="'.$servicio_id.'" data-inicio="'.$rango['from'].'" data-fin="'.$rango['to'].'" class="vlz_accion vlz_cancelar cancelar"> Eliminar </a>
	                	</div>
                	</div>
		    	';
	    	}
    	}
    }

	if( $cont == 0 ){
		$tabla = '<h2>No hay registros ingresados</h2>'.$tabla;
	}

    $tabla .= "</div>";

	$CONTENIDO = '
		<h1 class="theme_tite theme_table_title">No estoy disponible en:</h1>

		<input type="hidden" name="accion" value="perfil" />
        <input type="hidden" name="user_id" id="user_id" value="'.$user_id.'" />

		<div class="tabla_disponibilidad_box"> 

			'.$tabla.'

			<div class="botones_container">
		        <div class="botones_box box_100">
		        	<input type="button" id="editar_disponibilidad" class="km-btn-primary" value="Editar Disponibilidad" />
		        </div>
	        </div> 
		</div>

		<div class="fechas" >
			<div class="fechas_box " >

				<div class="fechas_item">
					<SELECT id="servicio" name="servicio">
						'.$opciones.'					
					</SELECT>
		        </div>

				<div class="fechas_item">
					<i class="icon-calendario embebed"></i>
			        <input type="text" id="inicio" name="inicio" class="fechas" placeholder="Inicio" min="'.date("Y-m-d").'" readonly>
		        </div>

				<div class="fechas_item">
					<div class="icono"><i class="icon-calendario embebed"></i></div>
			        <input type="text" id="fin" name="fin" class="fechas" placeholder="Fin" disabled readonly>
		        </div>
		    </div>

	        <div class="botones_container">
		        <div class="botones_box box_50">
		        	<input type="button" id="guardar_disponibilidad" class="km-btn-primary" value="Guardar" />
		        </div>
		        <div class="botones_box box_50">
		        	<input type="button" id="volver_disponibilidad" class="km-btn-primary" value="Volver" />
		        </div>
	        </div>
	    </div>

	';

	*/
?>