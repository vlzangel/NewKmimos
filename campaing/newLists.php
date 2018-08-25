<?php
	
	extract($_POST);

	include dirname(__DIR__)."/wp-load.php";
	global $wpdb;
	$credenciales = $wpdb->get_var("SELECT data FROM campaing WHERE id = 1");

	require_once __DIR__.'/campaing/csrest_campaigns.php';

	$credenciales = json_decode($credenciales);
	$data = array(
		'auth' => (array) $credenciales->auth,
		'lists' => (array) $credenciales->lists
	);

	if( isset($_POST["list"]) && isset($_POST["listid"]) ){
		$data['lists'][ $_POST["list"] ] = $_POST["listid"];

		$wpdb->query("UPDATE campaing SET data = '".json_encode( $data )."' WHERE id = 1");
	}

	echo "<pre>";
		print_r($data);
	echo "</pre>";

	/*
	{"auth":{"access_token":"AUTr8b+NQpBPowMkSjArXeExNQ==","refresh_token":"AXsKGf+zs59Nq3zXbsnVx2ExNQ=="},"lists":{"petco_popup":"4c6ef95e717057c865845737d91be72d","newsletter_home":"aabaca4317656fa19cf4c36e6bbf3597"}}
	*/
?>
<form action="?" method="POST">
	<div> Nombre Lista (slug) </div>
	<div> <input type="text" name="list"> </div>

	<div> Id Lista (En campaing ) </div>
	<div> <input type="text" name="listid"> </div>
	<div> <input type="submit" value="Actualizar"> </div>
</form>