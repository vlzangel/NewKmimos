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
?>