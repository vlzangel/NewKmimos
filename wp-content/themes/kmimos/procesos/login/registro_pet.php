<?php  
	include(__DIR__."../../../../../../vlz_config.php");

    extract($_POST);
	
    $conn = new mysqli($host, $user, $pass, $db);

	$errores = array();

	if ($conn->connect_error) {
        echo 'false';
	}else{
		
        $existen = $conn->query( "SELECT * FROM wp_users WHERE ID = '{$userid}'" );
        if( $existen->num_rows > 0 ){
            
            $sql = "
                INSERT INTO wp_postmeta VALUES
                    (NULL, {$userid}, 'name_pet',           '{$name_pet}'),
                    (NULL, {$userid}, 'type_pet',         '{$type_pet}'),
                    (NULL, {$userid}, 'race_pet',          '{$race_pet}'),
                    (NULL, {$userid}, 'color_pet',        '$color_pet'),
                    (NULL, {$userid}, 'colour_pet',            '{$colour_pet}'),
                    (NULL, {$userid}, 'date_birth',          '{$date_birth}'),
                    (NULL, {$userid}, 'gender_pet',           '{$gender_pet}'),
                    (NULL, {$userid}, 'size_pet',           '{$size_pet}'),
                    (NULL, {$userid}, 'pet_sterilized',           '{$pet_sterilized}'),
                    (NULL, {$userid}, 'pet_sociable',           '{$pet_sociable}'),
                    (NULL, {$userid}, 'aggresive_humans',           '{$aggresive_humans}'),
                    (NULL, {$userid}, 'aggresive_pets',           '{$aggresive_pets}'),
                    (NULL, {$userid}, 'rich_editing',        'true'),
                    (NULL, {$userid}, 'comment_shortcuts',   'false'),
                    (NULL, {$userid}, 'admin_color',         'fresh'),
                    (NULL, {$userid}, 'use_ssl',             '0'),
                    (NULL, {$userid}, 'show_admin_bar_front', 'false'),
                    (NULL, {$userid}, 'wp_capabilities',     'a:1:{s:10:\"subscriber\";b:1;}'),
                    (NULL, {$userid}, 'wp_user_level',       '0');
            ";
            $conn->query( utf8_decode( $sql ) );

            echo "Registrado"; //$user_id.;

        }else{
            echo "Usuario No registrado.";

            exit;


        }
        
	}
?>