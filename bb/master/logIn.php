<?php
if((isset($_POST['usuario']) && !empty($_POST['usuario'])) && (isset($_POST['clave']) && !empty($_POST['clave']))) {
	
	// Variables
	$usuario = addslashes(trim($_POST['usuario']));
	$clave = addslashes(trim($_POST['clave']));
	$key = hash("sha256", $usuario);
	$contrasena_hmac = hash_hmac('ripemd256', $clave, $key);

	// // Buscar 
	include('../conex.php');
 	$b = $db->get_row('SELECT id_usuario,clave FROM usuarios WHERE usuario = "'.$usuario.'" LIMIT 1');
	if($b!=false){
		if($b->clave == $contrasena_hmac){
			session_start();
			$_SESSION['i'] = $b->id_usuario;
			$_SESSION['n'] = 'Master';
			header('Location: listado.php');			
		} else{
			// echo 1;
			header('Location: index.php?e=1');
		}
	}else{
		// echo 2;
		header('Location: index.php?e=1');
	}
} else {
	// echo 3;
	header('Location: index.php?e=1');
}