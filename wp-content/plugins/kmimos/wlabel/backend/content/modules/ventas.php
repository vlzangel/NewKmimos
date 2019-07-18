<?php
$kmimos_load=dirname(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))))).'/wp-load.php';
if(file_exists($kmimos_load)){
    include_once($kmimos_load);
}
    date_default_timezone_set('America/Mexico_City');

function number_round($number){
    $number=(round($number*100))/100;
    $number=number_format($number, 2, ',', '.');
    return $number;
}

error_reporting(0);

global $wpdb;
$wlabel=$_wlabel_user->wlabel;
$WLresult=$_wlabel_user->wlabel_result;
$WLcommission=$_wlabel_user->wlabel_Commission();
//$_wlabel_user->wlabel_Options('detail');
$_wlabel_user->wLabel_Filter(array('tddate','tdcheck'));
$_wlabel_user->wlabel_Export('detail','DETALLE DE MONTOS','table');
?>
<div class="module_title">
    Resumen de ventas
</div>

<div class="section">

    <div class="table_container">
        <div class="table_titles tables">
            <table>
                <thead>
                    <tr><th>Titulo</th></tr>
                    <tr><th>Monto total de reservas Confirmadas, Canceladas y Completadas</th></tr>
                    <tr><th>Monto total de reservas confirmadas</th></tr>
                    <tr><th>Monto de Reservas Canceladas</th></tr>
                    <tr><th>Monto de reservas pendientes por pagar en tienda por conveniencia</th></tr>
                    <tr><th>Comision (20%)</th></tr>
                </thead>
            </table>
        </div>
        <div class="table_rows tables">
            <table cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <?php
                            $day_init=strtotime(date('m/d/Y',$WLresult->time));
                            $day_last=strtotime( date("Y")."-".(date('m')+1)."-01" );
                            $day_more=(24*60*60);
                            $_28 = true;

                            $anio = date("Y", $day_init);

                            for($day = $day_init; $day <= $day_last; $day+=$day_more){
                                $anio_act = date("Y", $day);
                                if( $anio_act != $anio ){
                                    $amount_day=0;
                                    $amount_month=0;
                                    $amount_year=0;
                                    $amount_total=0;
                                    $anio = $anio_act;
                                }

                                $print = true;
                                if( date('d/m/Y',$day) == "28/10/2018" ){
                                    if($_28){
                                        $_28 = false;
                                    }else{
                                        $print = false;
                                    }
                                }
                                if( $print ){
                                    if( time() > $day-84600 ){
                                        echo '<th class="day tdshow" data-check="day" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.date('d/m/Y',$day).' (MXN)</th>';
                                    }
                                    //date('d',$day).'--'.
                                    if(date('t',$day)==date('d',$day) || $day_last==$day){
                                        echo '<th class="month tdshow" data-check="month" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.date('F/Y',$day).' (MXN)</th>';
                                        if(date('m',$day)=='12' || $day_last==$day){
                                            echo '<th class="year tdshow" data-check="year" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">Acumulado '.date('Y',$day).' (MXN)</th>';
                                        }
                                    }
                                }
                            }
                            echo '<th class="total tdshow">Acumulado '.(date('Y',$day)).' (MXN)</th>';
                        ?>
                    </tr>
                </thead>
                <tbody><?php
                    $sql = "
                        SELECT
                            posts.*
                        FROM
                            wp_posts AS posts
                            LEFT JOIN wp_postmeta AS postmeta ON (postmeta.post_id=posts.post_parent AND postmeta.meta_key='_wlabel')
                            LEFT JOIN wp_usermeta AS usermeta ON (usermeta.user_id=posts.post_author AND (usermeta.meta_key='_wlabel' OR usermeta.meta_key='user_referred') AND usermeta.meta_value LIKE '%{$wlabel}%' )
                        WHERE
                            (
                                (posts.post_type = 'wc_booking' AND usermeta.meta_value LIKE '%{$wlabel}%')
                                OR
                                (posts.post_type = 'wc_booking' AND postmeta.meta_value = '{$wlabel}')
                            ) AND 
                            posts.post_status in ( 'confirmed', 'completed', 'cancelled'  ) AND
                            posts.post_date >= '2018-09-01 00:00:00'
                        ORDER BY
                            posts.ID DESC
                    ";
                    $bookings = $wpdb->get_results($sql);
                    $BUILDbookings = array();
                    foreach($bookings as $key => $booking){
                        //var_dump($booking);
                        $ID=$booking->ID;
                        $date=strtotime($booking->post_date);
                        $customer=$booking->post_author;
                        $status=$booking->post_status;
                        $order=$booking->post_parent;
                        $status_name=$status;

                        $_metas_booking = get_post_meta($ID);
                        $_metas_order = get_post_meta($order);

                        $IDproduct=$_metas_booking['_booking_product_id'][0];
                        $IDcustomer=$_metas_booking['_booking_customer_id'][0];
                        $IDorder_item=$_metas_booking['_booking_order_item_id'][0];

                        $_meta_WCorder_line_total = wc_get_order_item_meta($IDorder_item,'_line_subtotal');
                        
                        //CUSTOMER
                        $_metas_customer = get_user_meta($customer);
                        $_customer_name = $_metas_customer['first_name'][0] . " " . $_metas_customer['last_name'][0];

                        $product = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID ='$IDproduct'");
                        
                        $BUILDbookings[$ID] = array();
                        $BUILDbookings[$ID]['booking'] = $ID;
                        $BUILDbookings[$ID]['order'] = $order;
                        $BUILDbookings[$ID]['date'] = $date;
                        $BUILDbookings[$ID]['customer'] = $customer;
                        $BUILDbookings[$ID]['status'] = $status;
                        $BUILDbookings[$ID]['status_name'] = $status_name;
                        $BUILDbookings[$ID]['metas_booking'] = $_metas_booking;
                        $BUILDbookings[$ID]['metas_order'] = $_metas_order;
                        $BUILDbookings[$ID]['WCorder_line_total'] = $_meta_WCorder_line_total*1;
                    }

                    //TOTAL DE MONTO DE RESERVAS CANCELADAS
                    echo '<tr>';

                    $amount_day=0;
                    $amount_month=0;
                    $amount_year=0;
                    $amount_total=0;
                    $_28 = true;

                            $anio = date("Y", $day_init);

                            for($day = $day_init; $day <= $day_last; $day+=$day_more){
                                $anio_act = date("Y", $day);
                                if( $anio_act != $anio ){
                                    $amount_day=0;
                                    $amount_month=0;
                                    $amount_year=0;
                                    $amount_total=0;
                                    $anio = $anio_act;
                                }

                            
                        $print = true;
                        if( date('d/m/Y',$day) == "28/10/2018" ){
                            if($_28){
                                $_28 = false;
                            }else{
                                $print = false;
                            }
                        }
                        if( $print ){
                            foreach($BUILDbookings as $booking){

                                if(strtotime(date('m/d/Y',$booking['date']))==strtotime(date('m/d/Y',$day))){
                                    $amount_booking=0;
                                    //if($booking['status']!='modified'){} //$booking['status']=='cancelled'
                                    $amount_booking=$booking['WCorder_line_total'];

                                    $amount_booking=(round($amount_booking*100)/100);
                                    $amount_day=$amount_day+$amount_booking;
                                    $amount_month=$amount_month+$amount_booking;
                                    $amount_year=$amount_year+$amount_booking;
                                    $amount_total=$amount_total+$amount_booking;
                                }
                            }

                            if( $amount_day == 0 ){ $amount_day = ""; }else{ $amount_day = number_round($amount_day); }
                            if( $amount_mont == 0 ){ $amount_mont = ""; }
                            if( $amount_year == 0 ){ $amount_year = ""; }

                            if( time() > $day-84600 ){
                                echo '<td class="number day tdshow" data-check="day" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'"> '.$amount_day.'</td>';
                            }
                                 
                            $amount_day=0;

                            if(date('t',$day)==date('d',$day) || $day_last==$day){
                                echo '<td class="number month tdshow" data-check="month" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'"> '.number_round($amount_month).'</td>';
                                $amount_month=0;

                                if(date('m',$day)=='12' || $day_last==$day){
                                    echo '<th class="number year tdshow" data-check="year" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'"> '.number_round($amount_year).'</th>';
                                    $amount_year=0;
                                }
                            }
                        }
                    }
                    echo '<th class="total tdshow">'.number_round($amount_total).'</th>';
                    echo '</tr>';



                    //TOTAL DE MONTO DE RESERVAS
                    echo '<tr>';

                    $amount_day=0;
                    $amount_month=0;
                    $amount_year=0;
                    $amount_total=0;
                    $_28 = true;

                    $anio = date("Y", $day_init);

                    for($day = $day_init; $day <= $day_last; $day+=$day_more){
                        $anio_act = date("Y", $day);
                        if( $anio_act != $anio ){
                            $amount_day=0;
                            $amount_month=0;
                            $amount_year=0;
                            $amount_total=0;
                            $anio = $anio_act;
                        }

                        $print = true;
                        if( date('d/m/Y',$day) == "28/10/2018" ){
                            if($_28){
                                $_28 = false;
                            }else{
                                $print = false;
                            }
                        }
                        if( $print ){
                            foreach($BUILDbookings as $booking){
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
                            }

                            if( $amount_day == 0 ){ $amount_day = ""; }else{ $amount_day = number_round($amount_day); }
                            if( $amount_mont == 0 ){ $amount_mont = ""; }
                            if( $amount_year == 0 ){ $amount_year = ""; }

                            if( time() > $day-84600 ){
                                echo '<td class="number day tdshow" data-check="day" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'"> '.$amount_day.'</td>';
                            }
                                 
                            $amount_day=0;

                            if(date('t',$day)==date('d',$day) || $day_last==$day){
                                echo '<td class="number month tdshow" data-check="month" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'"> '.number_round($amount_month).'</td>';
                                $amount_month=0;

                                if(date('m',$day)=='12' || $day_last==$day){
                                    echo '<th class="number year tdshow" data-check="year" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'"> '.number_round($amount_year).'</th>';
                                    $amount_year=0;
                                }
                            }
                        }
                    }
                    echo '<th class="total tdshow">'.number_round($amount_total).'</th>';
                    echo '</tr>';




                    //TOTAL DE MONTO DE RESERVAS CANCELADAS
                    echo '<tr>';

                    $amount_day=0;
                    $amount_month=0;
                    $amount_year=0;
                    $amount_total=0;
                    $_28 = true;

                    $anio = date("Y", $day_init);

                    for($day = $day_init; $day <= $day_last; $day+=$day_more){
                        $anio_act = date("Y", $day);
                        if( $anio_act != $anio ){
                            $amount_day=0;
                            $amount_month=0;
                            $amount_year=0;
                            $amount_total=0;
                            $anio = $anio_act;
                        }

                        $print = true;
                        if( date('d/m/Y',$day) == "28/10/2018" ){
                            if($_28){
                                $_28 = false;
                            }else{
                                $print = false;
                            }
                        }
                        if( $print ){
                            foreach($BUILDbookings as $booking){
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
                            }

                            if( $amount_day == 0 ){ $amount_day = ""; }else{ $amount_day = number_round($amount_day); }
                            if( $amount_mont == 0 ){ $amount_mont = ""; }
                            if( $amount_year == 0 ){ $amount_year = ""; }

                            if( time() > $day-84600 ){
                                echo '<td class="number day tdshow" data-check="day" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'"> '.$amount_day.'</td>';
                            }
                                 
                            $amount_day=0;

                            if(date('t',$day)==date('d',$day) || $day_last==$day){
                                echo '<td class="number month tdshow" data-check="month" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'"> '.number_round($amount_month).'</td>';
                                $amount_month=0;

                                if(date('m',$day)=='12' || $day_last==$day){
                                    echo '<th class="number year tdshow" data-check="year" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'"> '.number_round($amount_year).'</th>';
                                    $amount_year=0;
                                }
                            }
                        }
                    }
                    echo '<th class="total tdshow">'.number_round($amount_total).'</th>';
                    echo '</tr>';

                    //TOTAL DE MONTO POR PAGAR
                    echo '<tr>';

                        $sql = "
                            SELECT
                                posts.*
                            FROM
                                wp_posts AS posts
                                LEFT JOIN wp_postmeta AS postmeta ON (postmeta.post_id=posts.post_parent AND postmeta.meta_key='_wlabel')
                                LEFT JOIN wp_usermeta AS usermeta ON (usermeta.user_id=posts.post_author AND (usermeta.meta_key='_wlabel' OR usermeta.meta_key='user_referred') AND usermeta.meta_value LIKE '%{$wlabel}%' )
                            WHERE
                                (
                                    ( posts.post_type = 'wc_booking' AND usermeta.meta_value LIKE '%{$wlabel}%' )
                                    OR
                                    ( posts.post_type = 'wc_booking' AND postmeta.meta_value = '{$wlabel}' )
                                ) AND 
                                posts.post_status in ( 'unpaid'  ) AND
                                posts.post_date >= '2018-09-01 00:00:00'
                            GROUP BY
                                posts.ID DESC
                        ";

                        $bookings = $wpdb->get_results($sql);

                        $_BUILDbookings = array();
                        foreach($bookings as $key => $booking){
                            $ID=$booking->ID;
                            $date=strtotime($booking->post_date);
                            $customer=$booking->post_author;
                            $status=$booking->post_status;
                            $order=$booking->post_parent;
                            $status_name=$status;
                            $_metas_booking = get_post_meta($ID);
                            $_metas_order = get_post_meta($order);
                            $IDproduct=$_metas_booking['_booking_product_id'][0];
                            $IDcustomer=$_metas_booking['_booking_customer_id'][0];
                            $IDorder_item=$_metas_booking['_booking_order_item_id'][0];
                            $_meta_WCorder_line_total = wc_get_order_item_meta($IDorder_item,'_line_subtotal');
                            $_metas_customer = get_user_meta($customer);
                            $_customer_name = $_metas_customer['first_name'][0] . " " . $_metas_customer['last_name'][0];
                            $product = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID ='$IDproduct'");
                            $_BUILDbookings[$ID] = array();
                            $_BUILDbookings[$ID]['booking'] = $ID;
                            $_BUILDbookings[$ID]['order'] = $order;
                            $_BUILDbookings[$ID]['date'] = $date;
                            $_BUILDbookings[$ID]['customer'] = $customer;
                            $_BUILDbookings[$ID]['status'] = $status;
                            $_BUILDbookings[$ID]['status_name'] = $status_name;
                            $_BUILDbookings[$ID]['metas_booking'] = $_metas_booking;
                            $_BUILDbookings[$ID]['metas_order'] = $_metas_order;
                            $_BUILDbookings[$ID]['WCorder_line_total'] = $_meta_WCorder_line_total*1;
                        }


                        $amount_day=0;
                        $amount_month=0;
                        $amount_year=0;
                        $amount_total=0;
                        $_28 = true;

                        $anio = date("Y", $day_init);

                        for($day = $day_init; $day <= $day_last; $day+=$day_more){
                            $anio_act = date("Y", $day);
                            if( $anio_act != $anio ){
                                $amount_day=0;
                                $amount_month=0;
                                $amount_year=0;
                                $amount_total=0;
                                $anio = $anio_act;
                            }

                            $print = true;
                            if( date('d/m/Y',$day) == "28/10/2018" ){
                                if($_28){
                                    $_28 = false;
                                }else{
                                    $print = false;
                                }
                            }
                            if( $print ){
                                foreach($_BUILDbookings as $booking){
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
                                }

                                if( $amount_day == 0 ){ $amount_day = ""; }else{ $amount_day = number_round($amount_day); }
                                if( $amount_mont == 0 ){ $amount_mont = ""; }
                                if( $amount_year == 0 ){ $amount_year = ""; }

                            if( time() > $day-84600 ){
                                echo '<td class="number day tdshow" data-check="day" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'"> '.$amount_day.'</td>';
                            }
                                 
                                $amount_day=0;

                                if(date('t',$day)==date('d',$day) || $day_last==$day){
                                    echo '<td class="number month tdshow" data-check="month" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'"> '.number_round($amount_month).'</td>';
                                    $amount_month=0;

                                    if(date('m',$day)=='12' || $day_last==$day){
                                        echo '<th class="number year tdshow" data-check="year" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'"> '.number_round($amount_year).'</th>';
                                        $amount_year=0;
                                    }
                                }
                            }
                        }
                        echo '<th class="total tdshow">'.number_round($amount_total).'</th>';
                    echo '</tr>';


                    //TOTAL DE MONTO DE COMISION
                    echo '<tr>';

                    $amount_day=0;
                    $amount_month=0;
                    $amount_year=0;
                    $amount_total=0;
                    $_28 = true;

                    $anio = date("Y", $day_init);

                    for($day = $day_init; $day <= $day_last; $day+=$day_more){
                        $anio_act = date("Y", $day);
                        if( $anio_act != $anio ){
                            $amount_day=0;
                            $amount_month=0;
                            $amount_year=0;
                            $amount_total=0;
                            $anio = $anio_act;
                        }

                        $print = true;
                        if( date('d/m/Y',$day) == "28/10/2018" ){
                            if($_28){
                                $_28 = false;
                            }else{
                                $print = false;
                            }
                        }
                        if( $print ){
                            foreach($BUILDbookings as $booking){
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
                            }

                            if( $amount_day == 0 ){ $amount_day = ""; }else{ $amount_day = number_round($amount_day); }
                            if( $amount_mont == 0 ){ $amount_mont = ""; }
                            if( $amount_year == 0 ){ $amount_year = ""; }

                            if( time() > $day-84600 ){
                                echo '<td class="number day tdshow" data-check="day" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'"> '.$amount_day.'</td>';
                            }
                                 
                            $amount_day=0;

                            if(date('t',$day)==date('d',$day) || $day_last==$day){
                                echo '<td class="number month tdshow" data-check="month" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'"> '.number_round($amount_month).'</td>';
                                $amount_month=0;

                                if(date('m',$day)=='12' || $day_last==$day){
                                    echo '<th class="number year tdshow" data-check="year" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'"> '.number_round($amount_year).'</th>';
                                    $amount_year=0;
                                }
                            }
                        }
                    }
                    echo '<th class="total tdshow">'.number_round($amount_total).'</th>';
                    echo '</tr>';?>
                </tbody>
            </table>
        </div>
    </div>

    <style type="text/css">
        .table_titles {
            width: 432px;
        }
        .table_rows {
            max-width: calc( 100% - 432px );
            margin-left: 430px;
        }
        .tables table th, .tables table td {
            padding: 8px 10px !important;
        }
    </style> 

</div>