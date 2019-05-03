<?php
	$admins = kmimos_get_mail_admins();
	wp_mail( 'a.veloz@kmimos.la', "Pendiente de pago paypal", "Reserva #{$RESERVA_ID} pendiente de pago paypal", $admins);
?>