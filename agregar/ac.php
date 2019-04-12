<?php
	include 'db.php';

	$ids = [
		21640,
		21641,
		21642,
		21645,
		21646,
		21647,
		21648,
		21649
	];

	$mensajes = [
		[
			254570,
			[
				'Son muy amorosos con mi perrhija me da confianza y me informa todo el tiempo de mi bebe  ',
				'Emilio regreso feliz a casa, Fer estuvo muy al pendiente y nos estuvo enviando fotos y videos de Emilio y sus compañeros de fin de semana. Barush es muy amable, esta fue la 1era vez que dejamos a Emilio y quedamos muy satisfechos con la experiencia, definitivamente volvería a recurrir a Fer y Barush, la recomiendo ampliamente. Saludos  ',
				'Excelente cuidadora! 100% recomendada  ',
				'Excelentes personas y excelentes cuidadores. Ponen atención a todos los detalles, comunicación y fotos constantes. Gracias por cuidar al grandote!! 100% recomendados. No se van a arrepentir.  ',
				'EXCELENTE SERVICIO Y UN CUIDADOR MUY CARIÑOSO !!!!  ',
				'Me gustó mucho como cuidó Barush a Burbuja y Peter. Los consintió todo el tiempo y nos mandaba vídeos muchas veces al día para que supiéramos como estaban. Este fin de semana los voy a volver a dejar con él, me da mucha confianza  ',
			]
		],
		[
			214633,
			[
				'Recomiendo ampliamente a Elvi para el cuidado de tu mascota porque es muy linda y dedicada. Los trata con mucho cariño y se aprecia que le gusta lo que hace.  ',
				'Excelente cuidadora , cariñosa y muy al pendiente de mis perritas ',
				'Excelente cuidadora. Atenta y amable con nosotros, cariñosa y cuidadosa con mis perritas. ',
				'Como siempre encantada con los cuidados de Elvi  ',
				'Elvi siempre comprometida con el bienestar de Luna, nos enviaba fotos y videos muy lindas. Luna feliz y yo más. 100% recomendable  ',
				'Excelente cuidadora, manda muchas fotos y está muy al pendiente de las necesidades de las mascotas. :) Muchas gracias por cuidar a mi Dalí :) ',
			]
		],
		[
			231910,
			[
				'Muy recomendable!!! ya es el cuidador oficial de mi bb, lo cuida muy bien y lo pasea mucho, ademas de que convive con sus perros y se divierte, sin duda me da mucha confianza dejarselo, me mantiene informada siempre',
				'Thor estuvo muy contento y consentido tal cual como si estuviese en casa :). Alfredo 100% recomendable.',
				'Hola, Alfredo muy atento y siempre me tuvo informada del estado y atencion de mi Perro',
				'Mis perras estuvieron encantadas y muy bien cuidadas en un hogar en el que jugaron, apapacharon y atendieron de maravilla. Altamente recomendable.',
				'Es el mejor cuidador es al único que le dejo a mi bebé a su cuidado y el siempre se queda feliz y veo cómo disfruta su estancia mientras no estoy  ',
				'Muy buen cuidador! Mis peluditos encantados y socializan pues fue su primera vez, lo recomiendo ampliamente.  ',
			]
		],
	];

	date_default_timezone_set('America/Mexico_City');
    $hoy = date("Y-m-d H:i:s");

	foreach ($mensajes as $key_1 => $info) {
		$cla = array_rand($ids, 6);

		foreach ( $info[1] as $key_2 => $comentario) {

			$user_id = $ids[ $cla[ $key_2 ] ];

		    $email = $db->get_var("SELECT user_email FROM wp_users WHERE ID = {$user_id}");
		    $nombre = $db->get_var("SELECT meta_value FROM wp_usermeta WHERE user_id = {$user_id} AND meta_key = 'first_name'");
		    $apellido = $db->get_var("SELECT meta_value FROM wp_usermeta WHERE user_id = {$user_id} AND meta_key = 'last_name'");

			$sql_comentario = "
		    	INSERT INTO 
		    		wp_comments 
		    	VALUES (
		    		NULL, 
			    	'{$info[0]}', 
			    	'{$nombre} {$apellido}', 
			    	'{$email}', 
			    	'', 
			    	'', 
			    	'{$hoy}', 
			    	'{$hoy}', 
			    	'{$comentario}', 
			    	'0', 
			    	'1', 
			    	'', 
			    	'', 
			    	'0', 
			    	'{$user_id}'
			   	);
			";	

			$db->query( utf8_decode($sql_comentario) );
			$coment_id = $db->insert_id();

			$sql  = "INSERT INTO wp_commentmeta VALUES (NULL, '{$coment_id}', 'care', '5');";
			$sql .= "INSERT INTO wp_commentmeta VALUES (NULL, '{$coment_id}', 'punctuality', '5');";
			$sql .= "INSERT INTO wp_commentmeta VALUES (NULL, '{$coment_id}', 'cleanliness', '5');";
			$sql .= "INSERT INTO wp_commentmeta VALUES (NULL, '{$coment_id}', 'trust', '5');";


			echo $sql_comentario."<br>";
			echo str_replace(";", ";<br>", $sql);
		}

		echo "<br><br>";
	}
?>