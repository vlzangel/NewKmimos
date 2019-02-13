<?php

require dirname(__DIR__) . '/paypal/lib/vendor/autoload.php';

//1. Import the PayPal SDK client that was created in `Set up the Server SDK`.
use Sample\PayPalClient;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

class CreateOrder
{

  // 2. Set up your server to receive a call from the client
  /**
   *This is the sample function to create an order. It uses the
   *JSON body returned by buildRequestBody() to create a new order.
   */
  public static function create($debug=false)
  {
    $request = new OrdersCreateRequest();
    $request->prefer('return=representation');
    $request->body = self::buildRequestBody();
   // 3. Call PayPal to set up a transaction
    $client = PayPalClient::client();
    $response = $client->execute($request);
    if ($debug)
    {
      echo json_encode($response->result, JSON_PRETTY_PRINT);
    }

    // 4. Return a successful response to the client.
    return $response;
  }

  /**
     * Setting up the JSON request body for creating the order with minimum request body. The intent in the
     * request body should be "AUTHORIZE" for authorize intent flow.
     *
     */
    private static function buildRequestBody(){
         return array(
            'intent' => 'CAPTURE',
            'application_context' =>
                array(
                    'return_url' => 'https://mx.kmimos.la/paypal/return.php',
                    'cancel_url' => 'https://mx.kmimos.la/paypal/cancel.php'
                ),
            'purchase_units' =>
                array(
                    0 =>
                        array(
                            'description' => 'Sporting Goods',
                            'custom_id' => 'CUST-HighFashions',
                            'soft_descriptor' => 'HighFashions',
                            'amount' =>
                                array(
                                    'currency_code' => 'USD',
                                    'value' => '6.00',
                                    'reference_id' => 'PUHF',
                                    'breakdown' =>
                                        array(
                                          'item_total' =>
                                            array(
                                              'currency_code' => 'USD',
                                              'value' => '6.00',
                                            ),
                                          'shipping' =>
                                            array(
                                              'currency_code' => 'USD',
                                              'value' => '0.00',
                                            ),
                                          'handling' =>
                                            array(
                                              'currency_code' => 'USD',
                                              'value' => '0.00',
                                            ),
                                          'tax_total' =>
                                            array(
                                              'currency_code' => 'USD',
                                              'value' => '0.00',
                                            ),
                                          'shipping_discount' =>
                                            array(
                                              'currency_code' => 'USD',
                                              'value' => '0.00',
                                            ),
                                        ),
                                ),
                            'items' =>
                                array(
                                  0 =>
                                    array(
                                      'name' => 'Hospedaje - Pedro P',
                                      'description' => 'Hospedaje',
                                      'sku' => '190921',
                                      'unit_amount' =>
                                        array(
                                          'currency_code' => 'USD',
                                          'value' => '6.00',
                                        ),
                                      'quantity' => '1',
                                      'category' => 'PHYSICAL_GOODS',
                                    ),
                                ),
                            'shipping' =>
                                array(
                                  'method' => 'United States Postal Service',
                                  'address' =>
                                    array(
                                      'address_line_1' => '123 Townsend St',
                                      'address_line_2' => 'Floor 6',
                                      'admin_area_2' => 'San Francisco',
                                      'admin_area_1' => 'CA',
                                      'postal_code' => '94107',
                                      'country_code' => 'US',
                                    ),
                                ),
                        )
                )
        );
    }
}

CreateOrder::create(true);
