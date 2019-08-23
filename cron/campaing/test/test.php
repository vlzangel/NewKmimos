<?php
	include dirname(dirname(dirname(__DIR__))).'/wp-load.php';

	function add_seguimiento($mensaje, $info){
		$mensaje = preg_replace("/[\r\n|\n|\r]+/", " ", $mensaje);
		preg_match_all("#href=\"http(.*?)\"#i", $mensaje, $matches);
		$url_base = get_home_url().'/campaing_2';
		foreach ($matches[1] as $key => $url) {
			$old_url = "http".substr($url, 0, -1);
			$data = base64_encode( json_encode( [
				"id" => $info["campaing"],
				"email" => $info["email"],
				"url" => $old_url,
			] ) );
			$new_url = $url_base.'/'.$data.'/redi';
			$mensaje = str_replace($old_url, $new_url, $mensaje);
		}
		return $mensaje;
	}

	$test = '
	<p>Esto es una <a href="https://www.kmimos.com.mx/">prueba</a> de <a href="https://www.google.co.ve/">links</a></p>
	<img src="https://www.img.com/">';
	echo add_seguimiento($test, [
		"campaing" => 14,
		"email" => "x@x.x",
	]);
?>