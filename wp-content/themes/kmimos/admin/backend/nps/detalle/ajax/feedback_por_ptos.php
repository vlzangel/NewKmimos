<?php
    session_start();
    date_default_timezone_set('America/Mexico_City');

    include_once(dirname(__DIR__).'/lib/nps.php');

    $score = $nps->feedback_group_ptos( $_POST['id'] );

    $t=[
    	"1" =>['date'=>1, 'total'=>0],
	    "2" =>['date'=>2, 'total'=>0],
	    "3" =>['date'=>3, 'total'=>0],
	    "4" =>['date'=>4, 'total'=>0],
	    "5" =>['date'=>5, 'total'=>0],
	    "6" =>['date'=>6, 'total'=>0],
	    "7" =>['date'=>7, 'total'=>0],
	    "8" =>['date'=>8, 'total'=>0],
	    "9" =>['date'=>9, 'total'=>0],
	    "10"=>['date'=>10, 'total'=>0]
	];
    foreach ($score as $item) {
		$t[ $item->puntos ]['total'] =$item->cant;
    }
    ksort($t);

	$data = [];
    foreach ($t as $r) {
		$data[] = $r;
    }

    echo json_encode($data, JSON_UNESCAPED_UNICODE);