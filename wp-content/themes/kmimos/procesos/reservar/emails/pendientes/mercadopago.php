<?php
	$admins = kmimos_get_mail_admins();
	wp_mail( 'a.veloz@kmimos.la', "Pendiente de pago mercadopago", "Reserva #{$RESERVA_ID} pendiente de pago mercadopago", $admins);
?>