<?php

require_once( dirname(__DIR__).'/class/general.php' );
require_once( dirname(__DIR__).'/class/cuidador.php' );

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

	$g = new general();
	$c = new cuidador();

	// Datos para mostrar
	$data = [];
	$suma_campana = 0;


	// html Opciones del Menu - plataformas
	$menu = $c->get_html_menu_plataformas();


	// Cargar datos de la plataforma seleccionada
	$sucursal = 'global';
	$datos_by_sucursal = [];
	$_action[] = $sucursal;
	if( isset($_POST['sucursal']) ){
		$_action = explode('.', $_POST['sucursal']);
	}




/*

	// Plataformas
	$plataformas = $g->get_plataforma();

	// Cargar datos de la plataforma seleccionada
	$sucursal = 'global';
	$_action = (isset($_POST['sucursal']))? explode('.', $_POST['sucursal']) : $sucursal ;

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

			try{
 

				$temp = $c->get_datos( $desde, $hasta, $plataforma['name'] );
				
				$month = $c->by_month( $temp['datos'], $plataforma['name'] );

				$suma_campana = $temp['total_campanas'];
				$data = $c->merge_branch( $month, $data, $suma_campana );

			}catch(Exception $e){
				$error[] = $plataforma['descripcion'];
			}
		}
	}

	$data = $c->procesar( $data, $desde, $hasta );
 
	// Meses en letras
	$meses = $c->getMeses();


// ******************************************
// Construir datos para la table y graficos
// ******************************************

if( !empty($data) ){

	$error = 0;

	// Rows: orden y descripcion de la tabla
	$tbl_body['total']  = "1, '<strong>Total Cuidadores certificados</strong>'";
	$tbl_body['nuevos'] = "2, 'Nuevos Cuidadores certificados'";
	$tbl_body['costos_por_campana'] = "3, '<strong>Costo por cuidador (CAC)</strong>'";
	$tbl_body['costo']  = "4, 'Costo por cuidador (CAC) - USD'";

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

		$tbl_body['total']  .= ", '".number_format($data[$value]['total'],0,',','.')."'";
		$tbl_body['nuevos'] .= ", '".number_format($data[$value]['nuevos'],0,',','.')."'";
		$tbl_body['costos_por_campana'] .= ", '".number_format($data[$value]['costos_por_campana'],2,',','.')."'";
		$tbl_body['costo']  .= ", '$ ".number_format($data[$value]['costo'],2,',','.')."'";

	}

}else{
	$error = 1;
}
*/