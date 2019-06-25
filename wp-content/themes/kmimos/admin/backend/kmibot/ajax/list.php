<?php

    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/wp-load.php");

    global $wpdb;

    date_default_timezone_set('America/Mexico_City');

    $_desde = ""; $_hasta = "";

    $kmibot = $wpdb->get_results("SELECT * FROM kmibot ORDER BY creado DESC");

    $editores = [
        0 => "Seleccione...",
        1 => "Nikole Merlo",
        2 => "Eyderman Peraza",
        3 => "Leomar Albarrán",
        4 => "Mariana Castello",
        5 => "Yrcel Chaudary",
    ];

    $_status = [
        'No interesado',
        'Sin Respuesta',
        'Solicito información',
        'Atendido por otro medio',
        'Solicito perfiles de cuidadores (Requerimiento)'
    ];

    // soporte.kmimos@gmail.com
 
    $data["data"] = array();
    $contador = 1; 
    foreach ($kmibot as $key => $datos) {
        $item = []; $id_actual = 0;
        foreach ($datos as $key => $info) {

            switch ( $key ) {
                
                case 'id':
                    $item[] = $contador;
                    $id_actual = $info;
                break;

                case 'creado': 
                    $item[] = date("d/m/Y H:i", strtotime($info) );
                break;

                case 'nombre':
                    $ID = $wpdb->get_var("SELECT ID FROM wp_users WHERE user_email = '{$datos->correo_cliente}' ");
                    $item[] = get_user_meta($ID, 'first_name', true)." ".get_user_meta($ID, 'last_name', true);
                break;
                case 'correo_cliente': 
                    $item[] = '<input onchange="updateInfo( jQuery(this) )" data-id="'.$id_actual.'" data-type="'.$key.'" type="text" value="'.$info.'" />';
                break;

                case 'status': 
                    $lista = '<select onchange="updateInfo( jQuery(this) )" data-id="'.$id_actual.'" data-type="'.$key.'">';
                    foreach ($_status as $k => $v) {
                        $lista .= '<option value="'.$v.'" '.selected($v, $info, false).'>'.$v.'</option>';
                    }
                    $lista .= '</select>';
                    $item[] = $lista;
                break;

                case 'medio': 
                    $lista = '<select onchange="updateInfo( jQuery(this) )" data-id="'.$id_actual.'" data-type="'.$key.'">';
                    foreach ($_medios as $k => $v) {
                        $lista .= '<option value="'.$v.'" '.selected($v, $info, false).'>'.$v.'</option>';
                    }
                    $lista .= '</select>';
                    $item[] = $lista;
                break;

                case 'descripcion':
                    $item[] = '<textarea class="comentarios" onchange="updateInfo( jQuery(this) )" data-id="'.$id_actual.'" data-type="descripcion">'.$info.'</textarea>';
                break;

                case 'observaciones':
                    $item[] = '<textarea class="comentarios" onchange="updateInfo( jQuery(this) )" data-id="'.$id_actual.'" data-type="observaciones">'.$info.'</textarea>';
                break;

                case 'checkin':
                case 'checkout':
                case 'ult_contacto':
                    $fecha = ( $info == NULL ) ? '' : date("Y-m-d", strtotime($info) ) ;
                    $item[] = '<input onchange="updateInfo( jQuery(this) )" data-id="'.$id_actual.'" data-type="'.$key.'" type="date" value="'.$fecha.'" />';
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