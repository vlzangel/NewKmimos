<?php

$raiz = dirname(__DIR__);
include_once($raiz."/vlz_config.php");

$tema = $raiz . '/wp-content/themes/kmimos';
include_once($tema."/procesos/funciones/db.php");
include_once($tema."/procesos/funciones/generales.php");
include_once($tema."/procesos/funciones/config.php");
include_once($tema.'/lib/openpay2/Openpay.php');

ini_set('display_errors', 'On');
error_reporting(E_ALL);

// credenciales testing -----------------------------------------
// $openpay = Openpay::getInstance('mbkjg8ctidvv84gb8gan', 'sk_883157978fc44604996f264016e6fcb7');
// --------------------------------------------------------------


$openpay = Openpay::getInstance($MERCHANT_ID, $OPENPAY_KEY_SECRET);
Openpay::setProductionMode( ($OPENPAY_PRUEBAS == 0) );
 
$db = new db( new mysqli($host, $user, $pass, $db) );


$hasta = date('Y-m-d');
$desde = date('Y-m-d', strtotime(date('Y-m-d') ." - 15 day"));

$findDataRequest = array(
    'creation[gte]' => $desde,
    'creation[lte]' => $hasta,
    'offset' => 0,
    'limit' => 1000
);

$payoutList = $openpay->payouts->getList($findDataRequest);

$query = '';
foreach ($payoutList as $pay) {
	$query .= "UPDATE cuidadores_pagos SET estatus = '{$pay->status}' WHERE openpay_id = '{$pay->id}';";
}

$db->multi_query( $query );
$query = str_replace("';", "'<br>", $query);
echo "<pre>{$query}</pre>";

