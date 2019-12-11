<?php
    $kmimos_load = dirname(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))))).'/wp-load.php';
    
    if(file_exists($kmimos_load)){
        include_once($kmimos_load);
    }
    date_default_timezone_set('America/Mexico_City');

    error_reporting(0);

    global $wpdb;
    $wlabel = $_wlabel_user->wlabel;
    $WLresult = $_wlabel_user->wlabel_result;
    $_wlabel_user->wLabel_Filter(array('tddate','tdcheck'));
    $_wlabel_user->wlabel_Export('monitor','Funnel de Conversión','table'); ?>

    <div class="module_title">
        Funnel de Conversión
    </div>

    <div class="section">

        <div class="table_container">
            <div class="table_titles tables">
                <table>
                    <thead>
                        <tr><th>Titulo</th></tr>
                        <tr><th>Leads</th></tr>
                        <tr><th>Usuarios Totales Registrados (White Label + Kmimos)</th></tr>
                        <tr><th>Usuarios Totales Reservando (White Label + Kmimos)</th></tr>
                        <tr><th>Total Eventos de Reserva</th></tr>
                        <tr><th>Total de Noches Reservadas y Confirmadas</th></tr>
                    </thead>
                </table>
            </div>
            <div class="table_rows tables">

                <table cellspacing="0" cellpadding="0">
                    <thead>
                        <tr><?php
                            $day_init = strtotime(date('m/d/Y',$WLresult->time));
                            $day_last = strtotime( "+1 month", strtotime( date("Y-m")."-01") );
                            $day_more = (24*60*60);

                            $_28 = true;
                            for( $day = $day_init; $day <= $day_last ; $day = $day+$day_more ){
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
                                        echo '<th class="day tdshow" data-check="day" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.date('d/m/Y',$day).'</th>';
                                    }
                                    if( date('t', $day) == date('d', $day) || $day_last == $day ){
                                        echo '<th class="month tdshow" data-check="month" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.date('F/Y',$day).'</th>';
                                        if( date('m',$day) == '12' || $day_last == $day ){
                                            echo '<th class="year tdshow" data-check="year" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.date('Y',$day).'</th>';
                                        }
                                    }
                                }
                            }
                            echo '<th class="total" >Acumulado '.(date('Y', time() )).'</th>'; ?>
                        </tr>
                    </thead>
                    <tbody> <?php

                        //USER
                        $condicion_referido = "( usermeta.meta_value = '{$wlabel}' OR usermeta_2.meta_value LIKE '%{$wlabel}%' )";
                        
                        $sql = "SELECT * FROM `wp_kmimos_subscribe` WHERE source = '{$wlabel}' AND time >= '2018-09-01 00:00:00' ";
                        $leads = $wpdb->get_results($sql);

                        $sql = "
                            SELECT 
                                users.ID,
                                users.user_login as login,
                                users.user_registered as date,

                                usermeta.meta_value AS es_wlabel_registro,
                                usermeta_2.meta_value AS es_wlabel_referido

                            FROM
                                wp_users AS users
                                LEFT JOIN wp_usermeta AS usermeta ON (usermeta.user_id=users.ID AND usermeta.meta_key='_wlabel')
                                LEFT JOIN wp_usermeta AS usermeta_2 ON (usermeta_2.user_id=users.ID AND usermeta_2.meta_key='user_referred')
                            WHERE
                                {$condicion_referido} AND
                                users.user_registered >= '2018-09-01 00:00:00'
                            ORDER BY
                                users.ID DESC
                        ";
                        $users = $wpdb->get_results($sql);

                        $sql = "
                            SELECT DISTINCT
                                reservas.ID AS ID_reserva,
                                reservas.post_author AS ID,
                                reservas.post_date AS date,
                                reservas.post_status AS reserva_status,

                                inicio.meta_value AS inicio,
                                fin.meta_value AS fin,
                                mascotas.meta_value AS mascotas,
                                servicio.post_title AS servicio

                            FROM
                                wp_posts AS reservas

                            LEFT JOIN wp_usermeta as wlabel_cliente ON (wlabel_cliente.user_id = reservas.post_author AND wlabel_cliente.meta_key = 'user_referred')
                            LEFT JOIN wp_usermeta as wlabel_cliente_2 ON (wlabel_cliente_2.user_id = reservas.post_author AND wlabel_cliente_2.meta_key = '_wlabel')

                            LEFT JOIN wp_postmeta as wlabel_reserva ON 
                                ( 
                                    wlabel_reserva.post_id = reservas.ID AND 
                                    wlabel_reserva.meta_key = '_wlabel' AND
                                    wlabel_reserva.meta_value = '{$wlabel}'
                                )

                            LEFT JOIN wp_postmeta AS inicio ON (inicio.post_id = reservas.ID AND inicio.meta_key = '_booking_start')
                            LEFT JOIN wp_postmeta AS fin ON (fin.post_id = reservas.ID AND fin.meta_key = '_booking_end')
                            LEFT JOIN wp_postmeta AS mascotas ON (mascotas.post_id = reservas.ID AND mascotas.meta_key = '_booking_persons')
                            LEFT JOIN wp_postmeta AS servicio_id ON (servicio_id.post_id = reservas.ID AND servicio_id.meta_key = '_booking_product_id')
                            LEFT JOIN wp_posts AS servicio ON ( servicio.ID =servicio_id.meta_value)

                            LEFT JOIN wp_users as cl ON cl.ID = reservas.post_author

                            WHERE
                                reservas.post_status = 'confirmed' AND
                                reservas.post_type = 'wc_booking' and ( 
                                    wlabel_cliente.meta_value LIKE '%{$wlabel}%' OR
                                    wlabel_cliente_2.meta_value = '{$wlabel}' OR
                                    wlabel_reserva.meta_value LIKE '%{$wlabel}%'
                                )
                                and cl.ID > 0 AND
                                reservas.post_date >= '2018-09-01 00:00:00'
                            ORDER BY
                                reservas.ID DESC
                        ";

                        $reservas = $wpdb->get_results($sql);

                        $total_noches = 0;
                        $_reservas = [];
                        $_reservas_clientes = [];
                        foreach ($reservas as $key => $reserva) {

                                $inicio = strtotime( $reserva->inicio );
                                $fin = strtotime( $reserva->fin );

                                $servicio = explode(" - ", strtolower($reserva->servicio) );
                                $servicio = trim( $servicio[0] );
                
                                if( $servicio == "hospedaje" ){
                                    $noches = ( ceil(( $fin - $inicio )/60/60/24)-1 );
                                }else{
                                    $noches = ( ceil(( $fin - $inicio )/60/60/24) );
                                }

                                $_mascotas = unserialize( $reserva->mascotas );
                                $mascotas = 0;
                                foreach ($_mascotas as $valor) {
                                    $mascotas += $valor;
                                }

                                $total_noches += $noches*$mascotas;

                                $reservas[ $key ]->noches = $noches*$mascotas;


                            if( !isset($_reservas[ $reserva->ID_reserva ]) ){
                                $_reservas[ $reserva->ID_reserva ] = $reservas[ $key ];
                            }

                            if( !isset($_reservas_clientes[ $reserva->ID ]) ){
                                $_reservas_clientes[ $reserva->ID ] = $reservas[ $key ];
                            }

                        }

                        // Leads
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
                                    foreach($leads as $reserva){
                                        $fecha = strtotime( date('m/d/Y', strtotime($reserva->time) ) );
                                        $hoy = strtotime(date('m/d/Y', $day));

                                        if( $fecha == $hoy ){

                                            $amount_day += 1;
                                            $amount_month += 1;
                                            $amount_year += 1;
                                            $amount_total += 1;
                                        }
                                    }

                                    if( $amount_day == 0 ){ $amount_day = ""; }
                                    if( $amount_mont == 0 ){ $amount_mont = ""; }
                                    if( $amount_year == 0 ){ $amount_year = ""; }

                                    if( time() > $day-84600 ){
                                        echo '<td class="day tdshow" data-check="day" data-month="'.date('n', $day).'" data-year="'.date('Y',$day).'">'.$amount_day.'</td>';
                                    }
                                    $amount_day=0;
                                    if(date('t',$day)==date('d',$day) || $day_last==$day){
                                        echo '<td class="month tdshow" data-check="month" data-month="'.date('n', $day).'" data-year="'.date('Y',$day).'">'.$amount_month.'</td>';
                                        $amount_month=0;
                                        if(date('m',$day)=='12' || $day_last==$day){
                                            echo '<th class="year tdshow" data-check="year" data-month="'.date('n', $day).'" data-year="'.date('Y',$day).'">'.$amount_year.'</th>';
                                            $amount_year=0;
                                        }
                                    }
                                }
                            }
                            echo '<th class="total" >'.$amount_total.'</th>';
                        echo '</tr>';


                        // Usuarios Totales Registrados (White Label + Kmimos)
                        echo '<tr>';

                            $amount_day=0;
                            $amount_month=0;
                            $amount_year=0;
                            $amount_total=0;

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
                                    foreach($users as $user){
                                        $metas = get_user_meta($user->ID);
                                        $rol = strrpos($metas["wp_capabilities"][0], "subscriber");
                                        if( $rol !== false ){

                                            $fecha = strtotime( date('m/d/Y', strtotime($user->date) ) );
                                            $hoy = strtotime(date('m/d/Y', $day));

                                            if( $fecha == $hoy ){
                                                $amount_user = 1;
                                                $amount_day += $amount_user;
                                                $amount_month += $amount_user;
                                                $amount_year += $amount_user;
                                                $amount_total += $amount_user;
                                            }

                                        }
                                    }

                                    if( $amount_day == 0 ){ $amount_day = ""; }
                                    if( $amount_mont == 0 ){ $amount_mont = ""; }
                                    if( $amount_year == 0 ){ $amount_year = ""; }

                                    if( time() > $day-84600 ){
                                        echo '<td class="day tdshow" data-check="day" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.$amount_day.'</td>';
                                    }
                                    $amount_day=0;
                                    if(date('t',$day)==date('d',$day) || $day_last==$day){
                                        echo '<td class="month tdshow" data-check="month" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.$amount_month.'</td>';
                                        $amount_month=0;
                                        if(date('m',$day)=='12' || $day_last==$day){
                                            echo '<th class="year tdshow" data-check="year" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.$amount_year.'</th>';
                                            $amount_year=0;
                                        }
                                    }
                                }
                            }
                            echo '<th class="total" >'.$amount_total.'</th>';
                        echo '</tr>';


                        // Usuarios Totales Reservando (White Label + Kmimos)
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
                                    foreach($_reservas_clientes as $reserva){
                                        $fecha = strtotime( date('m/d/Y', strtotime($reserva->date) ) );
                                        $hoy = strtotime(date('m/d/Y', $day));
                        
                                        if( $fecha == $hoy ){

                                        
                                            $amount_user = 1;
                                            $amount_day += $amount_user;
                                            $amount_month += $amount_user;
                                            $amount_year += $amount_user;
                                            $amount_total += $amount_user;
                                        }
                                    }

                                    if( $amount_day == 0 ){ $amount_day = ""; }
                                    if( $amount_mont == 0 ){ $amount_mont = ""; }
                                    if( $amount_year == 0 ){ $amount_year = ""; }

                                    if( time() > $day-84600 ){
                                        echo '<td class="day tdshow" data-check="day" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.$amount_day.'</td>';
                                    }
                                    $amount_day=0;
                                    if(date('t',$day)==date('d',$day) || $day_last==$day){
                                        echo '<td class="month tdshow" data-check="month" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.$amount_month.'</td>';
                                        $amount_month=0;
                                        if(date('m',$day)=='12' || $day_last==$day){
                                            echo '<th class="year tdshow" data-check="year" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.$amount_year.'</th>';
                                            $amount_year=0;
                                        }
                                    }
                                }
                            }
                            echo '<th class="total" >'.$amount_total.'</th>';
                        echo '</tr>';


                        // Total Eventos de Reserva
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
                                    foreach($_reservas as $reserva){
                                        $fecha = strtotime( date('m/d/Y', strtotime($reserva->date) ) );
                                        $hoy = strtotime(date('m/d/Y', $day));

                                        if( $fecha == $hoy ){
                                            $amount_user = 1;
                                            $amount_day += $amount_user;
                                            $amount_month += $amount_user;
                                            $amount_year += $amount_user;
                                            $amount_total += $amount_user;
                                        }
                                    }

                                    if( $amount_day == 0 ){ $amount_day = ""; }
                                    if( $amount_mont == 0 ){ $amount_mont = ""; }
                                    if( $amount_year == 0 ){ $amount_year = ""; }

                                    if( time() > $day-84600 ){
                                        echo '<td class="day tdshow" data-check="day" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.$amount_day.'</td>';
                                    }
                                    $amount_day=0;
                                    if(date('t',$day)==date('d',$day) || $day_last==$day){
                                        echo '<td class="month tdshow" data-check="month" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.$amount_month.'</td>';
                                        $amount_month=0;
                                        if(date('m',$day)=='12' || $day_last==$day){
                                            echo '<th class="year tdshow" data-check="year" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.$amount_year.'</th>';
                                            $amount_year=0;
                                        }
                                    }
                                }
                            }
                            echo '<th class="total" >'.$amount_total.'</th>';
                        echo '</tr>';


                        // Total de Noches Reservadas y Confirmadas
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
                                    foreach($reservas as $reserva){
                                        $fecha = strtotime( date('m/d/Y', strtotime($reserva->date) ) );
                                        $hoy = strtotime(date('m/d/Y', $day));

                                        if( $fecha == $hoy ){

                                            $amount_day += $reserva->noches;
                                            $amount_month += $reserva->noches;
                                            $amount_year += $reserva->noches;
                                            $amount_total += $reserva->noches;
                                        }
                                    }

                                    if( $amount_day == 0 ){ $amount_day = ""; }
                                    if( $amount_mont == 0 ){ $amount_mont = ""; }
                                    if( $amount_year == 0 ){ $amount_year = ""; }

                                    if( time() > $day-84600 ){
                                        echo '<td class="day tdshow" data-check="day" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.$amount_day.'</td>';
                                    }
                                    $amount_day=0;
                                    if(date('t',$day)==date('d',$day) || $day_last==$day){
                                        echo '<td class="month tdshow" data-check="month" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.$amount_month.'</td>';
                                        $amount_month=0;
                                        if(date('m',$day)=='12' || $day_last==$day){
                                            echo '<th class="year tdshow" data-check="year" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.$amount_year.'</th>';
                                            $amount_year=0;
                                        }
                                    }
                                }
                            }
                            echo '<th class="total" >'.$amount_total.'</th>';
                        echo '</tr>'; ?>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>