<?php
    require_once __DIR__.'/db.php';

    require_once __DIR__.'/campaing/csrest_campaigns.php';
	require_once __DIR__.'/campaing/csrest_general.php';

	extract($_GET);
	if( !isset($code)){
		extract($_POST);
		if( !isset($code)){
			extract($_REQUEST);
		}
	}

    $result = CS_REST_General::exchange_token(
        "114514",
        "Gay0i0rL0Tk0y6jGE0QV00Dr00lACN0czTk30E0Rd0JnbFeHBbLp50yW00PVV00rk0oS0v0F1xv9F0Vs",
        "https://www.kmimos.com.mx/campaing/correcto.php",
        $code
    );
    
    if ($result->was_successful()) {

        $access_token = $result->response->access_token;
        $expires_in = $result->response->expires_in;
        $refresh_token = $result->response->refresh_token;

        $config = $db->get_var("SELECT data FROM campaing WHERE id = 1");
        $config = json_decode($config);

        $config->auth->access_token = $access_token;
        $config->auth->refresh_token = $refresh_token;

        $config = json_encode($config);

        $db->query("UPDATE campaing SET data = '{$config}' WHERE id = 1");

        /*
            {"auth":{"access_token":"AQgmhGYspZlAiNg76SCU1DUxNA==","refresh_token":"AfaQa1KkqZdGo9jIIG6ntioxNA==","expires":1209600},"lists":{"petco_popup":"4c6ef95e717057c865845737d91be72d","newsletter_home":"aabaca4317656fa19cf4c36e6bbf3597","newsletter_volaris":"fadded8b6c9bdff6e9a423b21381ca65","petco_registro":"765b83059cea97ba8d46b42624368c73","social_blue_auto":"c345b9c7af8fee459597d485db92559a","social_blue":"0409899d192bff86e74d2cc8473b60fb"}}
        */

        echo "<h2>Token Actualizado Correctamente!</h2>";

        echo "<b>Access_token</b>: $access_token";


    } else {
        $response = 'An error occurred:<br/>';
        $response .= $result->response->error.': '.$result->response->error_description."<br/>";
    }
    echo $response;
?>