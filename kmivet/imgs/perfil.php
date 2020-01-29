<?php
    include dirname(__DIR__).'/wp-load.php';
	extract($_POST);

    $anterior = get_user_meta($user_id, 'name_photo', true);
    update_user_meta($user_id, 'name_photo', $img);

    $PATH = dirname(__DIR__)."/wp-content/uploads/avatares/".$user_id."/";
    if( !file_exists($PATH) ){ mkdir($PATH); }
    copy( __DIR__."/Temp/".$img, $PATH.$img );

    unlink( $PATH.$anterior );
    unlink( __DIR__."/Temp/".$img );

    print_r([
    	"user_id" 	=> $user_id,
    	"img" 		=> $img,
    	"anterior" 	=> $anterior
    ]);
?>