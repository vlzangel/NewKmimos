<?php
    require_once __DIR__.'/db.php';

	require_once __DIR__.'/campaing/csrest_campaigns.php';
	require_once __DIR__.'/campaing/csrest_general.php';

	$authorize_url = CS_REST_General::authorize_url(
        '114514',
        'https://www.kmimos.com.mx/campaing/correcto.php',
        'ViewReports,ManageLists,CreateCampaigns,ImportSubscribers,SendCampaigns,ViewSubscribersInReports,ManageTemplates,AdministerPersons,AdministerAccount,ViewTransactional,SendTransactional'
	);

    $config = $db->get_var("SELECT data FROM campaing WHERE id = 1");
    $config = json_decode($config);

	echo '<h2><a href="'.$authorize_url.'">Refrescar Token</a></h2>';
    echo '<b>Token Actual: </b> '.$config->auth->access_token;

?>