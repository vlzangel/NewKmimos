<link rel="stylesheet" type="text/css" href="css.css" />
<?php
	include_once dirname(dirname(__DIR__))."/wp-load.php";
	include 'funciones.php';
	global $wpdb;

    $wlabels = $wpdb->get_results("SELECT * FROM wlabel_ventas");
    foreach ($wlabels as $key => $_wlabel) {
        $wlabel = json_decode($_wlabel->data);
        $inicio_wlabel = $_wlabel->inicio_wlabel;
        echo '   
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
                                        "reservas_canceladas" => '',
                                        "total_reservas" => '',
                                        "total_reservas_canceladas" => '',
                                        "monto_por_pagar" => '',
                                        "monto_comision" => '',
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
                                    echo $data[ 'reservas_canceladas' ];
                                    echo $data[ 'total_reservas' ];
                                    echo $data[ 'total_reservas_canceladas' ];
                                    echo $data[ 'monto_por_pagar' ];
                                    echo $data[ 'monto_comision' ];
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