<?php
	$raiz = dirname(dirname(dirname(dirname(dirname(__DIR__)))));
	include_once($raiz."/vlz_config.php");
	include_once("../funciones/db.php");
	include_once("../funciones/generales.php");

	if( !isset($_SESSION)){ session_start(); }

	extract($_POST);

	$db = new db( new mysqli($host, $user, $pass, $db) );

	/* Get Data */

		$xcupon = $db->get_row("SELECT * FROM wp_posts WHERE post_title = '{$cupon}'");

		$xmetas = $db->get_results("SELECT * FROM wp_postmeta WHERE post_id = '{$xcupon->ID}'");
		$metas = array();
		foreach ($xmetas as $value) {
			$metas[ $value->meta_key ] = $value->meta_value;
		}

		$se_uso = $db->get_var("SELECT * FROM wp_postmeta WHERE post_id = {$xcupon->ID} AND meta_key = 'user_id' AND meta_value = {$cliente}");

	// TODO: Ajustar para que se puedan reaplicar todos los cupones

	/* Validaciones */
		if( isset($cupones) ){
			if( ya_aplicado($cupon, $cupones) ){
				echo json_encode(array(
					"error" => "El cupón ya fue aplicado"
				));
				exit;
			}
		}

		if( $xcupon == false ){
			echo json_encode(array(
				"error" => "Cupón Invalido"
			));
			exit;
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

		if( $se_uso ){
			echo json_encode(array(
				"error" => "El cupón ya fue usado"
			));
			exit;
		}

	/* Calculo */
		$descuento = 0;
		switch ( $metas["discount_type"] ) {
			case "percent":
				$descuento = $total*($metas["coupon_amount"]/100);
			break;
			case "fixed_cart":
				$descuento = $metas["coupon_amount"];
			break;
		}

		if( isset($_SESSION['MR_'.$servicio] ) ){
			if( strpos( $cupon, "saldo" ) !== false ){
				$descuento += $_SESSION['MR_'.$servicio]['saldo_temporal'];
			}
		}

		$cupones[] = array(
			$cupon,
			$descuento
		);

	/* Retorno */
		echo json_encode(array(
			"cupon"   => $xcupon,
			"cupones" => $cupones,
			"metas"   => $metas,
			"post"    => $_POST
		));

?>