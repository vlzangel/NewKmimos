<?php
	if(!function_exists('construir_botones')){
	    function construir_botones($botones){
	    	$respuesta = "";
	    	foreach ($botones as $boton => $accion) {
	    		switch ($boton) {
	    			case 'ver':
	    				$respuesta .= '<a href="ver/'.$accion.'" class="vlz_btn_ver"> <i class="fa fa-info" aria-hidden="true"></i> Ver</a>';
    				break;
	    			case 'confirmar':
	    				$respuesta .= '<a href="confirmar/'.$accion.'" class="vlz_btn_confirmar"> <i class="fa fa-check" aria-hidden="true"></i> Confirmar </a>';
    				break;
	    			case 'cancelar':
	    				$respuesta .= '<a href="cancelar/'.$accion.'" class="cancelar"> <i class="fa fa-trash-o" aria-hidden="true"></i> Cancelar</a>';
    				break;
	    			case 'modificar':
	    				$respuesta .= '<a href="modificar/'.$accion.'" class="vlz_btn_modificar"> <i class="fa fa-pencil" aria-hidden="true"></i> Modificar </a>';
    				break;
	    			case 'pdf':
	    				$respuesta .= '<a href="'.$accion.'" class="pdf"> <i class="fa fa-download" aria-hidden="true"></i> ¿Com&oacute; pagar? </a>';
    				break;
	    			case 'valorar':
	    				$respuesta .= '<a href="valorar/'.$accion.'" class="pdf"> <i class="fa fa-thumbs-o-up" aria-hidden="true"></i> Valorar </a>';
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
	        foreach($args as $reservas){
	        	if( count($reservas['reservas']) > 0 ){
	                $table.='<h1 class="titulo">'.$reservas['titulo'].'</h1>';
	                foreach ($reservas['reservas'] as $reserva) {

	                	$cancelar = '';
	                	if( isset($reserva["acciones"]["cancelar"]) ){
	                		$cancelar = '<a href="cancelar/'.$reserva["acciones"]["cancelar"].'" class="cancelar"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';
	                	}
	                	$botones = construir_botones($reserva["acciones"]);

	                	$vlz_tabla_inferior = "";
	                	$descuento = "";
	                	if( $reserva["desglose"]["descuento"]+0 > 0){
	                		$descuento = '
	                			<div class="item_desglose">
		                			<div>Descuento</div>
		                			<span>$'.$reserva["desglose"]["descuento"].'</span>
		                		</div>
	                		';
	                	}
	                	if( $reserva["desglose"]["enable"] == "yes" ){
	                		$vlz_tabla_inferior = '
		                		<div class="desglose_reserva">
			                		<div class="item_desglose vlz_bold">
			                			<div>MÉTODO DE PAGO</div>
			                			<span>DEPÓSITO DEL 17%</span>
			                		</div>

			                		<div class="item_desglose vlz_bold">
			                			<div style="color: #6b1c9b;" >Monto Restante a Pagar en EFECTIVO al cuidador</div>
			                			<span style="color: #6b1c9b;">$'.number_format( ($reserva["desglose"]["remaining"]-$reserva["desglose"]["descuento"]), 2, ',', '.').'</span>
			                		</div>

			                		'.$descuento.'

			                		<div class="item_desglose">
			                			<div>Pagó</div>
			                			<span>$'.number_format( $reserva["desglose"]["deposit"], 2, ',', '.').'</span>
			                		</div>
		                		</div>

		                		<div class="total_reserva">

			                		<div class="item_desglose">
			                			<div>TOTAL</div>
			                			<span>$'.number_format( $reserva["desglose"]["total"], 2, ',', '.').'</span>
			                		</div>

		                		</div>
		                	';
	                	}else{
	                		$vlz_tabla_inferior = '
		                		<div class="desglose_reserva">
			                		<div class="item_desglose vlz_bold">
			                			<div>MÉTODO DE PAGO</div>
			                			<span>PAGO TOTAL</span>
			                		</div>

			                		'.$descuento.'

			                		<div class="item_desglose">
			                			<div>Pagó</div>
			                			<span>$'.number_format( ($reserva["desglose"]["total"]-$reserva["desglose"]["descuento"]), 2, ',', '.') .'</span>
			                		</div>
		                		</div>

		                		<div class="total_reserva">

			                		<div class="item_desglose">
			                			<div>TOTAL</div>
			                			<span>$'.number_format( $reserva["desglose"]["total"], 2, ',', '.') .'</span>
			                		</div>

		                		</div>
		                	';
	                	}

		                $table.='
		                <div class="vlz_tabla">

		                	<div class="vlz_tabla_superior">
			                	<div class="vlz_tabla_img">
			                		<span style="background-image: url('.$avatar_img.');"></span>
			                	</div>
			                	<div class="vlz_tabla_cuidador vlz_celda">
			                		<span>Servicio</span>
			                		<div>'.$reserva["servicio"].'</div>
			                	</div>
			                	<div class="vlz_tabla_cuidador vlz_celda">
			                		<span>Fecha</span>
			                		<div>'.$reserva["inicio"].' <b> > </b> '.$reserva["fin"].'</div>
			                	</div>
			                	<div class="vlz_tabla_cuidador vlz_botones vlz_celda boton_interno">
			                		'.$cancelar.'
			                		<a class="ver_reserva_init">Ver Reserva</a>
			                	</div>
			                	<div class="vlz_tabla_cuidador vlz_cerrar">
			                		<span>Tu número de reserva</span>
			                		<div>'.$reserva["id"].'</div>
			                	</div>
		                	</div>
	                		<i class="fa fa-times ver_reserva_init_closet" aria-hidden="true"></i>

		                	<div class="vlz_tabla_cuidador vlz_botones vlz_celda boton_fuera">
		                		<a class="ver_reserva_init_fuera">Ver Reserva</a>
		                	</div>

		                	<div class="vlz_tabla_inferior">

		                		'.$vlz_tabla_inferior.'

		                		<div class="ver_reserva_botones">

			                		'.$botones.'

		                		</div>

		                	</div>


		                </div>';
	                }
	        	}
	        }
	        return $table;
	    }
	}
?>