<?php
	$raiz = dirname(dirname(dirname(dirname(dirname(__DIR__)))));
	include_once($raiz."/vlz_config.php");
	include_once("../funciones/db.php");

	extract($_POST);

	$db = new db( new mysqli($host, $user, $pass, $db) );

	// TODO: Ajustar para que se puedan reaplicar todos los cupones

	if( isset($cupones) ){
		if( ya_aplicado($cupon, $cupones) ){
			echo json_encode(array(
				"error" => "El cupón ya fue aplicado"
			));
			exit;
		}
	}

	$xcupon = $db->get_row("SELECT * FROM wp_posts WHERE post_title = '{$cupon}'");

	if( $xcupon == false ){
		echo json_encode(array(
			"error" => "Cupón Invalido"
		));
		exit;
	}

	$xmetas = $db->get_results("SELECT * FROM wp_postmeta WHERE post_id = '{$xcupon->ID}'");

	$metas = array();
	foreach ($xmetas as $value) {
		$metas[ $value->meta_key ] = $value->meta_value;
	}

	if( $metas["expiry_date"] != "" ){
	$hoy = time();
		$expiracion = (strtotime($metas["expiry_date"]))+86399;
		if( $hoy > $expiracion ){
			echo json_encode(array(
				"error" => "El cupón ya expiro"
			));
			exit;
		}
	}

	$se_uso = $db->get_var("SELECT * FROM wp_postmeta WHERE post_id = {$xcupon->ID} AND meta_key = 'user_id' AND meta_value = {$cliente}");

	if( $se_uso ){
		echo json_encode(array(
			"error" => "El cupón ya fue usado"
		));
		exit;
	}

	$descuento = 0;
	switch ( $metas["discount_type"] ) {
		case "percent":
			$descuento = $total*($metas["coupon_amount"]/100);
		break;
		case "fixed_cart":
			$descuento = $metas["coupon_amount"];
		break;
	}

	$cupones[] = array(
		$cupon,
		$descuento
	);

	echo json_encode(array(
		"cupon" => $xcupon,
		"cupones" => $cupones,
		"metas" => $metas,
		"post"  => $_POST
	));

	function ya_aplicado($cupon, $cupones){
		foreach ($cupones as $key => $valor) {
			if( $cupon == $valor[0] ){
				return true;
			}
		}
		return false;
	}
?>