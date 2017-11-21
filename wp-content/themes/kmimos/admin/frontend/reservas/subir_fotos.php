<?php

    date_default_timezone_set('America/Mexico_City');

    $id_orden = vlz_get_page();

	$hora_actual = strtotime("now");
    $xhora_actual = date("H", $hora_actual);

    $periodo = "";
    if( $xhora_actual < 12 ){
    	$periodo = date("d-m-Y")."_1";
    }else{
    	$periodo = date("d-m-Y")."_2";
    }

	$CONTENIDO = '
		<script>
			var ID_ORDEN = "'.$id_orden.'";
			var PERIODO = "'.$periodo.'";
		</script>
		<div>

			<img id="fondo" src="'.getTema().'/images/prueba_galeria/fondo.png" />

			<canvas id="myCanvas" width="600" height="430" ></canvas>

			<div class="img_container" >
				Cargar im&aacute;genes
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
?>