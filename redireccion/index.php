<?php
	
	include( '../wp-load.php' );
	// include( '../wp-content/themes/kmimos/NEW/funciones.php' );
	
	if( is_user_logged_in() ){
		$current_user = wp_get_current_user();
		$user_id = $current_user->ID;
		set_uso_banner(['user_id'=>$user_id, 'tag'=>'banner_localizacion']);
	}
	header('location:'.$_GET['url']);