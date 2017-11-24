<?php
    include_once(dirname(dirname(dirname(__DIR__)))."/recursos/conexion.php");

/*    echo "<pre>";
    	print_r($_POST);
    echo "</pre>";
*/
	$historial = "";
	$fehas = $db->get_results("SELECT * FROM fotos WHERE reserva = {$ID}");
	foreach ($fehas as $key => $value) {

        $status = ""; $status_txt = "";
        switch ( $value->status ) {
            case '0':
                $status = "status-inicio";
                $status_txt = "Por cargar fotos";
            break;
            case '1':
                $status = "status-ok";
                $status_txt = "Todo Bien";
            break;
            case '2':
                $status = "status-medio";
                $status_txt = "Solo cargo un flujo";
            break;
            case '3':
                $status = "status-mal";
                $status_txt = "No ha cargado a la hora";
            break;
            
            default:
                $status = "status-ok";
                $status_txt = "Todo Bien";
            break;
        }

        $dia = "No"; if( $value->subio_12 == 1 ){ $dia = "Si"; }
        $noche = "No"; if( $value->subio_06 == 1 ){ $noche = "Si"; }

		$historial .= "
			<tr>
				<td>".date("d/m/Y", strtotime($value->fecha))."</td>
				<td>{$dia}</td>
				<td>{$noche}</td>
				<td> <div class='status {$status}' title='{$status_txt}'>&nbsp;</div> </td>
			</tr>
		";
	}
?>
<table width="100%" cellspacing="0" cellpadding="0" class="tabla_horizontal">
	<tr>
		<th>Fecha</th>
		<th>12 m</th>
		<th>06 pm</th>
		<th>Status</th>
	</tr>
	<?php echo $historial; ?>
</table>