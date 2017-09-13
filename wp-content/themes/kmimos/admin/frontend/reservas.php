<?php
	$CONTENIDO .= '<div class="lista_Reservas" data-user="'.$user_id.'">';

		$sql = "
			SELECT 
				posts.post_status AS status,
				producto.post_title AS titulo,
				posts.post_parent AS orden,
				posts.ID AS ID
			FROM 
				wp_posts AS posts
			LEFT JOIN wp_postmeta AS metas_Reserva ON ( posts.ID = metas_Reserva.post_id AND metas_Reserva.meta_key='_booking_product_id' )
			LEFT JOIN wp_posts AS producto ON ( producto.ID = metas_Reserva.meta_value )
			LEFT JOIN wp_posts AS orden ON ( orden.ID = posts.post_parent )
			WHERE 
				posts.post_type = 'wc_booking' AND
				(
					posts.post_status = 'cancelled' OR 
					posts.post_status = 'paid' OR 
					posts.post_status = 'confirmed' OR 
					posts.post_status = 'complete' OR 
					( posts.post_status = 'unpaid' AND orden.post_status = 'wc-partially-paid' )					
				) AND
				producto.post_author = '{$user_id}'
			ORDER BY posts.ID DESC
		";

		$Reservas = $wpdb->get_results($sql);

		if( count($Reservas) > 0 ){

			//NUEVO
			$booking_coming=array();
			$booking_coming['pending']=array();
			$booking_coming['pending']['title']='Reservas Pendientes';
			$booking_coming['pending']['th']=array();
			$booking_coming['pending']['tr']=array();

			$booking_coming['confirmed']=array();
			$booking_coming['confirmed']['title']='Reservas Confirmadas';
			$booking_coming['confirmed']['th']=array();
			$booking_coming['confirmed']['tr']=array();

			$booking_coming['complete']=array();
			$booking_coming['complete']['title']='Reservas Completadas';
			$booking_coming['complete']['th']=array();
			$booking_coming['complete']['tr']=array();

			$booking_coming['cancelled']=array();
			$booking_coming['cancelled']['title']='Reservas Canceladas';
			$booking_coming['cancelled']['th']=array();
			$booking_coming['cancelled']['tr']=array();

			foreach($Reservas as $key => $Reserva){
				//var_dump($Reserva);

				$_metas_Reserva=get_post_meta($Reserva->ID);
				$_metas=get_post_meta($Reserva->post_parent);
				//var_dump($_metas);

				//Cliente
				$_metas_user = get_user_meta($_metas_Reserva['_booking_customer_id'][0]);
				$_Cliente = $_metas_user['first_name'][0]." ".$_metas_user['last_name'][0];

				$pedido = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID = ".$_metas_Reserva['_booking_product_id'][0]);
				//var_dump($pedido);


				//ReservaS CONFIRMADAS
				if($Reserva->status=='confirmed' && (strtotime($_metas_Reserva['_booking_end'][0])>time())){

					// $options='<a class="theme_btn" href="'.get_home_url().'/perfil-usuario/reservas/ver/'.$Reserva->orden.'">Ver</a>';
					//$options.='<a class="theme_btn cancelled" href="'.get_home_url().'/wp-content/plugins/kmimos/order.php?o='.$Reserva->orden.'&s=0">Cancelar</a>';

					$options=build_select(
					array(
						array(
							'text'=>'Ver',
							'value'=>get_home_url().'/perfil-usuario/reservas/ver/'.$Reserva->orden
						)
					));

					$booking_th=array();
					$booking_th[]=array('class'=>'','data'=>'Reserva');
					$booking_th[]=array('class'=>'','data'=>'Cliente');
					$booking_th[]=array('class'=>'','data'=>'Servicio');
					$booking_th[]=array('class'=>'td_responsive','data'=>'Inicio');
					$booking_th[]=array('class'=>'td_responsive','data'=>'Fin');
					$booking_th[]=array('class'=>'','data'=>'Acciones');
					$booking_coming['confirmed']['th']=$booking_th;

					$booking_td=array();
					$booking_td[]=array('class'=>'','data'=>$Reserva->ID);
					$booking_td[]=array('class'=>'','data'=>$_Cliente);
					$booking_td[]=array('class'=>'','data'=>'<a href="'.get_home_url().'/producto/'.$pedido->post_name.'" target="_blank" >'.$pedido->post_title.'</a>');
					$booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_Reserva['_booking_start'][0]));
					$booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_Reserva['_booking_end'][0]));
					$booking_td[]=array('class'=>'','data'=>$options);
					$booking_coming['confirmed']['tr'][]=$booking_td;


				//ReservaS COMPLETADAS
				}else if($Reserva->status=='complete' || ($Reserva->status=='confirmed' && strtotime($_metas_Reserva['_booking_end'][0])<=time())){

					// $options='<a class="theme_btn" href="'.get_home_url().'/perfil-usuario/reservas/ver/'.$Reserva->orden.'">Ver</a>';

					$options=build_select(
					array(
						array(
							'text'=>'Ver',
							'value'=>get_home_url().'/perfil-usuario/reservas/ver/'.$Reserva->orden
						)
					));

					$booking_th=array();
					$booking_th[]=array('class'=>'','data'=>'Reserva');
					$booking_th[]=array('class'=>'','data'=>'Cliente');
					$booking_th[]=array('class'=>'','data'=>'Servicio');
					$booking_th[]=array('class'=>'td_responsive','data'=>'Inicio');
					$booking_th[]=array('class'=>'td_responsive','data'=>'Fin');
					$booking_th[]=array('class'=>'','data'=>'Acciones');
					$booking_coming['complete']['th']=$booking_th;

					$booking_td=array();
					$booking_td[]=array('class'=>'','data'=>$Reserva->ID);
					$booking_td[]=array('class'=>'','data'=>$_Cliente);
					$booking_td[]=array('class'=>'','data'=>'<a href="'.get_home_url().'/producto/'.$pedido->post_name.'" target="_blank" >'.$pedido->post_title.'</a>');
					$booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_Reserva['_booking_start'][0]));
					$booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_Reserva['_booking_end'][0]));
					$booking_td[]=array('class'=>'','data'=>$options);
					$booking_coming['complete']['tr'][]=$booking_td;



				//ReservaS CANCELADAS
				}else if(($Reserva->status=='cancelled' || $Reserva->post_status=='wc_cancelled') && $_metas['_show'][0]!='noshow'){

					// $options='<a class="theme_btn" href="'.get_home_url().'/perfil-usuario/reservas/ver/'.$Reserva->orden.'">Ver</a>';
					
					$options=build_select(
					array(
						array(
							'text'=>'Ver',
							'value'=>get_home_url().'/perfil-usuario/reservas/ver/'.$Reserva->orden
						)
					));

					$booking_th=array();
					$booking_th[]=array('class'=>'','data'=>'Reserva');
					$booking_th[]=array('class'=>'','data'=>'Cliente');
					$booking_th[]=array('class'=>'','data'=>'Servicio');
					$booking_th[]=array('class'=>'td_responsive','data'=>'Inicio');
					$booking_th[]=array('class'=>'td_responsive','data'=>'Fin');
					$booking_th[]=array('class'=>'','data'=>'Acciones');
					$booking_coming['cancelled']['th']=$booking_th;

					$booking_td=array();
					$booking_td[]=array('class'=>'','data'=>$Reserva->ID);
					$booking_td[]=array('class'=>'','data'=>$_Cliente);
					$booking_td[]=array('class'=>'','data'=>'<a href="'.get_home_url().'/producto/'.$pedido->post_name.'" target="_blank" >'.$pedido->post_title.'</a>');
					$booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_Reserva['_booking_start'][0]));
					$booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_Reserva['_booking_end'][0]));
					$booking_td[]=array('class'=>'','data'=>$options);
					$booking_coming['cancelled']['tr'][]=$booking_td;


					//ReservaS PENDIENTES
				}else if($_metas['_show'][0]!='noshow'){

					// $options='<a class="theme_btn" href="'.get_home_url().'/perfil-usuario/reservas/ver/'.$Reserva->orden.'">Ver</a>';
					// $options.='<a class="theme_btn" href="'.get_home_url().'/wp-content/plugins/kmimos/order.php?o='.$Reserva->orden.'&s=1">Confirmar</a>';
					// $options.='<a class="theme_btn cancelled" href="'.get_home_url().'/wp-content/plugins/kmimos/order.php?o='.$Reserva->orden.'&s=0">Cancelar</a>';
					$options=build_select(
						array(
							array(
								'text'=>'Ver',
								'value'=>get_home_url().'/perfil-usuario/reservas/ver/'.$Reserva->orden
							),
							array(
								'text'=>'Confirmar',
								'value'=>get_home_url().'/wp-content/plugins/kmimos/order.php?o='.$Reserva->orden.'&s=1'
							),
							array(
								'text'=>'Cancelar',
								'class'=>'cancelled action_confirmed',
								'value'=>get_home_url().'/wp-content/plugins/kmimos/order.php?o='.$Reserva->orden.'&s=0'
							)
						));

					$booking_th=array();
					$booking_th[]=array('class'=>'','data'=>'Reserva');
					$booking_th[]=array('class'=>'','data'=>'Cliente');
					$booking_th[]=array('class'=>'','data'=>'Servicio');
					$booking_th[]=array('class'=>'td_responsive','data'=>'Inicio');
					$booking_th[]=array('class'=>'td_responsive','data'=>'Fin');
					$booking_th[]=array('class'=>'','data'=>'Acciones');
					$booking_coming['pending']['th']=$booking_th;

					$booking_td=array();
					$booking_td[]=array('class'=>'','data'=>$Reserva->ID);
					$booking_td[]=array('class'=>'','data'=>$_Cliente);
					$booking_td[]=array('class'=>'','data'=>'<a href="'.get_home_url().'/producto/'.$pedido->post_name.'" target="_blank" >'.$pedido->post_title.'</a>');
					$booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_Reserva['_booking_start'][0]));
					$booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_Reserva['_booking_end'][0]));
					$booking_td[]=array('class'=>'','data'=>$options);
					$booking_coming['pending']['tr'][]=$booking_td;

				}
			}

			//BUILD TABLE
			$CONTENIDO .= build_table($booking_coming);
			
		}else{
			$CONTENIDO .= "<h2 style='line-height: normal;' class='vlz_no_aun'>Usted no tiene Reservas pendientes</h2>";
		}

	$CONTENIDO .= '</div>';
?>