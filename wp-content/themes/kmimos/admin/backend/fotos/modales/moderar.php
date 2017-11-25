<?php
    include_once(dirname(dirname(dirname(__DIR__)))."/recursos/conexion.php");

    $param = explode("==", $ID);

    $ID = $param[0];
    $PERIODO = $param[1];


    $MODERADAS = $db->get_var( "SELECT moderacion FROM fotos WHERE reserva = {$ID} AND fecha = '".date("Y-m-d")."' " );
    $MODERADAS = unserialize($MODERADAS);


    $URL = get_home_url()."/wp-content/uploads/fotos/".$ID."/".date("Y-m-d")."_".$PERIODO."/";
    $PATH = dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))))."/uploads/fotos/".$ID."/".date("Y-m-d")."_".$PERIODO."/";

    $FOTOS = listar_archivos( $PATH );

	echo "<form id='form_moderar'><div class='fotos_moderar'>";
		$i = 1;
		foreach ($FOTOS as $foto) {
			$check = "";
			if( in_array($foto, $MODERADAS)){
				$check = "checked";
			}
			echo "
				<div style='background-image: url(".$URL.$foto.");'>
					<input type='checkbox' value='{$foto}' {$check} id='foto_{$i}' data-index='{$i}' data-url=\"".$URL.$foto."\" />
				</div>
			";
			$i++;
		}
	echo "
			<input type='hidden' value='{$i}' id='cantidad' name='cantidad' />
		</div>
		<div class='botones_container'>
			<input type='button' value='Moderar' onClick='moderar()' />
		</div></form>
		<script> var ID_RESERVA = $ID; </script>

		<img id='fondo' src='".getTema()."/images/prueba_galeria/fondo.png' />
		<canvas id='myCanvas' width='600' height='495' ></canvas>
		<div id='base_table' style='display: inline-block; background-color: #CCC; padding: 0px; margin: 20px; border: solid 1px #CCC; border-radius: 4px;'>
			<table width='600' height='495'>
				<tr><td align='center' valign='middle' id='base'></td></tr>
			</table>
		</div>
	";


?>