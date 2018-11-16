<?php 
    /*
        Template Name: Finalizar
    */

	if( !isset($_SESSION)){ session_start(); }
	unset($_SESSION["pagando"]);

    wp_enqueue_style('finalizar', getTema()."/css/finalizar.css", array(), '1.0.0');
	wp_enqueue_style('finalizar_responsive', getTema()."/css/responsive/finalizar_responsive.css", array(), '1.0.0');

	wp_enqueue_script('finalizar', getTema()."/js/finalizar.js", array("jquery"), '1.0.0');

	get_header();

		global $wpdb;
		
		$orden_id = vlz_get_page();
		$pdf = get_post_meta($orden_id, "_openpay_pdf", true);
		if( $pdf != "" ){
			$pdf = "<a class='btn_fin_reserva' href='{$pdf}' target='_blank'>DESCARGAR INSTRUCCIONES PARA PAGO EN TIENDA DE CONVENIENCIA</a>";
		}
		$data_reserva = kmimos_desglose_reserva_data($orden_id, true);
		kmimos_guardar_desglose_reserva_data( $data_reserva["servicio"]["id_reserva"], $data_reserva );
		
		if( !validar_datos_facturacion( $data_reserva['cliente']['id'] ) ){
			$mensaje_facturacion = "Tu comprobante será emitido al finalizar la reserva. Déjanos tu información para emitir la Factura y te avisaremos cuando esté lista. <br />
				<div class='text-center'>
					<a class='btn_fin_reserva' href='".get_home_url()."/perfil-usuario/datos-de-facturacion/'>CARGAR DATOS DE FACTURACI&OACUTE;N</a>
				</div>";
		}else{
			$mensaje_facturacion = "Te enviaremos tu comprobante fiscal en cuanto este listo por correo.
				<div class='text-center'>
					<a class='btn_fin_reserva' href='".get_home_url()."/perfil-usuario/historial/'>VER MIS RESERVAS</a>
				</div>";
		}


	 /*   if( strtolower($data_reserva["servicio"]["metodo_pago"]) == "tarjeta" ){
	    	$pixel = "<script> evento_fbq ('track','Purchase'); </script>";
	    }else{
	    	$pixel = "";
	    }*/



	    $info = $pixel.'
	        <div class="desglose_box">
	            <div>
	                <div class="sub_titulo">RESERVA</div>
	                <span>'.$data_reserva["servicio"]["id_reserva"].'</span>
	            </div>
	            <div>
	                <div class="sub_titulo">MEDIO DE PAGO</div>
	                <span>Pago por '.$data_reserva["servicio"]["metodo_pago"].'</span>
	            </div>
	        </div>
	        <div class="desglose_box datos_cuidador">
	            
	            <strong>CLIENTE</strong>
	            <div class="item">
	                <div>Nombre</div>
	                <span>
	                    '.$data_reserva["cliente"]["nombre"].'
	                </span>
	            </div>
	            <div class="item">
	                <div>Email</div>
	                <span>
	                    '.$data_reserva["cliente"]["email"].'
	                </span>
	            </div>
	            <div class="item">
	                <div>Tel&eacute;fono</div>
	                <span>
	                    '.$data_reserva["cliente"]["telefono"].'
	                </span>
	            </div>
	        </div>
	    ';

	    $variaciones = "";
	    foreach ($data_reserva["servicio"]["variaciones"] as $value) {
	        $variaciones .= '
	            <div class="item">
	                <div>'.$value[0].' '.$value[1].' x '.$value[2].' x $'.$value[3].'</div>
	                <span>$'.$value[4].'</span>
	            </div>
	        ';
	    }
	    $variaciones = "
	        <div class='desglose_box'>
	            <strong>Servicio</strong>
	            <div class='item'>
	                <div>".$data_reserva["servicio"]["tipo"]."</div>
	                <span>
	                    <span>".date("d/m/Y", $data_reserva["servicio"]["inicio"])."</span>
	                        &nbsp; &gt; &nbsp;
	                    <span>".date("d/m/Y", $data_reserva["servicio"]["fin"])."</span>
	                </span>
	            </div>
	        </div>
	        <div class='desglose_box'>
	            <strong>Mascotas</strong>
	            ".$variaciones."
	        </div>
	    ";

	    $numero_servicios = 1;
	    $nombre_servicios = $data_reserva["servicio"]["tipo"];

	    $adicionales = "";
	    if( count($data_reserva["servicio"]["adicionales"]) > 0 ){
	        foreach ($data_reserva["servicio"]["adicionales"] as $value) {
	            $adicionales .= '
	                <div class="item">
	                    <div>'.$value[0].' - '.$value[1].' x $'.$value[2].'</div>
	                    <span>$'.$value[3].'</span>
	                </div>
	            ';
	            $numero_servicios++;
	            $nombre_servicios .= " - ".$value[0];
	        }
	        $adicionales = "
	            <div class='desglose_box'>
	                <strong>Servicios Adicionales</strong>
	                ".$adicionales."
	            </div>
	        ";
	    }

	    $transporte = "";
	    if( count($data_reserva["servicio"]["transporte"]) > 0 ){
	        foreach ($data_reserva["servicio"]["transporte"] as $value) {
	            $transporte .= '
	                <div class="item">
	                    <div>'.$value[0].'</div>
	                    <span>$'.$value[2].'</span>
	                </div>
	            ';
	            $numero_servicios++;
	            $nombre_servicios .= " - ".$value[0];
	        }
	        $transporte = "
	            <div class='desglose_box'>
	                <strong>Transportaci&oacute;n</strong>
	                ".$transporte."
	            </div>
	        ";
	    }

	    $totales = ""; $descuento = "";

	    if( $data_reserva["servicio"]["desglose"]["descuento"]+0 > 0 ){
	        $descuento = "
	            <div class='item'>
	                <div>Descuento</div>
	                <span>".number_format( $data_reserva["servicio"]["desglose"]["descuento"], 2, ',', '.')."</span>
	            </div>
	        ";
	    }

	    if( $data_reserva["servicio"]["desglose"]["enable"] == "yes" ){
	        
	        $totales = "
	            <div class='desglose_box totales'>
	                <strong>Totales</strong>
	                <div class='item'>
	                    <div class='pago_en_efectivo'>Monto a pagar en EFECTIVO al cuidador</div>
	                    <span>".number_format( ($data_reserva["servicio"]["desglose"]["remaining"]), 2, ',', '.')."</span>
	                </div>
	                <div class='item'>
	                    <div>Pagado</div>
	                    <span>".number_format( $data_reserva["servicio"]["desglose"]["deposit"], 2, ',', '.')."</span>
	                </div>
	                ".$descuento."
	                <div class='item total'>
	                    <div>Total</div>
	                    <span>".number_format( $data_reserva["servicio"]["desglose"]["total"], 2, ',', '.')."</span>
	                </div>
	            </div>
	        ";
	        
	    }else{
	        
	        $totales = "
	            <div class='desglose_box totales'>
	                <strong>Totales</strong>
	                <div class='item'>
	                    <div>Pagado</div>
	                    <span>".number_format( $data_reserva["servicio"]["desglose"]["deposit"], 2, ',', '.')."</span>
	                </div>
	                ".$descuento."
	                <div class='item total'>
	                    <div>Total</div>
	                    <span>".number_format( $data_reserva["servicio"]["desglose"]["total"], 2, ',', '.')."</span>
	                </div>
	            </div>
	        ";
	    }

	    $CONTENIDO .= 
	        "
	        <div class='desglose_container'>".
	            $info.
	            $variaciones.
	            $adicionales.
	            $transporte.
	            $totales.
	        "</div>"
	    ;

	    $que_hacer = "
	    <div class='que_debo_hacer'>
			<div>¿QUÉ DEBO HACER AHORA?</div>
			<ul>
				<li>Revisa tu correo. Te enviaremos la Confirmación o Rechazo de tu Reserva en unos momentos (puede durar desde 30 min a 4 horas).</li>
				<li>Luego de aceptada la Reserva, el cuidador seleccionado y/o Atención al Cliente de Kmimos se pondrán en contacto contigo para alinear la logística de entrega.</li>
				<li>En caso de dudas, puedes contactarte con nuestro equipo de atención al cliente al teléfono 01 (55) 8526 1162, Whatsapp +52 1 (33) 1261 41 86, o al correo contactomex@kmimos.la</li>
			</ul>
		</div>";
	    if( $data_reserva["metodo_pago"] == "Tienda" ){
		    $que_hacer = "
		    <div class='que_debo_hacer'>
				<div>¿QUÉ DEBO HACER AHORA?</div>
				<ul>
					<li>Pícale al botón con las Instrucciones para pagar en la Tienda de Conveniencia que elijas.</li>
					<li>Recuerda que tienes 48 horas para hacer el pago.</li>
					<li><strong>El Cuidador que seleccionaste no recibirá tu solicitud de Reserva sino hasta que hayas hecho el pago en la tienda.</strong></li>
					<li>Una vez que pagues en la Tienda de Conveniencia, el cuidador seleccionado y/o Atención al Cliente de Kmimos se pondrán en contacto contigo dentro de las siguientes 1 a 4 horas para alinear la logística de entrega.</li>
					<li>En caso de dudas, puedes contactarte con nuestro equipo de atención al cliente al teléfono 01 (55) 8526 1162, Whatsapp +52 1 (33) 1261 41 86, o al correo contactomex@kmimos.la</li>

				</ul>

			</div>";
	    }

		if( $_SESSION["wlabel"] == "petco" ){
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
		}

		$HTML .= '
	 		<div class="km-content km-step-end" style="max-width: 840px;">
				<div style="padding: 0px 10px 20px; background: #FFF;">
					<img src="'.getTema().'/images/new/km-reserva/img-end-step.png" width="197">
					<br>
					¡Genial '.get_user_meta($data_reserva["cliente"]["id"], "first_name", true).' '.get_user_meta($data_reserva["cliente"]["id"], "last_name", true).'!<br>
					Reservaste Exitosamente
					<div class="que_debo_hacer" style="margin-top: 5px;">
						<div style="max-width: 450px; margin: 0px auto; text-align: center;">Te acabamos de enviar un correo a tu dirección registrada con ésta información. Por favor revisa tu Buzón de Entrada o Buzón de No Deseados.</div>
					</div>
					<div style="text-align: left; max-width: 840px;" >
						'.$CONTENIDO.'
					</div>
					'.$que_hacer.'
					<div style="padding-top: 20px;">
						'.$pdf.'
						<button class="btn_fin_reserva" data-id="'.$data_reserva['cliente']['id'].'" data-target="emitir_factura">
						    ¿DESEAS EMITIR TU COMPROBANTE FISCAL DIGITAL?
						</button>
						<a class="btn_fin_reserva" href="'.get_home_url().'/perfil-usuario/historial/">VER MIS RESERVAS</a>
					</div>
					'.get_publicidad("reserva").'
				</div>
			</div>

			<!-- SECCIÓN BENEFICIOS -->
			<div class="km-beneficios km-beneficios-footer" style="margin-top: 60px;">
				<div class="container">
					<div class="row">
						<div class="col-xs-4">
							<div class="km-beneficios-icon">
								<img src="'.getTema().'/images/new/km-pago.svg">
							</div>
							<div class="km-beneficios-text">
								<h5 class="h5-sub">PAGO EN EFECTIVO O CON TARJETA</h5>
							</div>
						</div>
						<div class="col-xs-4 brd-lr">
							<div class="km-beneficios-icon">
								<img src="'.getTema().'/images/new/km-certificado.svg">
							</div>
							<div class="km-beneficios-text">
								<h5 class="h5-sub">CUIDADORES CERTIFICADOS</h5>
							</div>
						</div>
						<div class="col-xs-4">
							<div class="km-beneficios-icon">
								<img src="'.getTema().'/images/new/km-veterinaria.svg">
							</div>
							<div class="km-beneficios-text">
								<h5 class="h5-sub">COBERTURA VETERINARIA</h5>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="modal fade" tabindex="-1" role="dialog" id="emitir_factura">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        <h5 class="modal-title text-center">¿DESEAS EMITIR TU COMPROBANTE FISCAL DIGITAL?</h5>
			      </div>
			      <div class="modal-body"><p style="font-size:14px; text-align: center;">'.$mensaje_facturacion.'</p></div>
			    </div>
			  </div>
			</div>
	 	';

	 	$_user_wlabel = false;
	 	if( $_SESSION["wlabel"] == "petco" ){
	 		$_user_wlabel = true;
	 		echo "WLABEL_PETCO<br>";
	 	}
	 	$data = $wpdb->get_var("SELECT count(*) FROM wp_postmeta WHERE ( meta_key = '_wlabel' OR meta_key = 'user_referred' ) AND meta_value LIKE '%petco%' ");
	 	if( $data > 0 ){
	 		$_user_wlabel = true;
	 		echo "USER_PETCO<br>";
	 	}

	 	echo "<pre>";
	 		print_r($data);
	 	echo "</pre>";

	 	/*
			
	 		strrpos($_SERVER["HTTP_REFERER"], "reservar") > 0 && 
	 		!isset($_SESSION[ "reserva_".$data_reserva["servicio"]["id_reserva"] ]) &&
	 	*/

	 	if( 
	 		$_user_wlabel
	 	){
	 		echo "ES_USUARIO_PETCO";
	 		$HTML .= '
				<script>

					switch ( "'.trim(strtolower($data_reserva["servicio"]["metodo_pago"])).'" ) {
			 			case "tienda":
			 				evento_google("nueva_reserva_tienda_completado");
							evento_fbq("track", "traking_code_nueva_reserva_tienda_completado");
			 			break;
			 			case "tarjeta":
			 				evento_google("nueva_reserva_tarjeta_completado");
							evento_fbq("track", "traking_code_nueva_reserva_tarjeta_completado");
			 			break;
			 			case "nueva_reserva_descuento_saldo":
			 				evento_google("nueva_reserva_descuento_saldo");
							evento_fbq("track", "traking_code_nueva_reserva_descuento_saldo");
			 			break;
			 		}

				</script>
		 	';

		 	$_SESSION[ "reserva_".$data_reserva["servicio"]["id_reserva"] ] = "YA_CONTADO";
	 	}	
			


		echo comprimir_styles($HTML);

    get_footer(); 
?>