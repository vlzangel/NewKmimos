<?php

    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/wp-load.php");

    date_default_timezone_set('America/Mexico_City');

    $_desde = ""; $_hasta = "";

    $reservas = $wpdb->get_results("SELECT * FROM reporte_reserva_new ORDER BY reserva_id DESC"); // WHERE fecha_reservacion >= '{$_desde}' AND fecha_reservacion <= '{$_hasta}' 

    $editores = [
        0 => "Seleccione...",
        1 => "Nikole Merlo",
        2 => "Eyderman Peraza",
        3 => "Leomar Albarrán",
        4 => "Mariana Castello",
        5 => "Yrcel Chaudary",
    ];

    $data["data"] = array();
    $contador = 1; 
    foreach ($reservas as $key => $datos) {
        $item = []; $id_actual = 0;
        foreach ($datos as $key => $info) {

            switch ( $key ) {

                case 'recompra_1_mes':
                case 'recompra_3_meses':
                case 'recompra_6_meses':
                case 'recompra_12_meses':
                case 'mascotas':
                case 'razas':
                case 'edad':
                break;

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