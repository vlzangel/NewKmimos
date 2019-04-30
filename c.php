<?php
	/*
	include 'wp-load.php';
	
	if( !isset($_SESSION) ){ session_start(); }

	global $wpdb;

	$cuidadores = $wpdb->get_results("SELECT * FROM cuidadores WHERE activo = 1");

	$cuida = [];

	foreach ($cuidadores as $key => $cuidador) {
		$estados = explode("=", $cuidador->estados);
		$municipios = explode("=", $cuidador->municipios);
		if( count( $estados ) > 3 || count( $municipios ) > 3 ){
			$cuida[] = $cuidador->id;
		}
	}
	

	date_default_timezone_set('America/Mexico_City');

	echo "Fecha: ".date("d/m/Y H:i:s", 1556563873)."<br>";
	echo "Fecha Ahora: ".date("d/m/Y H:i:s", ( time() + 60 ) );

	/*
	$ids = implode(",", $cuida);

	echo "
		SELECT
			nombre,
			apellido,
			titulo,
			email,
			telefono,
			estados,
			municipios
		FROM
			cuidadores
		WHERE
			id IN ({$ids})
	";
	*/
?>

<br />
<font size='1'><table class='xdebug-error xe-notice' dir='ltr' border='1' cellspacing='0' cellpadding='1'>
<tr><th align='left' bgcolor='#f57900' colspan="5"><span style='background-color: #cc0000; color: #fce94f; font-size: x-large;'>( ! )</span> Notice: Trying to get property of non-object in /kmimos2/produccion/kmimos.mx/QA2/wp-content/themes/kmimos/procesos/reservar/pasarelas/paypal/create.php on line <i>32</i></th></tr>
<tr><th align='left' bgcolor='#e9b96e' colspan='5'>Call Stack</th></tr>
<tr><th align='center' bgcolor='#eeeeec'>#</th><th align='left' bgcolor='#eeeeec'>Time</th><th align='left' bgcolor='#eeeeec'>Memory</th><th align='left' bgcolor='#eeeeec'>Function</th><th align='left' bgcolor='#eeeeec'>Location</th></tr>
<tr><td bgcolor='#eeeeec' align='center'>1</td><td bgcolor='#eeeeec' align='center'>0.0003</td><td bgcolor='#eeeeec' align='right'>419992</td><td bgcolor='#eeeeec'>{main}(  )</td><td title='/kmimos2/produccion/kmimos.mx/QA2/wp-content/themes/kmimos/procesos/reservar/pasarelas/paypal/create.php' bgcolor='#eeeeec'>.../create.php<b>:</b>0</td></tr>
<tr><td bgcolor='#eeeeec' align='center'>2</td><td bgcolor='#eeeeec' align='center'>0.0017</td><td bgcolor='#eeeeec' align='right'>809648</td><td bgcolor='#eeeeec'>CreateOrder::create(  )</td><td title='/kmimos2/produccion/kmimos.mx/QA2/wp-content/themes/kmimos/procesos/reservar/pasarelas/paypal/create.php' bgcolor='#eeeeec'>.../create.php<b>:</b>216</td></tr>
</table></font>
