<?php  

    include("../../../../../vlz_config.php");
    include("../../../../../wp-load.php");

    include("../funciones/db.php");
    include("../generales/save_terms.php");

    $db = new db( new mysqli($host, $user, $pass, $db) );
    
    date_default_timezone_set('America/Mexico_City');
    extract($_POST);

    if( preg_match("/[\+]{1,}/", $email) || !filter_var($email, FILTER_VALIDATE_EMAIL) ){
        $fields = [ 'name'=>'email', 'msg'=>"Formato de E-mail invalido"];
        echo "Formato de E-mail invalido";
        exit();
    }

    $existen = $db->get_var( "SELECT ID FROM wp_users WHERE user_email = '{$email}'" );

    if( $existen+0 > 0 ){
        echo "E-mail ya registrado";
        exit;
    }else{
        $hoy = date("Y-m-d H:i:s");

        $nacimiento = date("Y-m-d", strtotime( str_replace("/", "-", $nacimiento) ) );

        $userdata = array(
            'user_pass'             => $password,
            'user_login'            => $email,
            'user_nicename'         => $name,
            'user_email'            => $email,
            'first_name'            => $name,
            'last_name'             => $lastname,
            'show_admin_bar_front'  => false
        );

        $user_id = wp_insert_user( $userdata );

        save_user_accept_terms ($user_id, $db);

        $name_photo = "";
        $user_photo = 0;
        if( $img_profile != "" ){
            $user_photo = 1;
            $name_photo = $img_profile;
            $dir = "../../../../uploads/avatares/".$user_id."/";
            @mkdir($dir);
            $path_origen = "../../../../../imgs/Temp/".$img_profile;
            $path_destino = $dir.$img_profile;
            if( file_exists($path_origen) ){
                if( copy($path_origen, $path_destino) ){
                    unlink($path_origen);
                }
            }                  
        }

        $METAS = [
            'first_name' => $name,
            'last_name' => $lastname,
            'user_country' => 'México',
            'registrado_desde' => 'Pagina',
            'tipo_usuario' => 'paciente',
            'user_pass' => $password,
            'user_mobile' => $movil,
            'user_phone' => $movil,
            'user_gender' => $gender,
            'user_photo' => $user_photo,
            'name_photo' => $name_photo,
            'user_age' => $age,
            'user_birthday' => $nacimiento,
            'user_referred' => $referido,
            'user_email' => $email,
        ];

        foreach ($METAS as $key => $value) {
            update_user_meta($user_id, $key, $value);
        }

        if( !validar_paciente($user_id, $email) ){
            // Se registra el paciente en mediqo.
            // El id se agrega en el meta _mediqo_customer_id desde el mismo meta
            $id = crear_paciente($user_id, [
                'email'     => $email,
                'firstName' => $name,
                'lastName'  => $lastname,
                'phone'     => $movil,
                'password'  => $password,
                'birthday'  => $nacimiento
            ]);
        }

        $METAS['_mediqo_customer_id'] = get_user_meta($user_id, '_mediqo_customer_id', true);

        new_paciente($user_id, $METAS);

        $mensaje = kv_get_email_html(
            'KMIVET/cliente/nuevo', 
            [
                "KV_URL_IMGS" => getTema().'/KMIVET/img',
                "URL"         => get_home_url(),
                "NAME"        => $name.' '.$lastname,
                "EMAIL"       => $email,
                "PASS"        => $password
            ]
        );

        wp_mail( $email, "Kmivet - Gracias por registrarte!", $mensaje);

        //USER LOGIN
        $user = get_user_by('id', $user_id );
        wp_set_current_user($user_id, $user->user_login);
        wp_set_auth_cookie($user_id);

        echo $user_id;
    }

    exit;

?>