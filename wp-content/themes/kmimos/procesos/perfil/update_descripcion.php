<?php 

	$cuidador = $db->get_row("SELECT * FROM cuidadores WHERE user_id = {$user_id}");

	$mascotas_cuidador = array(
		'pequenos' => $tengo_pequenos,
		'medianos' => $tengo_medianos,
		'grandes'  => $tengo_grandes,
		'gigantes' => $tengo_gigantes
	);
	$mascotas_cuidador = serialize($mascotas_cuidador);
	$tamanos_aceptados = array(
		'pequenos' => $acepta_pequenos,
		'medianos' => $acepta_medianos,
		'grandes'  => $acepta_grandes,
		'gigantes' => $acepta_gigantes
	);
	$tamanos_aceptados = serialize($tamanos_aceptados);

	$edades_aceptadas = array(
		'cachorros' => $acepta_cachorros,
		'adultos'	=> $acepta_adultos
	);
	$edades_aceptadas = serialize($edades_aceptadas);
	$comportamientos_aceptados = array(
		'sociables' 		  => $sociables,
		'no_sociables'		  => $no_sociables,
		'agresivos_humanos'	  => $agresivos_humanos,
		'agresivos_mascotas'  => $agresivos_mascotas
	);

	$comportamiento_gatos = [];
	$comportamientos_db = $db->get_results("SELECT * FROM comportamientos_mascotas");
    foreach ($comportamientos_db as $value) {
    	$comportamiento_gatos[ $value->slug ] = $_POST[ 'comportamiento_gatos_'.$value->slug ];
    }

    $comportamientos_aceptados = [
    	"perros" => $comportamientos_aceptados,
    	"gatos" => $comportamiento_gatos
    ];

	$comportamientos_aceptados = serialize($comportamientos_aceptados);
	
	$ini_url = substr($video_youtube, 0, 5);
	if( $ini_url == 'https' ){
		preg_match_all("#v=(.*?)&#", $video_youtube."&", $matches);
		if( count( $matches[0] ) > 0 ){
			$video_youtube = $matches[1][0];
		}else{
			preg_match_all("#be/(.*?)\?#", $video_youtube."?", $matches);
			$video_youtube = $matches[1][0];
		}
	}
/*
	$atributos = array(
		'yard'	  		=> $yard,
		'green'		  	=> $green,
		'propiedad' 	=> $propiedad,
		'esterilizado'  => $solo_esterilizadas,
		'emergencia' 	=> $emergencia,
		'video_youtube' => $video_youtube,
		'nacimiento'	=> $fecha,
		'gatos'		=> $gatos
	);
	$atributos = serialize($atributos);
*/

	$atributos = (array) unserialize( $cuidador->atributos );

	$atributos["yard"] = $yard;
	$atributos["green"] = $green;
	$atributos["propiedad"] = $propiedad;
	$atributos["esterilizado"] = $solo_esterilizadas;
	$atributos["emergencia"] = $emergencia;
	$atributos["video_youtube"] = $video_youtube;
	$atributos["nacimiento"] = $fecha;
	$atributos["tipo_doc"] = $tipo_doc;
	$atributos["gatos"] = $gatos;

	$atributos["colonia"] = $colonia;
	$atributos["postal"] = $postal;

	$atributos = serialize($atributos);

	$latitud  = $lat;
	$longitud = $lng;

	$mascotas_cuidador = str_replace('"', '\"', $mascotas_cuidador);
	$tamanos_aceptados = str_replace('"', '\"', $tamanos_aceptados);
	$edades_aceptadas = str_replace('"', '\"', $edades_aceptadas);
	$atributos = str_replace('"', '\"', $atributos);
	$comportamientos_aceptados = str_replace('"', '\"', $comportamientos_aceptados);

	if( $dni+0 == 0 ){ $dni = "0000000000000"; }
	if( strlen($dni) > 13 ){
		$dni = substr($dni, 0, 13);
	}else{
		$dni = str_pad($dni, 13, "0", STR_PAD_LEFT);
	}

	$cuidador = $db->get_row("SELECT * FROM cuidadores WHERE user_id = {$user_id}");
	$db->query("UPDATE cupos SET acepta = '{$acepto_hasta}' WHERE cuidador = {$user_id} OR cuidador = {$cuidador->id_post} ");
    $db->query("UPDATE cupos SET full = 1 WHERE ( cuidador = {$user_id} OR cuidador = {$cuidador->id_post} ) AND ( cupos >= acepta ) ");
    $db->query("UPDATE cupos SET full = 0 WHERE ( cuidador = {$user_id} OR cuidador = {$cuidador->id_post} ) AND ( cupos < acepta ) ");


	$sql  = "UPDATE cuidadores SET dni = '{$dni}', experiencia = '{$cuidando_desde}', direccion = '{$direccion}', check_in = '{$entrada}', check_out = '{$salida}', num_mascotas = '{$num_mascotas_casa}', mascotas_permitidas = '{$acepto_hasta}', latitud = '{$latitud}', longitud = '{$longitud}' WHERE id = {$cuidador_id}; ";
	$sql .= "UPDATE cuidadores SET estados = '={$estado}=', municipios = '={$delegacion}=', mascotas_cuidador = '{$mascotas_cuidador}', tamanos_aceptados = '{$tamanos_aceptados}', edades_aceptadas = '{$edades_aceptadas}', atributos = '{$atributos}', comportamientos_aceptados = '{$comportamientos_aceptados}' WHERE id = {$cuidador_id}; ";
	//$sql .= "UPDATE ubicaciones SET estados = '={$estado}=', municipios = '={$delegacion}=' WHERE cuidador = {$cuidador_id}; ";

	$query = "SELECT * FROM wp_posts WHERE post_author='{$user_id}' AND post_type='product'";
	$result = $db->get_results($query);
	foreach($result as $product){
		$product_id = $product->ID;
		$sql .= "UPDATE wp_postmeta SET meta_value = '{$acepto_hasta}' WHERE post_id = {$product_id} AND meta_key = '_wc_booking_qty'; ";
		$sql .= "UPDATE wp_postmeta SET meta_value = '{$acepto_hasta}' WHERE post_id = {$product_id} AND meta_key = '_wc_booking_max_persons_group'; ";
	}

	$db->query_multiple( utf8_decode($sql) );

	$respuesta = array(
		"status" => "OK"
	);
?>