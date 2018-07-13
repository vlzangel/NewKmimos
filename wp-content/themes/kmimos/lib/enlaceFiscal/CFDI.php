<?php
date_default_timezone_set('America/Mexico_City');

$CFDI = new CFDI();

// Test
//$CONTENIDO .= $CFDI->obtenerSaldo();
//$CONTENIDO .= $CFDI->probarConexion();
//$CONTENIDO .= $CFDI->generarCfdi();

class CFDI {

	// EndPoint Enlace Fiscal
	protected $url = 'https://api.enlacefiscal.com/v6/';

	// Modo:  [ produccion , debug ]
	protected $modo = 'debug'; 

	// RFC Cuenta principal
	protected $RFC = 'KMI160615640';

	// Credenciales de acceso 
	protected $auth = [
		'produccion' => [
			'token' => '',
			'x-api-key' => ''
		],
		'debug' => [
			'token' => 'c83e1f14de69b963add399109a97a392',
			'x-api-key' => 'e9aT1ajrRh1NyRkzOtDoN1ZEGmIsEKuJ6f3FYyLh'
		]
	];

	// Saldo en enlaceFiscal
	protected $saldo = 0;


	// Verificar si tiene saldo
	public function CFDI(){
		
		// $_saldos = $this->obtenerSaldo();
		// if( !empty($_saldos) ){
		// 	$r = json_decode($_saldos);
		// 	$this->saldo = $r['AckEnlaceFiscal']['saldo'];
		// 	if( $this->saldo < 0.89 ){
		// 		// wp_mail( 'italococchini@gmail.com', 'Prueba - Saldo enlaceFiscal', 'Sin saldo debe de recargar - Prueba' );
		// 	}
		// }

	}

	// Probar conexion con enlaceFiscal
	public function probarConexion(){ 
		$aData = array(
			'Solicitud' => array(
				'rfc'  => $this->RFC,
                'accion' => 'probarConexion'
            )
		);

		return $this->request( $aData, 'probarConexion' );
	}

	// Obtener Saldo para Generar CFDI
	public function obtenerSaldo(){
		$aData = array(
			'Solicitud' => array(
				'rfc'  => $this->RFC,
                'accion' => 'obtenerSaldo'
            )
		);

		return $this->request( $aData, 'obtenerSaldo' );
	}

