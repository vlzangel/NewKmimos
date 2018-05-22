<?php

	require_once('funciones.php');

	$hoy = date('Y-m-d');
	if( isset($_GET['d']) && !empty($_GET['d']) ){
		$hoy = $_GET['d'];
	}

	$usuarios = getUsuarios( $hoy, $hoy );

	/* ************************************* */
	// Estructura de los datos
	/* ************************************* */
	/*
		$data[ tipo ] = [ email => [ referred, sexo, tipo, edad ];
	*/
	$data = [];
 
	foreach ($usuarios['rows'] as $key => $usuario) {
		
  		/* ******************************************* */
  		// Buscar datos 
  		/* ******************************************* */

  			$email = $usuario['user_email'];

			# Metadatos de usuarios
				$meta_usuario = getMetaUsuario( $usuario['ID'] );

			# sexo
				$meta_usuario['sexo'] = ( isset($meta_usuario['sexo']) ) ? strtoupper($meta_usuario['sexo']) : 'O' ;

			# referencia: "Donde nos conocio?"
				$meta_usuario['dondo_conociste'] = ( !empty($meta_usuario['dondo_conociste']) )? $meta_usuario['dondo_conociste'] : 'Otros' ;
  			
			# edad
				if( !isset($meta_usuario['edad']) || $meta_usuario['edad'] == "" ){
	  				$meta_usuario['edad'] = "25-35 A&ntilde;os";
	  			}else{
	  				$meta_usuario['edad'] .= " A&ntilde;os";
	  			}

	  		# Tipo de usuario
	  			$tipo = 'CL';

		/* ******************************************* */
  		// Agregar datos 
  		/* ******************************************* */
	  		
	  		$data[$tipo][$email]["referred"] = $meta_usuario['dondo_conociste'];  // Donde nos conocio?
  			$data[$tipo][$email]["sexo"] = $meta_usuario['sexo'];		// M: Masculino, F: Femenino, O: Otros
  			$data[$tipo][$email]['tipo'] = $tipo;  // CU: Cuidador, CL: Cliente
  			$data[$tipo][$email]['edad'] = $meta_usuario['edad'];

	}



	/* ******************************************* */
	// Guardar Datos 
	/* ******************************************* */
		echo '<pre>';
		//print_r(['reserva', $hoy, $data]);
		if( !empty($data) ){
			$d = save( 'usuario', $hoy, $data );
			print_r($data);
		}
		echo '</pre>';
