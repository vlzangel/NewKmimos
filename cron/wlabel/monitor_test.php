<?php
	include_once dirname(dirname(__DIR__))."/wp-load.php";
	include 'funciones.php';
	global $wpdb;


    $wlabels = $wpdb->get_results("SELECT * FROM wlabel_monitor");

    foreach ($wlabels as $key => $value) {
        echo "<pre>";
            print_r( json_decode($value->data) );
        echo "</pre>";
    }

?>