	// Generar CFDI para el Cliente ( Monto: 100% )
	public function generar_Cfdi_Cliente( $data=[] ){


		$data['rfc'] = $this->RFC; // Dato de prueba hasta que se registre los datos del cuidador
		$data['serie'] = 'CC';

		// Variables de Estructura
			$data['fechaEmision'] = date('Y-m-d H:i:s');
			$partidas = [];
			$personalizados = [];
			$iva = 16; // IVA
			$base_iva = 100 + $iva; 
			$tasaCuota = $iva / 100; // IVA
			$totalImporte = 0; // Total de Impuesto
			$formaDePago = "";
			$_subtotal = 0;
			$_total = 0;
			$sinDescuento = false;

		// Forma de Pago - Catalago del SAT
			switch( $data['servicio']['tipo_pago'] ){
				//01;Efectivo;efectivo
				case "PAGO EN TIENDA":
					$formaDePago = "01";
					break;

				//04;Tarjeta de crédito;tarjeta_credito
				case "PAGO":
					$formaDePago = "04";
					break;

				//03;Transferencia electrónica de fondos;transferencia
				case "TRANSFERENCIA":
					$formaDePago = "03";
					break;			

				//99;Por definir;otro
				case "Pago por Saldo y/o Descuentos":
					$sinDescuento = false;
					$formaDePago = "99";
					break;			
				default:
					$formaDePago = "99";
					break;			

			}

		// Agregar Partida: Variaciones
			if( isset($data['servicio']['variaciones']) && !empty($data['servicio']['variaciones']) ){			
				foreach ($data['servicio']['variaciones'] as $item) {

					$item[3] = str_replace(".", "", $item[3]);
					$item[3] = str_replace(",", ".", $item[3]);

					// Buscar Numeros de Noches
					$num_noches = explode(" ", $item[2]);
					if( !isset($num_noches[0]) || $num_noches[0] <= 0 ){
						$num_noches[0] = 1;
					}

					// Cantidad
					$cantidad = $item[0] * $num_noches[0];

					// Valor Unitario del servicio
					$valorUnitario = $item[3];

					// Desglose Impuesto: Calcular precio base
					$base = $valorUnitario * 100 / $base_iva;

					// Valor del servicio por la cantidad
					$subtotal = $cantidad * $base;

					// Desglose Impuesto: Calcular impuestos
					$impuesto = $subtotal * $tasaCuota;

					$totalImporte += number_format( $impuesto, 2 );

					$_subtotal += number_format($subtotal, 2);
					$_total += $subtotal + $impuesto; 

					$partidas[] = [
					    "cantidad" => $cantidad,
					    "claveUnidad" => "DAY",
					    "claveProdServ" => "90111500", // Pendiente por verificar en el SAT *******************************
					    "descripcion" => $item[0]." ". $item[1] ." x ".$item[2] ." x $".$item[3],
					    "valorUnitario" =>(float) number_format($base, 2, '.', ''),
					    "importe" => (float) number_format( $subtotal, 2, '.', ''),
						"descuento" => (float) number_format( $data['servicio']['desglose']['descuento'], 2, '.', ''),
					    "Impuestos" => [
					    	0 => [
								"tipo" => "traslado",
								"claveImpuesto" => "IVA",
								"tipoFactor" => "tasa",
								"tasaOCuota" => (float) $tasaCuota,
								"baseImpuesto" => (float) number_format( $subtotal, 2, '.', ''),
								"importe" => (float) number_format( $impuesto, 2, '.', '')
						    ]
					    ]				
					];
				}
			}
		
		// Agregar Partida: Transporte
			if( isset($data['servicio']['transporte']) && !empty($data['servicio']['transporte']) ){			
				foreach ($data['servicio']['transporte'] as $item) {

					$item[2] = str_replace(".", "", $item[2]);
					$item[2] = str_replace(",", ".", $item[2]);

					// Buscar Numeros de Noches
					$num_noches = explode(" ", $item[1]);
					if( !isset($num_noches[0]) || $num_noches[0] <= 0 ){
						$num_noches[0] = 1;
					}

					// Cantidad
					$cantidad = 1 * $num_noches[0];

					// Valor Unitario del servicio
					$valorUnitario = $item[2];

					// Desglose Impuesto: Calcular precio base
					$base = $valorUnitario * 100 / $base_iva;

					// Valor del servicio por la cantidad
					$subtotal = $cantidad * $base;

					// Calcular impuestos
					$impuesto = $subtotal * $tasaCuota;

					$totalImporte += number_format( $impuesto, 2 );

					$_subtotal += number_format($subtotal, 2);
					$_total += $subtotal + $impuesto; 

					$partidas[] = [
					    "cantidad" => $cantidad,
					    "claveUnidad" => "DAY",
					    "claveProdServ" => "90111500", // Pendiente por verificar en el SAT *******************************
					    "descripcion" =>  $item[0] ." x ".$item[1] ." x $".$item[2],
					    "valorUnitario" =>(float)  number_format($base, 2, '.', ''),
					    "importe" => (float) number_format( $subtotal, 2, '.', ''),
					    "Impuestos" => [
					    	0 => [
								"tipo" => "traslado",
								"claveImpuesto" => "IVA",
								"tipoFactor" => "tasa",
								"tasaOCuota" => (float) $tasaCuota,
								"baseImpuesto" => (float) number_format( $subtotal, 2, '.', ''),
								"importe" => (float) number_format( $impuesto, 2, '.', '')
						    ]
					    ]				
					];
				}
			}

		// Agregar Partida: Adicionales
			if( isset($data['servicio']['adicionales']) && !empty($data['servicio']['adicionales']) ){	
				foreach ($data['servicio']['adicionales'] as $item) {

					$item[2] = str_replace(".", "", $item[2]);
					$item[2] = str_replace(",", ".", $item[2]);

					// Buscar Numeros de Noches
					$num_noches = explode(" ", $item[1]);
					if( !isset($num_noches[0]) || $num_noches[0] <= 0 ){
						$num_noches[0] = 1;
					}

					// Cantidad
					$cantidad = 1 * $num_noches[0];

					// Valor Unitario del servicio
					$valorUnitario = $item[2];

					// Desglose Impuesto: Calcular precio base
					$base = $valorUnitario * 100 / $base_iva;

					// Valor del servicio por la cantidad
					$subtotal = $cantidad * $base;

					// Calcular impuestos
					$impuesto = $subtotal * $tasaCuota;

					$totalImporte += number_format( $impuesto, 2 );

					$_subtotal += number_format($subtotal, 2);
					$_total += $subtotal + $impuesto; 

					$partidas[] = [
					    "cantidad" => $cantidad,
					    "claveUnidad" => "DAY",
					    "claveProdServ" => "90111500", // Pendiente por verificar en el SAT *******************************
					    "descripcion" => $item[0] ." x ".$item[1] ." x $".$item[2],
					    "valorUnitario" =>(float)  number_format($base, 2, '.', ''),
					    "importe" => (float) number_format($subtotal, 2, '.', ''),
					    "Impuestos" => [
					    	0 => [
								"tipo" => "traslado",
								"claveImpuesto" => "IVA",
								"tipoFactor" => "tasa",
								"tasaOCuota" => (float) $tasaCuota,
								"baseImpuesto" => (float) number_format( $subtotal, 2, '.', ''),
								"importe" => (float) number_format( $impuesto, 2, '.', '')
						    ]
					    ]				
					];
				}
			}

		// Agregar Campos Personalizados
			$personalizados[] = [
                "nombreCampo" => "Número de Reserva",
                "valor" => $data['servicio']['id_reserva']
	        ];

        // Calcular precio base de la factura
			// $subtotal = $data['servicio']['desglose']['total'] * 100 / $base_iva;

	       	if ( $sinDescuento ){
	       		$data['servicio']['desglose']['descuento'] = 0;
	       		$descuento = 0;
	       	}

		// Estructura de datos CFDI
			$CFDi = [
				"CFDi" => [
					"modo" => $this->modo,
					"versionEF" => "6.0",
					"serie" => $data['serie'], //"FAA",
					"folioInterno" => $data['servicio']['id_reserva'],
					"tipoMoneda" => "MXN",
					"fechaEmision" => $data['fechaEmision'], //"2017-02-22 11:03:43",
					"subTotal" => (float) number_format( $_subtotal, 2, '.', ''), //"20.00", ( Sin IVA )
					"total" => (float) number_format( $_total, 2, '.', ''), // "23.20" ( Con IVA )
					"rfc" => $data['rfc'],
					"descuentos" => (float) number_format( $data['servicio']['desglose']['descuento'], 2, '.', ''),
					"DatosDePago" => [
						"metodoDePago" => "PUE",
						"formaDePago" => $formaDePago, //"03"
					],
					"Receptor" => [
						"rfc" => $data['receptor']['rfc'], //"BBB010101BB1",
						"nombre" => $data['receptor']['nombre'], //"Empresa Demo's",
						"usoCfdi" => "gastos"
					],
					"Partidas" => $partidas,
					"Impuestos" => [
						"Totales" => [
							"traslados" =>  (float) number_format( $totalImporte, 2, '.', '')
						],
						"Impuestos" => [
							0 => [
								"tipo" => "traslado",
								"claveImpuesto" => "IVA",
								"tipoFactor" => "tasa",
								"tasaOCuota" => (float)$tasaCuota,
								"importe" =>(float) number_format( $totalImporte, 2, '.', '')
							]
						]
					],
					"Personalizados" => $personalizados
					/*"EnviarCFDI" => [
			            "Correos" => [
			                "italococchini@gmail.com"
			            ],
			            "tipo" => "persona",
			            "mensajeCorreo" => "Mensaje personalizado que se incluirá en el cuerpo del correo a enviar."
			        ]*/
				]
			];

	 	// return $CFDi;
		$cfdi_respuesta = $this->request( $CFDi, 'generarCfdi' );
		return [ 
			'ack' => $cfdi_respuesta, 
			'param' => $CFDi,  
		];
	}

