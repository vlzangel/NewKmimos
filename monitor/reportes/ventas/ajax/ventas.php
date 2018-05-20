<?php

require_once( dirname(dirname(__DIR__)).'/class/procesar.php' );

// ******************************************
// Procesar datos
// ******************************************

	$hoy = date('Y-m-d');

	$desde = date('Y-m-d', strtotime( '-12 month', strtotime($hoy) ));
	if( isset($_POST['desde']) && !empty($_POST['desde']) ){
		$desde = $_POST['desde'];
	}

	$hasta = $hoy;
	if( isset($_POST['hasta']) && !empty($_POST['hasta']) ){
		$hasta = $_POST['hasta'];
	}

	$c = new procesar();

	// Datos para mostrar
	$data = [];

	// Plataformas
	$plataformas = $c->get_plataforma();

	// Cargar datos de la plataforma seleccionada
	$sucursal = 'global';
	$datos_by_sucursal = [];
	$_action = explode('.', $_POST['sucursal']);


	foreach ($plataformas as $plataforma) {
		$sts = 0;
		switch( $_action[0] ){
			case 'bygroup':
				if( $_action[1] == $plataforma['grupo'] ){
					$sts = 1;
					$sucursal = $plataforma['grupo'];
				}
				break;
			case 'byname':
				if( $_action[1] == $plataforma['name'] ){
					$sts = 1;
					$sucursal = $plataforma['descripcion'];
				}
				break;
			default: // global
				$sts = 1;
				break;
		}
		if( $sts == 1 ){

			// Datos
			// $datos = $c->getData( $desde, $hasta);
			try{
				$datos = $c->request( 
					$plataforma['dominio']."/monitor/services/getData.php", 
					['desde'=>$desde, 'hasta'=>$hasta] 
				);
				// Analizar datos
				if( !empty($datos) ){
					$data_sucursal = $c->porSucursal( $datos, $desde, $hasta );
				}
			}catch(Exception $e){
				$datos = [];
				$data_sucursal = [];
			}

			if( !empty( $data_sucursal ) ){
				$data = $data_sucursal;
				$datos_by_sucursal['activo'][ $plataforma['name'] ] = [ 
					'descripcion' => $plataforma['descripcion'],
					'data' => $data_sucursal,
				];
			}else{
				$datos_by_sucursal[ 'error' ][] = $plataforma['descripcion'];
			}
		}
	}

	// Meses en letras
	$meses = $c->getMeses();


// ******************************************
// Construir datos para la table y graficos
// ******************************************

if( !empty($data) ){

	$error = 0;

	// Rows: orden y descripcion de la tabla
	$tbl_body['noches_reservadas'] = "1, '<strong># Noches reservadas</strong>'";
	$tbl_body['noches_promedio'] = "2, 'Noches promedio'";
	$tbl_body['noches_recompradas'] = "3, '% Nights Repurchased'";
	$tbl_body['total_perros_hospedados'] = "4, 'Total perros hospedados'";
	$tbl_body['eventos_de_compra'] = "5, '<strong># Eventos de compra</strong>'";
	$tbl_body['clientes_nuevos'] = "6, '<strong># Clientes nuevos</strong>'";
	$tbl_body['clientes_wom'] = "7, '% Clientes - WOM'";
	$tbl_body['numero_clientes_que_recompraron'] = "8, '# Clientes que recompraron'";
	$tbl_body['porcentaje_clientes_que_recompraron'] = "9, '% Clientes que recompraron'";
	$tbl_body['precio_por_noche_pagada_promedio'] = "10, 'Precio por noche pagada Promedio'";
	$tbl_body['clientes'] = "11, '<strong># Clientes</strong>'";
	$tbl_body['numero_clientes_vs_mes_anterior'] = "12, '% Crecimiento número Clientes vs. Mes anterior'";
	$tbl_body['clientes_nuevos_vs_mes_anterior'] = "13, '% incremento de Clientes nuevos vs. Mes anterior'";

	$_meses = array_keys($data);
	$graficos_data = [];
	$tbl_header = '<th></th><th>Descripci&oacute;n</th>';
	foreach ($_meses as $key => $value) {

		$anio_corto = substr($value, 4, 2);
		$mes_corto = $meses[substr($value, 0, 2)-1];
		$anio_largo = substr($value, 2, 4);
		$mes = $mes_corto.$anio_largo;

		// Grafico
		$data[$value]['date'] = $mes_corto.$anio_corto;
		$graficos_data[] = $data[$value];

	 	// tabla
		$tbl_header .= "<th>".$mes."</th>";

		$tbl_body['noches_reservadas'] .= ",'".$data[$value]['noches_reservadas']."'";
		$tbl_body['noches_promedio'] .= ",'".$data[$value]['noches_promedio']."'";
		$tbl_body['noches_recompradas'] .= ",'".$data[$value]['noches_recompradas']."'";
		$tbl_body['total_perros_hospedados'] .= ",'".$data[$value]['total_perros_hospedados']."'";
		$tbl_body['eventos_de_compra'] .= ",'".$data[$value]['eventos_de_compra']."'";
		$tbl_body['clientes_nuevos'] .= ",'".$data[$value]['clientes_nuevos']."'";
		$tbl_body['clientes_wom'] .= ",'".$data[$value]['clientes_wom']."'";
		$tbl_body['numero_clientes_que_recompraron'] .= ",'".$data[$value]['numero_clientes_que_recompraron']."'";
		$tbl_body['porcentaje_clientes_que_recompraron'] .= ",'".$data[$value]['porcentaje_clientes_que_recompraron']."'";
		$tbl_body['precio_por_noche_pagada_promedio'] .= ",'".$data[$value]['precio_por_noche_pagada_promedio']."'";
		$tbl_body['clientes'] .= ",'".$data[$value]['clientes']."'";
		$tbl_body['numero_clientes_vs_mes_anterior'] .= ",'".$data[$value]['numero_clientes_vs_mes_anterior']."'";
		$tbl_body['clientes_nuevos_vs_mes_anterior'] .= ",'".$data[$value]['clientes_nuevos_vs_mes_anterior']."'";

	}

}else{
	$error = 1;
}
