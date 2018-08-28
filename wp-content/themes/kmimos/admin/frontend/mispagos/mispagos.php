<?php

	global $wpdb;

	$cuidador_id = get_current_user_id();

	$sql_cuidador = "SELECT * FROM cuidadores WHERE user_id = {$cuidador_id} ";
	$cuidador = $wpdb->get_results($sql_cuidador);
	
	$sql_pagos = "SELECT * FROM pagos WHERE user_id = {$cuidador_id} ";
	$pagos = $wpdb->get_results($sql_pagos);

	if( count($pagos) > 0 ){

		$pagos_array = array(
			"pagos" => array(
				"titulo" => 'Pagos Recibidos',
				"pagos" => array()
			),
		);

		foreach ($pagos as $pago) {
			
			$mes = date('m', strtotime($pago->fechaGeneracion));
			$anio = date('Y', strtotime($pago->fechaGeneracion));

			$listado_fecha['mes'][$mes] = '<option value="'.$mes.'">'.$mes.'</option>';
			$listado_fecha['anio'][$anio] = '<option value="'.$anio.'">'.$anio.'</option>';

			$archivos['comision'][] = $pago->reserva_id.'_'.$pago->numeroReferencia;

			$pagos_array["pagos"][] = array(
				'id' => $factura->id, 
				'fecha_mes' => $mes,
				'fecha_anio' => $anio,
				'fecha_creacion' => $factura->fechaGeneracion, 
			);

		}
		

		$pagos = construir_listado($pagos_array);
		   
		//BUILD TABLE
		$CONTENIDO .= '

			<h1 style="margin: 0px; padding: 0px;">Mis Pagos</h1><hr style="margin: 5px 0px 10px;">

			<div class="contenedor-botones '.$ocultar.'">
				<label>Filtrar por: </label>
				<select data-action="filtro" name="filtro_mes" >
					<option value="0">Mes</option>
					<option value="01">Enero</option>
					<option value="02">Febrero</option>
					<option value="03">Marzo</option>
					<option value="04">Abril</option>
					<option value="05">Mayo</option>
					<option value="06">Junio</option>
					<option value="07">Julio</option>
					<option value="08">Agosto</option>
					<option value="09">Septiembre</option>
					<option value="10">Octubre</option>
					<option value="11">Noviembre</option>
					<option value="12">Diciembre</option>
				</select>
				<select data-action="filtro" name="filtro_anio" >
					<option value="">Año</option>
				</select>
			</div>

			<div>

			  <h1 class="titulo titulo_pequenio">Sin datos para mostrar</h1>
			  '.$pagos.'

			</div>

		';
	}else{
		$CONTENIDO .= "<h1 style='line-height: normal;'>Usted aún no tiene pagos realizados.</h1><hr>";
	}

?>

