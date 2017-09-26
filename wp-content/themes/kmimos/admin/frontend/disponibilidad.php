<?php
	date_default_timezone_set('America/Mexico_City');

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

/*    echo "<pre>";
    	print_r( $rangos );
    echo "</pre>";*/

    $tabla = "
    	<table class='tabla_disponibilidad'>
    		<tr>
    			<th> Servicio </th>
    			<th> Desde </th>
    			<th> Hasta </th>
    			<th> Acción </th>
    		</tr>
    ";

	$opciones = "<OPTION value='Todos' >Todos</OPTION>";
    foreach ($rangos as $value) {
    	$servicio_id = $value['servicio_id'];
    	$servicio = $value['servicio'];
    	$opciones .= "<OPTION value='{$servicio_id}' >{$servicio}</OPTION>";
    	if( $value['rangos'] != "" ){
    		foreach ($value['rangos'] as $rango) {

    			$from = date("d/m/Y", strtotime($rango['from']));
    			$to = date("d/m/Y", strtotime($rango['to']));

    			if( $servicio != '' ){
    				$servicio_top = "border-top: 1px solid #dadada;";
    			}else{
    				$servicio_top = "border-top: 1px solid #f1f1f1;";
    			}

		    	$tabla .= "
		    		<tr>
		    			<td style='{$servicio_top}'> {$servicio} </td>
		    			<td> {$from} </td>
		    			<td> {$to} </td>
		    			<td class='acciones' > 
							<input type='button' class='delete_disponibilidad' value='Eliminar' data-id='{$servicio_id}' data-inicio='{$rango['from']}' data-fin='{$rango['to']}' />
						</td>
		    		</tr>
		    	";

    			if( $servicio != '' ){
    				$servicio = "";
    			}
	    	}
    	}
    }

    $tabla .= "</table>";

	$CONTENIDO = '
		<h1 class="theme_tite theme_table_title">Administración de disponibilidad</h1>

		<input type="hidden" name="accion" value="perfil" />
        <input type="hidden" name="user_id" id="user_id" value="'.$user_id.'" />

		<div class="fechas_box table_main tabla_disponibilidad_box"> 
			'.$tabla.'
			<div class="botones_container">
		        <div class="botones_box">
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
			        <input type="text" id="inicio" name="inicio" class="fechas" placeholder="Inicio" min="'.date("Y-m-d").'">
		        </div>

				<div class="fechas_item">
					<div class="icono"><i class="icon-calendario embebed"></i></div>
			        <input type="text" id="fin" name="fin" class="fechas" placeholder="Fin" disabled>
		        </div>
		    </div>

	        <div class="botones_container">
		        <div class="botones_box">
		        	<input type="button" id="guardar_disponibilidad" class="km-btn-primary" value="Guardar" />
		        </div>
		        <div class="botones_box">
		        	<input type="button" id="volver_disponibilidad" class="km-btn-primary" value="Volver" />
		        </div>
	        </div>
	    </div>

	';
?>