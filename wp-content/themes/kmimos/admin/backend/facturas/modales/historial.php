<?php
    include_once(dirname(dirname(dirname(__DIR__)))."/recursos/conexion.php");

/*    echo "<pre>";
    	print_r($_POST);
    echo "</pre>";
*/
    $actual = time();

	$historial = "";
	$fehas = $db->get_results("SELECT * FROM fotos WHERE reserva = {$ID}");
	foreach ($fehas as $key => $value) {

        $status_val = $value->subio_12+$value->subio_06;

        if( date("Y-m-d", $actual) == $value->fecha ){
            if( date("H", $actual) < 18 && $status_val == 1 ){
                $status_val = 4;
            }

            if( date("H", $actual) > 11 && $status_val == 0 ){
                $status_val = 3;
            }
        }else{
            if( $actual > strtotime( $value->fecha ) ){
                if( $status_val == 0 ){
                    $status_val = 3;
                }
            }else{
                $status_val = 5;
            }
        }
        
        $status = ""; $status_txt = "";
        switch ( $status_val ) {
            case '0':
                $status = "status-inicio";
                $status_txt = "Por cargar fotos";
            break;
            case '1':
                $status = "status-ok-medio";
                $status_txt = "Cargo al menos un flujo";
            break;
            case '2':
                $status = "status-ok";
                $status_txt = "En tiempo";
            break;
            case '3':
                $status = "status-mal";
                $status_txt = "No enviado";
            break;
            case '4':
                $status = "status-ok-medio";
                $status_txt = "Cargo al menos un flujo";
            break;
            case '5':
                $status = "status-futuro";
                $status_txt = "Cargas futuras";
            break;
            
            default:
                $status = "status-ok";
                $status_txt = "En tiempo";
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
<div class="legenda">
    <table width="100%" cellspacing="2" cellpadding="2" >
        <tr>
            <th colspan="3"> <span>Status</span> </th>
        </tr>
        <tr>
            <td> <div class='status-2 status-inicio'>&nbsp;</div> Por cargar fotos</td>
            <td> <div class='status-2 status-ok-medio'>&nbsp;</div> Cargo al menos un flujo</td>
            <td> <div class='status-2 status-ok'>&nbsp;</div> Cargo todas</td>
        </tr>
        <tr>
            <td> <div class='status-2 status-mal'>&nbsp;</div> No cargo fotos</td>
            <td colspan="2"> <div class='status-2 status-futuro'>&nbsp;</div> Cargas futuras</td>
        </tr>
    </table>
</div>


<table width="100%" cellspacing="0" cellpadding="0" class="tabla_horizontal">
	<tr>
		<th>Fecha</th>
		<th>12 m</th>
		<th>06 pm</th>
		<th>Status</th>
	</tr>
	<?php echo $historial; ?>
</table>