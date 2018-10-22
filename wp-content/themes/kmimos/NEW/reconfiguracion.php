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
    	");

    	foreach ($cuidadores as $key => $value) {
    		$wpdb->query("UPDATE cuidadores SET titulo = '{$value->titulo}' WHERE cuidadores.id = {$value->id};");
    	}
    }

?>