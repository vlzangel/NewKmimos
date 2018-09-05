<?php
	include("openpay2/Openpay.php");
	include("../wp-content/themes/kmimos/procesos/funciones/config.php");

	$openpay = Openpay::getInstance($MERCHANT_ID, $OPENPAY_KEY_SECRET);
	Openpay::setProductionMode( true );

	$webhook = array(
	    'url' => 'http://kmimosmx.sytes.net/QA2/openpay/RWH.php',
	    'event_types' => array(
			'charge.refunded',
			'charge.failed',
			'charge.cancelled',
			'charge.created',
			'charge.succeeded',
			'charge.rescored.to.decline',
			'subscription.charge.failed',
			'payout.created',
			'payout.succeeded',
			'payout.failed',
			'transfer.succeeded',
			'fee.succeeded',
			'fee.refund.succeeded',
			'spei.received',
			'chargeback.created',
			'chargeback.rejected',
			'chargeback.accepted',
			'order.created',
			'order.activated',
			'order.payment.received',
			'order.completed',
			'order.expired',
			'order.cancelled',
			'order.payment.cancelled'
	    )
	);

	$webhook = $openpay->webhooks->create($webhook);

	echo "<pre>";
		print_r( 
			array(
				"id" => $webhook->id,
				"url" => $webhook->url,
				"user" => $webhook->user,
				"event_types" => $webhook->event_types,
				"status" => $webhook->status
			)
		);
		print_r( $webhook );
	echo "</pre>";
?>
























