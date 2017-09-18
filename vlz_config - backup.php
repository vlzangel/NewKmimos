<?php
	if(!function_exists('page_wc_valida')){
		function page_wc_valida(){

			return false;
		    $page = explode("/", $_SERVER["REQUEST_URI"]);

		    if( count($page) > 0 ){
		        $validas = array(
		            "producto",
		            "carro",
		            "finalizar-comprar",
		            "wp-admin",
		            "perfil-usuario",
		        );

		        return ( in_array($page[1], $validas));
		    }else{
		        return false;
		    }
		}
	}

	$host = "localhost";
	$user = "root";
	$pass = "";
	$db = "kmimos_mx_new";
?>