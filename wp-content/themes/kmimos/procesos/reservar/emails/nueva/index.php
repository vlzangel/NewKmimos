<?php

	$info = kmimos_get_info_syte();

	add_filter( 'wp_mail_from_name', function( $name ) {
        global $info;
        return $info["titulo"];
    });
    add_filter( 'wp_mail_from', function( $email ) {
        global $info;
        return $info["email"]; 
    });

	include(dirname(__FILE__)."/vlz_data_orden.php");
	
 	
 	$modificacion_de = get_post_meta($reserva_id, "modificacion_de", true);
    if( $modificacion_de != "" ){		
 		$modificacion = 'Esta es una modificación de la reserva #: '.$modificacion_de;
 	}else{		
 		$modificacion = "";		
 	}

	$email_admin = $info["email"];

	$aceptar_rechazar = '
		<center>
			<p><strong>¿ACEPTAS ESTA RESERVA?</strong></p>
			<table> <tr> <td>
				<a href="'.get_home_url().'/wp-content/plugins/kmimos/order.php?o='.$orden_id.'&s=1&t=1" style="text-decoration: none; padding: 7px 0px; background: #00d2b7; color: #FFF; font-size: 16px; font-weight: 500; border-radius: 5px; width: 100px; display: inline-block; text-align: center;">
					Aceptar
				</a> </td> <td>
				 <a href="'.get_home_url().'/wp-content/plugins/kmimos/order.php?o='.$orden_id.'&s=0&t=1" style="text-decoration: none; padding: 7px 0px; background: #dc2222; color: #FFF; font-size: 16px; font-weight: 500; border-radius: 5px; width: 100px; display: inline-block; text-align: center;">
				 	Rechazar
				 </a> </td> </tr>
			</table>
		</center>
	';

	$dudas = '<p align="justify">Para cualquier duda y/o comentario puedes contactar al Staff Kmimos a los teléfonos '.$info["telefono"].', o al correo '.$info["email"].'</p>';

	if( strtolower($metodo_pago) == "tienda" ){
		include("tienda.php");
	}else{
		include("otro.php");
	}

?>