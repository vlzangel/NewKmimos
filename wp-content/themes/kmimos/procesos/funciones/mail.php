<?php
	require( dirname(dirname(__DIR__))."/lib/phpmailer/class.smtp.php" );
	require( dirname(dirname(__DIR__))."/lib/phpmailer/class.phpmailer.php" );
	
	function wp_mail($titulo, $body, $email, $BCCs = array() ){

		$mail = new PHPMailer();

		$mail->IsSMTP();
		$mail->SMTPAuth = true;
		$mail->Host = "smtp.gmail.com"; // A RELLENAR. Aquí pondremos el SMTP a utilizar. Por ej. mail.midominio.com
		$mail->Username = "soporte.kmimos@gmail.com"; // A RELLENAR. Email de la cuenta de correo. ej.info@midominio.com La cuenta de correo debe ser creada previamente. 
		$mail->Password = "@km!m05@"; // A RELLENAR. Aqui pondremos la contraseña de la cuenta de correo
		$mail->SMTPSecure = "tls";
		$mail->Port = 587; // Puerto de conexión al servidor de envio. 
		$mail->From = "kmimosmex@kmimos.la"; // A RELLENARDesde donde enviamos (Para mostrar). Puede ser el mismo que el email creado previamente.
		$mail->FromName = "Kmimos México"; //A RELLENAR Nombre a mostrar del remitente. 

		$mail->AddAddress($email); // Esta es la dirección a donde enviamos 

		$mail->Subject  = $titulo; // Este es el titulo del email. 
		$mail->Body  	= $body; // Mensaje a enviar. 

		foreach ($BCCs as $correo) {
			$mail->addCustomHeader("BCC: ".$correo);
		}

		$mail->smtpConnect([
			    'ssl' => [
			        'verify_peer' => false,
			        'verify_peer_name' => false,
			        'allow_self_signed' => true
			    ]
			]);

		$mail->IsHTML(true); // El correo se envía como HTML 

		return $mail->Send(); // Envía el correo.
	}
?>