<?php

	date_default_timezone_set('America/Bogota');

    global $wpdb;
    global $current_user;

    $user_id = $current_user->ID;

    $servicios = array(
		2598 => "Hospedaje",
		2599 => "Guardería",
		2602 => "Adiestramiento Básico",
		2606 => "Adiestramiento Intermedio",
		2607 => "Adiestramiento Avanzado",
		2601 => "Paseos"
	);

    $productos 	= $wpdb->get_results("SELECT ID FROM wp_posts WHERE post_author = '{$user_id}' AND post_type = 'product'");
    $rangos = array();
    foreach ($productos as $key => $value) {
    	$servicio = $wpdb->get_results("SELECT term_taxonomy_id FROM wp_term_relationships WHERE object_id = '{$value->ID}' ");
	    $xrangos = array();
	    $fechas = $wpdb->get_results("SELECT * FROM cupos WHERE servicio = '{$value->ID}' AND fecha >= NOW() AND no_disponible = 1");
	    foreach ($fechas as $fecha) {
	    	$xrangos[] = $fecha->fecha;
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
    	if( count( $value['rangos'] ) > 0 ){
    		$rangos_str = "";
    		foreach ($value['rangos'] as $rango) {
    			$cont++;
		    	$rangos_str .= '<div>'.date("d/m/Y", strtotime($rango) ).' <a data-id="'.$servicio_id.'" data-inicio="'.$rango.'" data-fin="'.$rango.'" class="vlz_accion vlz_cancelar cancelar"> Eliminar </a></div>';
	    	}
    		$tabla .= '
	    		<div class="vlz_tabla">
                	<div class="vlz_tabla_superior">
                		<div class="vlz_row">
		                	<div class="vlz_tabla_cuidador vlz_celda" style="width: 30%;">
		                		<span>Servicio</span>
		                		<div>'.$servicio.'</div>
		                	</div>
		                	<div class="vlz_tabla_cuidador vlz_celda">
		                		<span>Fechas</span>
		                		<div class="fechas_bloqueadas">'.$rangos_str.'</div>
		                	</div>
	                	</div>
                	</div>
            	</div>
	    	';
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
?>