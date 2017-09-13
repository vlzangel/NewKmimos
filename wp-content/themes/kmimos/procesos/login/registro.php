<?php  
	include(__DIR__."../../../../../../vlz_config.php");
    
	date_default_timezone_set('America/Mexico_City');
    extract($_POST);
	
    $conn = new mysqli($host, $user, $pass, $db);

	$errores = array();

	if ($conn->connect_error) {
        echo 'false';
	}else{
		
        $existen = $conn->query( "SELECT * FROM wp_users WHERE user_email = '{$email}'" );
        if( $existen->num_rows > 0 ){
            $datos = $existen->fetch_assoc();

            $msg = "Se encontraron los siguientes errores:\n\n";

            if( $datos['user_email'] == $email ){
                $msg .= "Este E-mail [{$email}] ya esta en uso\n";
            }

            $error = array(
                "error" => "SI",
                "msg" => $msg
            );

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

            $conn->query( utf8_decode( $new_user ) );
            $user_id = $conn->insert_id;




            //WHITE_LABEL
            /**
                Nota Dajan: Yo no estoy usando esta funcion. 
                Solo la deje para un futuro si es solicitado. En el 
                Nuevo registro no hay esta opcion.
            */
            if (!isset($_SESSION)) {
                session_start();
            }

            if(array_key_exists('wlabel',$_SESSION) || $referido=='Volaris' || $referido=='Vintermex'){
                $wlabel='';

                if(array_key_exists('wlabel',$_SESSION)){
                    $wlabel=$_SESSION['wlabel'];

                }else if($referido=='Volaris'){
                    $wlabel='volaris';

                }else if($referido=='Vintermex'){
                    $wlabel='viajesintermex';
                }

                if ($wlabel!=''){
                    $query_wlabel = "INSERT INTO wp_usermeta VALUES (NULL, '".$user_id."', '_wlabel', '".$wlabel."');";
                    $conn->query( utf8_decode( $query_wlabel ) );
                }
            }


            
            $sql = "
                INSERT INTO wp_usermeta VALUES
                    (NULL, {$user_id}, 'user_pass',           '{$password}'),
                    (NULL, {$user_id}, 'user_mobile',         '{$movil}'),
                    (NULL, {$user_id}, 'user_gender',          '{$gender}'),
                    (NULL, {$user_id}, 'user_country',        'México'),
                    (NULL, {$user_id}, 'nickname',            '{$email}'),
                    (NULL, {$user_id}, 'first_name',          '{$name}'),
                    (NULL, {$user_id}, 'last_name',           '{$lastname}'),
                    (NULL, {$user_id}, 'user_age',           '{$age}'),
                    (NULL, {$user_id}, 'user_smoker',           '{$smoker}'),
                    (NULL, {$user_id}, 'rich_editing',        'true'),
                    (NULL, {$user_id}, 'comment_shortcuts',   'false'),
                    (NULL, {$user_id}, 'admin_color',         'fresh'),
                    (NULL, {$user_id}, 'use_ssl',             '0'),
                    (NULL, {$user_id}, 'show_admin_bar_front', 'false'),
                    (NULL, {$user_id}, 'wp_capabilities',     'a:1:{s:10:\"subscriber\";b:1;}'),
                    (NULL, {$user_id}, 'wp_user_level',       '0');
            ";
            $conn->query( utf8_decode( $sql ) );

            echo $user_id;

        }
        
	}
?>