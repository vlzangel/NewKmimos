<?php
	error_reporting(0);
	include dirname(__DIR__).'/campaing/db.php';
	$info = (array) json_decode(base64_decode( $_GET['info']));
	extract($info);

	$existe = $db->get_row("SELECT * FROM vlz_desuscritos WHERE email = '{$email}' ");
	if( $existe === false ){
		$sql = "
			INSERT INTO vlz_desuscritos VALUES (
				NULL,
				'{$email}',
				'{$campaing_id}',
				NOW()
			)
		";
		$db->query($sql);
	}
?>
Suscripción Finalizada :(