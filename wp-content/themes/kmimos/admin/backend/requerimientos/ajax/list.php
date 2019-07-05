<?php

    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/wp-load.php");

    global $wpdb;

    date_default_timezone_set('America/Mexico_City');

    $_desde = ""; $_hasta = "";

    $requerimientos = $wpdb->get_results("SELECT * FROM requerimientos ORDER BY creado DESC"); //

    $editores = [
        0 => "Seleccione...",
        1 => "Nikole Merlo",
        2 => "Eyderman Peraza",
        3 => "Leomar Albarrán",
        4 => "Mariana Castello",
        5 => "Yrcel Chaudary",
    ];

    $_status = [
        'Reservado',
        'Pendiente - Por enviar sugerencias',
        'Pendiente - Sugerencias enviadas',
        'Pendiente - Cliente quiere ver más sugerencias',
        'Pendiente - Cliente comento que realizará la Reserva',
        'Cancelada - Sin respuesta del cliente',
        'Cancelada - Cliente no ocupara el servicio',
        'Cancelada - Cliente dejará la mascota con un familiar',
        'Cancelada - Cliente viajará con la mascota',
        'Cancelada - Cliente no le agradaron las sugerencias',
        'Cancelada por motivo de salud de la mascota',
        'Cancelada por falta de cuidadores en la localidad',
    ];

    $_medios = [
        'Zopim',
        'Recibido desde Mx',
        'Llamada',
        'Facebook',
        'Kmibots'
    ];

    $data["data"] = array();
    $contador = 1; 
    foreach ($requerimientos as $key => $datos) {
        $item = []; $id_actual = 0;
        foreach ($datos as $key => $info) {

            switch ( $key ) {
                
                case 'id':
                    $item[] = $contador;
                    $id_actual = $info;
                break;

                case 'cliente_id':
                case 'creado': break;

                case 'nombre_cliente':
                case 'correo_cliente': 
                case 'telf_cliente': 
                case 'noches_total': 
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