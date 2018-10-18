<?php

	require_once(dirname(dirname(dirname(dirname(__DIR__)))).'/lib/pagos/pagos_cuidador.php');

    extract( $_POST );

    $result = $pagos->db->query( 
    	"UPDATE cuidadores SET pago_periodo = '{$periodo}' WHERE user_id={$ID}" 
    );
