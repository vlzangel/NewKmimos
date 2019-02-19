<pre>
<?php 
	require dirname(__DIR__) . '/paypal/lib/vendor/autoload.php';

	use Sample\PayPalClient;
	use PayPalCheckoutSdk\Orders\OrdersGetRequest;
	use Sample\CaptureIntentExamples\CreateOrder;
	
	extract($_REQUEST);

	$client = PayPalClient::client();
    $response = $client->execute(new OrdersGetRequest($token));


    /**
     * Enable below line to print complete response as JSON.
     */
	print_r($_REQUEST); 
    // print json_encode($response->result);
    print "Status Code: {$response->statusCode}\n";
    print "Status: {$response->result->status}\n";
    print "Order ID: {$response->result->id}\n";
    // print "Intent: {$response->result->intent}\n";
    // print "Links:\n";
    // foreach($response->result->links as $link)
    // {
    //     print "\t{$link->rel}: {$link->href}\tCall Type: {$link->method}\n";
    // }

    // print "Gross Amount: {$response->result->purchase_units[0]->amount->currency_code} {$response->result->purchase_units[0]->amount->value}\n";

    // To toggle printing the whole response body comment/uncomment below line
    // echo json_encode($response->result, JSON_PRETTY_PRINT), "\n";

?>	
</pre>