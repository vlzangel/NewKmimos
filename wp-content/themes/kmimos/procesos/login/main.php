<?php
    include(__DIR__."../../../../../../vlz_config.php");
	
	$conn = new mysqli($host, $user, $pass, $db);
	
    $errores = array();
    
    $email = $_POST['email'];
	
    if ($conn->connect_error) {
        echo 'false';
        echo "No se conecto";
	}else{
        $existen = $conn->query( "SELECT * FROM wp_users WHERE user_email = '{$email}'" );
        if( $existen->num_rows > 0 ){
            $datos = $existen->fetch_assoc();
            echo "SI";
        }else{
            echo "NO";
        }
	}
    
?>