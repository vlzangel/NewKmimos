<?php
	include_once dirname(__DIR__).'/wp-load.php';
	
	global $wpdb;

	extract($_GET);

	$db = $wpdb;
	
	function normaliza($cadena){
	    $originales = 'ÁáÉéÍíÓóÚúÑñ';
	    $modificadas = 'aaeeiioouunn';
	    $cadena = utf8_decode($cadena);
	    $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
	    $cadena = strtolower($cadena);
	    return utf8_encode($cadena);
	}

	$s = normaliza($s);

	$estados_str = '';
	
    $colonias = [];

	$estados = [];
    $_estados = $db->get_results("SELECT * FROM states WHERE country_id = 1 ORDER BY `order`, name ASC");
    foreach ($_estados as $key => $estado) {
    	$_txt = utf8_decode($estado->name);
    	$estados[ $estado->id ] = $_txt;
    }

    $municipios = [];
    $_municipios = $db->get_results("SELECT * FROM locations ORDER BY `order`, name ASC ");
    foreach ($_municipios as $key => $municipio) {
    	$_txt = utf8_decode($municipio->name);
    	$municipios[ $municipio->state_id ][ $municipio->id ] = $_txt;
    }

    $_colonias = $db->get_results("SELECT * FROM colonias ORDER BY name ASC");
    foreach ($_colonias as $key => $colonia) {
    	$colonia_txt = str_replace('"', '', $colonia->name );
    	$colonias[ $colonia->estado ][ $colonia->municipio ][ $colonia->id ] = $colonia_txt;
    }

    $all = [];

    foreach ($estados as $estado_id => $estado_txt) {

    	$all[ $estado_id ] = $estado_txt;

    	foreach ($municipios[ $estado_id ] as $municipio_id => $municipio_txt ) {

    		$all[ $estado_id."_".$municipio_id ] = $estado_txt.", ".$municipio_txt;

    		foreach ($colonias[ $estado_id ][ $municipio_id ] as $colonia_id => $colonia_txt ) {
	    		
	    		$_txt = $estado_txt.", ".$municipio_txt.", ".$colonia_txt;

    			$all[ $estado_id."_".$municipio_id."_".$colonia_id ] = $estado_txt.", ".$municipio_txt.", ".$colonia_txt;

	    	}
    	}
    }

    $array = "<?php\n\n";
    $array .= "\t\$data = array(\n";

        foreach ($all as $_id => $_txt) {

            $_txt = str_replace('"', '', $_txt);
            $_txt = str_replace("'", '', $_txt);

            $array .= "\t\t'{$_id}' => '{$_txt}',\n";
            
        }

    $array .= "\t);\n";
    $array .= "?>";

    $fp = fopen("ubicaciones.php", "w");
        fputs($fp, $array);
    fclose($fp);

    /*
    echo "<pre>";
        print_r($array);
    echo "</pre>";


    /*

    foreach ($all as $_id => $_txt) {

		$_value = normaliza( str_replace(', ', ' ', $_txt ) );

    	if( stripos($_value, $s) !== false ){

    		$estados_str .= "<li value='".$_id."' data-value='".$_value."'>".$_txt."</li>";

    	}
    }

    echo $estados_str;
    

   
    /*
    echo "<pre>";
    	print_r($colonias);
    echo "</pre>";

    /*
    $ubicaciones = array();

    foreach ($estados as $value) {
		$municipios = $db->get_results("SELECT * FROM locations WHERE state_id = ".$value->id." ORDER BY order, name ASC ");

		$estado_value = normaliza( ($value->name) );
    	$estados_str .= ("<".$tag_start." value='".$value->id."' data-value='".$estado_value."' >".$value->name."</".$tag_end.">");
    	
    	$ubicaciones[] = array(
    		"id" => $value->id,
    		"valor" => $estado_value,
    		"txt" => $value->name
    	);

		if( is_array($municipios) && count($municipios) > 1 ){
			$cont = 0;
    		foreach ($municipios as $municipio) {

    			$municipio_value = normaliza( ($municipio->name) );
    			$estados_str .= ("<".$tag_start." value='".$value->id."_".$municipio->id."' data-value='".$estado_value." ".$municipio_value."' >".$value->name.", ".$municipio->name."</".$tag_end.">");

    			$ubicaciones[] = array(
		    		"id" => $value->id."_".$municipio->id,
		    		"valor" => $estado_value.", ".$municipio_value,
		    		"txt" => $value->name.", ".$municipio->name
		    	);

		    	$colonias = $db->get_results("SELECT * FROM colonias WHERE municipio = ".$municipio->id." ORDER BY name ASC ");
		    	foreach ($colonias as $colonia) {
		    		$estados_str .= ("<".$tag_start." value='".$value->id."_".$municipio->id."_".$colonia->id."' data-value='".$estado_value." ".$municipio_value."' >".$value->name.", ".$municipio->name.", ".$colonia->name."</".$tag_end.">");
		    	}
    		}
		}
    }
    echo $estados_str;
    */

?>