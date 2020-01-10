<?php
    include dirname(__DIR__).'/wp-load.php';

	extract($_POST);

    print_r($_POST);

    echo $img_actual = get_user_meta($user_id, 'name_photo', true);

    update_user_meta($user_id, 'name_photo', $img);

    copy(__DIR__."/Temp/".$img, dirname(__DIR__)."/wp-content/uploads/".$tipo."/".$img);
    unlink( dirname(__DIR__)."/wp-content/uploads/".$tipo."/".$img_actual );
    unlink( __DIR__."/Temp/".$img );


?>