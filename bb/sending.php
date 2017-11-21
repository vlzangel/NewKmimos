<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require 'phpmailer/PHPMailer.php'; // <<<< Ruta de PHP Mailer
require 'phpmailer/Exception.php'; // <<<< Ruta de PHP Mailer
require 'phpmailer/SMTP.php';      // <<<< Ruta de PHP Mailer

if(isset($_GET['f']) && !empty($_GET['f'])){

	$f=addslashes(trim($_GET['f']));

	///////////////////////////////////////////////////////
	///////////////////////////////////////////////////////
	///////////////////////////////// > ENVIANDO DATA 1
	/////////////////////////////////
	if($f==1){
		// Data
		$fecha = date("Y-m-d");
		$nombre = addslashes(trim($_POST['nombre']));
		$apellido = addslashes(trim($_POST['apellido']));
		$telefono = addslashes(trim($_POST['telefono']));
		$correo = addslashes(trim($_POST['correo']));
		$estado = '';
		$municipio = '';
		$desarrollo = '';
		$raza = '';
		$tamano = '';
		$peso = '';
		$norecuerdo = '';
		$brucelosis = '';
		$ehrlichiosis = '';
		$hemobartonelosis = '';
		$leishmaniasis = '';
		$babesiosis = '';
		$filariasis = '';
		$toxoplasmosis = '';
		$anaplasma = '';
		$ninguna = '';
		$moquillo = '';
		$hepatitis = '';
		$parvovirus = '';
		$parainfluenza = '';
		$rabia = '';
		$leptospirosis = '';
		$desparasitado = '';
		$nombremascota = '';
		// DB
		include('conex.php');
		// Query
		$query = 'INSERT INTO formulario(fecha,nombre,apellido,telefono,correo,estado,municipio,desarrollo,raza,tamano,peso,norecuerdo,brucelosis,ehrlichiosis,hemobartonelosis,leishmaniasis,babesiosis,filariasis,toxoplasmosis,anaplasma,ninguna,moquillo,hepatitis,parvovirus,parainfluenza,rabia,leptospirosis,desparasitado,nombremascota) VALUES("'.$fecha.'","'.$nombre.'","'.$apellido.'","'.$telefono.'","'.$correo.'","'.$estado.'","'.$municipio.'","'.$desarrollo.'","'.$raza.'","'.$tamano.'","'.$peso.'","'.$norecuerdo.'","'.$brucelosis.'","'.$ehrlichiosis.'","'.$hemobartonelosis.'","'.$leishmaniasis.'","'.$babesiosis.'","'.$filariasis.'","'.$toxoplasmosis.'","'.$anaplasma.'","'.$ninguna.'","'.$moquillo.'","'.$hepatitis.'","'.$parvovirus.'","'.$parainfluenza.'","'.$rabia.'","'.$leptospirosis.'","'.$desparasitado.'","'.$nombremascota.'")';
		// Insert
		$insertar=$db->query($query);
		// Necesito el ultimo ID insertado en el query anterior
		$pre = $db->query('SELECT id_formulario FROM formulario ORDER BY id_formulario DESC LIMIT 1');
		while($r = $pre->fetch_array(MYSQLI_NUM)){
			$id=$r[0];
		}
		// Header
		if(!$insertar){
			echo 'Error:'.$db->error;
		}else{
			header('Location: send.php?open=2&id='.$id);
		}
	}

	///////////////////////////////////////////////////////
	///////////////////////////////////////////////////////
	///////////////////////////////// > ENVIANDO DATA 2
	/////////////////////////////////
	if($f==2){
		// Data
		$id = addslashes(trim($_POST['id_formulario']));
		$estado = addslashes(trim($_POST['estado']));
		$municipio = addslashes(trim($_POST['municipio']));
		$desarrollo = addslashes(trim($_POST['desarrollo']));
		$raza = addslashes(trim($_POST['raza']));
		$tamano = addslashes(trim($_POST['tamano']));
		$peso = addslashes(trim($_POST['peso']));
		// DB
		include('conex.php');
		// Query
		$query = 'UPDATE formulario SET  estado="'.$estado.'", municipio="'.$municipio.'", desarrollo="'.$desarrollo.'", raza="'.$raza.'", tamano="'.$tamano.'", peso="'.$peso.'" WHERE id_formulario = '.$id.'';
		// Insert
		$insertar=$db->query($query);
		// Header
		if(!$insertar){
			echo 'Error:'.$db->error;
		}else{
			header('Location: send.php?open=3&id='.$id);
		}

	}

	///////////////////////////////////////////////////////
	///////////////////////////////////////////////////////
	///////////////////////////////// > ENVIANDO DATA 3
	/////////////////////////////////
	if($f==3){
		// Data
		$id = addslashes(trim($_POST['id_formulario']));
		if(isset($_POST['norecuerdo']) && addslashes(trim($_POST['norecuerdo']))==='1'){$norecuerdo = 'No Recuerdo';}else{$norecuerdo='';}
		if(isset($_POST['brucelosis']) && addslashes(trim($_POST['brucelosis']))==='1'){$brucelosis = 'Brucelosis';}else{$brucelosis='';}
		if(isset($_POST['ehrlichiosis']) && addslashes(trim($_POST['ehrlichiosis']))==='1'){$ehrlichiosis = 'Ehrlichiosis';}else{$ehrlichiosis='';}
		if(isset($_POST['hemobartonelosis']) && addslashes(trim($_POST['hemobartonelosis']))==='1'){$hemobartonelosis = 'Hemobartonelos.';}else{$hemobartonelosis='';}
		if(isset($_POST['leishmaniasis']) && addslashes(trim($_POST['leishmaniasis']))==='1'){$leishmaniasis = 'Leishmaniasis';}else{$leishmaniasis='';}
		if(isset($_POST['babesiosis']) && addslashes(trim($_POST['babesiosis']))==='1'){$babesiosis = 'Babesiosis';}else{$babesiosis='';}
		if(isset($_POST['filariasis']) && addslashes(trim($_POST['filariasis']))==='1'){$filariasis = 'Filariasis';}else{$filariasis='';}
		if(isset($_POST['toxoplasmosis']) && addslashes(trim($_POST['toxoplasmosis']))==='1'){$toxoplasmosis = 'Toxoplasmosis';}else{$toxoplasmosis='';}
		if(isset($_POST['anaplasma']) && addslashes(trim($_POST['anaplasma']))==='1'){$anaplasma = 'Anaplasma';}else{$anaplasma='';}
		if(isset($_POST['ninguna']) && addslashes(trim($_POST['ninguna']))==='1'){$ninguna = 'Ninguna';}else{$ninguna='';}
		// Vacunas
		if(isset($_POST['moquillo']) && addslashes(trim($_POST['moquillo']))==='1'){$moquillo = 'Moquillo';}else{$moquillo='';}
		if(isset($_POST['hepatitis']) && addslashes(trim($_POST['hepatitis']))==='1'){$hepatitis = 'Hepatitis';}else{$hepatitis='';}
		if(isset($_POST['parvovirus']) && addslashes(trim($_POST['parvovirus']))==='1'){$parvovirus = 'Parvovirus';}else{$parvovirus='';}
		if(isset($_POST['parainfluenza']) && addslashes(trim($_POST['parainfluenza']))==='1'){$parainfluenza = 'Parainfluenza';}else{$parainfluenza='';}
		if(isset($_POST['rabia']) && addslashes(trim($_POST['rabia']))==='1'){$rabia = 'Rabia';}else{$rabia='';}
		if(isset($_POST['leptospirosis']) && addslashes(trim($_POST['leptospirosis']))==='1'){$leptospirosis = 'Leptospirosis';}else{$leptospirosis='';}
		$desparasitado = addslashes(trim($_POST['desparasitado']));
		$nombremascota = addslashes(trim($_POST['nombremascota']));
		// DB
		include('conex.php');
		// Query
		$query = 'UPDATE formulario SET norecuerdo="'.$norecuerdo.'", brucelosis="'.$brucelosis.'", ehrlichiosis="'.$ehrlichiosis.'", hemobartonelosis="'.$hemobartonelosis.'", leishmaniasis="'.$leishmaniasis.'", babesiosis="'.$babesiosis.'", filariasis="'.$filariasis.'", toxoplasmosis="'.$toxoplasmosis.'", anaplasma="'.$anaplasma.'", ninguna="'.$ninguna.'", moquillo="'.$moquillo.'", hepatitis="'.$hepatitis.'", parvovirus="'.$parvovirus.'", parainfluenza="'.$parainfluenza.'", rabia="'.$rabia.'", leptospirosis="'.$leptospirosis.'", desparasitado="'.$desparasitado.'", nombremascota="'.$nombremascota.'" WHERE id_formulario = '.$id.'';

		// Insert
		$insertar=$db->query($query);
		// Header
		if(!$insertar){
			echo 'Error:'.$db->error;
		}else{
			// Detectando Datos del Usuario
			$pre = $db->query('SELECT nombre, apellido, correo FROM formulario WHERE id_formulario='.$id.' LIMIT 1');
			while($r = $pre->fetch_array(MYSQLI_NUM)){
				$nombre=$r[0];
				$apellido=$r[1];
				$correo=$r[2];
			}
			// ENVIO DE CORREO A USUARIO REGISTRADO
			$mail = new PHPMailer;
		    $mail->IsSMTP();
		    $mail->SMTPAuth = true;                // enable SMTP authentication 
		    $mail->SMTPSecure = "tls";              // sets the prefix to the servier
		    $mail->Host = "smtp.gmail.com";        // sets Gmail as the SMTP server
		    $mail->Port = 587;                     // set the SMTP port for the GMAIL 
		    $mail->Username = "contactomex@kmimos.la"; // Correo completo a utilizar
		    $mail->Password = "Kmimos2017"; // Contraseña
			$mail->setFrom('contactomex@kmimos.la', 'Blood Brothers | Kmimos');
			$mail->addAddress($correo, $nombre.'-'.$apellido);
			$mail->Subject = 'Suscripcion a Blood Brothers | Kmimos';
			$mail->IsHTML(true);
			$url_imagen_1 = 'https://kmimos.com.mx/bb/boletin-a.jpg'; // <<<<< SUSTITUIR POR URL FINAL
			$url_imagen_2 = 'https://kmimos.com.mx/bb/boletin-c.jpg'; // <<<<< SUSTITUIR POR URL FINAL
			$body = '<center><img src="'.$url_imagen_1.'" width="100%">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr><td style="background-color: #f7234f;">&nbsp;</td></tr>
					<tr>
						<td align="center" style="font-family:\'Arial\';color: #FFF;background-color: #f7234f;font-size: 33px;letter-spacing: 0.5px;">
							'.$nombremascota.'
						</td>
					</tr>
					<tr><td style="background-color: #f7234f;">&nbsp;</td></tr>
				</table>
				<img src="'.$url_imagen_2.'" width="100%"></center>';
			$mail->Body = $body;
			$envio1=false;
			if ($mail->send()) {
			    $envio1=true;
			}
			// ENVIO DE CORREO A ADMINISTRACION
			$mail = new PHPMailer;
		    $mail->IsSMTP();
		    $mail->SMTPAuth = true;                // enable SMTP authentication 
		    $mail->SMTPSecure = "tls";              // sets the prefix to the servier
		    $mail->Host = "smtp.gmail.com";        // sets Gmail as the SMTP server
		    $mail->Port = 587;                     // set the SMTP port for the GMAIL 
		    $mail->Username = "contactomex@kmimos.la"; // Correo completo a utilizar
		    $mail->Password = "Kmimos2017"; // Contraseña
			$mail->setFrom('contactomex@kmimos.la', 'Blood Brothers | Kmimos');
			$mail->addAddress('r.cuevas@kmimos.la');
			$mail->addAddress('r.gonzalez@kmimos.la');
			$mail->addAddress('j.macias@kmimos.la');
			$mail->Subject = 'Suscripcion a Blood Brothers | Kmimos';
			$mail->IsHTML(true);
			$url_imagen = 'https://kmimos.com.mx/bb/boletin.jpg';
			$el_mensaje = '<b>Enviado:</b> '.@date("d-m-Y").'<br>';
			$el_mensaje .= '<b>Nueva Suscripci&oacute;n:</b> '.$nombre.' '.$apellido.'<br>';
			$el_mensaje .= '<b>Nombre Mascota:</b> '.$nombremascota.'<br>';
			$el_mensaje .= '<b>E-Mail:</b> '.$correo.'<br>';
			$mail->Body = $el_mensaje;
			$envio2=0;
			if ($mail->send()) {
			    $envio2=1;
			}
			// Redireccionamiento
			if($envio1 && $envio2){
				// header('Location: send.php?ex=1&mascota='.$nombremascota);
				header('Location: index.php');

			}

		}
	}


}

