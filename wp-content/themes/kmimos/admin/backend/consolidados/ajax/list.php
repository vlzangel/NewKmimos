<?php

    date_default_timezone_set('America/Mexico_City');

    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/vlz_config.php");
    include_once($raiz."/wp-load.php");

    $tema = (dirname(dirname(dirname(dirname(__DIR__)))));

    include_once($tema."/procesos/funciones/db.php");
    include_once($tema."/procesos/funciones/generales.php");


    require_once(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))).'/plugins/kmimos/dashboard/core/base_db.php');
    require_once(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))).'/plugins/kmimos/dashboard/core/GlobalFunction.php');
    require_once(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))).'/plugins/kmimos/dashboard/core/ControllerReservas.php');

    $_desde = ""; $_hasta = "";
    // $reservas = getReservas($_desde, $_hasta);

    $reservas = $wpdb->get_results("SELECT * FROM reporte_reserva_new WHERE fecha_reservacion >= '2019-03-01' AND fecha_reservacion <= NOW()  ORDER BY fecha_reservacion DESC"); // WHERE fecha_reservacion >= '{$_desde}' AND fecha_reservacion <= '{$_hasta}' 

    /*
    echo "<pre>";
        print_r( $reservas );
    echo "</pre>";
    */

    $editores = [
        0 => "Seleccione...",
        1 => "Nikole Merlo",
        2 => "Eyderman Peraza",
        3 => "Leomar Albarrán",
        4 => "Mariana Castello",
        5 => "Yrcel Chaudary",
    ];

    $db = new db( new mysqli($host, $user, $pass, $db) );

    $data["data"] = array();
    $contador = 1; 
    foreach ($reservas as $key => $datos) {
        $item = []; $id_actual = 0;
        foreach ($datos as $key => $info) {

            switch ( $key ) {
                case 'id':
                    $item[] = $contador;
                    $id_actual = $info;
                break;

                case 'comentarios':
                    $item[] = '<textarea class="comentarios" onchange="updateInfo( jQuery(this) )" data-id="'.$id_actual.'" data-type="comentarios">'.$info.'</textarea>';
                break;

                case 'ult_contacto':
                    $fecha = ( $info == NULL ) ? '' : date("Y-m-d", strtotime($info) ) ;
                    $item[] = '<input onchange="updateInfo( jQuery(this) )" data-id="'.$id_actual.'" data-type="ult_contacto" type="date" value="'.$fecha.'" />';
                break;

                case 'atendido_por':
                    $lista = '<select onchange="updateInfo( jQuery(this) )" data-id="'.$id_actual.'" data-type="atendido_por">';
                    foreach ($editores as $key => $value) {
                        $lista .= '<option value="'.$key.'" '.selected($key, $info, false).'>'.$value.'</option>';
                    }
                    $lista .= '</select>';
                    $item[] = $lista;
                break;
                
                default:
                    $item[] = $info;
                break;
            }

        }
        $contador++;
        $data["data"][] = $item;
    }

    echo json_encode($data);

?>