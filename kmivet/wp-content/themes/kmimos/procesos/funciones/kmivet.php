<?php
    /* CITAS */
        function new_cita($_POST_) {
            global $wpdb;
            extract( $_POST_ );
            $user_id = $_POST_['user_id'];
            unset( $_POST_['user_id'] );
            $data = json_encode($_POST_, JSON_UNESCAPED_UNICODE);
            $sql = "INSERT INTO wp_kmivet_reservas VALUES ( NULL, '{$user_id}', '{$medico_id}', '{$paciente_id}', '{$appointment_id}', '{$cita_fecha}', '{$data}', 0, NULL, 0, '', '', NOW() )";
            if( $wpdb->query( $sql ) ){
                return [
                    'status' => true,
                    'id' => $wpdb->insert_id
                ];
            }else{
                return [
                    'status' => false,
                    'info' => $sql
                ];
            }
        }

        function cancelar_cita($params){
            global $wpdb;
            extract($params);
            if( $motivo == 'otro' ){ $motivo = $otro_motivo; }
            $res = $wpdb->query("UPDATE wp_kmivet_reservas SET status = 4, observaciones = '{$motivo}', cancelado_por = '{$cancelado_por}' WHERE cita_id = '{$cita_id}' ");

            if( $res != false ){
                $rcs = change_status($cita_id, [ "status" => 4, "description" => $motivo ]);

                if( $rcs['status'] == 'ok' ){                        

                        $info_email = $wpdb->get_var("SELECT info_email FROM wp_kmivet_reservas WHERE cita_id = '{$cita_id}' ");
                        $INFORMACION = (array) json_decode( $info_email );


                        $medicos = get_medics(
                            // $INFORMACION['SPE_MEDIC'],
                            'e41ed3d30309496a845c611dfd8f2e3d',
                            $INFORMACION['LAT_MEDIC'],
                            $INFORMACION['LNG_MEDIC']
                        );

                        $RECOMENDACIONES = ''; $cont = 0;
                        foreach ($medicos["res"]->objects as $key => $medico) {
                            $img = ( ( $medico->profilePic ) != "" ) ? $medico->profilePic : 'http://www.psi-software.com/wp-content/uploads/2015/07/silhouette-250x250.png';
                            $RECOMENDACIONES .= buildEmailTemplate('KMIVET/partes/recomendacion', [
                                'IMG' => $img,
                                'NAME' => email_set_format_name( $medico->firstName.' '.$medico->lastName),
                                'RATE' => email_set_format_ranking( getTema().'/KMIVET/img', $medico->rating),
                                'PRECIO' => email_set_format_precio($medico->price)
                            ]);
                            $cont++;
                            if( $cont > 2 ){
                                break;
                            }
                        }
                        

                        $INFORMACION['RECOMENDACIONES'] = $RECOMENDACIONES;
                        $INFORMACION['KV_URL_IMGS'] = getTema().'/KMIVET/img';

                        $mensaje = kv_get_email_html(
                            'KMIVET/reservas/cancelacion_cliente', 
                            $INFORMACION
                        );
                        wp_mail($INFORMACION['CORREO_CLIENTE'], 'Kmivet - Consulta Cancelada', $mensaje);

                        $mensaje = kv_get_email_html(
                            'KMIVET/reservas/cancelacion_veterinario', 
                            $INFORMACION
                        );
                        wp_mail($INFORMACION['CORREO_VETERINARIO'], 'Kmivet - Consulta Cancelada', $mensaje);

                        $mensaje = kv_get_email_html(
                            'KMIVET/reservas/cancelacion_admin', 
                            $INFORMACION
                        );
                        wp_mail('soporte.kmimos@gmail.com', 'Kmivet - Consulta Cancelada', $mensaje);


                    return ( [ 'status' => true, 'extra' => $INFORMACION ] );
                }else{
                    return ( ([ 'status' => $res, 'error' => 'Error cambiando el estatus en el API' ]) );
                }
            
            }else{
                return ( ([ 'status' => false, 'error' => 'Error cambiando el estatus en kmivet', 'extra' => $res, 'x' => "UPDATE wp_kmivet_reservas SET status = 4, observaciones = '{$motivo}' WHERE cita_id = '{$cita_id}' " ]) );
            }

            return ( ([ 'status' => false, 'error' => 'Error inesperado' ]) );
        }

    /* PACIENTES */

    function new_paciente($user_id, $METAS) {
        global $wpdb;
        $data = json_encode($METAS, JSON_UNESCAPED_UNICODE);
        $sql = "INSERT INTO wp_kmivet_pacientes VALUES (
            NULL,
            '{$user_id}',
            '{$data}',
            NOW()
        )";
        if( $wpdb->query( $sql ) ){
            return $wpdb->insert_id;
        }else{
            return 0;
        }
    }

    /* VETERINARIOS */

    function existe_veterinario($email){
        global $wpdb;
        return $wpdb->get_var("SELECT user_id FROM wp_kmivet_veterinarios WHERE email = '{$email}' ");
    }

    function update_veterinario($user_id, $info){
        global $wpdb;
        extract($info);

        $veterinario = $wpdb->get_row("SELECT id FROM wp_kmivet_veterinarios WHERE user_id = '{$user_id}' ");

        $cambios = [];

        if( $veterinario_id != '' )
            $cambios[] = " veterinario_id = '{$veterinario_id}' ";

        if( $dni != '' )
            $cambios[] = " dni = '{$dni}' ";

        if( $data != '' ){
            $_data = (array) json_decode($veterinario->data);
            foreach ($data as $key => $value) {
                $_data[ $key ] = $value;
            }
            $_data = json_encode($_data, JSON_UNESCAPED_UNICODE);
            $cambios[] = " data = '{$data}' ";
        }

        if( $api != '' ){
            $_api = (array) json_decode($veterinario->api);
            foreach ($api as $key => $value) {
                $_api[ $key ] = $value;
            }
            $_api = json_encode($_api, JSON_UNESCAPED_UNICODE);
            $cambios[] = " api = '{$_api}' ";
        }

        if( $agenda != '' ){
            $_agenda = json_encode($agenda, JSON_UNESCAPED_UNICODE);
            $cambios[] = " agenda = '{$_agenda}' ";
        }

        if( $status != '' )
            $cambios[] = " status = '{$status}' ";

        $cambios = implode(", ", $cambios);

        $sql = "UPDATE wp_kmivet_veterinarios SET {$cambios} WHERE user_id = '{$user_id}'";
        $r = $wpdb->query( $sql );
        if( $r === false ){
            return [ 
                'status' => false, 
                'extras' => [
                    $r,
                    $sql
                ] 
            ];
        }
        
        return [ 
            'status' => true, 
            'user_id' => $user_id
        ];
    }

    function new_veterinario($DATA, $DATA_API) {
        global $wpdb;
        extract($DATA);
        $user_id = username_exists( $kv_email );
        if ( ! $user_id && false == email_exists( $kv_email ) ) {
            $random_password = wp_generate_password( $length = 5, $include_standard_special_chars = false );
            $user_id = wp_create_user( 
                $kv_email, 
                $random_password, 
                $kv_email 
            );
            update_user_meta($user_id, 'first_name', $kv_nombre);
            update_user_meta($user_id, 'user_mobile', $kv_telf_movil);
            update_user_meta($user_id, 'user_phone', $kv_telf_fijo);
            update_user_meta($user_id, 'clave_temp', $random_password);
            update_user_meta($user_id, 'user_referred', 'kmivet');
            update_user_meta($user_id, 'tipo_usuario', 'veterinario');
            $info = array();
            $info['user_login']     = sanitize_user($kv_email, true);
            $info['user_password']  = sanitize_text_field($random_password);
            $info['remember']       = true;
            $user_signon = wp_signon( $info, true );
            wp_set_auth_cookie($user_signon->ID, true);
            $usuario = 'si';
        } else {
            $random_password = "La misma clave de tu usuario de kmivet.";
            $usuario = 'no';
        }  
        if( is_wp_error( $user_id ) )
            return [
                'status' => false,
                'error' => 'Error creando el usuario en kmivet - '.$random_password,
                'wp_info' => $user_id->get_error_message(),
                'extra' => [
                    $DATA,
                    $DATA_API
                ]
            ];
        $existe = $wpdb->get_row("SELECT * FROM wp_kmivet_veterinarios WHERE email = '{$kv_email}' ");
        if( $existe !== false ){

            foreach ($DATA as $key => $value) {
                $temp = preg_replace("/[\r\n|\n|\r]+/", "<br>", $value);
                $temp = str_replace('"', '', $temp);
                $DATA[$key] = $temp;
            }

            $data = json_encode($DATA, JSON_UNESCAPED_UNICODE);
            $api = json_encode($DATA_API, JSON_UNESCAPED_UNICODE);
            $sql = "INSERT INTO wp_kmivet_veterinarios VALUES (
                NULL,
                '{$user_id}',
                NULL,
                '{$kv_email}',
                '{$kv_dni}',
                '{$kv_estado}',
                '{$kv_delegacion}',
                '{$kv_colonia}',
                '{$lat}',
                '{$lng}',
                0,
                0,
                '{$data}',
                '{$api}',
                NULL,
                0
            )";
            if( $wpdb->query( $sql ) ){
                return [
                    'status' => true,
                    'user_id' => $user_id,
                    'veterinario_id' => $wpdb->insert_id,
                    'random_password' => $random_password
                ];
            }else{
                return [
                    'status' => false,
                    'error' => 'No se pudo crear el registro del veterinario'
                ];
            }
        }else{
            return [
                'status' => false,
                'error' => 'Ya existe un veterinario con este email registrado'
            ];
        }
    }
?>