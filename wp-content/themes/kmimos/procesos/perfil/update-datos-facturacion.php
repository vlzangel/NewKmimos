<?php

	extract( $_POST );
	$user = wp_get_current_user();
	$user_id = $user->ID;

	if( isset($user_id) && $user_id > 0 ){
		update_user_meta( $user_id, "billing_rfc", $rfc);
		update_user_meta( $user_id, "billing_fullname", $nombre); 
		update_user_meta( $user_id, "billing_calle", $calle); 
		update_user_meta( $user_id, "billing_postcode", $cp); 
		update_user_meta( $user_id, "billing_noExterior", $noExterior); 
		update_user_meta( $user_id, "billing_noInterior", $noInterior); 
		update_user_meta( $user_id, "billing_state", $rc_estado);
		update_user_meta( $user_id, "billing_city", $rc_municipio);
		update_user_meta( $user_id, "billing_colonia", $colonia);
		update_user_meta( $user_id, "billing_localidad", $localidad); 

		$respuesta['status']='OK';
	}else{
		$respuesta['status']='ERROR';
	} 
