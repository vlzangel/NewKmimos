<?php
    $kmimos_load = dirname(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))))).'/wp-load.php';
    if(file_exists($kmimos_load)){
        include_once($kmimos_load);
    }

    global $wpdb;
    $wlabel = $_wlabel_user->wlabel;
    $WLresult = $_wlabel_user->wlabel_result;
    $_wlabel_user->wlabel_Options('client');
    $_wlabel_user->wLabel_Filter(array('tddate','tdcheck'));
    $_wlabel_user->wlabel_Export('client','CLIENTES','table'); ?>


    <div class="module_title">
        Funnel de Conversión
    </div>

    <div class="section">
        <div class="tables">
            <table cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th>Titulo</th> <?php
                        $day_init = strtotime(date('m/d/Y',$WLresult->time));
                        $day_last = strtotime(date('m/d/Y',time()));
                        $day_more = (24*60*60);
                        for($day=$day_init; $day<=$day_last ; $day=$day+$day_more){
                            echo '<th class="day tdshow" data-check="day" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.date('d/m/Y',$day).'</th>';//date('d',$day).'--'.
                            if(date('t',$day)==date('d',$day) || $day_last==$day){
                                echo '<th class="month tdshow" data-check="month" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.date('F/Y',$day).'</th>';
                                if(date('m',$day)=='12' || $day_last==$day){
                                    echo '<th class="year tdshow" data-check="year" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.date('Y',$day).'</th>';
                                }
                            }
                        }
                        echo '<th class="total" >TOTAL DESDE '.strtoupper(date('F/Y',$day)).'</th>'; ?>
                    </tr>
                </thead>
                <tbody> <?php

                    //USER
                    $condicion_referido = "usermeta_2.meta_value = '{$wlabel}'";
                    if( $wlabel == "petco" ){
                        $condicion_referido = "( usermeta_2.meta_value = '{$wlabel}' OR usermeta_2.meta_value = 'CC-Petco' )";
                    }

                    $sql = "SELECT * FROM `wp_kmimos_subscribe` WHERE source = '{$wlabel}' AND time >= '2018-09-01 00:00:00' ";
                    $leads = $wpdb->get_results($sql);

                    $sql = "
                        SELECT DISTINCT
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
                            ( usermeta.meta_value = '{$wlabel}' OR {$condicion_referido} ) AND
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
                            fin.meta_value AS fin

                        FROM
                            wp_posts AS reservas

                        LEFT JOIN wp_usermeta as wlabel_cliente ON 
                            ( 
                                wlabel_cliente.user_id = reservas.post_author AND 
                                (
                                    wlabel_cliente.meta_key = 'user_referred' OR
                                    wlabel_cliente.meta_key = '_wlabel' 
                                ) AND
                                wlabel_cliente.meta_value = '{$wlabel}'
                            )

                        LEFT JOIN wp_postmeta as wlabel_reserva ON 
                            ( 
                                wlabel_reserva.post_id = reservas.ID AND 
                                wlabel_reserva.meta_key = '_wlabel' AND
                                wlabel_reserva.meta_value = '{$wlabel}'
                            )

                        LEFT JOIN wp_postmeta AS inicio ON (inicio.post_id = reservas.ID AND inicio.meta_key = '_booking_start')
                        LEFT JOIN wp_postmeta AS fin ON (fin.post_id = reservas.ID AND fin.meta_key = '_booking_end')

                        LEFT JOIN wp_users as cl ON cl.ID = reservas.post_author

                        WHERE
                            reservas.post_status = 'confirmed' AND
                            reservas.post_type = 'wc_booking' and (
                                wlabel_cliente.meta_value = '{$wlabel}' OR
                                wlabel_reserva.meta_value = '{$wlabel}'
                            )
                            and cl.ID > 0 AND
                            reservas.post_date >= '2018-09-01 00:00:00'
                        ORDER BY
                            reservas.ID DESC
                    ";
                    $reservas = $wpdb->get_results($sql);

                    $total_noches = 0;
                    $_reservas = [];
                    foreach ($reservas as $key => $reserva) {

                        if( !isset($_reservas[ $reserva->ID_reserva ]) ){
                            $inicio = strtotime( $reserva->inicio );
                            $fin = strtotime( $reserva->fin );
                            $noches = ( ceil(( $fin - $inicio )/60/60/24)-1 );
                            $total_noches += $noches;
                            $reservas[ $key ]->noches = $noches;

                            $_reservas[ $reserva->ID_reserva ] = $reservas[ $key ];
                        }

                    }

                    $day_init=strtotime(date('m/d/Y',$WLresult->time));
                    $day_last=strtotime(date('m/d/Y',time()));
                    $day_more=(24*60*60);

                    //CANTIDAD DE USUARIOS REGISTRADOS WL + KMIMOS
                    echo '<tr>';
                        echo '<th class="title">Usuarios totales reservando (White Label + Kmimos)</th>';

                        $amount_day=0;
                        $amount_month=0;
                        $amount_year=0;
                        $amount_total=0;

                        for($day = $day_init; $day <= $day_last; $day+=$day_more){

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

                            echo '<td class="day tdshow" data-check="day" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.$amount_day.'</td>';
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
                        echo '<th class="total" >'.$amount_total.'</th>';
                    echo '</tr>';

                    //CANTIDAD DE USUARIOS REGISTRADOS WL + KMIMOS
                    echo '<tr>';
                        echo '<th class="title">Usuarios totales registrados (White Label + Página Kmimos)</th>';

                        $amount_day=0;
                        $amount_month=0;
                        $amount_year=0;
                        $amount_total=0;

                        for($day = $day_init; $day <= $day_last; $day+=$day_more){

                            foreach($users as $user){
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

                            echo '<td class="day tdshow" data-check="day" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.$amount_day.'</td>';
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
                        echo '<th class="total" >'.$amount_total.'</th>';
                    echo '</tr>';

                    //CANTIDAD DE USUARIOS REGISTRADOS REFERIDOS WLABEL
                    echo '<tr>';
                        echo '<th class="title">Total de Noches reservadas y confirmadas</th>';

                        $amount_day=0;
                        $amount_month=0;
                        $amount_year=0;
                        $amount_total=0;

                        for($day = $day_init; $day <= $day_last; $day+=$day_more){

                            
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

                            echo '<td class="day tdshow" data-check="day" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.$amount_day.'</td>';
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
                        echo '<th class="total" >'.$total_noches.'</th>';
                    echo '</tr>';


                    //TOTAL DE LEADS
                    echo '<tr>';
                    echo '<th class="title">Leads</th>';
                    
                        $amount_day=0;
                        $amount_month=0;
                        $amount_year=0;
                        $amount_total=0;

                        for($day = $day_init; $day <= $day_last; $day+=$day_more){

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

                            echo '<td class="day tdshow" data-check="day" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.$amount_day.'</td>';
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
                        echo '<th class="total" >'.$amount_total.'</th>';
                    echo '</tr>'; ?>
                </tbody>
            </table>
        </div>
    </div>

<style type="text/css">
    body{
        background: #DDD;
    }
</style>