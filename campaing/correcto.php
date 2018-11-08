<?php
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
        $response = "<pre>";
        $response .= "Your user is successfully authenticated. Here are the details you need:<br/><br/>";
        $response .= "access token: ".$access_token."<br/>";
        $response .= "refresh token: ".$refresh_token."<br/>";
        $response .= "expires in: ".$expires_in."<br/>";
        $response .= "<br/><br/>";
        $auth = array(
          'access_token' => $access_token,
          'refresh_token' => $refresh_token
        );
        $cs = new CS_REST_General($auth);
        $clients = $cs->get_clients()->response;
        $response .= "We've made an API call too. Here are your clients:<br/><br/>";
        $response .= var_export($clients, true);
        $response .= "</pre>";
    } else {
        $response = 'An error occurred:<br/>';
        $response .= $result->response->error.': '.$result->response->error_description."<br/>";
    }
    echo $response;
?>