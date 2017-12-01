<?php
    include_once(dirname(dirname(dirname(__DIR__)))."/recursos/conexion.php");
	
	$mascotas_cliente = $db->get_results("SELECT * FROM wp_posts WHERE post_author = '{$ID}' AND post_type='pets' AND post_status = 'publish'");

    $comportamientos_array = array(
        "pet_sociable"           => "Sociables",
        "pet_sociable2"          => "No sociables",
        "aggressive_with_pets"   => "Agresivos con perros",
        "aggressive_with_humans" => "Agresivos con humanos",
    );
    $tamanos_array = array(
        "Pequeño",
        "Mediano",
        "Grande",
        "Gigante"
    );
    
    $mascotas = array();

    foreach ($mascotas_cliente as $key => $mascota) {
        $data_mascota = kmimos_get_post_meta($mascota->ID);
        $temp = array();
        foreach ($data_mascota as $key => $value) {
            switch ($key) {
                case 'pet_sociable':
                    if( $value[0] == 1 ){ $temp[] = "Sociable"; }else{ $temp[] = "No sociable"; }
                break;
                case 'aggressive_with_pets':
                    if( $value[0] == 1 ){ $temp[] = "Agresivo con perros"; }
                break;
                case 'aggressive_with_humans':
                    if( $value[0] == 1 ){ $temp[] = "Agresivo con humanos"; }
                break;
            }
        }
        $data_mascota['birthdate_pet'] = str_replace("/", "-", $data_mascota['birthdate_pet']);
        $anio = strtotime($data_mascota['birthdate_pet']);
        $edad_time = strtotime(date("Y-m-d"))-$anio;
        $edad = (date("Y", $edad_time)-1970)." año(s) ".date("m", $edad_time)." mes(es)";

        $raza = $db->get_var("SELECT nombre FROM razas WHERE id=".$data_mascota['breed_pet']);
        $mascotas[] = array(
            "nombre" => $data_mascota['name_pet'],
            "raza" => $raza,
            "edad" => $edad,
            "tamano" => $tamanos_array[ $data_mascota['size_pet'] ],
            "conducta" => implode("<br>", $temp)
        );
    }

    $mascotas_txt = "";
    foreach ($mascotas as $mascota) {
    	$mascotas_txt = "
    	<tr>
			<td class=''>".utf8_encode($mascota['nombre'])."</td>
			<td class=''>
				<table width='100%' cellspacing='0' cellpadding='0'>
					<tr>
						<td class='left top' style='width: 75px;'>
							<strong>Edad:</strong>
						</td>
						<td class='left'>
							".$mascota['edad']."
						</td>
					</tr>
					<tr>
						<td class='left' style='width: 75px;'>
							<strong>Tama&ntilde;o:</strong>
						</td>
						<td class='left top'>
							".$mascota['tamano']."
						</td>
					</tr>
					<tr>
						<td class='left top' style='width: 75px;'>
							<strong>Raza:</strong>
						</td>
						<td class='left'>
							".utf8_encode($mascota['raza'])."
						</td>
					</tr>
					<tr>
						<td class='left top' style='width: 75px;'>
							<strong>Conducta:</strong>
						</td>
						<td class='left'>
							".utf8_encode($mascota['conducta'])."
						</td>
					</tr>
				</table>
			</td>
		</tr>";
    }

    /*echo "<pre>";
    	print_r($mascotas);
    echo "</pre>";*/

?>
<table width="100%" cellspacing="0" cellpadding="0" class="tabla_horizontal">
	<tr>
		<th>Nombre</th>
		<th colspan="2">Informaci&oacute;n</th>
	</tr>
	<?php echo $mascotas_txt; ?>
</table>