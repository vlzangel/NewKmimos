<?php
	include dirname(dirname(__DIR__)).'/wp-load.php';
	include dirname(__DIR__).'/ubicaciones.php';

	extract($_POST);

	global $wpdb;

	$sql = "SELECT * FROM cuidadores WHERE id = ".$id;
	$cuidador = $wpdb->get_row($sql);
	
	$estado = explode("=", $cuidador->estados);
	$municipio = explode("=", $cuidador->municipios);
	$atributo = unserialize($cuidador->atributos);

	/*
	echo "<pre>";	
		print_r($estado);
		print_r($municipio);
		print_r($atributo);
	echo "</pre>";
	echo "SELECT * FROM colonias WHERE estado = '".$estado[1]."' AND municipio = '".$municipio[1]."' ORDER BY name ASC";
	*/
	echo $sql_co = "SELECT * FROM colonias WHERE estado = '".$estado[1]."' AND municipio = '".$municipio[1]."' ORDER BY name ASC";
	$colonias = $wpdb->get_results($sql_co);

	$select_colonias = '<select name="colonia" >';
	$select_colonias .= '<option value="">Seleccione la colonia correspondiente</option>';
	foreach ($colonias as $colonia) {
		$select_colonias .= '<option value="'.$colonia->id.'">'.($colonia->name).'</option>';
	}
	$select_colonias .= '</select>';
	echo '
		<input type="hidden" name="id" value="'.$id.'" >
		<div> <b>Estado y Delegación: </b> '.$ubicaciones[ $estado[1].'_'.$municipio[1] ].' </div>
		<div> <b>Colonia (actual): </b> '.$atributo['colonia'].' </div>
		<div> <b>Nueva Colonia: </b> '.$select_colonias.' </div>
		<div>
			<input type="submit" value="Actualizar" />
		</div>
	';
	
?>