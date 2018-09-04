<?php
	$raiz = dirname(__DIR__);
	include_once('../wp-content/themes/kmimos/lib/openpay/Openpay.php');
    include_once($raiz."/vlz_config.php");

    $tema = $raiz . '/wp-content/themes/kmimos';
    include_once($tema."/procesos/funciones/db.php");
    include_once($tema."/procesos/funciones/generales.php");
     
    $db = new db( new mysqli($host, $user, $pass, $db) );


// Cambiar credenciales -----------------------------------------
$openpay = Openpay::getInstance('mbkjg8ctidvv84gb8gan', 'sk_883157978fc44604996f264016e6fcb7');
// --------------------------------------------------------------

$findDataRequest = array(
    'creation[gte]' => '2013-01-01',
    'creation[lte]' => '2019-12-31',
    'offset' => 0,
    'limit' => 1000
);

$payoutList = $openpay->payouts->getList($findDataRequest);

$query = '';
foreach ($payoutList as $pay) {
	$query .= "UPDATE cuidadores_pagos SET estatus = '{$pay->status}' WHERE openpay_id = '{$pay->id}';";
}

$db->multi_query( $query );
echo "<pre>{$query}</pre>";

