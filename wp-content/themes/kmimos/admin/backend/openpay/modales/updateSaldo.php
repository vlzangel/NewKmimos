<?php
	
	extract($_POST);
    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/vlz_config.php");

    $tema = (dirname(dirname(dirname(dirname(__DIR__)))));
    include_once($tema."/procesos/funciones/db.php");
    include_once($tema."/procesos/funciones/generales.php");

    $db = new db( new mysqli($host, $user, $pass, $db) );

	$saldo = $db->get_var("SELECT meta_value FROM wp_usermeta WHERE user_id = {$ID} AND meta_key = 'kmisaldo' ");
	$saldo += 0;
?>
<table width="100%" cellspacing="0" cellpadding="0" class="tabla_vertical">
	<tr>
		<th>Saldo</th>
		<td>
			<input type="number" name="saldo" id="saldo" value="<?php echo $saldo; ?>" />
			<input type="hidden" name="ID" id="ID" value="<?php echo $ID; ?>" />
		</td>
	</tr>
</table>
<div class='botones_container'>
	<input type='button' id='Bloquear' value='Actualizar' onClick='updateSaldo()' class="button button-primary button-large" />
</div>