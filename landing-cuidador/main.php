<?php
    include("../vlz_config.php");
	
	$conn = new mysqli($host, $user, $pass, $db);
	
    $errores = array();
    
    $email = $_POST['email'];
    // if( strpos($email , '@') === false || (strpos($email , '@') !== false &&  strpos($email , '.', strpos($email , '@')) === false)) {
    //     echo "NO_MAIL";
    //     exit();
    // }
	
    if ($conn->connect_error) {
        echo 'false';
        echo "No se conecto";
        exit();
	}else{
        $existen = $conn->query( "SELECT * FROM resgistro_multinivel WHERE email = '{$email}'" );
        if( $existen->num_rows > 0 ){
            $datos = $existen->fetch_assoc();
            echo "SI";
            exit();
        }else{
            echo "NO";
            exit();
        }
	}
    
?>