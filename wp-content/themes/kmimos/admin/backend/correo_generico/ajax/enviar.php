<?php    

    session_start();

    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include($raiz.'/wp-load.php');

    date_default_timezone_set('America/Mexico_City');
    error_reporting(E_ERROR | E_WARNING | E_PARSE );

    global $wpdb;

    extract( $_POST );

    $cliente_id = $wpdb->get_var("SELECT ID FROM wp_users WHERE user_email = '{$correo}' ");

    if( $cliente_id === false ){

        echo json_encode([
            "error" => "No es un email valido",
            "sql" => $sql
        ]); die();

    }else{

        $metas = get_user_meta($cliente_id);

        $cliente_name = $metas["first_name"][0]." ".$metas["last_name"][0];

        $INFORMACION = [
            "CLIENTE" => $cliente_name,
        ];
        $cont = 0;
        $INFORMACION["PARRAFOS"] = [];
        foreach ($parrafos as $key => $parrafo) {
            if( $parrafo != "" ){
                if( $cont == 0 ){
                    $INFORMACION["CONTENIDO_0"] = $parrafo;
                }else{
                    $INFORMACION["PARRAFOS"]["correo_generico/parrafo_".$cont] = [
                        "CONTENIDO_".$key => $parrafo
                    ];
                }
                $cont++;
            }
        }

        if( $sugerencias+0 == 0 ){
            $INFORMACION["SUGERENCIA"] = "";
        }else{

                $sql = "
                    SELECT 
                        id_post,
                        user_id,
                        hospedaje_desde,
                        adicionales,
                        experiencia
                    FROM 
                        cuidadores
                    WHERE
                        activo = 1 AND
                        user_id != 8631
                    ORDER BY rating DESC, valoraciones DESC
                    LIMIT 0, ".($sugerencias+0)."
                ";

                $sugeridos = $wpdb->get_results($sql);

                $str_sugeridos = "";

                $plantilla_cuidador = getTemplate("correo_generico/cuidadores");
                foreach ($sugeridos as $valor) {
                    $nombre = $wpdb->get_row("SELECT post_title, post_name FROM wp_posts WHERE ID = ".$valor->id_post);
                    $rating = kmimos_petsitter_rating($valor->id_post, true); $rating_txt = "";
                    foreach ($rating as $key => $value) {
                        if( $value == 1 ){ $rating_txt .= "<img style='width: 15px; padding: 0px 1px;' src='[URL_IMGS]/new/huesito.png' >";
                        }else{ $rating_txt .= "<img style='width: 15px; padding: 0px 1px;' src='[URL_IMGS]/new/huesito_vacio.png' >"; }
                    }
                    $servicios = vlz_servicios($valor->adicionales, true);
                    $servicios_txt = "";
                    if( count($servicios)+0 > 0 && $servicios != "" ){
                        foreach ($servicios as $key => $value) {
                            $servicios_txt .= "<img style='margin: 0px 3px 0px 0px;' src='[URL_IMGS]/servicios/".str_replace('.svg', '_.png', $value["img"])."' height='100%' align='middle' >";
                        }
                    }
                    if( $valor->experiencia > 1900 ){
                        $valor->experiencia = date("Y")-$valor->experiencia;
                    }
                    $monto = explode(",", number_format( ($valor->hospedaje_desde*getComision()), 2, ',', '.') );
                    $temp = str_replace("[EXPERIENCIA]", $valor->experiencia, $plantilla_cuidador);
                    $temp = str_replace("[MONTO]", $monto[0], $temp);
                    $temp = str_replace("[MONTO_DECIMALES]", ",".$monto[1], $temp);
                    $temp = str_replace("[AVATAR]", kmimos_get_foto($valor->user_id), $temp);
                    $temp = str_replace("[NAME_CUIDADOR]", $nombre->post_title, $temp);
                    $temp = str_replace("[HUESOS]", $rating_txt, $temp);
                    $temp = str_replace("[SERVICIOS]", $servicios_txt, $temp);
                    $temp = str_replace('[LIKS]', get_home_url()."/petsitters/".$nombre->post_name."/", $temp);
                    $str_sugeridos .= $temp;
                }

                $plantilla_sugeridos = getTemplate("correo_generico/sugeridos");
                $plantilla_sugeridos = str_replace("[CUIDADORES]", $str_sugeridos, $plantilla_sugeridos);

            $INFORMACION["SUGERENCIA"] = $plantilla_sugeridos;
        }

        $mensaje = buildEmailTemplate(
            'correo_generico/base', 
            $INFORMACION
        );

        wp_mail( $correo, $titulo, $mensaje);

        echo json_encode([
            "error" => "",
            "respuesta" => "Correo Enviado Exitosamente!",
            /*
            "POST" => $_POST,
            "INFORMACION" => $INFORMACION,
            */
            "html" => $mensaje,
        ]); die();
    }
?>

