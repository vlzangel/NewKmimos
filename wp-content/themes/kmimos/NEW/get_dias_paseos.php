<?php
	error_reporting( 0 );

	extract($_POST);

	$inicio = strtotime($inicio);
	$fin = strtotime($fin);

	

    $_dias = [
    	"domingo" => 0,
    	"lunes" => 1,
    	"martes" => 2,
    	"miercoles" => 3,
    	"jueves" => 4,
    	"viernes" => 5,
    	"sabado" => 6
    ];

    $dias_seleccionados = [];
    foreach ($dias as $key => $value) {
    	$dias_seleccionados[] = $_dias[$value];
    }

    $num_dias = 0;
    $cont = 0;

	for ($i=$inicio; $i <= $fin; $i+=86400) { 
		$cont++;
		$dia = date("w", $i);
		if( in_array($dia, $dias_seleccionados) ){
			$num_dias++;
		}
	}
	echo $num_dias;
?>