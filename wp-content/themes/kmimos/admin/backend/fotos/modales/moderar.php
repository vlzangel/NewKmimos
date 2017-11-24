<?php
    include_once(dirname(dirname(dirname(__DIR__)))."/recursos/conexion.php");

	   
/*	echo "<pre>";
    	print_r($_POST);
    echo "</pre>";*/
	

    $param = explode("==", $ID);

    $ID = $param[0];
    $PERIODO = $param[1];


    $MODERADAS = kmimos_get_post_meta( $ID, "fotos_moderadas" );
    if( $MODERADAS != false ){
    	$MODERADAS = unserialize($MODERADAS);
    }else{
    	$MODERADAS = array();
    }


    $URL = get_home_url()."/wp-content/uploads/fotos/".$ID."/".date("Y-m-d")."_".$PERIODO."/";
    $PATH = dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))))."/uploads/fotos/".$ID."/".date("Y-m-d")."_".$PERIODO."/";

    $FOTOS = listar_archivos( $PATH );

	echo "<form id='form_moderar'><div class='fotos_moderar'>";
		$i = 1;
		foreach ($FOTOS as $foto) {
			$check = "checked";
			if( in_array($foto, $MODERADAS)){
				$check = "";
			}
			echo "
				<div style='background-image: url(".$URL.$foto.");'>
					<input type='checkbox' value='{$foto}' {$check} id='foto_{$i}' />
				</div>
			";
			$i++;
		}
	echo "</div>
		<div class='botones_container'>
			<input type='button' value='Moderar' onClick='moderar()' />
		</div></form>
		<script> var ID_RESERVA = $ID; </script>
	";


?>