/*
// Enfermedades


// Conexion a base de datos
include('conex.php');

// Añadiendo a base de datos
$query = 'INSERT INTO formulario(fecha,nombre,apellido,telefono,correo,estado,municipio,desarrollo,raza,tamano,peso,norecuerdo,brucelosis,ehrlichiosis,hemobartonelosis,leishmaniasis,babesiosis,filariasis,toxoplasmosis,anaplasma,ninguna,moquillo,hepatitis,parvovirus,parainfluenza,rabia,leptospirosis,desparasitado,nombremascota) VALUES("'.$fecha.'","'.$nombre.'","'.$apellido.'","'.$telefono.'","'.$correo.'","'.$estado.'","'.$municipio.'","'.$desarrollo.'","'.$raza.'","'.$tamano.'","'.$peso.'","'.$norecuerdo.'","'.$brucelosis.'","'.$ehrlichiosis.'","'.$hemobartonelosis.'","'.$leishmaniasis.'","'.$babesiosis.'","'.$filariasis.'","'.$toxoplasmosis.'","'.$anaplasma.'","'.$ninguna.'","'.$moquillo.'","'.$hepatitis.'","'.$parvovirus.'","'.$parainfluenza.'","'.$rabia.'","'.$leptospirosis.'","'.$desparasitado.'","'.$nombremascota.'")';
$insertar=$db->query($query);
	
	// ENVIO DE CORREO A USUARIO REGISTRADO
	$mail = new PHPMailer;
    $mail->IsSMTP();
    $mail->SMTPAuth = true;                // enable SMTP authentication 
    $mail->SMTPSecure = "tls";              // sets the prefix to the servier
    $mail->Host = "smtp.gmail.com";        // sets Gmail as the SMTP server
    $mail->Port = 587;                     // set the SMTP port for the GMAIL 
    $mail->Username = "contactomex@kmimos.la"; // Correo completo a utilizar
    $mail->Password = "Kmimos2017"; // Contraseña
	$mail->setFrom('contactomex@kmimos.la', 'Blood Brothers | Kmimos');
	$mail->addAddress($correo, $nombre.'-'.$apellido);
	$mail->Subject = 'Suscripcion a Blood Brothers | Kmimos';
	$mail->IsHTML(true);
	$url_imagen_1 = 'https://kmimos.com.mx/bb/boletin-a.jpg'; // <<<<< SUSTITUIR POR URL FINAL
	$url_imagen_2 = 'https://kmimos.com.mx/bb/boletin-c.jpg'; // <<<<< SUSTITUIR POR URL FINAL
	$body = '<center><img src="'.$url_imagen_1.'" width="100%">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr><td style="background-color: #f7234f;">&nbsp;</td></tr>
			<tr>
				<td align="center" style="font-family:\'Arial\';color: #FFF;background-color: #f7234f;font-size: 33px;letter-spacing: 0.5px;">
					'.$nombremascota.'
				</td>
			</tr>
			<tr><td style="background-color: #f7234f;">&nbsp;</td></tr>
		</table>
		<img src="'.$url_imagen_2.'" width="100%"></center>';
	$mail->Body = $body;
	$envio1=false;
	if ($mail->send()) {
	    $envio1=true;
	}
	// ENVIO DE CORREO A ADMINISTRACION
	$mail = new PHPMailer;
    $mail->IsSMTP();
    $mail->SMTPAuth = true;                // enable SMTP authentication 
    $mail->SMTPSecure = "tls";              // sets the prefix to the servier
    $mail->Host = "smtp.gmail.com";        // sets Gmail as the SMTP server
    $mail->Port = 587;                     // set the SMTP port for the GMAIL 
    $mail->Username = "contactomex@kmimos.la"; // Correo completo a utilizar
    $mail->Password = "Kmimos2017"; // Contraseña
	$mail->setFrom('contactomex@kmimos.la', 'Blood Brothers | Kmimos');
	$mail->addAddress('r.cuevas@kmimos.la');
	$mail->addAddress('r.gonzalez@kmimos.la');
	$mail->addAddress('j.macias@kmimos.la');
	$mail->Subject = 'Suscripcion a Blood Brothers | Kmimos';
	$mail->IsHTML(true);
	$url_imagen = 'https://kmimos.com.mx/bb/boletin.jpg';
	$el_mensaje = '<b>Enviado:</b> '.@date("d-m-Y").'<br>';
	$el_mensaje .= '<b>Nueva Suscripci&oacute;n:</b> '.$nombre.' '.$apellido.'<br>';
	$el_mensaje .= '<b>Nombre Mascota:</b> '.$nombremascota.'<br>';
	$el_mensaje .= '<b>E-Mail:</b> '.$correo.'<br>';
	$mail->Body = $el_mensaje;
	$envio2=0;
	if ($mail->send()) {
	    $envio2=1;
	}
	// Redireccionamiento
	if($envio1 && $envio2){
		header('Location: send.php?ex=1&mascota='.$nombremascota);
	}
*/
?>