	// Registra el CFDI en la data de Kmimos
	public function guardarCfdi( $CFDi_receptor, $data, $ack , $db){
		if( empty($data) || empty($ack) ){ return false; }

		$raiz = dirname(dirname(dirname(dirname(dirname(__DIR__)))));		

		$ef = $ack->AckEnlaceFiscal;
		if( isset($ef->estatusDocumento) && $ef->estatusDocumento == 'aceptado' ){
			
			$reserva_id = $db->get_var("select reserva_id from facturas where numeroReferencia = '".$ef->numeroReferencia."'", "reserva_id");
			if( $reserva_id <= 0 ){
	
				// guardar datos en DB
				$sql = "INSERT INTO facturas ( 
					receptor,
					cuidador_id,
					cliente_id,
					pedido_id,
					reserva_id,
					serie,
					numeroReferencia,
					serieCertificadoSAT,
					serieCertificado,
					folioFiscalUUID,
					fechaTFD,
					fechaGeneracion,
					estado,
					xml,
					urlXml,
					urlPdf,
					urlQR
				 )values(
				 	'".$CFDi_receptor."',
					".$data['cuidador']['id'].",
					".$data['cliente']['id'].",
					".$data['servicio']['id_orden'].",
					".$ef->folioInterno.",
					'".strtoupper($ef->serie)."',
					'".$ef->numeroReferencia."',
					'".$ef->noSerieCertificadoSAT."',
					'".$ef->noSerieCertificado."',
					'".$ef->folioFiscalUUID."',
					'".$ef->fechaTFD."',
					'".$ef->fechaGeneracionCFDi."',
					'".strtoupper($ef->estadoCFDi)."',
					'".$ef->xmlCFDi."',
					'".$ef->descargaXmlCFDi."',
					'".$ef->descargaArchivoPDF."',
					'".$ef->descargaArchivoQR."'
				 );
				";
				$db->query( $sql );

				// descargar archivo PDF
				$path = $raiz.'/wp-content/uploads/facturas/';
				$filename = $path . $ef->folioInterno.'_'.$ef->numeroReferencia; // [ folioInterno = Reserva_id ]

				$file_pdf_sts = file_put_contents( 
					$filename. '.pdf', 
					$this->descargar_cfdi($ef->descargaArchivoPDF) 
				);
				
				$file_xml_sts = file_put_contents( 
					$filename. '.xml', 
					$this->descargar_cfdi($ef->descargaXmlCFDi) 
				);

				if( $file_pdf_sts ){
					$respuesta = $filename. '.pdf';
				}
			}
		}

