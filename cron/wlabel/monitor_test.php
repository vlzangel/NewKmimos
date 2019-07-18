<link rel="stylesheet" type="text/css" href="css.css" />
<?php
	include_once dirname(dirname(__DIR__))."/wp-load.php";
	include 'funciones.php';
	global $wpdb;

    $wlabels = $wpdb->get_results("SELECT * FROM wlabel_monitor");
    foreach ($wlabels as $key => $_wlabel) {
        $wlabel = json_decode($_wlabel->data);
        $inicio_wlabel = $_wlabel->inicio_wlabel;
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
                                <tr>';
                                    $day_init = strtotime(date('m/d/Y', strtotime($inicio_wlabel) ));
                                    $day_last = strtotime( date("Y")."-".(date('m')+1)."-01" );
                                    $day_more = (24*60*60);
                                    $_28 = true;
                                    for( $day = $day_init; $day <= $day_last ; $day = $day+$day_more ){
                                        $print = true;
                                        if( date('d/m/Y',$day) == "28/10/2018" ){
                                            if($_28){ $_28 = false; }else{ $print = false; }
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
                                    echo '<th class="total" >Acumulado '.(date('Y', time() )).'</th>';
                                    $data = [
                                        "leads" => '',
                                        "usuarios_registrados" => '',
                                        "usuarios_reservando" => '',
                                        "eventos_reserva" => '',
                                        "noches_reservadas" => '',
                                    ];
                                    foreach ($wlabel as $key => $_data) {
                                        $data[ $key ] .= '<tr>';
                                            foreach ($_data as $key_2 => $fechas) {
                                                $day = explode("-", $fechas[0]);
                                                $tipo = count( $day );
                                                if( $tipo == 3){
                                                    $data[ $key ] .= '<td class="day tdshow" data-check="day" data-month="'.($day[1]+0).'" data-year="'.($day[0]).'">'.$fechas[1].'</td>';
                                                }
                                                if( $tipo == 2){
                                                    $data[ $key ] .= '<td class="month tdshow" data-check="month" data-month="'.($day[1]+0).'" data-year="'.($day[0]).'">'.$fechas[1].'</td>';
                                                }
                                                if( $tipo == 1){
                                                    $data[ $key ] .= '<td class="month tdshow" data-check="year" data-month="'.($day[1]+0).'" data-year="'.($day[0]).'">'.$fechas[1].'</td>';
                                                }
                                            }
                                        $data[ $key ] .= '</tr>';
                                    }
                                    echo $data[ 'leads' ];
                                    echo $data[ 'usuarios_registrados' ];
                                    echo $data[ 'usuarios_reservando' ];
                                    echo $data[ 'eventos_reserva' ];
                                    echo $data[ 'noches_reservadas' ];
                                    echo '
                                </tr>
                            </thead>
                            <tbody> 
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        ';
    }
?>