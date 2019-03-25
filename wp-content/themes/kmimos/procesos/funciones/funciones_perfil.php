<?php
	if(!function_exists('construir_botones')){
	    function construir_botones($botones){
	    	$respuesta = "";
	    	foreach ($botones as $boton => $accion) {
	    		switch ($boton) {
	    			case 'ver':
	    				$respuesta .= '<a data-accion="ver/'.$accion.'" class="vlz_accion vlz_ver"> <i class="fa fa-info" aria-hidden="true"></i> Ver</a>';
    				break;
	    			case 'subir_fotos':
	    				$respuesta .= '<a data-accion="'.get_home_url().'/perfil-usuario/reservas/subir/'.$accion.'" class="vlz_accion vlz_ver"> <i class="fa fa-cloud-upload" aria-hidden="true"></i> Subir Fotos</a>';
    				break;
	    			case 'confirmar':
	    				$respuesta .= '<a data-accion="confirmar/'.$accion.'" class="vlz_accion vlz_confirmar"> <i class="fa fa-check" aria-hidden="true"></i> <span>Confirmar</span> </a>';
    				break;
	    			case 'cancelar':
	    				$respuesta .= '<a data-accion="cancelar/'.$accion.'" class="vlz_accion vlz_cancelar"> <i class="fa fa-trash-o" aria-hidden="true"></i> <span>Cancelar</span> </a>';
    				break;
	    			case 'modificar':
	    				$respuesta .= '<a data-accion="'.$accion.'" class="vlz_accion vlz_modificar"> <img src="'.get_recurso('img/PERFILES').'Modificar.svg" /> Modificar </a>';
    				break;
	    			case 'pdf':
	    				$respuesta .= '<a data-accion="'.$accion.'" class="vlz_accion vlz_pdf"> <i class="fa fa-download" aria-hidden="true"></i> ¿Com&oacute; pagar? </a>';
    				break;
	    			case 'valorar':
	    			    $respuesta .= '<a href="'.get_home_url().'/valorar/'.$accion.'" class="vlz_accion vlz_valorar"> <i class="fa fa-thumbs-o-up" aria-hidden="true"></i> Valorar </a>';
    				break;


	    			case 'ver_s':
	    				$respuesta .= '<a data-accion="'.$accion.'" class="vlz_accion vlz_ver"> <i class="fa fa-info" aria-hidden="true"></i> Ver</a>';
    				break;
	    			case 'confirmar_s':
	    				$respuesta .= '<a data-accion="confirmar/'.$accion.'" class="vlz_accion vlz_confirmar"> <i class="fa fa-check" aria-hidden="true"></i> <span>Confirmar</span> </a>';
    				break;
	    			case 'cancelar_s':
	    				$respuesta .= '<a data-accion="cancelar/'.$accion.'" class="vlz_accion vlz_cancelar"> <i class="fa fa-trash-o" aria-hidden="true"></i> <span>Cancelar</span> </a>';
    				break;
	    			case 'facturar':
	    				$respuesta .= '<a data-accion="factura/'.$accion.'" class="vlz_accion vlz_ver"> <i class="fa fa-file-o" aria-hidden="true"></i> Generar factura</a>';
    				break;
	    			case 'noFacturar':
	    				$disabled = ( $accion == 'disabled' )? 'disabled' : '' ;
	    				$respuesta .= '<a data-accion="" data-toggle="modal" data-target="#'.$accion.'" class="vlz_accion '.$disabled.'"> <i class="fa fa-file-o" aria-hidden="true"></i> Generar factura</a>';
    				break;


	    			case 'factura_pdf':
	    				$respuesta .= '<a href="'.$accion.'" class="vlz_accion vlz_ver boton boton_border_gris"> <i class="fa fa-cloud-download" aria-hidden="true"></i>Descargar PDF</a>';
    				break;
	    			case 'factura_xml':
	    				$respuesta .= '<a href="'.$accion.'" class="vlz_accion vlz_ver boton boton_border_gris"> <i class="fa fa-cloud-download" aria-hidden="true"></i>Descargar XML</a>';
    				break;
	    			case 'factura_PdfXml':
	    				$respuesta .= '<a data-pdfxml="'.$accion.'" class="vlz_accion vlz_ver boton boton_border_gris"> <i class="fa fa-cloud-download" aria-hidden="true"></i> PDF y XML</a>';
    				break;
	    			
	    		}
	    	}
	    	return $respuesta;
	    }
	}

	if(!function_exists('construir_listado')){
	    function construir_listado($args = array()){
	        $table='';
	        $avatar_img = get_home_url()."/wp-content/themes/kmimos/images/noimg.png";
	        if( !isset($args['cliente']) && !isset($args['cuidador']) ){
		        foreach($args as $reservas){
					if( !isset($reservas['solicitudes']) ){
		        		$table.='
		                	<h1 class="titulo">'.$reservas['titulo'].'</h1>
		                	<div class="vlz_tabla_box">
		                ';
			        	if( is_array($reservas['reservas']) && count($reservas['reservas']) > 0 ){

			                foreach ($reservas['reservas'] as $reserva) {

			                	$cancelar = '';
			                	if( isset($reserva["acciones"]["cancelar"]) ){
			                		//$cancelar = '<a data-accion="'.get_home_url().'/wp-content/plugins/kmimos/'.$reserva["acciones"]["cancelar"].'" class="vlz_accion vlz_cancelar cancelar"> <i class="fa fa-trash-o" aria-hidden="true"></i></a>';
			                	}

			                	$botones = construir_botones($reserva["acciones"]);

			                	$vlz_tabla_inferior = "";

			                	$descuento = "";
			                	if( $reserva["desglose"]["descuento"]+0 > 0){
			                		$descuento = '
			                			<div class="item_desglose">
				                			<div>Descuento</div>
				                			<span>$'.number_format( $reserva["desglose"]["descuento"], 2, ',', '.' ).'</span>
				                		</div>
			                		';
			                	}

			                	if( isset($reserva["desglose"]["remaining"]) ){
				                	$remanente = '
				                		<div class="item_desglose vlz_bold">
				                			<div style="color: #6b1c9b;" >Monto Restante a Pagar en EFECTIVO al cuidador</div>
				                			<span style="color: #6b1c9b;">$'.number_format( ($reserva["desglose"]["remaining"]), 2, ',', '.').'</span>
				                		</div>
				                	';
				                	$pago = '
				                		<div class="item_desglose">
				                			<div>Pagó</div>
				                			<span>$'.number_format( $reserva["desglose"]["deposit"], 2, ',', '.').'</span>
				                		</div>
				                	';
			                	}else{
			                		$remanente = '';
				                	$pago = '
				                		<div class="item_desglose">
				                			<div>Pagó</div>
				                			<span>$'.number_format( $reserva["desglose"]["subtotal"], 2, ',', '.').'</span>
				                		</div>
				                	';

			                	}

			                	if( $reserva["cliente"] != "" ){
				                	$reserva["cliente"] = '
				                		<br> <span>Por</span>
				                		<div class="cliente_reserva">
				                			<p>'.$reserva["cliente"].'</p>
				                		</div>
				                	';
			                	}

			                	$ayuda = '';
			                	if( $reserva['ayuda'] == 'factura' ){
			                		$ayuda = "<small>Tienes problemas con tu facturación? Escríbenos a este número <i class='fa fa-whatsapp'></i> +52 (33) 1261 4186, o al correo contactomex@kmimos.la</small>";
			                	}

			                	$conocer = '';
			                	if( isset($reserva['conocer']) && $reserva['conocer'] = 'b'  ){
			                		$conocer = '
			                		<a 
			                			role="button" 
			                			data-name="'.$reserva['data_conocer']['name'].'" 
			                			data-id="'.$reserva['data_conocer']['id'].'" 
			                			data-url="'.$reserva['data_conocer']['url'].'" 
			                			data-target="#popup-conoce-cuidador" 
			                			class="ver_conocer_init boton boton_border_gris"
			                			onclick="evento_google_kmimos(\'conocer_busqueda\'); evento_fbq_kmimos(\'conocer_busqueda\');"
			                		>Conocer Cuidador</a>';
			                	}

			                	if( isset($reserva["desplegado"]) ){
									$table.='
					                <div class="vlz_tabla vlz_desplegado">
					                	<div class="vlz_img">
					                		<span style="background-image: url('.$reserva["foto"].');"></span>
					                	</div>
					                	<div class="vlz_tabla_superior">
						                	<div class="vlz_tabla_cuidador vlz_celda">
						                		<span>Servicio: <a href="'.get_home_url().'/reservar/'.$reserva["servicio_id"].'/">'.$reserva["servicio"].'</a></span>
						                	</div>
						                	<div class="vlz_tabla_cuidador vlz_celda">
						                		<span>Fecha</span>
						                		<div>'.$reserva["inicio"].' a '.$reserva["fin"].'</div>
						                	</div>
						                	<div class="vlz_tabla_cuidador vlz_cerrar">
						                		<span>Reserva</span>
						                		<div>'.$reserva["id"].'</div>
						                	</div>
					                	</div>
					                	<div class="vlz_tabla_cuidador vlz_botones vlz_celda boton_fuera">
					                		<a class="ver_reserva_init_fuera boton boton_borde_gris">Ver Reserva</a>
					                	</div>
					                	<div class="vlz_tabla_inferior">
					                		
					                		<div class="desglose_reserva">
						                		<div class="item_desglose vlz_bold vlz_solo_movil">
						                			<div>RESERVA</div>
						                			<span>'.$reserva["id"].'</span>
						                		</div>
						                		<div class="item_desglose vlz_bold">
						                			<div>MÉTODO DE PAGO</div>
						                			<span>'.$reserva["desglose"]["tipo"].'</span>
						                		</div>
						                		'.$remanente.'
						                		'.$descuento.'
						                		'.$pago.'
					                		</div>
					                		<div class="total_reserva">
						                		<div class="item_desglose">
						                			<div>TOTAL</div>
						                			<span class="total_desglose">$'.number_format( $reserva["desglose"]["total"]+0, 2, ',', '.').'</span>
						                		</div>
					                		</div>
					                		<div class="ver_reserva_botones">
						                		'.$botones.'
					                		</div>
					                	</div>
					                </div>';
			                	}else{
			                		$table.='
					                <div class="vlz_tabla">
					                	<div class="vlz_img">
					                		<span style="background-image: url('.$reserva["foto"].');"></span>
					                	</div>
					                	<div class="vlz_tabla_superior">
						                	<div class="vlz_tabla_cuidador vlz_celda">
						                		<span>Servicio: <a href="'.get_home_url().'/reservar/'.$reserva["servicio_id"].'/">'.$reserva["servicio"].'</a></span>
						                		<span>Fecha: '.$reserva["inicio"].' a '.$reserva["fin"].'</span>
						                	</div>
						                	<div class="vlz_tabla_cuidador vlz_botones vlz_celda boton_interno" style="vertical-align: middle;">
						                		'.$cancelar.'

						                		<a class="ver_reserva_init boton boton_border_gris">Ver Reserva</a>

						                		'.$conocer.'
						                	</div>
						                	<div class="vlz_tabla_cuidador vlz_cerrar" style="vertical-align: top;">
						                		<span>Reserva: '.$reserva["id"].'</span>
						                	</div>
					                	</div>
				                		<i class="fa fa-times-circle ver_reserva_init_closet" aria-hidden="true"></i>
					                	<div class="vlz_tabla_cuidador vlz_botones vlz_celda boton_fuera">
					                		<a class="ver_reserva_init_fuera">Ver Reserva</a>
					                	</div>
					                	<div class="vlz_tabla_inferior">
					                		
					                		<div class="desglose_reserva">
						                		<div class="item_desglose vlz_bold vlz_solo_movil">
						                			<div>RESERVA</div>
						                			<span>'.$reserva["id"].'</span>
						                		</div>
						                		<div class="item_desglose vlz_bold">
						                			<div>MÉTODO DE PAGO</div>
						                			<span>'.$reserva["desglose"]["tipo"].'</span>
						                		</div>
						                		'.$remanente.'
						                		'.$descuento.'
						                		'.$pago.'
					                		</div>
					                		<div class="total_reserva">
						                		<div class="item_desglose">
						                			<div>TOTAL</div>
						                			<span class="total_desglose">$'.number_format( $reserva["desglose"]["total"]+0, 2, ',', '.').'</span>
						                		</div>
					                		</div>
					                		<div class="ver_reserva_botones">
												<div style="padding-bottom:10px">'.$ayuda.'</div>
						                		'.$botones.'
					                		</div>
					                	</div>
					                </div>';
			                	}
					                
			                }

			        	}else{
			        		$table.='<div class="info-detalle">Sin datos para mostrar</div>';
			        	}
		                $table.='</div>';
		        	}
		        }

		        foreach($args as $reservas){
		        	if( isset($reservas['solicitudes']) > 0 ){
		        		$table.='<h1 class="titulo titulo_pequenio">'.$reservas['titulo'].'</h1><div class="vlz_tabla_box">';
			        	if( count($reservas['solicitudes']) > 0 ){
			        			 
		                	foreach ($reservas['solicitudes'] as $reserva) {

			                	$cancelar = '';
			                	if( isset($reserva["acciones"]["cancelar"]) ){
			                		//$cancelar = '<a data-accion="'.get_home_url().'/wp-content/plugins/kmimos/'.$reserva["acciones"]["cancelar"].'" class="vlz_accion vlz_cancelar cancelar"> <i class="fa fa-trash-o" aria-hidden="true"></i></a>';
			                	}

			                	$botones = construir_botones($reserva["acciones"]);

			                	$title_registro = "Cuidador seleccionado";

		                		$informacion = "
		                			<div class='info_solicitud'>
		                				<div class='info_titulo'>Importante</div>
		                				<ul>
		                					<li>
		                						<span>Dentro de las siguientes 12 horas recibir&aacute; una llamada o correo electr&oacute;nico por parte del Cuidador y/o de un asesor Kmimos para confirmar tu cita o brindarte soporte con este proceso.</span>
		                					</li>
		                					<li>
		                						<span>Tambi&eacute;n podr&aacute;s contactar al cuidador a partir de este momento, a los tel&eacute;fonos y/o correos mostrados arriba para acelerar el proceso si as&iacute; lo deseas.</span>
		                					</li>
		                					<li>
		                						<span>Para cualquier duda y/o comentario puedes contactar al staff Kmimos:</span>
		                					</li>
		                				</ul>
		                				<div class='datos_de_contacto'>
		                					<ul>
			                					<li>
			                						<span><img src='".getTema()."/images/new/icon/km-redes/icon-wsp.svg' style='' /></span> +52 1 (33) 1261 41 86
			                					</li>
			                					<li>
			                						<span><img src='".getTema()."/images/new/icon/km-redes/icon-cel.svg' style='' /></span>  01 (55) 8526 1162
			                					</li>
			                					<li>
			                						<span><img src='".getTema()."/images/new/icon/km-redes/icon-mail.svg' style='height: 13px;' /></span> contactomex@kmimos.la
			                					</li>
			                				</ul>
		                				</div>
		                			</div>
		                		";
			                	if( $reserva["detalle"]["quien_soy"] == "DATOS DEL CLIENTE" ){
			                		$title_registro = "Cliente";
			                		$informacion = "";
			                	}

				                $table.='
				                <div class="vlz_tabla">
				                	<div class="vlz_img">
				                		<span style="background-image: url('.$reserva["foto"].');"></span>
				                	</div>
				                	<div class="vlz_tabla_superior">
					                	<div class="vlz_tabla_cuidador vlz_celda">
					                		<span>'.$title_registro.'</span>
					                		<div>'.$reserva["servicio"].'</div>
					                	</div>
					                	<div class="vlz_tabla_cuidador vlz_celda">
					                		<span>Fecha</span>
					                		<div style="text-transform: lowercase;" >'.$reserva["detalle"]["desde"].' > '.$reserva["detalle"]["hasta"].'</div>
					                	</div>
					                	<div class="vlz_tabla_cuidador vlz_botones vlz_celda boton_interno">
					                		'.$cancelar.'
					                		<a class="ver_reserva_init">Ver Solicitud</a>
					                	</div>
					                	<div class="vlz_tabla_cuidador vlz_cerrar">
					                		<span>Solicitud</span>
					                		<div>'.$reserva["id"].'</div>
					                	</div>
				                	</div>
			                		<i class="fa fa-times-circle ver_reserva_init_closet" aria-hidden="true"></i>
				                	<div class="vlz_tabla_cuidador vlz_botones vlz_celda boton_fuera">
				                		<a class="ver_reserva_init_fuera">Ver Solicitud</a>
				                	</div>
				                	<div class="vlz_tabla_inferior">

				                		<div class="desglose_reserva desglose_sin_borde">
					                		<div class="desglose_columna">
					                			<div class="desglose_titulo">'.$reserva["detalle"]["quien_soy"].'</diV>
						                		<div class="item_desglose">
						                			<div>Nombre: </div>
						                			<span>'.$reserva["servicio"].'</span>
						                		</div>
						                		<div class="item_desglose">
						                			<div>Tel&eacute;fono: </div>
						                			<span>'.$reserva["detalle"]["telefono"].'</span>
						                		</div>
						                		<div class="item_desglose">
						                			<div>Correo: </div>
						                			<span>'.$reserva["detalle"]["correo"].'</span>
						                		</div>
					                		</div>
					                		<div class="desglose_columna">
					                			<div class="desglose_titulo">DATOS DE LA REUNI&Oacute;N</diV>
						                		<div class="item_desglose">
						                			<div>Fecha: </div>
						                			<span>'.$reserva["inicio"].'</span>
						                		</div>
						                		<div class="item_desglose">
						                			<div>Hora: </div>
						                			<span>'.$reserva["fin"].'</span>
						                		</div>
						                		<div class="item_desglose">
						                			<div>Lugar: </div>
						                			<span>'.$reserva["detalle"]["donde"].'</span>
						                		</div>
					                		</div>
				                		</div>

				                		'.$informacion.'

				                		<div class="ver_reserva_botones">
					                		'.$botones.'
				                		</div>
				                	</div>
				                </div>';
			                }
					            
			        	}else{
			        		$table.='<div class="info-detalle">Sin datos para mostrar</div>';
			        	}
		                $table.='</div>';
		        	}
		        }
		    }

	        foreach($args as $tipo_item => $reservas){
	        	if( is_array($reservas['facturas']) && count($reservas['facturas']) > 0 ){


    				$cliente_title = 'Cliente';
	        		if ($tipo_item == 'cuidador') {
        				$cliente_title = 'Emisor';
	        		}

	        		$table.='<h1 class="titulo titulo_pequenio">'.$reservas['titulo'].'</h1><div class="vlz_tabla_box">';
		                foreach ($reservas['facturas'] as $reserva) {
			        		
			        		$infoExtra = '';
							if ($tipo_item != 'cuidador') {
				        		$infoExtra = '
					        		<div class="item_desglose vlz_bold">
			                			<div>Servicio</div>
			                			<span>'.$reserva["servicio"].'</span>
			                		</div>
			                	';
			                }

		                	$botones = construir_botones($reserva["acciones"]);		                	 

		                	$table .= '
		                	<div class="vlz_tabla" data-list="'.$tipo_item.'" data-reserva="'.$reserva['archivo_name'].'" data-mes="'.$reserva['fecha_mes'].'" data-anio="'.$reserva['fecha_anio'].'">
			                	<div class="vlz_img">
			                		<span style="background-image: url('.$reserva["foto"].');"></span>
			                	</div>
			                	<div class="vlz_tabla_superior">
				                	<div class="vlz_tabla_cuidador vlz_celda">
				                		<span>'.$cliente_title.'</span>
				                		<div>'.$reserva["cliente"].'</div>
				                	</div>
				                	<div class="vlz_tabla_cuidador vlz_celda">
				                		<span>Serie y Folio</span>
				                		<div style="text-transform: uppercase;" >'.$reserva["serie"].'-'.$reserva["reserva_id"].'</div>
				                	</div>
				                	<div class="vlz_tabla_cuidador vlz_botones vlz_celda boton_interno">
				                		<a class="ver_reserva_init boton boton_border_gris"><i class="fa fa-eye"></i> Ver</a>
				                		'.$botones.'
				                	</div>
				                	<div class="vlz_tabla_cuidador vlz_cerrar">
				                		<span>Nro. de Referencia</span>
				                		<div>'.$reserva["numeroReferencia"].'</div>
				                	</div>
			                	</div>
		                		<i class="fa fa-times-circle ver_reserva_init_closet" aria-hidden="true"></i>
			                	<div class="vlz_tabla_cuidador vlz_botones vlz_celda boton_fuera">
			                		<a class="ver_reserva_init_fuera"><i class="fa fa-eye"></i> Ver</a>
			                	</div>
			                	<div class="vlz_tabla_inferior">
			                		
			                		<div class="desglose_reserva">
				                		<div class="item_desglose vlz_bold vlz_solo_movil">
				                			<div>Estado</div>
				                			<span>'.$reserva["estado"].'</span>
				                		</div>
				                		<div class="item_desglose vlz_bold_title">
				                			<div>Estado</div>
				                			<span>'.$reserva["estado"].'</span>
				                		</div>
				                		<div class="item_desglose vlz_bold">
				                			<div>Fecha de Creaci&oacute;n</div>
				                			<span>'.$reserva["fecha_creacion"].'</span>
				                		</div>
				                		'.$infoExtra.'
			                		</div>
			                		<div class="total_reserva">
				                		<div class="item_desglose">
				                			<div>TOTAL</div>
				                			<span class="total_desglose">$'.number_format( $reserva["total"]+0, 2, ',', '.').'</span>
				                		</div>
			                		</div>
			                		<div class="ver_reserva_botones">
				                		'.$botones.'
			                		</div>
			                	</div>
			                </div>';
	                		 
		                }

	                $table.='</div>';
	        	}
	        }


	        return $table;
	    }
	}
?>