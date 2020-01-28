<?php
	include 'wp-load.php';

    $json = '';

    $json = preg_replace("/[\r\n|\n|\r]+/", " ", $json);
    $json = str_replace('"', '', $json);
?>