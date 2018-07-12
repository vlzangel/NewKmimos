<?php
    include_once(dirname(dirname(dirname(__DIR__)))."/recursos/conexion.php");

	$bloqueo = $db->get_var("SELECT bloqueo FROM fotos WHERE reserva = {$ID} AND fecha = '".date("Y-m-d")."'");

?>
<script> 
	var ID_RESERVA = <?php echo $ID ?>; 
</script>
<table width="100%" cellspacing="0" cellpadding="0" class="tabla_vertical">
	<tr>
		<th>Bloquear</th>
		<td>
			<select id="bloquear" name="bloquear">
				<option value=1 <?php if( $bloqueo == 1 ){ echo "selected"; } ?> >Si</option>
				<option value=0 <?php if( $bloqueo == 0 ){ echo "selected"; } ?> >No</option>
			</select>
		</td>
	</tr>
</table>
<div class='botones_container'>
	<input type='button' id='Bloquear' value='Bloquear' onClick='Bloquear()' />
</div>