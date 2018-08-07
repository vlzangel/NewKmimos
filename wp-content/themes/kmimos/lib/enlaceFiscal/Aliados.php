<?php
date_default_timezone_set('America/Mexico_City');

$aliados = new Aliados();

class Aliados {

	public $db;

	protected $raiz='';

	// EndPoint Enlace Fiscal
	protected $url = 'https://api-debug.enlacefiscal.com/aliados/v1/';
	// protected $url = 'https://api.enlacefiscal.com/aliados/v1/';

	// RFC Cuenta principal
	protected $RFC = '';

	// Modo:  [ produccion , debug ]
	protected $modo = 'debug'; 

	// Credenciales de acceso 
	protected $auth = [
		'produccion' => [
			'user' => 'g8iuk2p56',
			'pass' => '0ab1842b5f9a4eef56cbeb1f9f4ea22fea9463c7'
		],
		'debug' => [
			'user' => 'g8iuk2p56',
			'pass' => '0ab1842b5f9a4eef56cbeb1f9f4ea22fea9463c7'
		]
	];

	// Init
	public function Aliados(){
		$this->raiz = dirname(dirname(dirname(dirname(dirname(__DIR__)))));

		if( !isset($db) || is_string( $db ) ){
			include($this->raiz.'/vlz_config.php');
			if( !class_exists('db') ){
				include($this->raiz.'/wp-content/themes/kmimos/procesos/funciones/db.php');
			}
		    $db = new db( new mysqli($host, $user, $pass, $db) );
		}

		$this->db = $db;
	}

	// Paso #1 - Verificar las firmas digitales
	public function fielValidar  ( $cer, $key, $rfc, $pass ){
		$datos = [
			"cer" => $cer,
			"key" => $key,
			"rfc" => $rfc,
			"password" => $pass,
		];
		return $this->request( $datos, 'fiel/validar ');
	}

	// Paso #2 - Registrar cuidador con firmas
	public function prospectosAlta ( $data ){
		return $this->request( $data, 'prospectos/alta');
	}

	// Paso #3 - Registrar Sucursal del Prospecto  
	public function clienteSucursales ( $rfc ){
		$data = [ 'rfc' => $rfc ];
		return $this->request( $data, 'clientes/sucursales/obtener');
	}

	// Paso #4 - Configurar Serie del Prospecto
	public function clienteSeries ( $serie, $tipoComp, $regimenFiscal, $numeroFolioFiscal, $rfc, $idSucursal ){
		$data = [
			"serie" => $serie,
			"tipoComp" => $tipoComp, 					// "FA",
			"regimenFiscal" => $regimenFiscal, 			// "RGLPM",
			"numeroFolioFiscal" => $numeroFolioFiscal,	//  1,
			"api" => true,								//  true,
			"idSucursal" => $idSucursal, 				// "07b046c1-788e-11e8-9bd1-0800274ce66f",
			"rfc" => $rfc, 								// "AAA010101AAA"
		];
		return $this->request( $data, 'clientes/series/alta');
	}

	// Paso #5 - Obtener Informacion del Prospecto
	public function clientesInfo ( $rfc ){
		$data = [ 'rfc' => $rfc ];
		return $this->request( $data, 'clientes/obtenerInfoCliente');
	}

	// Subir las firmas digitales sin datos generales
	public function fielSubir ( $data ){
		return $this->request( $data, 'fiel/subir');
	}

	public function clientesCertificados ( $cer, $key, $rfc, $pass, $sucursal_id  ){
		$datos = [
			"cer" => $cer,
			"key" => $key,
			"rfc" => $rfc,
			"password" => $pass,
			"idSucursal" => $sucursal_id
		];
		return $this->request( $datos, 'clientes/certificados/subir');
	}

	public function prospectosDesglose( $user_id, $param ){
		extract($param);

		$prospecto = $this->db->get_row( "SELECT * FROM facturas_aliados WHERE user_id = {$user_id}" );
		$cuidador = $this->db->get_row( "SELECT * FROM cuidadores WHERE user_id = {$user_id}" );
		$estado = $this->db->get_row( "SELECT * FROM states WHERE id = {$rc_estado}" );

		$datos = [
			"plan" => [
		    	"tipo" => "Personal"
			],
			"datosFiscales" => [
				"rfc" => $rfc,
				"nombreFiscal" => $razon_social,
				"regimenFiscal" => $regimen_fiscal
			],
			"datosAdministrador" => [
				"nombre" => $nombre,
				"apellidos" => $apellido_paterno,
				"correo" => $cuidador->email,
				"telefono" => $cuidador->telefono,
				"representanteLegal" => 1
			],
			"direccionEmisor" => [
				"calle" =>  $calle,
				"numeroExterior" => $noExterior,
				"numeroInterior" => $noInterior,
				"colonia" =>  $colonia,
				"localidad" =>  $localidad,
				"referencia" =>  'no asignada',
				"municipio" =>  $rc_municipio,
				"estado" =>  $estado->iso,
				"codigoPostal" => $cp,
				"folioSAT" => $prospecto->folioSAT
			],
			"fiel" => [
				"cer" => $fielCer,
				"key" => $fielKey,
				"password" => $fielPass
			],
			"consentimiento" => [
				"terminosYCondiciones" => 1,
				"politicasDePrivacidad" => 1,
				"tramiteCSD" => 1
			],
			"configuraciones" => [
				"enviarCorreoActivacion" => 0
			]			
		];
		
		return $datos;
	}

	// Enviar solicitud a enlaceFiscal
	public function request( $aData = [], $accion = '' ){
		if( empty($aData) || empty($accion) ){ return false; }

		// Autenticacion
		$param = $this->auth[ $this->modo ];
		$aAuth = [
			'User' => $param['user'],
			'Pass' => $param['pass']
		];

		// Endpoint
		$sUrl = $this->url . $accion;

		// Datos
		$sDataJson =  json_encode($aData, JSON_UNESCAPED_UNICODE);
		$nContentLenght = strlen($sDataJson);

		// Configuracion cURL
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $sUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $sDataJson);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		    'Content-Type: application/json',
		    'Content-Length: ' . $nContentLenght
		));
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, "{$aAuth['User']}:{$aAuth['Pass']}");

		// Ejecutar Solicitud
		$Output = curl_exec($ch);
		curl_close($ch);

		return $Output;
	}
	
}