		return $respuesta;
	}

	// Descargar archivo - Retorna bufer con la data del archivo
	public function descargar_cfdi ($url){
	    $bufer = '';

	    if (ini_get ('allow_url_fopen')) {

	        // La forma facil...

	        $da = fopen ($url, 'r');

	        if (! $da) {
	            //echo "No ha podido leerse el contenido de $url\n";
	            return FALSE;
	        }

	        while (! feof ($da))
	            $bufer .= fread ($da, 4096);

	        fclose ($da);
	    } else {

	        preg_match ('/^\\s*(?:\\w+:\\/{2})?(.*?)(:\\d+)?(\\/.*)$/',
	                    $url, $coincidencias);

	        $dominio = $coincidencias[1];
	        $puerto  = $coincidencias[2];
	        $ruta    = $coincidencias[3];

	        if (! $puerto)
	            $puerto = '80';

	        if (! $ruta)
	            $ruta = '/';

	        $socket = fsockopen ($dominio, $puerto);

	        if (! $socket) {
	            //echo "No pudo establecerse una conexion con $dominio\n";
	            return FALSE;
	        }

	        fwrite ($socket, "GET $ruta HTTP/1.0\n\n");

	        while (! feof ($socket))
	            $bufer .= fread ($socket, 4096);

	        fclose ($socket);

	        $bufer = preg_replace ('/^.*?(\\r?\\n){2}/s', '', $bufer);
	    }

	    return $bufer;
	}

	// Enviar solicitud a enlaceFiscal
	public function request( $aData = [], $accion = '' ){
		if( empty($aData) || empty($accion) ){ return false; }

		// Autenticacion
		$param = $this->auth[ $this->modo ];
		$aAuth = [
			'User' => $this->RFC,
			'Pass' => $param['token']
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
		    'x-api-key: ' . $param['x-api-key'],
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