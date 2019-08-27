<?php
	session_start();

	include '../wp-load.php';
	global $wpdb;

	if( count($_POST) ){
		$correos = [];
		$info = explode("\n", utf8_encode( file_get_contents($_FILES[0]['tmp_name']) ) );
		$separador = ";";
		$formato = explode($separador, $info[0]);
		if ( count($formato) != 18 ){
			$separador = ",";
			$formato = explode($separador, $info[0]);
		}

		$formato = ( count($formato) == 18 ) ? "YES" : "NO";
		if( $formato == "NO" ){
			echo "error-El Excel no tiene el formato correcto";
		}else{
			$data = [];
			$z = false;
			foreach ($info as $value) {
				if( $value != "" ){
					$temp = explode($separador, $value);
					if( $z ){
						$data[] = [
							$temp[12],
							$temp[13],
							$temp[14],
							$temp[15],
							str_replace("p:", "", $temp[16]),
							$temp[17]
						];
					}else{
						$z = true;
					}
				}
			}

			$hoy = date("Y-m-d H:i:s");
			foreach ($data as $key => $datos) {
				$datos[5] = trim($datos[5]);
				$existe = $wpdb->get_var("SELECT ID FROM wp_users WHERE user_email = '{$datos[5]}' ");
				$correos[] = $datos[5];
				if( $existe == null ){
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
			        $wpdb->query( ( $new_user ) );
			        $user_id = $wpdb->insert_id;

			        update_user_meta($user_id, 'registrado_desde', "pagina");
			        update_user_meta($user_id, 'registrado_con', "FacebookSB");
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

				$wpdb->query("INSERT INTO wp_usermeta VALUES (NULL, {$user_id}, 'wp_capabilities', 'a:1:{s:10:\"subscriber\";b:1;}');");

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

			        $tam = [
			        	"pequeño" => 0,
			        	"mediano" => 1,
			        	"grande" => 2,
			        	"gigante" => 3
			        ];

			        $raza = $wpdb->get_var("SELECT id FROM razas WHERE nombre LIKE '%{$data[0]}%'");

		            update_post_meta($pet_id, "owner_pet", $user_id);
		            update_post_meta($pet_id, "name_pet", "Mascota");
		            update_post_meta($pet_id, "colors_pet", "Blanco");
		            update_post_meta($pet_id, "birthdate_pet", "2018-10-01");
		            update_post_meta($pet_id, "size_pet", $tam[ $data[1] ]);
		            update_post_meta($pet_id, "breed_pet", $raza);
		            update_post_meta($pet_id, "gender_pet", "2");
		            update_post_meta($pet_id, "pet_sterilized", "");
		            update_post_meta($pet_id, "pet_sociable", "");
		            update_post_meta($pet_id, "aggressive_with_humans", "");
		            update_post_meta($pet_id, "aggressive_with_pets", "");
		            update_post_meta($pet_id, "about_pet", "");
		            update_post_meta($pet_id, "pet_type", "2605");

			        wp_mail( $datos[5], "Kmimos México Gracias por registrarte! Kmimos la NUEVA forma de cuidar a tu perro!", $message);

		        	$content = '';
					$content = '<h2>Datos del Cliente</h2>';
					$content .= '<div>Nombre: '. $datos[2] .'</div>';
					$content .= '<div>Apellido: '. $datos[3] . '</div>';
					$content .= '<div>Correo: '. $datos[5] .'</div>';
					$content .= '<div>Telefono: '. $datos[4].' </div>';
					$content .= '<div>Donde nos conocio: FacebookSB</div>';
					$content .= '<h2>Datos de la Mascota</h2>';  
					$content .= '<div>No agreg&oacute; mascota en el registro</div>';
					
					kmimos_mails_administradores_new( "Registro de Nuevo Cliente", $content );

				}
				$credenciales = $wpdb->get_var("SELECT data FROM campaing WHERE id = 1");
				require_once __DIR__.'/campaing/csrest_campaigns.php';
				$credenciales = json_decode( $credenciales );
				$auth = (array) $credenciales->auth;
				$lists = (array) $credenciales->lists;
				require_once __DIR__.'/campaing/csrest_subscribers.php';
				$list = new CS_REST_Subscribers("0409899d192bff86e74d2cc8473b60fb", $auth);
				foreach ($correos as $key => $email) {
					$r = $list->add([
						"EmailAddress" => $email,
					    "Resubscribe" => true,
					    "RestartSubscriptionBasedAutoresponders" => true,
					    "ConsentToTrack" => "Yes"
					]);
				}
			}
		}
	}
?>