<?php  
	include("../vlz_config.php");

    
	date_default_timezone_set('America/Mexico_City');
    extract($_POST);
	
    $conn = new mysqli($host, $user, $pass, $db);

	$errores = array();

	if ($conn->connect_error) {
        echo 'false';
	}else{
		
        $existen = $conn->query( "SELECT * FROM resgistro_multinivel WHERE email = '{$email}'" );
        if( $existen->num_rows > 0 ){
            $datos = $existen->fetch_assoc();

            $msg = "Se encontraron los siguientes errores:\n\n";

            if( $datos['email'] == $email ){
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
                INSERT INTO resgistro_multinivel VALUES (
                    NULL,
                    '".$nombres."',
                    '".$email."',
                    now()
                );
            ";

            $conn->query( utf8_decode( $new_user ) );
          
            echo "SI";

        }
        
	}
?>