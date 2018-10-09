<?php
	$adicionales_principales = array(
        "guarderia"                 => "Guarder&iacute;a",
        "adiestramiento_basico"     => "Adiestramiento B&aacute;sico",
        "adiestramiento_intermedio" => "Adiestramiento Intermedio",
        "adiestramiento_avanzado"   => "Adiestramiento Avanzado"
    );
    
    $adicionales_extra = array(
        "bano"                      => "Ba&ntilde;o",
        "corte"                     => "Corte de pelo y u&ntilde;as",
        "limpieza_dental"           => "Limpieza Dental",
        "acupuntura"                => "Acupuntura",
        "visita_al_veterinario"     => "Visita al Veterinario"
    );

    $tam = array(
        "pequenos" => "Peque&ntilde;os",
        "medianos" => "Medianos",
        "grandes"  => "Grandes",
        "gigantes" => "Gigantes",
    );



    $mascotas = array(
		"pequenos" => "Peque&ntilde;as",
		"medianos" => "Medianas",
		"grandes"  => "Grandes",
		"gigantes" => "Gigantes",
	);

    $preciosSugeridos = array(
        "pequenos" => "Te sugerimos un precio entre $120 a $180 pesos.",
        "medianos" => "Te sugerimos un precio entre $180 a $220 Pesos.",
        "grandes"  => "Te sugerimos un precio entre $220 a $250 pesos.",
        "gigantes" => "Te sugerimos un precio entre $250 a $350 pesos"
    );

    global $wpdb;

    $sql = "SELECT * FROM cuidadores WHERE user_id = ".$user_id;

    $cuidador = $wpdb->get_row($sql);

    $atributos = unserialize($cuidador->atributos);

    if( $atributos["gatos"] == "Si" ){
        $tam["gatos"] = "Gatos";
        $mascotas["gatos"] = "Gatos";
    }

    $hospedaje = "";
    $precios_hospedaje = unserialize($cuidador->hospedaje);

    foreach ($tam as $key => $value) {
        if( $precios_hospedaje[$key] == "" ){ $precios_hospedaje[$key] = 0; }
        $hospedaje .= "
            <div class='vlz_celda_25'>
                <label>".$value."</label>
                <input 
                    type='number' 
                    step='0.01' 
                    min=0 
                    data-minvalue=0 
                    data-charset='num' 
                    class='vlz_input' 
                    id='hospedaje_".$key."' 
                    name='hospedaje_".$key."' 
                    value='".$precios_hospedaje[$key]."' 
                    data-toggle='tooltip' 
                    data-title='".$preciosSugeridos[ $key ]."' 
                />
            </div>
        ";
    }


	$status_servicios = array();
	$sql = "SELECT * FROM wp_posts WHERE post_author = {$user_id} AND post_type = 'product'";
    $productos = $wpdb->get_results($sql);
    foreach ($productos as $producto) {
    	$servicio = explode("-", $producto->post_name);
    	$status_servicios[ $servicio[0] ] = $producto->post_status;
    }

    $precios_adicionales_cuidador = unserialize($cuidador->adicionales);

    $adicionales = array(
    	"guarderia"						=> "Guardería",
    	"paseos"						=> "Paseos",
    	"adiestramiento_basico"			=> "Entrenamiento Básico",
    	"adiestramiento_intermedio"		=> "Entrenamiento Intermedio",
    	"adiestramiento_avanzado"		=> "Entrenamiento Avanzado"
    );
    $precios_adicionales = "";
    foreach ($adicionales as $key => $value) {
    	$temp = "";
    	foreach ($tam as $key2 => $value2) {
            if( $key2 == 'gatos' && $key != 'guarderia' ){

            }else{
                if( isset($precios_adicionales_cuidador[$key] ) ){
                    $precio = $precios_adicionales_cuidador[$key][$key2];
                }else{
                    $precio = "";
                }
                
                if( $precio == "" ){ $precio = 0; }

                $temp .= "
                    <div class='vlz_celda_25'>
                        <label>".$value2."</label>
                        <input type='number' step='0.01' min=0 data-minvalue=0 data-charset='num' class='vlz_input' id='".$key."_".$key2."' name='".$key."_".$key2."' value='".$precio."' data-toggle='tooltip' data-title='Ingresa el precio de {$value} para mascotas ".($mascotas[ $key2 ])."' />
                    </div>
                ";
            }
    	}

		//if( $status_servicios[ $key ] == 'publish' ){
		if(isset($precios_adicionales_cuidador['status_'.$key]) && $precios_adicionales_cuidador['status_'.$key]==1){
    		$boton = "<input type='button' value='Activado' class='vlz_activador km-btn-primary vlz_activado' id='status_{$key}' > <input type='hidden' id='oculto_status_{$key}' name='status_{$key}' value='1' >";
    	}else{
    		$boton = "<input type='button' value='Desactivado' class='vlz_activador km-btn-primary vlz_desactivado' id='status_{$key}' > <input type='hidden' id='oculto_status_{$key}' name='status_{$key}' value='0' >";
    	}

        if( $key == "paseos" ){
            $precios_adicionales .= "
                <div class='sugerencia_paseos'>
                    <div>¿Cómo funciona paseos?</div>
                    <div>El servicio dura de 2 a 3 horas dependiendo las condiciones de cada mascota, pregunta al cliente los hábitos de paseo y coméntale las zonas por donde realizarás la actividad.</div>
                    <div>El rango sugerido para el precio del paseo es $80 a $100 pesos. Recuerda, tu eres libre de colocar el precio que desees.</div>
                </div>
            ";
        }
    	$precios_adicionales .= "
    		<div class='vlz_seccion'>
    			<div class='vlz_titulo_seccion container_btn'>".$value."  {$boton} </div>
    			<div class='vlz_seccion_interna'>
    				".$temp."
    			</div>
			</div>
    	";
    }

    $adicionales = unserialize($cuidador->adicionales);

    $adicionales_extra_str = "";
    foreach ($adicionales_extra as $key => $value) {
    	if( $adicionales[$key]+0 == 0 ){ $adicionales[$key] = 0; }
    	$adicionales_extra_str .= "
    		<div class='vlz_celda_20'>
    			<label>".$value."</label>
    			<input type='number' step='0.01' min=0 data-minvalue=0 data-charset='num'  class='vlz_input' id='adicional_".$key."' name='adicional_".$key."' value='".$adicionales[$key]."' data-toggle='tooltip' data-title='Ingresa el precio para el servicio de {$value}' />
			</div>
    	";
    }

    $rutas = array(
        "corto" => "Cortas",
        "medio" => "Medias",
        "largo" => "Largas"
    );

    $transporte_sencillo_str = "";
	$temp = "";
	foreach ($rutas as $slug => $valor) {
		$temp .= "
    		<div class='vlz_celda_33'>
    			<label>".$valor."</label>
    			<input type='number' step='0.01' min=0 data-minvalue=0 data-charset='num'  class='vlz_input' id='transportacion_sencilla_".$slug."' name='transportacion_sencilla_".$slug."' value='".$adicionales['transportacion_sencilla'][$slug]."' data-toggle='tooltip' data-title='Ingresa el precio para transporte sencillo en rutas {$valor}' />
			</div>
		";
	}
	$transporte_sencillo_str .= "
		<div class='vlz_celda_50'>
			<div class='vlz_titulo_seccion container_btn'>Transportación Sencilla</div>
			<div class='vlz_seccion_interna'>
    			".$temp."
    		</div>
		</div>
	";

    $transporte_redondo_str = "";
	$temp = "";
	foreach ($rutas as $slug => $valor) {
		$temp .= "
    		<div class='vlz_celda_33'>
    			<label>".$valor."</label>
    			<input type='number' step='0.01' class='vlz_input' min=0 data-minvalue=0 data-charset='num'  id='transportacion_redonda_".$slug."' name='transportacion_redonda_".$slug."' value='".$adicionales['transportacion_redonda'][$slug]."' data-toggle='tooltip' data-title='Ingresa el precio para transporte redondo en rutas {$valor}' />
			</div>
		";
	}
	$transporte_redondo_str .= "
		<div class='vlz_celda_50'>
			<div class='vlz_titulo_seccion container_btn'>Transportación Redonda</div>
			<div class='vlz_seccion_interna'>
    			".$temp."
    		</div>
		</div>
	";

	if( $status_servicios[ "hospedaje" ] == 'publish' ){
		$boton = "<input type='button' value='Activado' class='vlz_activador km-btn-primary vlz_activado' id='status_hospedaje' > <input type='hidden' id='oculto_status_hospedaje' name='status_hospedaje' value='1' >";
	}else{
		$boton = "<input type='button' value='Desactivado' class='vlz_activador km-btn-primary vlz_desactivado' id='status_hospedaje' > <input type='hidden' id='oculto_status_hospedaje' name='status_hospedaje' value='0' >";
	}

	$CONTENIDO .= $stylos."
    	<input type='hidden' name='user_id' value='{$user_id}'>
    	<input type='hidden' name='accion' value='update_servicios'>
        <h1 style='margin: 0px; padding: 0px;'>Mis servicios</h1><hr style='margin: 5px 0px 10px;'>
    	<div>
    		<div class='vlz_seccion'>
    			<div class='vlz_titulo_seccion container_btn'>Tamaños de Mascotas</div>
    			<div class='vlz_seccion_interna'>
    				<div class='vlz_celda_25 vlz_celda_25_x'>
	    				<strong>Pequeños</strong> (0.0 - 25.0 cm)
	    			</div>
    				<div class='vlz_celda_25 vlz_celda_25_x'>
						<strong>Medianos</strong> (25.0 - 58.0 cm)
	    			</div>
    				<div class='vlz_celda_25 vlz_celda_25_x'>
						<strong>Grandes</strong> (58.0 - 73.0 cm)
	    			</div>
    				<div class='vlz_celda_25 vlz_celda_25_x'>
						<strong>Gigantes</strong> (73.0 - 200.0 cm)
	    			</div>
    			</div>
    		</div>
    		<div class='vlz_seccion'>
    			<div class='vlz_titulo_seccion container_btn'>Hospedaje <!-- {$boton} --> </div>
    			<div class='vlz_seccion_interna' id='precios_hospedaje'>
    				".$hospedaje."
    			</div>
				<div class='no_error' id='error_hospedaje'>Debe llenar al menos uno de los campos</div>
    		</div>
    		
    		".$precios_adicionales."

    		<div class='vlz_seccion'>
    			<div class='vlz_seccion_interna'>
        			".$transporte_sencillo_str."
        			".$transporte_redondo_str."
    			</div>

    			<div class='vlz_titulo_seccion container_btn'>Servicos Extras</div>
    			<div class='vlz_seccion_interna'>
    				".$adicionales_extra_str."
    			</div>
    		</div>
    		
    	</div>
    ";
?>