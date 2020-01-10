<?php 
	
	$path = explode("/", substr($_SERVER["REDIRECT_URL"], 1));

	if( count($path) == 4 ){
		switch ($path[0]) {
			case 'perfil-usuario':
				$orden_id = $path[3];
				include( "procesos/perfil/".$path[1]."/".$path[2].".php" );
			break;
		}
	}else{

		if( $path[0] == 'petsitters' ) {
			$slug = explode("-", $path[1]);
			$permitidos = [
				5014,
				11665,
				5006,
				8650,
				1428
			];
			if( in_array($slug[0], $permitidos)){
				header("location: ".get_home_url()."/petsitters/".$slug[0] );
			}
		}

		get_header();

			echo '
	 		<div class="km-ficha-bg" style="background-image:url('.getTema().'/images/new/km-ficha/km-bg-ficha.jpg);">
				<div class="overlay"></div>
			</div>
			<div class="km-content km-step-end">
				<div style="padding: 20px 40px 20px; background: #FFF;">
					<h1 style="text-align: center;">Error, pagina no encontrada.</h1>
				</div>
			</div>
			';

		get_footer();
	}
?>