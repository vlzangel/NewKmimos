<?php
	include 'wp-load.php';

	if( count($_POST) ){
		$correos = [];
		$info = explode("\n", utf8_encode( file_get_contents($_FILES['txt']['tmp_name']) ) );
		$data = [];
		$z = false;
		foreach ($info as $value) {
			if( $value != "" ){
				$temp = explode(";", $value);
				if( $z ){
					$data[] = [
						$temp[12],
						$temp[13],
						$temp[14],
						$temp[15],
						$temp[16],
						$temp[17]
					];
				}else{
					$z = true;
				}
			}
		}
		$hoy = date("Y-m-d H:i:s");
		$datos[5] = trim($datos[5]);
		$correos[] = $datos[5];
		foreach ($data as $key => $datos) {
			$pass = substr(md5( $datos[5] ), 0, 5);
			$new_user = "
	            INSERT INTO wp_users VALUES (
	                NULL,
	                '".$datos[5]."',
	                '".md5( $pass )."',
	                '".$datos[5]."',
	                '".$datos[5]."',
	                '',
	                '".$hoy."',
	                '',
	                0,
	                '".$datos[2]." ".$datos[3]."'
	            );
	        ";
	        $wpdb->query( utf8_decode( $new_user ) );
	        $user_id = $wpdb->insert_id;
	        
	        update_user_meta($user_id, 'nickname', $datos[5]);
	        update_user_meta($user_id, 'first_name', $datos[2]);
	        update_user_meta($user_id, 'last_name', $datos[3]);
	        update_user_meta($user_id, 'user_mobile', $datos[4]);
	        update_user_meta($user_id, 'user_phone', $datos[4]);
	        update_user_meta($user_id, 'user_pass', $pass);
	        update_user_meta($user_id, 'user_age', '18-25');
	        update_user_meta($user_id, 'user_smoker', 'NO');
	        update_user_meta($user_id, 'user_gender', 'hombre');
	        update_user_meta($user_id, 'use_ssl', '0');
	        update_user_meta($user_id, 'wp_user_level', '0');
	        update_user_meta($user_id, 'rich_editing', 'true');
	        update_user_meta($user_id, 'admin_color', 'fresh');
	        update_user_meta($user_id, 'user_country', 'México');
	        update_user_meta($user_id, 'comment_shortcuts', 'false');
	        update_user_meta($user_id, 'user_referred', 'FacebookSB');
	        update_user_meta($user_id, 'show_admin_bar_front', 'false');
	        update_user_meta($user_id, 'wp_capabilities', 'a:1:{s:10:"subscriber";b:1;}');

	        //MESSAGE
	        $mensaje = buildEmailTemplate(
	            'registro', 
	            [
	            	"name" => $datos[2]." ".$datos[3],
	            	"email" => $datos[5],
	            	"pass" => $pass,
	            	"url" => site_url()
	            ]
	        );

	        $message = get_email_html($mensaje, false, true, $user_id);

            $args = array(
                'post_title'    => ("Mascota"),
                'post_status'   => 'publish',
                'post_author'   => $user_id,
                'post_type'     => 'pets'
            );
            $pet_id = wp_insert_post( $args );

            update_post_meta($pet_id, "owner_pet", $user_id);
            update_post_meta($pet_id, "name_pet", "Mascota");
            update_post_meta($pet_id, "breed_pet", "5");
            update_post_meta($pet_id, "colors_pet", "Blanco");
            update_post_meta($pet_id, "birthdate_pet", "2018-10-01");
            update_post_meta($pet_id, "size_pet", "1");
            update_post_meta($pet_id, "gender_pet", "2");
            update_post_meta($pet_id, "pet_sterilized", "");
            update_post_meta($pet_id, "pet_sociable", "");
            update_post_meta($pet_id, "aggressive_with_humans", "");
            update_post_meta($pet_id, "aggressive_with_pets", "");
            update_post_meta($pet_id, "about_pet", "");
            update_post_meta($pet_id, "pet_type", "2605");


	        // wp_mail( $datos[5], "Kmimos México Gracias por registrarte! Kmimos la NUEVA forma de cuidar a tu perro!", $message);
		
		}
	}
?>

<form action="?" method="POST" enctype="multipart/form-data" >
	<input type="file" name="txt" />
	<input type="submit" value="Procesar" />
</form>