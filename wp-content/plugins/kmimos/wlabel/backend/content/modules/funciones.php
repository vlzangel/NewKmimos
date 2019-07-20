<?php
	function loop($_data, $inits, $tipo){
		extract($inits);
		$data = [];
		$amount_day = 0; $amount_month = 0; $amount_year = 0; $amount_total = 0; $_28 = true;
	    $anio = date("Y", $day_init);
	    for($day = $day_init; $day <= $day_last; $day+=$day_more){
	        $anio_act = date("Y", $day);
	        if( $anio_act != $anio ){
	            $amount_day = 0; $amount_month = 0; $amount_year = 0;
	            $anio = $anio_act;
	        }
	        $print = true;
	        if( date('d/m/Y', $day) == "28/10/2018" ){ if($_28){ $_28 = false; }else{ $print = false; } }
	        if( $print ){
	        	$hoy = strtotime(date('m/d/Y', $day));
	            foreach($_data as $item){
	                switch ($tipo) {
	                	case 'leads':
			                $fecha = strtotime( date('m/d/Y', strtotime($item->time) ) );
			                if( $fecha == $hoy ){
			                    $amount_day += 1; $amount_month += 1; $amount_year += 1; $amount_total += 1;
			                }
	                	break;
	                	case 'users':
			                $metas = get_user_meta($item->ID);
			                $rol = strrpos($metas["wp_capabilities"][0], "subscriber");
			                if( $rol !== false ){
			                    $fecha = strtotime( date('m/d/Y', strtotime($item->date) ) );
			                    $hoy = strtotime(date('m/d/Y', $day));
			                    if( $fecha == $hoy ){
			                        $amount_user = 1; $amount_day += 1; $amount_month += 1; $amount_year += 1; $amount_total += 1;
			                    }
			                }
	                	break;
	                	case 'reservando':
			                $fecha = strtotime( date('m/d/Y', strtotime($item->date) ) );
			                $hoy = strtotime(date('m/d/Y', $day));
			                if( $fecha == $hoy ){
			                    $amount_user = 1;
			                    $amount_user = 1; $amount_day += 1; $amount_month += 1; $amount_year += 1; $amount_total += 1;
			                }
	                	break;
	                	case 'eventos':
			                $fecha = strtotime( date('m/d/Y', strtotime($item->date) ) );
			                $hoy = strtotime(date('m/d/Y', $day));
			                if( $fecha == $hoy ){
			                    $amount_user = 1;
			                    $amount_user = 1; $amount_day += 1; $amount_month += 1; $amount_year += 1; $amount_total += 1;
			                }
	                	break;
	                	case 'noches':
			                $fecha = strtotime( date('m/d/Y', strtotime($item->date) ) );
			                $hoy = strtotime(date('m/d/Y', $day));
			                if( $fecha == $hoy ){
			                    $amount_day += $item->noches; $amount_month += $item->noches; $amount_year += $item->noches; $amount_total += $item->noches;
			                }
	                	break;
	                }
	            }
	            if( time() > $day-84600 ){
	            	$data[] = [ date('d-m-Y', $day), $amount_day ];
	            }
	            $amount_day = 0;
	            if(date('t',$day) == date('d',$day) || $day_last == $day){
	            	$data[] = [ date('m-Y',$day), $amount_month];
	                $amount_month = 0;
	                if(date('m',$day) == '12' || $day_last == $day){
	            		$data[] = [ date('Y',$day), $amount_year ];
	                    $amount_year = 0;
	                }
	            }
	        }
	    }
	    $data[] = ["total", $amount_total];
	    return $data;
	}

	function loop_ventas($_data, $inits, $tipo){
		extract($inits);
		$data = [];

		$amount_day=0; $amount_month=0; $amount_year=0; $amount_total=0; $_28 = true;
		$anio = date("Y", $day_init);

        for($day = $day_init; $day <= $day_last; $day+=$day_more){
            $anio_act = date("Y", $day);
            if( $anio_act != $anio ){
                $amount_day=0; $amount_month=0; $amount_year=0; $amount_total=0;
                $anio = $anio_act;
            }
	        $print = true;
	        if( date('d/m/Y', $day) == "28/10/2018" ){ if($_28){ $_28 = false; }else{ $print = false; } }
            if( $print ){
	            foreach($_data as $booking){
	                switch ($tipo) {
	                	case 'reservas_canceladas':
			                if(strtotime(date('m/d/Y',$booking['date']))==strtotime(date('m/d/Y',$day))){
			                    $amount_booking=0;
			                    $amount_booking=$booking['WCorder_line_total'];
			                    $amount_booking=(round($amount_booking*100)/100);
			                    $amount_day=$amount_day+$amount_booking;
			                    $amount_month=$amount_month+$amount_booking;
			                    $amount_year=$amount_year+$amount_booking;
			                    $amount_total=$amount_total+$amount_booking;
			                }
	                	break;
	                	case 'total_reservas':
							if(strtotime(date('m/d/Y',$booking['date']))==strtotime(date('m/d/Y',$day))){
                                $amount_booking=0;
                                if($booking['status']!='cancelled' && $booking['status']!='modified'){
                                    $amount_booking=$booking['WCorder_line_total'];
                                }
                                $amount_booking=(round($amount_booking*100)/100);
                                $amount_day=$amount_day+$amount_booking;
                                $amount_month=$amount_month+$amount_booking;
                                $amount_year=$amount_year+$amount_booking;
                                $amount_total=$amount_total+$amount_booking;
                            }
	                	break;
	                	case 'total_reservas_canceladas':
							if(strtotime(date('m/d/Y',$booking['date']))==strtotime(date('m/d/Y',$day))){
                                $amount_booking=0;
                                if($booking['status']=='cancelled' || $booking['status']=='modified'){
                                    $amount_booking=$booking['WCorder_line_total'];
                                }
                                $amount_booking=(round($amount_booking*100)/100);
                                $amount_day=$amount_day+$amount_booking;
                                $amount_month=$amount_month+$amount_booking;
                                $amount_year=$amount_year+$amount_booking;
                                $amount_total=$amount_total+$amount_booking;
                            }
	                	break;
	                	case 'total_reservas_canceladas':
							if(strtotime(date('m/d/Y',$booking['date']))==strtotime(date('m/d/Y',$day))){
                                $amount_booking=0;
                                if($booking['status']=='cancelled' || $booking['status']=='modified'){
                                    $amount_booking=$booking['WCorder_line_total'];
                                }
                                $amount_booking=(round($amount_booking*100)/100);
                                $amount_day=$amount_day+$amount_booking;
                                $amount_month=$amount_month+$amount_booking;
                                $amount_year=$amount_year+$amount_booking;
                                $amount_total=$amount_total+$amount_booking;
                            }
	                	break;
	                	case 'monto_por_pagar':
							if(strtotime(date('m/d/Y',$booking['date']))==strtotime(date('m/d/Y',$day))){
                                $amount_booking=0;
                                if($booking['status']=='unpaid' && $booking['metas_order']['_payment_method'][0] == 'tienda'){
                                    $amount_booking=$booking['WCorder_line_total'];
                                }
                                $amount_booking=(round($amount_booking*100)/100);
                                $amount_day=$amount_day+$amount_booking;
                                $amount_month=$amount_month+$amount_booking;
                                $amount_year=$amount_year+$amount_booking;
                                $amount_total=$amount_total+$amount_booking;
                            }
	                	break;
	                	case 'monto_comision':
							if(strtotime(date('m/d/Y',$booking['date']))==strtotime(date('m/d/Y',$day))){
                                $amount_booking=0;
                                if($booking['status']!='cancelled' && $booking['status']!='modified'){
                                    $amount_booking=$booking['WCorder_line_total']*0.20;
                                }
                                $amount_booking=(round($amount_booking*100)/100);
                                $amount_day=$amount_day+$amount_booking;
                                $amount_month=$amount_month+$amount_booking;
                                $amount_year=$amount_year+$amount_booking;
                                $amount_total=$amount_total+$amount_booking;
                            }
	                	break;
	                }
	            }
	            if( time() > $day-84600 ){
	            	$data[] = [ date('d-m-Y', $day), $amount_day ];
	            }
	            $amount_day = 0;
	            if(date('t',$day) == date('d',$day) || $day_last == $day){
	            	$data[] = [ date('m-Y',$day), $amount_month];
	                $amount_month = 0;
	                if(date('m',$day) == '12' || $day_last == $day){
	            		$data[] = [ date('Y',$day), $amount_year ];
	                    $amount_year = 0;
	                }
	            }
            }
        }
	    $data[] = ["total", $amount_total];


		/*
		$amount_day = 0; $amount_month = 0; $amount_year = 0; $amount_total = 0; $_28 = true;
	    $anio = date("Y", $day_init);
	    for($day = $day_init; $day <= $day_last; $day+=$day_more){
	        $anio_act = date("Y", $day);
	        if( $anio_act != $anio ){
	            $amount_day = 0; $amount_month = 0; $amount_year = 0;
	            $anio = $anio_act;
	        }
	        $print = true;
	        if( date('d/m/Y', $day) == "28/10/2018" ){ if($_28){ $_28 = false; }else{ $print = false; } }
	        if( $print ){
	        	$hoy = strtotime(date('m/d/Y', $day));
	            foreach($_data as $item){
	                switch ($tipo) {
	                	case 'leads':
			                $fecha = strtotime( date('m/d/Y', strtotime($item->time) ) );
			                if( $fecha == $hoy ){
			                    $amount_day += 1; $amount_month += 1; $amount_year += 1; $amount_total += 1;
			                }
	                	break;
	                	case 'users':
			                $metas = get_user_meta($item->ID);
			                $rol = strrpos($metas["wp_capabilities"][0], "subscriber");
			                if( $rol !== false ){
			                    $fecha = strtotime( date('m/d/Y', strtotime($item->date) ) );
			                    $hoy = strtotime(date('m/d/Y', $day));
			                    if( $fecha == $hoy ){
			                        $amount_user = 1; $amount_day += 1; $amount_month += 1; $amount_year += 1; $amount_total += 1;
			                    }
			                }
	                	break;
	                	case 'reservando':
			                $fecha = strtotime( date('m/d/Y', strtotime($item->date) ) );
			                $hoy = strtotime(date('m/d/Y', $day));
			                if( $fecha == $hoy ){
			                    $amount_user = 1;
			                    $amount_user = 1; $amount_day += 1; $amount_month += 1; $amount_year += 1; $amount_total += 1;
			                }
	                	break;
	                	case 'eventos':
			                $fecha = strtotime( date('m/d/Y', strtotime($item->date) ) );
			                $hoy = strtotime(date('m/d/Y', $day));
			                if( $fecha == $hoy ){
			                    $amount_user = 1;
			                    $amount_user = 1; $amount_day += 1; $amount_month += 1; $amount_year += 1; $amount_total += 1;
			                }
	                	break;
	                	case 'noches':
			                $fecha = strtotime( date('m/d/Y', strtotime($item->date) ) );
			                $hoy = strtotime(date('m/d/Y', $day));
			                if( $fecha == $hoy ){
			                    $amount_day += $item->noches; $amount_month += $item->noches; $amount_year += $item->noches; $amount_total += $item->noches;
			                }
	                	break;
	                }
	            }
	            if( time() > $day-84600 ){
	            	$data[] = [ date('d-m-Y', $day), $amount_day ];
	            }
	            $amount_day = 0;
	            if(date('t',$day) == date('d',$day) || $day_last == $day){
	            	$data[] = [ date('m-Y',$day), $amount_month];
	                $amount_month = 0;
	                if(date('m',$day) == '12' || $day_last == $day){
	            		$data[] = [ date('Y',$day), $amount_year ];
	                    $amount_year = 0;
	                }
	            }
	        }
	    }
	    $data[] = ["total", $amount_total];
	    */
	    return $data;
	}
?>