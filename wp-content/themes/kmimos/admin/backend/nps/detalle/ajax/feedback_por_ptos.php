<?php
    session_start();
    date_default_timezone_set('America/Mexico_City');

    include_once(dirname(__DIR__).'/lib/nps.php');

    $score = $nps->feedback_group_ptos( $_POST['id'] );

    $t=[
    	// Detractors
    	"1" =>['date'=>1, 'total'=>0, 'color'=>'#d9534f' ],
	    "2" =>['date'=>2, 'total'=>0, 'color'=>'#d9534f' ],
	    "3" =>['date'=>3, 'total'=>0, 'color'=>'#d9534f' ],
	    "4" =>['date'=>4, 'total'=>0, 'color'=>'#d9534f' ],
	    "5" =>['date'=>5, 'total'=>0, 'color'=>'#d9534f' ],
	    "6" =>['date'=>6, 'total'=>0, 'color'=>'#d9534f' ],

    	// Pasivos
	    "7" =>['date'=>7, 'total'=>0, 'color'=>'#f0ad4e' ],
	    "8" =>['date'=>8, 'total'=>0, 'color'=>'#f0ad4e' ],

    	// Promoters
	    "9" =>['date'=>9, 'total'=>0, 'color'=>'#5cb85c' ],
	    "10"=>['date'=>10, 'total'=>0, 'color'=>'#5cb85c' ]
	];
    foreach ($score as $item) {
        if( $item->puntos > 0 ){
    		$t[ $item->puntos ]['total'] =$item->cant;
        }
    }
    ksort($t);

	$data = [];
    foreach ($t as $r) {
		$data[] = $r;
    }

    echo json_encode($data, JSON_UNESCAPED_UNICODE);