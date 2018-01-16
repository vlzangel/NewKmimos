<?php 
    global $wpdb;
	
	$PATH = dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))))."/wp-content/uploads/fotos/";

    $reserva = vlz_get_page();
    $ver = $_GET["ver"];
    $fecha = $_GET["fecha"];

    $moderacion = unserialize( $wpdb->get_var("SELECT moderacion FROM fotos WHERE reserva = $reserva AND fecha = '$fecha'") );

    switch ( $ver ) {
    	case 1:
    		$collage = "";
            foreach ($moderacion[ 1 ] as $key => $foto) {
                $collage .= '
                	<div data-value="'.get_home_url().'/wp-content/uploads/fotos/'.$reserva.'/'.$fecha.'_1/'.$foto.'">
                		<span style="background-image: url('.get_home_url().'/wp-content/uploads/fotos/'.$reserva.'/'.$fecha.'_1/'.$foto.');"></span>
                		<div style="background-image: url('.get_home_url().'/wp-content/uploads/fotos/'.$reserva.'/'.$fecha.'_1/'.$foto.');"></div>
                	</div>
                ';
            }
		break;
    	case 2:
            foreach ($moderacion[ 2 ] as $key => $foto) {
                $collage .= '
                	<div data-value="'.get_home_url().'/wp-content/uploads/fotos/'.$reserva.'/'.$fecha.'_2/'.$foto.'">
                		<span style="background-image: url('.get_home_url().'/wp-content/uploads/fotos/'.$reserva.'/'.$fecha.'_2/'.$foto.');"></span>
                		<div style="background-image: url('.get_home_url().'/wp-content/uploads/fotos/'.$reserva.'/'.$fecha.'_2/'.$foto.');"></div>
                	</div>
                ';
            }
		break;
    	case 3:
    		$collage = "";
           	if( is_array($moderacion[ 1 ]) && count($moderacion[ 1 ]) > 0 ){
	            foreach ($moderacion[ 1 ] as $key => $foto) {
	                $collage .= '
	                	<div data-value="'.get_home_url().'/wp-content/uploads/fotos/'.$reserva.'/'.$fecha.'_1/'.$foto.'">
	                		<span style="background-image: url('.get_home_url().'/wp-content/uploads/fotos/'.$reserva.'/'.$fecha.'_1/'.$foto.');"></span>
	                		<div style="background-image: url('.get_home_url().'/wp-content/uploads/fotos/'.$reserva.'/'.$fecha.'_1/'.$foto.');"></div>
	                	</div>
	                ';
	           	}
           	}
           	if( is_array($moderacion[ 2 ]) && count($moderacion[ 2 ]) > 0 ){
	            foreach ($moderacion[ 2 ] as $key => $foto) {
	                $collage .= '
	                	<div data-value="'.get_home_url().'/wp-content/uploads/fotos/'.$reserva.'/'.$fecha.'_2/'.$foto.'">
	                		<span style="background-image: url('.get_home_url().'/wp-content/uploads/fotos/'.$reserva.'/'.$fecha.'_2/'.$foto.');"></span>
	                		<div style="background-image: url('.get_home_url().'/wp-content/uploads/fotos/'.$reserva.'/'.$fecha.'_2/'.$foto.');"></div>
	                	</div>
	                ';
	            }
           	}
		break;
    }

	$CONTENIDO = "
		<h1 style='margin: 10px 0px; padding: 0px; display: inline-block;'>Fotos del d&iacute;a ".date("d/m/Y", strtotime($fecha) )." de la reserva # $reserva</h1>
		<div class='ver_fotos_container'>
			$collage
		</div>
	";
?>