<?php
include_once('../wp-content/themes/kmimos/lib/openpay/Openpay.php');

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
	$query .= "UPDATE cuidadores_pagos SET estatus = '{$pay->status}' WHERE openpay_id = '{$pay->id}'; <br>";
}

echo "<pre>{$query}</pre>";

