<?php
    include_once(dirname(dirname(dirname(__DIR__)))."/recursos/conexion.php");
	
	$email = $db->get_var("SELECT user_email FROM wp_users WHERE ID = {$ID}");

	$metas = kmimos_get_user_meta($ID);

	$name = $db->get_var("SELECT post_title FROM wp_posts WHERE post_author = {$ID} AND post_type = 'petsitters' ");

	$name_real = $metas["first_name"]." ".$metas["last_name"];

	$telf = array();
    $telf[] = $metas["user_mobile"];
    $telf[] = $metas["user_phone"];

    $telf = implode(" / ", $telf);

?>
<table width="100%" cellspacing="0" cellpadding="0" class="tabla_vertical">
	<tr>
		<th>Nombre</th>
		<td><?php echo utf8_encode( $name ); ?> (<?php echo utf8_encode( $name_real ); ?>)</td>
	</tr>
	<tr>
		<th>E-mail</th>
		<td><?php echo $email; ?></td>
	</tr>
	<tr>
		<th>Tel&eacute;fono</th>
		<td><?php echo $telf; ?></td>
	</tr>
</table>