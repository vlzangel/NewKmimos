<?php  
	//include(__DIR__."../../../../../../vlz_config.php");
    $config = dirname(__DIR__,5)."/wp-config.php";
    if(file_exists($config)){
        include_once($config);
    }

    extract($_POST);
	
    $conn = new mysqli($host, $user, $pass, $db);

	$errores = array();

	if ($conn->connect_error) {
        echo 'false';
	}else{
        $userid=trim($userid);
        $existen = $conn->query( "SELECT * FROM wp_users WHERE ID = '{$userid}'" );
        if( $existen->num_rows > 0 ){

            // Pets - Photo
            $photo_pet = "";
            if( $img_pet != "" ){
                $photo_pet = time();
                $img_exlode = explode(',', $img_pet);
                $img = end($img_exlode);
                $sImagen = base64_decode($img);
                $tmp_user_id = ($userid) - 5000;
                $dir = "../../../../uploads/mypet/{$tmp_user_id}/";
                @mkdir($dir);
                file_put_contents($dir.'temp.jpg', $sImagen);
                $sExt = mime_content_type( $dir.'temp.jpg' );
                switch( $sExt ) {
                    case 'image/jpeg': $aImage = @imageCreateFromJpeg( $dir.'temp.jpg' ); break;
                    case 'image/gif':  $aImage = @imageCreateFromGif( $dir.'temp.jpg' );  break;
                    case 'image/png':  $aImage = @imageCreateFromPng( $dir.'temp.jpg' );  break;
                    case 'image/wbmp': $aImage = @imageCreateFromWbmp( $dir.'temp.jpg' ); break;
                }
                $nWidth  = 800;
                $nHeight = 600;
                $aSize = getImageSize( $dir.'temp.jpg' );
                if( $aSize[0] > $aSize[1] ){
                    $nHeight = round( ( $aSize[1] * $nWidth ) / $aSize[0] );
                }else{
                    $nWidth = round( ( $aSize[0] * $nHeight ) / $aSize[1] );
                }
                $aThumb = imageCreateTrueColor( $nWidth, $nHeight );
                imageCopyResampled( $aThumb, $aImage, 0, 0, 0, 0, $nWidth, $nHeight, $aSize[0], $aSize[1] );
                imagejpeg( $aThumb, $dir.$photo_pet.".jpg" );
                imageDestroy( $aImage );
                imageDestroy( $aThumb );
                unlink($dir."temp.jpg");

                $photo_pet=$photo_pet.'.jpg';
            }
            // END Pets - Photo


            $args = array(
                'post_title'    => wp_strip_all_tags($name_pet),
                'post_status'   => 'publish',
                'post_author'   => $userid,
                'post_type'     => 'pets'
            );
            $pet_id = wp_insert_post( $args );

            $sql = "
                INSERT INTO wp_postmeta VALUES
                    (NULL, {$pet_id}, 'name_pet',           '{$name_pet}'),
                    (NULL, {$pet_id}, 'photo_pet',         '{$photo_pet}'),
                    (NULL, {$pet_id}, 'type_pet',         '{$type_pet}'),
                    (NULL, {$pet_id}, 'race_pet',          '{$race_pet}'),
                    (NULL, {$pet_id}, 'color_pet',        '$color_pet'),
                    (NULL, {$pet_id}, 'colour_pet',            '{$colour_pet}'),
                    (NULL, {$pet_id}, 'date_birth',          '{$date_birth}'),
                    (NULL, {$pet_id}, 'gender_pet',           '{$gender_pet}'),
                    (NULL, {$pet_id}, 'size_pet',           '{$size_pet}'),
                    (NULL, {$pet_id}, 'pet_sterilized',           '{$pet_sterilized}'),
                    (NULL, {$pet_id}, 'pet_sociable',           '{$pet_sociable}'),
                    (NULL, {$pet_id}, 'aggresive_humans',           '{$aggresive_humans}'),
                    (NULL, {$pet_id}, 'aggresive_pets',           '{$aggresive_pets}'),
                    (NULL, {$pet_id}, 'rich_editing',        'true'),
                    (NULL, {$pet_id}, 'comment_shortcuts',   'false'),
                    (NULL, {$pet_id}, 'admin_color',         'fresh'),
                    (NULL, {$pet_id}, 'use_ssl',             '0'),
                    (NULL, {$pet_id}, 'show_admin_bar_front', 'false'),
                    (NULL, {$pet_id}, 'wp_capabilities',     'a:1:{s:10:\"subscriber\";b:1;}'),
                    (NULL, {$pet_id}, 'wp_user_level',       '0');
            ";
            $conn->query( utf8_decode( $sql ) );

            echo "Registrado"; //$user_id.;

        }else{
            echo "Usuario No registrado.";

            exit;


        }
        
	}
?>