<?php
	$_user_wlabel = false;
 	if( $_SESSION["wlabel"] == "petco" ){
 		$_user_wlabel = true;
 	}
 	$data = $wpdb->get_var("SELECT count(*) FROM wp_usermeta WHERE user_id = '{$data_reserva["cliente"]["id"]}' AND ( meta_key = '_wlabel' OR meta_key = 'user_referred' ) AND meta_value LIKE '%Petco%' ");
 	if( $data > 0 ){
 		$_user_wlabel = true;
 	}

 	if( 
 		strrpos($_SERVER["HTTP_REFERER"], "reservar") > 0 && 
 		!isset($_SESSION[ "reserva_".$data_reserva["servicio"]["id_reserva"] ]) &&
 		$_user_wlabel 
 	){
		$HTML .= '
			<script type="text/javascript">
			    window._adftrack.push({
			        pm: 1453019,
			        divider: encodeURIComponent("|"),
			        pagename: encodeURIComponent("MX_Kmimos_TYP_180907"),
			        order : { 
			            sales: "'.$data_reserva["servicio"]["desglose"]["total"].'",
			            orderid: "'.$data_reserva["servicio"]["id_reserva"].'",
			            sv1: "'.$data_reserva["servicio"]["metodo_pago"].'",
			            itms: [{ 
			                productcount: "'.$numero_servicios.'",
			                productname: "'.$nombre_servicios.'",
			                step: ""
			            }]
			        }
			    });
			</script>
			<noscript>
			    <p style="margin:0;padding:0;border:0;">
			        <img src="https://a2.adform.net/Serving/TrackPoint/?pm=1453019&ADFPageName=MX_Kmimos_TYP_180907&ADFdivider=|" width="1" height="1" alt="" />
			    </p>
			</noscript>
		';
 		$HTML .= '<script>';
 		switch ( trim(strtolower($data_reserva["servicio"]["metodo_pago"])) ) {
 			case "tienda":
 				$HTML .= '
 					evento_google("nueva_reserva_tienda_completado");
					evento_fbq_2("track", "traking_code_nueva_reserva_tienda_completado");
				';
 			break;
 			case "tarjeta":
 				$HTML .= '
	 				evento_google("nueva_reserva_tarjeta_completado");
					evento_fbq_2("track", "traking_code_nueva_reserva_tarjeta_completado");
				';
 			break;
 			case "nueva_reserva_descuento_saldo":
 				$HTML .= '
	 				evento_google("nueva_reserva_descuento_saldo");
					evento_fbq_2("track", "traking_code_nueva_reserva_descuento_saldo");
				';
 			break;
 		}
 		$HTML .= '</script>';

	 	$_SESSION[ "reserva_".$data_reserva["servicio"]["id_reserva"] ] = "YA_CONTADO";
 	}	

 	echo '<script> evento_google_kmimos(\'confirmacion_reserva\'); evento_fbq_kmimos(\'confirmacion_reserva\'); </script>';
?>