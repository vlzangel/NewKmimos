<?php

	require_once(dirname(dirname(__DIR__)).'/cron/kmimos/funciones.php');

	$hoy = date('Y-m-d');
	if( isset($_GET['d']) && !empty($_GET['d']) ){
		$hoy = $_GET['d'];
	}

	$f = new funciones();

	$usuarios = $f->getUsuarios( $hoy, $hoy );


	/* ************************************* */
	// Estructura de los datos
	/* ************************************* */
	/*
		$data[ tipo ] = [ email => [ referred, sexo, tipo, edad ];
	*/
	$data = ['CL'=>[], 'CU'=>[]];
 
 	if( !empty($usuarios) ){
		foreach ($usuarios as $key => $usuario) {

			
	  		/* ******************************************* */
	  		// Buscar datos 
	  		/* ******************************************* */

	  			$email = $usuario['user_email'];

				# Metadatos de usuarios
					$meta_usuario = $f->getMetaUsuario( $usuario['ID'] );

				# sexo
					$meta_usuario['user_gender'] = ( isset($meta_usuario['user_gender']) ) ? $meta_usuario['user_gender'] : '' ;
					switch (strtolower($meta_usuario['user_gender'])) {
						case 'mujer':
							$meta_usuario['user_gender'] = "F";
							break;
						case 'hombre':
							$meta_usuario['user_gender'] = "M";
							break;
						default:
							$meta_usuario['user_gender'] = "O";
							break;
					}

				# referencia: "Donde nos conocio?"
					$meta_usuario['user_referred'] = ( !empty($meta_usuario['user_referred']) )? $meta_usuario['user_referred'] : 'Otros' ;
	  			
	  			# tipo de usuario
					$tipo = ($usuario['cuidador_id']>0)?'CU':'CL';

				# edad
					if( !isset($meta_usuario['user_age']) || $meta_usuario['user_age'] == "" ){
		  				$meta_usuario['user_age'] = "25-35 A&ntilde;os";
		  			}else{
		  				$meta_usuario['user_age'] .= " A&ntilde;os";
		  			}

			/* ******************************************* */
	  		// Agregar datos 
	  		/* ******************************************* */
		  		
		  		$data[$tipo][] = [ 
		  			"referred" = $meta_usuario['user_referred'],
	  				"sexo" = $meta_usuario['user_gender'],
	  				'tipo' = $tipo,
	  				'edad' = $meta_usuario['user_age'],
	  				'email' = $email
				];
		}
	}

	print_r( json_encode($data, JSON_UNESCAPED_UNICODE) );