<?php
    include_once(dirname(dirname(dirname(__DIR__)))."/recursos/conexion.php");

	$email = $db->get_var("SELECT user_email FROM wp_users WHERE ID = {$ID}");

	$metas = kmimos_get_user_meta($ID);

	$name = $metas["first_name"]." ".$metas["last_name"];

	$telf = array();
    $telf[] = $metas["user_mobile"];
    $telf[] = $metas["user_phone"];

    $telf = implode(" / ", $telf);

?>
<table width="100%" cellspacing="0" cellpadding="0" class="tabla_vertical">
	<tr>
		<th>Nombre</th>
		<td><?php echo utf8_encode( $name ); ?></td>
	</tr>
	<tr>
		<th>E-mail</th>
		<td><?php echo $email; ?></td>
	</tr>
	<tr>
		<th>Tel&eacute;fono</th>
		<td><?php echo $telf; ?></td>
	</tr>
	<tr>
		<th> Administrador </th>
		<td> <a href="<?php echo get_home_url()."?i=".md5($ID); ?>">Editar</a> </td>
	</tr>
</table>