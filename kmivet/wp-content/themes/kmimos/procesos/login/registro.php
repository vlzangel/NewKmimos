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

        $new_user = "
            INSERT INTO wp_users VALUES (
                NULL,
                '".$email."',
                '".md5($password)."',
                '".$email."',
                '".$email."',
                '',
                '".$hoy."',
                '',
                0,
                '".$name." ".$lastname."'
            );
        ";

        $db->query( utf8_decode( $new_user ) );
        $user_id = $db->insert_id();

        save_user_accept_terms ($user_id, $db);

        print_r($user_id);

        if (!isset($_SESSION)) {
            session_start();
        }

        $name_photo = "";
        $user_photo = 0;
        if( $img_profile != "" ){
            $user_photo = 1;
            $name_photo = $img_profile;

            $dir = "../../../../uploads/avatares_clientes/".$user_id."/";
            @mkdir($dir);

            $path_origen = "../../../../../imgs/Temp/".$img_profile;
            $path_destino = $dir.$img_profile;
            if( file_exists($path_origen) ){
                if( copy($path_origen, $path_destino) ){
                    unlink($path_origen);
                }
            }                  
        }

        $sql = " INSERT INTO wp_usermeta VALUES ";

        $sql .= "
                (NULL, {$user_id}, 'registrado_desde',     'pagina'),

                (NULL, {$user_id}, 'user_pass',            '{$password}'),
                (NULL, {$user_id}, 'user_mobile',          '{$movil}'),
                (NULL, {$user_id}, 'user_phone',           '{$movil}'),
                (NULL, {$user_id}, 'user_gender',          '{$gender}'),
                (NULL, {$user_id}, 'user_country',         'México'),

                (NULL, {$user_id}, 'user_photo',           '{$user_photo}'),
                (NULL, {$user_id}, 'name_photo',           '{$name_photo}'),

                (NULL, {$user_id}, 'description',          ''),

                (NULL, {$user_id}, 'nickname',             '{$email}'),
                (NULL, {$user_id}, 'first_name',           '{$name}'),
                (NULL, {$user_id}, 'last_name',            '{$lastname}'),
                (NULL, {$user_id}, 'user_age',             '{$age}'),
                (NULL, {$user_id}, 'user_smoker',          '{$smoker}'),
                (NULL, {$user_id}, 'user_referred',        '{$referido}'),
                (NULL, {$user_id}, 'rich_editing',         'true'),
                (NULL, {$user_id}, 'comment_shortcuts',    'false'),
                (NULL, {$user_id}, 'admin_color',          'fresh'),
                (NULL, {$user_id}, 'use_ssl',              '0'),
                (NULL, {$user_id}, 'show_admin_bar_front', 'false'),
                (NULL, {$user_id}, 'wp_capabilities',      'a:1:{s:10:\"subscriber\";b:1;}'),
                (NULL, {$user_id}, 'wp_user_level',        '0');
        ";
        $db->multi_query( utf8_decode( $sql ) );

        if (!isset($_SESSION)) { session_start(); }
        $_SESSION["nuevo_registro"] = "YES";

        $mensaje = kv_get_email_html(
            'KMIVET/cliente/nuevo', 
            [
                "KV_URL_IMGS"   => getTema().'/KMIVET/img',
                "URL"           => get_home_url(),
                "NAME"          => 'Angel Veloz',
                "EMAIL"         => 'angel@mail.com',
                "PASS"          => 'Clave',
            ]
        );

        wp_mail( $email, "Kmimos México Gracias por registrarte! Kmimos la NUEVA forma de cuidar a tu perro!", $mensaje);

        //USER LOGIN
        $user = get_user_by( 'id', $user_id );
        wp_set_current_user($user_id, $user->user_login);
        wp_set_auth_cookie($user_id);

    }

    exit;

?>