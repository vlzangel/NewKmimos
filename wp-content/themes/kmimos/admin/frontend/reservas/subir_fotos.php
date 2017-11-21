<?php

    //date_default_timezone_set('America/Mexico_City');

    $ID_RESERVA = vlz_get_page();

	$hora_actual = strtotime("now");
	$hoy = date("Y-m-d", $hora_actual);
    $xhora_actual = date("H", $hora_actual);

    $periodo_corto = "";
    $periodo = "";
    $hora = "";
    if( $xhora_actual < 12 ){
    	$periodo_corto = 1;
    	$periodo = date("Y-m-d")."_1";
   		$hora = "12:00 m";
    }else{
    	$periodo_corto = 2;
    	$periodo = date("Y-m-d")."_2";
   		$hora = "06:00 pm";
    }

    $error = "";

    global $wpdb;

    echo "SELECT * FROM fotos WHERE reserva = {$ID_RESERVA} AND fecha = '{$hoy}' ";
    $actual = $wpdb->get_row("SELECT * FROM fotos WHERE reserva = {$ID_RESERVA} AND fecha = '{$hoy}' ");

    if( $periodo_corto == 1 && $actual->subio_12 == 1 ){
    	$error = "Su proximo envio de fotos es a partir de la 01:00 pm";
    }

    if( $periodo_corto == 2 && $actual->subio_06 == 1 ){
    	$error = "Usted ya ha enviado las fotos del día de hoy.";
    }

    if( $error != "" ){
    	$CONTENIDO = '
			<div class="modulo_container">

				<div class="img_container" >
					'.$error.'
				</div>

			</div>
		';
    }else{
		$CONTENIDO = '
			<script>
				var ID_RESERVA = "'.$ID_RESERVA.'";
				var PERIODO = "'.$periodo.'";
			</script>

			<div class="modulo_container">

				<div class="cargando_container">
					<div class="cargando_box"></div>
					<i id="cargando" class="fa fa-spinner fa-spin fa-3x fa-fw" aria-hidden="true"></i> Procesando...
				</div>

				<img id="fondo" src="'.getTema().'/images/prueba_galeria/fondo.png" />

				<canvas id="myCanvas" width="600" height="430" ></canvas>

				<div class="img_container" >
					Cargar im&aacute;genes del <strong>'.date("d/m/Y").'</strong> que se enviaran a las <strong>'.$hora.'</strong>
				</div>

				<div id="img_container" ></div>
				<div id="img_msg" ></div>

				<div class="botones_container"> 
					<div class="boton"> 
						<i id="cargar_ico" class="fa fa-plus" aria-hidden="true"></i>
						<input type="file" id="cargar_imagenes" accept="image/*" multiple />
					</div>
					<div class="boton"> 
						<i id="enviar_ico" class="fa fa-check" aria-hidden="true"></i>
					</div>
				</div>

				<div id="base_table" style="display: inline-block; background-color: #CCC; padding: 0px; margin: 20px; border: solid 1px #CCC; border-radius: 4px;">
					<table width="600" height="430">
						<tr><td align="center" valign="middle" id="base"></td></tr>
					</table>
				</div>

			</div>
		';
    }
?>