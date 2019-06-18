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
            $INFORMACION["SUGERENCIA"] = "Sugerencias";
        }

        $mensaje = buildEmailTemplate(
            'correo_generico/base', 
            $INFORMACION
        );



        /*
        $mensaje = buildEmailHtml(
            $mensaje, 
            [
                'user_id' => 367, 
                'barras_ayuda' => false,
                'test' => false,
                'dudas' => false,
                'beneficios' => false,
            ]
        );

        // kmimos_mails_administradores_new('Confirmación de Disponibilidad Reserva #'.$id, $mensaje);
    
        wp_mail( $cliente_email, 'Confirmación de Disponibilidad Reserva #'.$id, $mensaje);
        */

        echo json_encode([
            /*
            "error" => "",
            "respuesta" => "Correo Enviado Exitosamente!",
            */
            "POST" => $_POST,
            "INFORMACION" => $INFORMACION,
            "html" => $mensaje,
        ]); die();
    }
?>

