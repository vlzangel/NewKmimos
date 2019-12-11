<?php
    $kmimos_load = dirname(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))))).'/wp-load.php';
    if(file_exists($kmimos_load)){
        include_once($kmimos_load);
    }
    date_default_timezone_set('America/Mexico_City');

    error_reporting(0);

    global $wpdb;

    include 'funciones.php';

    $wlabel = $_wlabel_user->wlabel;
    $WLresult = $_wlabel_user->wlabel_result;
    $_wlabel_user->wLabel_Filter(array('tddate','tdcheck'));
    $_wlabel_user->wlabel_Export('monitor','Funnel de Conversión','table');

    $_wlabel_info = $wpdb->get_row("SELECT * FROM wlabel_monitor WHERE wlabel = '{$wlabel}' ");

    $HTML_TITULOS = '';

    $inicio_wlabel = $_wlabel_info->inicio_wlabel;

    $day_init = strtotime(date('m/d/Y', strtotime($inicio_wlabel) ));
    $day_last = strtotime( "+1 month", strtotime( date("Y-m")."-01") );
    if( date("Y", $day_init) != date("Y", $day_last) ){ $day_last = strtotime( date("Y")."-12-31"); }

    $day_more = (24*60*60);
    $_28 = true;
    for( $day = $day_init; $day <= $day_last ; $day = $day+$day_more ){
        $print = true;
        if( date('d/m/Y',$day) == "28/10/2018" ){
            if($_28){ $_28 = false; }else{ $print = false; }
        }
        if( $print ){
            if( time() > $day-84600 ){
                $HTML_TITULOS .= '<th class="day tdshow" data-check="day" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.date('d/m/Y',$day).'</th>';
            }
            if( date('t', $day) == date('d', $day) || $day_last == $day ){
                $HTML_TITULOS .= '<th class="month tdshow" data-check="month" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.date('F/Y',$day).'</th>';
                if( date('m',$day) == '12' || $day_last == $day ){
                    $HTML_TITULOS .= '<th class="year tdshow" data-check="year" data-month="'.date('n',$day).'" data-year="'.date('Y',$day).'">'.date('Y',$day).'</th>';
                }
            }
        }
    }
    $HTML_TITULOS .= '<th class="total" >Acumulado '.(date('Y', time() )).'</th>';

    $data = [
        "leads" => '',
        "usuarios_registrados" => '',
        "usuarios_reservando" => '',
        "eventos_reserva" => '',
        "noches_reservadas" => '',
    ];
    
    $_wlabel = json_decode($_wlabel_info->data);
    foreach ($_wlabel as $key => $_data) {
        $data[ $key ] .= '<tr>';
            foreach ($_data as $key_2 => $fechas) {
                $day = explode("-", $fechas[0]);
                $tipo = count( $day );
                if( $fechas[1] == 0 ){
                    $fechas[1] = "";
                }
                if( $day[0] == "total" ){
                    $data[ $key ] .= '<td class="total">'.$fechas[1].'</td>';
                }else{
                    if( $tipo == 3){
                        $data[ $key ] .= '<td class="day tdshow" data-check="day" data-month="'.($day[1]+0).'" data-year="'.($day[2]).'">'.$fechas[1].'</td>';
                    }
                    if( $tipo == 2){
                        $data[ $key ] .= '<td class="month tdshow" data-check="month" data-month="'.($day[0]+0).'" data-year="'.($day[1]).'">'.$fechas[1].'</td>';
                    }
                    if( $tipo == 1){
                        $data[ $key ] .= '<td class="year tdshow" data-check="year" data-month="'.($day[1]+0).'" data-year="'.($day[0]).'">'.$fechas[1].'</td>';
                    }
                }
            }
        $data[ $key ] .= '</tr>';
    }
    /*
    echo $data[ 'leads' ];
    echo $data[ 'usuarios_registrados' ];
    echo $data[ 'usuarios_reservando' ];
    echo $data[ 'eventos_reserva' ];
    echo $data[ 'noches_reservadas' ];
    */

    echo '   
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
                            <tr>'.$HTML_TITULOS.'
                            </tr>
                        </thead>
                        <tbody>
                            '.$data[ 'leads' ].'
                            '.$data[ 'usuarios_registrados' ].'
                            '.$data[ 'usuarios_reservando' ].'
                            '.$data[ 'eventos_reserva' ].'
                            '.$data[ 'noches_reservadas' ].'
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    ';
    /*
    echo "<pre>";  
        print_r( $_wlabel );
    echo "</pre>";
    */
?>