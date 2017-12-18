<?php
	
    include("../../../../../vlz_config.php");
    include("../../../../../wp-load.php");
    include("../funciones/db.php");
    include("../funciones/generales.php");

	/* *************************** *
	 * Validar datos de POST
	 * *************************** */
	$obligatorios = [
		'nombre' => 'Nombre',
		'apellido' => 'Apellido',
		'dni_tipo' => 'Tipo de Documento',
		'dni_numero' => 'Numero de Documento',
		'telefono' => 'Telefono',
		'email' => 'Email',
		'direccion' => 'Direccion',
		'departamento' => 'Departamento',
		'estado'=> 'Estado',
		'municipio'=> 'Municipio',
		'tipo'=> 'Tipo de reclamo',
		'relacionado_a'=> 'Relacionado a',
		'descripcion'=> 'Descripcion',
		'proveedor'=> 'Proveedor',
		'detalle'=> 'Detalle de reclamo',
		'pedido_cliente'=> 'Solicitud del cliente'
	];
	$no_declarados = [];
	foreach ($_POST as $key => $value) {
		if( array_key_exists($key,$obligatorios) ){
			if(!empty($_POST[$key])){
				unset($obligatorios[$key]);
			}
		}else{
			$no_declarados[$key] = 'NULL';
		}
	}
	
	if( count($obligatorios) > 0 ){ 
		echo json_encode(
			[
				'code' => 404,
				'mensaje' => 'Debe completar todos los datos',
				'campos' => implode(",", $obligatorios)
			]
		);
		exit();
	}

	$conn = new mysqli($host, $user, $pass, $db);
	extract($_POST);
	extract($no_declarados);

	/* *************************** *
	 * Guardar Datos
	 * *************************** */
	$id = md5(rand());
	$sql = "
		INSERT INTO `reclamos` VALUES (
			'{$id}',
			'".$_POST['nombre']."', 
			'".$_POST['apellido']."', 
			'".$_POST['dni_tipo']."', 
			'".$_POST['dni_numero']."', 
			'".$_POST['telefono']."', 
			'".$_POST['email']."', 
			'".$_POST['direccion']."', 
			'".$_POST['departamento']."', 
			'".$_POST['estado']."',
			'".$_POST['municipio']."',
			'".$_POST['menor_edad']."',
			'".$_POST['tipo']."',
			'".$_POST['relacionado_a']."',
			'".$_POST['pedido']."',
			'".$_POST['descripcion']."',
			'".$_POST['proveedor']."',
			'".$_POST['date_compra']."',
			'".$_POST['date_consumo']."',
			'".$_POST['date_vence']."',
			'".$_POST['lote']."',
			'".$_POST['detalle']."',
			'".$_POST['pedido_cliente']."',
			'".$_POST['monto']."',
			NULL
		);
	";

	$result = [
		'code' => 400,
		'mensaje' => 'Los datos no fueron guardados',
		'campos' => implode(",", $obligatorios)
	];
	if( $conn->query( utf8_decode( $sql ) ) ){
		$result = [
			'code' => 200,
			'mensaje' => 'Datos guardados' 
		];		
	} 

	echo json_encode($result);