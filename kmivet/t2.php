<?php
	include 'wp-load.php';

    echo $mensaje = kv_get_email_html(
            'recuperar', 
            [
                "URL_IMGS" => get_home_url()."/wp-content/themes/kmimos/images",
                "url"         => $url_activate,
                "name"        => "Angel Veloz"
            ]
        );
?>