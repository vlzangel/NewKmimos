<?php 
 
 	include_once( dirname(dirname(dirname(__DIR__))).'/conf/database.php' );

	extract($_POST);

	$d = new db();
	if(!empty($nombre) && !empty($costo) && !empty($canal) && !empty($fecha) && !empty($tipo) && !empty($plataforma) ){	
		if( $id > 0 ){
			$sql = "UPDATE monitor_marketing SET
						nombre = '{$nombre}', 
						costo = '{$costo}', 
						fecha = '{$fecha}',
						tipo = '{$tipo}',
						canal = '{$canal}',
						plataforma = '{$plataforma}'
					WHERE id = {$id} ";
			$d->update( $sql );
		}else{	
			$sql = "INSERT INTO monitor_marketing (
						nombre, costo, fecha, tipo, canal, plataforma
					) VALUES (
						'{$nombre}', 
						'{$costo}', 
						'{$fecha}',
						'{$tipo}',
						'{$canal}',
						'{$plataforma}'
					) ";
			$id = $d->insert( $sql );
		}
	}

	if( $id > 0 ){
		echo json_encode(['sts'=>1]);
	}else{
		echo json_encode(['sts'=>0]);	
	}
