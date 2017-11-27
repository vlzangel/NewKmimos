<?php

    date_default_timezone_set('America/Mexico_City');

    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/vlz_config.php");

    $tema = (dirname(dirname(dirname(dirname(__DIR__)))));
    include_once($tema."/procesos/funciones/db.php");
    include_once($tema."/procesos/funciones/generales.php");

    $db = new db( new mysqli($host, $user, $pass, $db) );

    $data = array(
        "data" => array()
    );

    $actual = time();
    $actual = strtotime("11:48:00");

    $fotos = $db->get_results("SELECT * FROM fotos WHERE fecha = '".date("Y-m-d")."' ");
    if( $fotos != false ){
        foreach ($fotos as $key => $value) {

            $cuidador = $db->get_row("SELECT * FROM cuidadores WHERE user_id = {$value->cuidador}");
            $cuidador_id = $cuidador->id_post;
            $cuidador_name = $db->get_var("SELECT post_title FROM wp_posts WHERE ID = {$cuidador_id}");

            $cliente_id = $db->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = {$value->reserva} AND meta_key = '_booking_customer_id' ");

            $metas_cliente = kmimos_get_user_meta($cliente_id);
            $cliente_name = $metas_cliente["first_name"]." ".$metas_cliente["last_name"];

            $metas_cuidador = kmimos_get_user_meta($cuidador->user_id);

            $telf = array();
            $telf[] = $metas_cuidador["user_mobile"];
            $telf[] = $metas_cuidador["user_phone"];

            $telf = implode(" / ", $telf);

            $cuidador_name = utf8_encode($cuidador_name);
            $cliente_name = utf8_encode($cliente_name);

            $status_val = $value->subio_12+$value->subio_06;

            if( date("H", $actual) < 18 && $status_val == 1 ){
                $status_val = 4;
            }

            if( date("H", $actual) > 11 && $status_val == 0 ){
                $status_val = 3;
            }

            $status = ""; $status_txt = "";
            switch ( $status_val ) {
                case '0':
                    $status = "status-inicio";
                    $status_txt = "Por cargar fotos";
                break;
                case '1':
                    $status = "status-medio";
                    $status_txt = "Solo cargo un flujo";
                break;
                case '2':
                    $status = "status-ok";
                    $status_txt = "Todo Bien";
                break;
                case '3':
                    $status = "status-mal";
                    $status_txt = "No se cargaron fotos";
                break;
                case '4':
                    $status = "status-ok-medio";
                    $status_txt = "Cargo al menos un flujo";
                break;
                
                default:
                    $status = "status-ok";
                    $status_txt = "Todo Bien";
                break;
            }

            $moderacion = unserialize( $value->moderacion );

            $dia = "No"; if( $value->subio_12 == 1 ){ 
                $moderar = "";
                if( date("H", $actual) < 12 ){
                    $moderado = "";
                    if( isset( $moderacion[1] ) ){
                        $moderado = "(Fue moderado)";
                    }
                    $moderar = "<span class='enlaces' onclick='abrir_link( jQuery(this) );' data-id='{$value->reserva}==1' data-titulo='Moderaci&oacute;n de Fotos' data-modal='moderar'>Moderar {$moderado}</span>";
                }
                $dia = "Si ".$moderar; 
            }
            $noche = "No"; if( $value->subio_06 == 1 ){ 
                $moderar = "";
                if( date("H", $actual) < 18 ){
                    $moderar = "<span class='enlaces' onclick='abrir_link( jQuery(this) );' data-id='{$value->reserva}==2' data-titulo='Moderaci&oacute;n de Fotos' data-modal='moderar'>Moderar</span>";
                }
                $noche = "Si ".$moderar;
            }

            $bloqueo = "No";
            if( $value->bloqueo == 1 ){ 
                $bloqueo = "Si";
            }

            $data["data"][] = array(
                "<span onclick='abrir_link( jQuery(this) );' class='enlaces reserva' data-id='{$value->reserva}' data-titulo='Historial de Fotos' data-modal='historial' >{$value->reserva}</span>",
                "<span onclick='abrir_link( jQuery(this) );' class='enlaces nombre_cuidador' data-id='{$cuidador->user_id}' data-titulo='Datos del Cuidador' data-modal='cuidador' >{$cuidador_name}</span>",
                "<span onclick='abrir_link( jQuery(this) );' class='enlaces nombre_cliente' data-id='{$cliente_id}' data-titulo='Datos del Cliente' data-modal='cliente' >{$cliente_name}</span>",
                "<span onclick='abrir_link( jQuery(this) );' class='enlaces mascotas_cliente' data-id='{$cliente_id}' data-titulo='Datos de las Mascotas' data-modal='mascotas' >Mascotas del Cliente</span>",
                $dia,
                $noche,
                "<span onclick='abrir_link( jQuery(this) );' class='enlaces bloqueo' data-id='{$value->reserva}' data-titulo='Bloqueador de envios' data-modal='bloqueo' >{$bloqueo}</span>",
                "<div class='status {$status}' title='{$status_txt}'>&nbsp;</div>"
            );
        }
    }

    /*    
    echo "<pre style='display: none;'>";
        print_r($_SERVER["HTTP_REFERER"]);
    echo "</pre>";
    */

    echo json_encode($data);

?>