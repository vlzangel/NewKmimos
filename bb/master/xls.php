<?php
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Suscritos Blood Brothers / ".@date("d-m-Y").".xls");
include('../conex.php');
///// QUERY DE BUSQUEDA
$query = 'SELECT * FROM formulario ORDER BY id_formulario ASC ';

$consulta = $db->get_results($query);
$q=count($consulta);

//////////////////////////////////////
//////////////////////////////////////
//////////////////////////////////////
?>


<h2>SUSCRIPTORES BLOOD BROTHERS</h2>
<b>Última Descarga <?php echo date("d-m-Y"); ?></b><br><br>
<table cellpadding="4" cellspacing="4" border="1">
	<tr>
		<td rowspan="2" style="border:1px solid #555;text-align:center;"><b>#</b></td>
		<td rowspan="2" style="border:1px solid #555;text-align:center;"><b>Fecha</b></td>
		<td rowspan="2" style="border:1px solid #555;text-align:center;"><b>Nombre</b></td>
		<td rowspan="2" style="border:1px solid #555;text-align:center;"><b>Apellido</b></td>
		<td rowspan="2" style="border:1px solid #555;text-align:center;"><b>Nombre Mascota</b></td>
		<td rowspan="2" style="border:1px solid #555;text-align:center;"><b>Telefono</b></td>
		<td rowspan="2" style="border:1px solid #555;text-align:center;"><b>Correo</b></td>
		<td rowspan="2" style="border:1px solid #555;text-align:center;"><b>Estado</b></td>
		<td rowspan="2" style="border:1px solid #555;text-align:center;"><b>Municipio</b></td>
		<td rowspan="2" style="border:1px solid #555;text-align:center;"><b>Desarrollo</b></td>
		<td rowspan="2" style="border:1px solid #555;text-align:center;"><b>Raza</b></td>
		<td rowspan="2" style="border:1px solid #555;text-align:center;"><b>Tama&ntilde;o</b></td>
		<td rowspan="2" style="border:1px solid #555;text-align:center;"><b>Peso</b></td>
		<td colspan="10" style="border-top:1px solid #555;text-align:center;"><b>Enfermedades</b></td>
		<td colspan="6" style="border-top:1px solid #555;text-align:center;"><b>Vacunas</b></td>
		<td rowspan="2" style="border:1px solid #555;text-align:center;"><b>Desparasitado</b></td>
	</tr>
	<tr>
		<td style="border:1px solid #555;text-align:center;"><b>No Recuerdo</b></td>
		<td style="border:1px solid #555;text-align:center;"><b>Brucelosis</b></td>
		<td style="border:1px solid #555;text-align:center;"><b>Ehrlichiosis</b></td>
		<td style="border:1px solid #555;text-align:center;"><b>Hemobartonelosis</b></td>
		<td style="border:1px solid #555;text-align:center;"><b>Leishmaniasis</b></td>
		<td style="border:1px solid #555;text-align:center;"><b>Babesiosis</b></td>
		<td style="border:1px solid #555;text-align:center;"><b>Filariasis</b></td>
		<td style="border:1px solid #555;text-align:center;"><b>Toxoplasmosis</b></td>
		<td style="border:1px solid #555;text-align:center;"><b>Anaplasma</b></td>
		<td style="border:1px solid #555;text-align:center;"><b>Ninguna</b></td>
		<td style="border:1px solid #555;text-align:center;"><b>Moquillo</b></td>	
		<td style="border:1px solid #555;text-align:center;"><b>Hepatitis</b></td>	
		<td style="border:1px solid #555;text-align:center;"><b>Parvovirus</b></td>	
		<td style="border:1px solid #555;text-align:center;"><b>Parainfluenza</b></td>	
		<td style="border:1px solid #555;text-align:center;"><b>Rabia</b></td>	
		<td style="border:1px solid #555;text-align:center;"><b>Leptospirosis</b></td>	
	</tr>
	<?php
	$cont = $q;
	foreach ($consulta as $r){
	?>
	<tr>
		<td style="text-align:center;"><?php echo $cont; ?></td>
		<td style="text-align:center;"><?php $pre = explode('-', $r->fecha); echo $pre[2].'-'.$pre[1].'-'.$pre[0]; ?></td>
		<td style="text-align:center;" width="150"><?php echo $r->nombre; ?></td>
		<td style="text-align:center;" width="150"><?php echo $r->apellido; ?></td>
		<td style="text-align:center;" width="180"><?php echo $r->nombremascota; ?></td>
		<td style="text-align:center;" width="150"><?php echo $r->telefono; ?></td>
		<td style="text-align:center;" width="180"><?php echo $r->correo; ?></td>
		<td style="text-align:center;" width="150"><?php echo $r->estado; ?></td>
		<td style="text-align:center;" width="150"><?php echo $r->municipio; ?></td>
		<td style="text-align:center;"><?php echo $r->desarrollo; ?></td>
		<td style="text-align:center;" width="180"><?php echo $r->raza; ?></td>
		<td style="text-align:center;"><?php echo $r->tamano; ?></td>
		<td style="text-align:center;"><?php echo $r->peso; ?></td>
		<td style="text-align:center;"><?php echo $r->norecuerdo; ?></td>
		<td style="text-align:center;"><?php echo $r->brucelosis; ?></td>
		<td style="text-align:center;"><?php echo $r->ehrlichiosis; ?></td>
		<td style="text-align:center;" width="100"><?php echo $r->hemobartonelosis; ?></td>
		<td style="text-align:center;"><?php echo $r->leishmaniasis; ?></td>
		<td style="text-align:center;"><?php echo $r->babesiosis; ?></td>
		<td style="text-align:center;"><?php echo $r->filariasis; ?></td>
		<td style="text-align:center;" width="150"><?php echo $r->toxoplasmosis; ?></td>
		<td style="text-align:center;"><?php echo $r->anaplasma; ?></td>
		<td style="text-align:center;"><?php echo $r->ninguna; ?></td>
		<td style="text-align:center;"><?php echo $r->moquillo; ?></td>
		<td style="text-align:center;"><?php echo $r->hepatitis; ?></td>
		<td style="text-align:center;"><?php echo $r->parvovirus; ?></td>
		<td style="text-align:center;"><?php echo $r->parainfluenza; ?></td>
		<td style="text-align:center;"><?php echo $r->rabia; ?></td>
		<td style="text-align:center;"><?php echo $r->leptospirosis; ?></td>
		<td style="text-align:center;" width="150"><?php echo $r->desparasitado; ?></td>

	</tr>
	<?php
	$cont--;
	}
	?>

</table>