<?php
	
	// include dirname(__FILE__).'/reconfiguracion.php';

	function get_admins(){
		return [
			'admin' => 'soporte.kmimos@gmail.com',
			'otros' => [
				'BCC: a.veloz@kmimos.la',
				'BCC: y.chaudary@kmimos.la'
			]
		];
	}

	function get_recurso($tipo){
		return getTema()."/recursos/".$tipo."/";
	}

	function CalculaEdad( $fecha ) {
	    list($Y,$m,$d) = explode("-",$fecha);
	    return( date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y );
	}
?>