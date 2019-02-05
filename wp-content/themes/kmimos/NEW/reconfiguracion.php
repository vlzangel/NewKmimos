<?php

    function update_cuidador_url(){
    	global $wpdb;

    	$cuidadores = $wpdb->get_results("
    		SELECT 
    			*
    		FROM 
    			wp_posts
    		WHERE
    			post_type = 'petsitters'
    	");

    	foreach ($cuidadores as $key => $value) {
    		$wpdb->query("UPDATE wp_posts SET post_name = '{$value->post_author}' WHERE ID = {$value->ID};");
    	}
    }

    function update_ubicacion(){
    	global $wpdb;

    	$cuidadores = $wpdb->get_results("
    		SELECT 
    			c.id,
    			c.email,
    			u.estado,
    			u.municipios
    		FROM 
    			cuidadores AS c
    		INNER JOIN ubicaciones AS u  ON ( u.cuidador = c.id )
    	");

    	foreach ($cuidadores as $key => $value) {
    		$est = ( $value->estado == "==" ) ? "": $value->estado;
    		$mun = ( $value->municipios == "==" ) ? "": $value->municipios;
    		$wpdb->query("UPDATE cuidadores SET estados = '{$est}', municipios = '{$mun}' WHERE cuidadores.id = {$value->id};");
    	}
    }

    function update_titulo(){
        global $wpdb;

        $cuidadores = $wpdb->get_results("
            SELECT 
                c.id,
                p.post_title AS titulo
            FROM 
                cuidadores AS c
            INNER JOIN wp_posts AS p  ON ( p.ID = c.id_post )
            WHERE c.titulo = ''
        ");

        echo "<pre>";
            print_r($cuidadores);
        echo "</pre>";

        foreach ($cuidadores as $key => $value) {
            // $wpdb->query("UPDATE cuidadores SET titulo = '{$value->titulo}' WHERE cuidadores.id = {$value->id};");
        }
    }

    function update_servicios(){
        global $wpdb;

        $cuidadores = $wpdb->get_results("SELECT * FROM `cuidadores` WHERE `adicionales` NOT LIKE '%hospedaje%'");

        $new_adicionales = [];

        foreach ($cuidadores as $key => $value) {
            $hospedaje = unserialize($value->hospedaje);
            $adicionales = unserialize($value->adicionales);
            $adicionales["hospedaje"] = $hospedaje;

            $new_adicionales[] = $adicionales;
            
            $adicionales = serialize($adicionales);
            $wpdb->query("UPDATE cuidadores SET adicionales = '{$adicionales}' WHERE cuidadores.id = {$value->id};");
        }

/*        echo "<pre>";
            print_r($new_adicionales);
        echo "</pre>";*/
    }

    function update_precios_paseos(){
    	global $wpdb;

    	$cuidadores = $wpdb->get_results("SELECT * FROM `cuidadores` WHERE `adicionales` LIKE '%paseos%'");

        $new_adicionales = [];

    	foreach ($cuidadores as $key => $value_1) {
            $adicionales = unserialize($value_1->adicionales);

            $paseos = $adicionales["paseos"];
            $menor = 0;
            foreach ($paseos as $key => $value_2) {
                if( ($value_2 != 0 && $value_2 < $menor) || $menor == 0 ){
                    $menor = $value_2;
                }
            }

    		$wpdb->query("UPDATE cuidadores SET paseos_desde = '{$menor}' WHERE cuidadores.id = {$value_1->id};");
    	}

    }

?>