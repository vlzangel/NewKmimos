<?php
 
	include dirname(__DIR__)."/wp-load.php";
	global $wpdb;
	$credenciales = $wpdb->get_var("SELECT data FROM campaing WHERE id = 1");

	require_once __DIR__.'/campaing/csrest_lists.php';
 
	$credenciales = json_decode($credenciales);
	$data = array(
		'auth' => (array) $credenciales->auth,
		'lists' => (array) $credenciales->lists
	);
 
$wrap = new CS_REST_Lists('API Subscriber List ID', $data['auth']);

$result = $wrap->get();

echo "Result of GET /api/v3.1/lists/{ID}\n<br />";
if($result->was_successful()) {
    echo "Got list details\n<br /><pre>";
    var_dump($result->response);
} else {
    echo 'Failed with code '.$result->http_status_code."\n<br /><pre>";
    var_dump($result->response);
}
echo '</pre>';