<?php 
	
	$path = explode("/", substr($_SERVER["REDIRECT_URL"], 1));

/*	echo "<pre>";
		print_r( $path );
	echo "</pre>";*/

	if( count($path) == 4 ){

		switch ($path[0]) {
			case 'perfil-usuario':
				
				$orden_id = $path[3];
				include( "procesos/perfil/".$path[1]."/".$path[2].".php" );

			break;
		}

	}else{

		get_header();

			echo "404";

		get_footer();
	}
?>