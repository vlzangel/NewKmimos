<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<div class="container-fluid">
<?php

	
    session_start();

    date_default_timezone_set('America/Mexico_City');
    
    include('../wp-load.php');
    include('../wp-content/themes/kmimos/lib/enlaceFiscal/Aliados.php');


echo "<br><br><div><strong>Fase I</strong></div>";

	echo '<hr>Validar Cuidador<br>';
		$datos = [

			"cer" => "MIIGQDCCBCigAwIBAgIUMzAwMDEwMDAwMDAzMDAwMjM2ODUwDQYJKoZIhvcNAQELBQAwggFmMSAwHgYDVQQDDBdBLkMuIDIgZGUgcHJ1ZWJhcyg0MDk2KTEvMC0GA1UECgwmU2VydmljaW8gZGUgQWRtaW5pc3RyYWNpw7NuIFRyaWJ1dGFyaWExODA2BgNVBAsML0FkbWluaXN0cmFjacOzbiBkZSBTZWd1cmlkYWQgZGUgbGEgSW5mb3JtYWNpw7NuMSkwJwYJKoZIhvcNAQkBFhphc2lzbmV0QHBydWViYXMuc2F0LmdvYi5teDEmMCQGA1UECQwdQXYuIEhpZGFsZ28gNzcsIENvbC4gR3VlcnJlcm8xDjAMBgNVBBEMBTA2MzAwMQswCQYDVQQGEwJNWDEZMBcGA1UECAwQRGlzdHJpdG8gRmVkZXJhbDESMBAGA1UEBwwJQ295b2Fjw6FuMRUwEwYDVQQtEwxTQVQ5NzA3MDFOTjMxITAfBgkqhkiG9w0BCQIMElJlc3BvbnNhYmxlOiBBQ0RNQTAeFw0xNzA1MTYyMzI5MTdaFw0yMTA1MTUyMzI5MTdaMIH6MSkwJwYDVQQDEyBBQ0NFTSBTRVJWSUNJT1MgRU1QUkVTQVJJQUxFUyBTQzEpMCcGA1UEKRMgQUNDRU0gU0VSVklDSU9TIEVNUFJFU0FSSUFMRVMgU0MxKTAnBgNVBAoTIEFDQ0VNIFNFUlZJQ0lPUyBFTVBSRVNBUklBTEVTIFNDMQswCQYDVQQGEwJNWDEjMCEGCSqGSIb3DQEJARYURmFjdEVsZWN0QHNhdC5nb2IubXgxJTAjBgNVBC0THEFBQTAxMDEwMUFBQSAvIEhFR1Q3NjEwMDM0UzIxHjAcBgNVBAUTFSAvIEhFR1Q3NjEwMDNNREZSTk4wOTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAI\/dbhYNNpkjJwc518pE+8Flbhk4nGwAm4X+J28WF763e1Q1+acPvjDu86dNBuW7kz3P+0C7AgSfXdUqT7oxSLe7aMaLhhpYiTKEXScBiiJSjXI9lpocs729Ab09+fwIHXHb93SetchE\/yebwrcM075kHF+jakFiYYlWncFzX\/syJwTrgaXxmPV+haTspkJrRJykSs1TXu1dGcqM2bL2kPZCdFjM9ymbQ\/b8klpFJQqc4c+vqW863GVGGveN8h+yrbRrJGjkOGDkijRoCvQoMId3G35FJfzCSBCDTtaTybrfhmF5IbPxToLnf1OsfUN11q3310ifpyIjHCfP1PgZZ9MCAwEAAaNPME0wDAYDVR0TAQH\/BAIwADALBgNVHQ8EBAMCA9gwEQYJYIZIAYb4QgEBBAQDAgWgMB0GA1UdJQQWMBQGCCsGAQUFBwMEBggrBgEFBQcDAjANBgkqhkiG9w0BAQsFAAOCAgEAZeaCDYM+52BcfDgSKUbWodQKdVD0T1qITWUJ2jclUAJlZqfcwhrlQJsDwZ5yTg4SWa1nXrhu4MElIy+hyqNrf8rtFTz6iJKBhn+rDihZEtwKU3hwwKKt742YxxGB08+8I2cvipgOstvjTbyhC4M1FXUcWgGRhHxt0ovMRdsYKqgpmp+FJaAmBP4pbuHrDh\/wVIDy3luyezW34I342vHUtsmyRb0RZmlFC0mJBTs\/Mx29MVXQONsh8hocLGZeXgO62tEoIg99Ulno8pibZomVq+SCjqoOY7ralR+Jci6FI\/JaWHKUuK\/feCH91kvQxREbB6\/EuL19xcTcJtBf6P+hY\/G5pqu04kRaUExNspHC8f+WWM0s66F2SCk+uRcOK6hIj9CyW\/gz\/8RXSBbf\/YA31aLz9sNpVtPxNdcOSVNedDD3aH3sgvlH43QQ17BdYuW9BHjs70jdxvT7hhcVCaA3IfZVJY0xNk5F3a4ZYR44fA\/qFvR6e0sH1WgV5l20xiLzQgAIKZ\/4d+XiDaBwdM8omQQK+Q+\/dLJVldWo9Lc5pjYpqHWjnNxsxCCO\/wnT9wyXwM1rxhLPxB8mc7cOpFs0ucBXEOGzCNQFKnnmRK7FM0ibfrYkZ2jwhud4uHfvoRpRMdtnJi2UgMxjMwU\/r41DbgOtQJhtCoHal9gc\/mEZAAg=",
		  
			"key" => "MIIFDjBABgkqhkiG9w0BBQ0wMzAbBgkqhkiG9w0BBQwwDgQIAgEAAoIBAQACAggAMBQGCCqGSIb3DQMHBAgwggS9AgEAMASCBMh4EHl7aNSCaMDA1VlRoXCZ5UUmqErAbucRBAKNQXH8t2clLPjwiZOsJOaFZ5T9k1\/Vl3Q6otOmKmoNpl9U9ylEoWNdKoj\/xRea3Rti8YrmigOEkRcBWKyrnkKvdEjzw\/PSgOxkvNCi7cppD5uQi15UcuwGa+hbg\/JC4CMORYH3xpqvayr3EjxEE15GnuvNy8fITkPKSCaiihAAP\/UuCKBxIzt9CwfAeiPsu7cvqNHpq91eY1uH7+KT+\/c1pCwNMo8g62Y15AC7s5NDe47\/1F0jrP95N9\/V1zZXoikSUDghmEG7HU6GqesmRJnd\/UtVhHBVsVzWcy\/dJPGXAJMa80jvQh6CuPavyVj6EzwWDfoSH6tGOQDPZE34JVb8AQM8gDgZc+4cYNRGTDZhHAlMxdTVQDoNnsHLFSu6Led+jzcMjC4KJlVHTBVKgY7dq6BvLcmUclgJ8jjkr0qp7nhhnjWdzSymREr\/N+Yq0uYBPoFQspoTumRDiAAmTUviUa+6+l+r8Bqryi0I8JqbYOlzvlVrljD3cjtyUDnD0Q05NfT59jNONs\/wS4DLUhTRwFbUgGAzkZzSL9aYl0LnUFvpXkOe472ZMW736tTE0+\/v5QVrkbOnLn+98Epm2k2sg4PKCkjO1h29ct8SaF2bQx5jcagTIcfhhDgfMaQWvZ2kPklt3tEPpPmyLAGgZEU94+xZra313hnA2HTAVh\/xiUx1Ui+X4c94Ib0Q5hoNUAnXEUeZiTtsUlv4mgyot4TTYHf\/RRMpsDCBlp3QGdh11oU2mFafYOyOqTt2iROZM+jcztIEujUqayBFQj6gZHq3OwAj02KrdQ+yutcAmQTZxplm2YnFCJ5H4nm27ZaeLbZuGD30NKrQ7v8H+cOII8ZUbCLLhHoE6FRd6lNOybtwCdh8xl1seARRVUdy3bA\/+WkZpXDyIQe\/EYUrZxZ1rIrXA2rjYxUyBu+CgCgvJ1vhKfGZc9ECs0Mnc6XWgvLn3fjCx78t545iSHqPPv6SM2ibO6OP4MgyOal8uwupw6nqhdFo179Ma18Wb8nYnV+g6K+NE\/QGSzswvu56ADgaIkdWkE+XhihVENbE+Zj4qRevu8kZUUhgtUbAN61Vi5azJoJ\/0WYcA7onE2k2IxCIvAgyrsGKjSGeuQvl3++W0W+CSMWwLbXb+ktzCFhyGW+HRifSUil5jRD83hP9n3VgXHNxhqFtvEdhFqK\/Rh+YIC4uj3p\/BiCKionxZiPOM+yjoCJV6l9W+G31lBz3VxzjZHO4cI8HJ7\/j6Z42jc4bSYbZfmqngRslMTbeMa0t6XICoJ9TIvmI1RBl6D+iW2WNTgn1EC+1gU1XVj\/EvJtAIc6U9y2Iak5NNsLmzdRVrxKdl15IdFDYnpiuvYtXblf6Nq\/nKQGFhGpwqKDJCtRqfGOf70muQB7qB05fV6BtNJqHd1dD\/E7kmf7mqC10JsOlvam23KJ5d+6d0xUBp7T6sgMSLBsWvtPXKU2Vpj0H14Z0LAk3q8aOLG2JZH7TVqYOkslu+khvLklEUV3BYrqX5+X0I9EuqaXzEXxOfpM+lZK2kpg+M9p6uvW59yTU8GORvhr+B+VY3em6boV20377hlI6dAaNjUTKwCq9wg+QAs4=",

			"pass" => "MTIzNDU2Nzhh",

			"rfc" => "AAA010101AAA"
		];
		$respuesta = $aliados->fielValidar( $datos['cer'], $datos['key'], $datos['rfc'], $datos['pass'] );
		respuesta( $datos, $respuesta );

	echo '<hr>Registrar prospecto<br>';
		$registro = [
		  "plan" => [
		    "tipo" => "Personal"
		  ],
		  "datosFiscales" => [
		    "rfc" => $datos['rfc'],
		    "nombreFiscal" => "Empresa demons Jose perez",
		    "regimenFiscal" => "RGLPM"
		  ],
		  "datosAdministrador" => [
		    "nombre" => "Pedro",
		    "apellidos" => "Perez Perez",
		    "correo" => "italococchini@gmail.com",
		    "telefono" => "3312345678",
		    "representanteLegal" => 1
		  ],
		  "direccionEmisor" => [
		    "calle" => "Luciernaga",
		    "numeroExterior" => "123",
		    "numeroInterior" => "A-23",
		    "colonia" => "Esperanza",
		    "localidad" => "Zapopan",
		    "referencia" => "Antes de llegar a periferico",
		    "municipio" => "Zapopan",
		    "estado" => "JAL",
		    "codigoPostal" => "12332",
		    "folioSAT" => "0132556498"
		  ],
		  "fiel" => [
		    "cer" => $datos['cer'],
		    "key" => $datos['key'],
		    "password" => $datos['pass']
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
		$respuesta = $aliados->prospectosAlta( $registro );
		respuesta( $registro, $respuesta );


echo "<br><br><div><strong>Fase II</strong></div>";

	echo '<hr>Sucursales<br>';
		$respuesta = $aliados->clienteSucursales( $datos['rfc'] );
		respuesta( ['rfc'=>$datos['rfc']], $respuesta );

	echo '<hr>Cliente Series<br>';
		$da = [
			'serie' => "CC",
			'tipoComp' => "FA",
			'regimenFiscal' => "RGLPM",
			'numeroFolioFiscal' => 1,
			'api' => true,
			'idSucursal' => "07b046c1-788e-11e8-9bd1-0800274ce66f",
			'rfc' => $datos['rfc'],
		];

		$respuesta = $aliados->clienteSucursales( 
			$da['serie'], 
			$da['tipoComp'], 
			$da['regimenFiscal'], 
			$da['numeroFolioFiscal'], 
			$da['api'], 
			$da['idSucursal'], 
			$da['rfc'] 
		);
		respuesta( $da, $respuesta );

	echo '<hr>Subir Certificados<br>';
		$datos["idSucursal"] = "07b046c1-788e-11e8-9bd1-0800274ce66f";
		$respuesta = $aliados->clientesCertificados( $datos['cer'], $datos['key'], $datos['rfc'], $datos['pass'], $datos["idSucursal"] );
		respuesta( $datos, $respuesta );

echo "<br><br><div><strong>Complementos</strong></div>";

	echo '<hr>Informacion del Cliente<br>';
		$respuesta = $aliados->clientesInfo( $datos['rfc'] );
		respuesta( ['rfc'=>$datos['rfc']], $respuesta );









function respuesta( $datos, $respuesta ){

		echo '<pre class="col-md-12">Parametros: ';
		print_r( json_encode($datos, JSON_UNESCAPED_UNICODE) );
		echo '</pre><br><pre class="col-md-12">Respuesta: ';
		print_r($respuesta);
		echo '</pre>';

}

?>

</div>