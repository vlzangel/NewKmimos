<?php

    if(!function_exists('buildEmailHtml')){
        
        function buildEmailHtml($content, $params = []){
            $_params = [
                'dudas' => true, 
                'beneficios' => true, 
                'user_id' => null, 
                'header' => false, 
                'barras_ayuda' => false,
                'test' => false,
            ];
            if( !empty($params) ){
                foreach ($params as $key => $value) {
                    $_params[ $key ] = $value;
                }
            }
            return get_email_html(
                $content, 
                $_params["dudas"],
                $_params["beneficios"],
                $_params["user_id"],
                $_params["header"],
                $_params["barras_ayuda"],
                $_params["test"]
            );
        }
    }

    if(!function_exists('get_email_html')){
        
        function get_email_html($content, $dudas = true, $beneficios = true, $user_id = null, $_header = true, $_barras_ayuda = false, $test = false){

            $ayuda = "";
            if( $dudas ){
                if( !$_barras_ayuda ){
                    $ayuda = "
                        <div style='float:left; width:100%; margin-bottom: 31px;'>   
                            <div style='text-align:center;'>
                                <p style='    
                                    font-family: Verdana;
                                    font-size: 16px;
                                    color: #666;
                                    text-align: center;
                                    font-weight: 400;
                                    padding: 30px 0px;
                                    margin: 0px 30px;
                                '>
                                    En caso de dudas, puedes contactarte con nuestro equipo de atención al cliente al teléfono 01 (55) 8526 1162, Whatsapp +52 1 (33) 1261 41 86, o al correo 
                                    <a href='mailto:contactomex@kmimos.la' target='_blank' style='text-decoration: none; '>contactomex@kmimos.la</a>
                                </p>
                                <div  style='clear:both;'></div>
                            </div>
                            <div  style='clear:both;'></div>
                        </div>
                    ";
                }else{
                    $ayuda = "
                        <div style='float:left; width:100%; margin-bottom: 31px;'>   
                            <div style='text-align:center;'>
                                <p style='    
                                    font-family: Verdana;
                                    font-size: 16px;
                                    color: #666;
                                    text-align: center;
                                    font-weight: 400;
                                    border: solid 1px #CCC;
                                    padding: 30px 0px;
                                    margin: 0px;
                                    border-left: 0px;
                                    border-right: 0px;
                                '>
                                    En caso de dudas, puedes contactarte con nuestro equipo de atención al cliente al teléfono (01) 55 3137 4829, Whatsapp +52 1 (33) 1261 41 86, o al correo 
                                    <a href='mailto:contactomex@kmimos.la' target='_blank' style='text-decoration: none; '>contactomex@kmimos.la</a>
                                </p>
                                <div  style='clear:both;'></div>
                            </div>
                            <div  style='clear:both;'></div>
                        </div>
                    ";
                }
            }

            $beneficios_txt = "";
            if( $beneficios ){
                $beneficios_txt = "
                    <div style='font-family: Arial;width:100%; font-size: 12px; font-weight: bold; letter-spacing: 0.2px; color: #6b1c9b; margin-bottom: 10px;'>
                        CON LA CONFIANZA Y SEGURIDAD QUE NECESITAS
                    </div>

                    <img style='margin-bottom: 16px; max-width: 100%;' src='".get_home_url()."/wp-content/themes/kmimos/images/emails/caracteristicas.png' >
                ";
            }

            $header = "";
            if( $_header ){
                $header = "
                <div style='text-align:center; background-color: #000;'>
                    <img src='".get_home_url()."/wp-content/themes/kmimos/images/emails/new/km-logo.png' style='margin: 10px; height: 40px;' />
                </div>";
            }

            $html = "
            <html>
                <head>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no'>
                    <style>
                        p{ margin:0px; }
                        a[id*='kmimos_container'] > div {
                            max-width: 600px !important;
                        }
                        @media max-device-width: 480px {
                            a[id*='kmimos_container'] > div {
                                max-width: 600px !important;
                            }
                        }
                    </style>
                </head>
                <body>
                    <div id='kmimos_container' style='font-family: Arial;'>
                        <div style='margin: 0px auto; padding: 0px; max-width: 600px;'>
                            ".$header."

                            ".$content."

                            <div style='text-align:center;width:100%;'>
                                
                                ".$ayuda."

                                ".get_publicidad("correo", $user_id)."

                                ".$beneficios_txt."

                                <img style='margin-bottom: 30px;width:100%;' src='".get_home_url()."/wp-content/themes/kmimos/images/emails/dog_footer.png' >

                                <div style='background-color:#000000; color: #fff; display: table; width: 100%; height: 62px; font-size: 11px; letter-spacing: 0.2px; padding: 0px; box-sizing: border-box;'>

                                    <div style='display: table-cell; width: 240px; vertical-align: middle; text-align: left; padding-right: 15px; padding-left: 30px;'>
                                        <a href='".get_home_url()."' style='color: #FFF; text-decoration: none;'>
                                            <img src='".get_home_url()."/wp-content/themes/kmimos/images/emails/kamimos_footer.png' style='height: 21px;float:left;'> 
                                        </a>
                                    </div>

                                    <div style='display: table-cell; width: 240px; vertical-align: middle; text-align: right; padding-left:15px; padding-right: 30px;'>
                                        <span style='display: inline-block; padding: 0px 5px 2px 0px; float:right'>
                                            <a href='https://www.facebook.com/Kmimosmx/'>
                                                <img src='".get_home_url()."/wp-content/themes/kmimos/images/emails/icono_facebook_2.png' style='vertical-align: bottom; height: 30px;' align='center'>
                                            </a>
                                        </span> 
                                    </div>

                                </div>

                                <p style='text-align: center; font-family: Arial; font-size: 11px; line-height: 1.73; padding: 10px;'>
                                    ¿Tienes dudas? | <a href='".get_home_url()."/contacta-con-nosotros/'>Contáctanos</a>
                                </p>
                            </div>
                        </div>      
                    </div>
                </body>
            </html>";

            if( $test ){
                $html = str_replace(get_home_url(), "http://kmimosmx.sytes.net/QA2", $html);
            }

            return $html;
        }
    }

























































    /**
     *  Devuelve el contenido formateado de los correos.
     * */

    if(!function_exists('kmimos_get_email_header')){

        function kmimos_get_email_header(){        
            $html  = '
            <!DOCTYPE html>
            <html>
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                    <meta name="viewport" content="width=320, target-densitydpi=device-dpi">
                    
                    <style>
                        .body {margin: 0;padding: 20px 0px;background-color: #cccccc;min-height: 320px;}
                        .wrap {max-width: 640px; margin: 0px auto; background-color: #ffffff;}
                        .header {border-top: 3px solid #f25555; background-color: #00d2b7; color: #494949; padding: 30px;}
                        .container {min-height: 200px; padding: 30px; margin-top: 0px; font-family: HelveticaNeue, sans-serif; text-align: left;font-size: 13px; line-height: 18px; color: #444; }
                        .title {font-size: 18px; line-height: 24px; color: #7d7d7d; font-weight: bold; }
                        .gretting, .content {margin-top: 20px;}
                        .gretting span {font-size: 14px; line-height: 18px; color: #7d7d7d; font-weight: bold;}
                        .gretting img { margin: 10px 0; }
                        .footer {border-bottom: 3px solid #f25555; background-color: #00d2b7; font-size: 14px;font-family: HelveticaNeue, sans-serif; color: #494949; padding: 30px; }
                    </style>
                </head>
                <body>
                    <div class="body">
                        <div class="wrap">
                            <div class="header">
                                <a href="'.get_home_url().'">
                                    <img src="https://www.kmimos.com.mx/wp-content/uploads/2016/02/logo-kmimos.png" alt="Logo Kmimos">
                                </a>
                            </div>';
            return $html;
        }
    }

/**
 *  Devuelve el contenido formateado de los correos.
 * */

if(!function_exists('kmimos_get_email_html')){

    function kmimos_get_email_html($title, $content, $gretting='', $banners=false, $body=false){

        if($gretting=='') $gretting='Atentamente,';

        if($body){ 
            $html = '';
            $html .= '<!DOCTYPE html>';
            $html .= '<html>';
            $html .= '  <head>';
            $html .= '      <meta charset="UTF-8">';
            $html .= '      <meta name="viewport" content="width=device-width, initial-scale=1.0">';
            $html .= '      <title>'.$title.'</title>';
            $html .= '  </head>';
            $html .= '  <body>';
        }else{ 
            $html = '';
        }

        $html .= kmimos_get_email_header();

        $html .= '<div class="container">';
        $html .= '  <span class="title">'.$title.'</span>';
        $html .= '  <div class="content">'.$content.'</div>';
        $html .= '  <div class="gretting">';
        $html .= '      <span>'.$gretting.'</span><br>';
        $html .= '      <img src="https://www.kmimos.com.mx/wp-content/uploads/2016/03/logo-kmimos_120x30.png" alt="Firma Kmimos">';
        $html .= '  </div>';

        if($banners) $html .= kmimos_get_email_banners();

        $html .= '</div>';

        $html .= kmimos_get_email_footer();

        if($body){ $html .= '</body></html>'; }

        return $html;
    }

}

/*

*   Introduce los banners

*/

if(!function_exists('kmimos_get_email_banners')){

    function kmimos_get_email_banners(){

        $html  = '';
        $html .= '
        <div>
            <div style="font-size:0.7em; color:#cccccc;">Publicidad</div>
            <ul style="overflow: hidden; padding: 0px;">
                <li style="float:left; margin: 5px; width: 48%; list-style: none;">
                    <a style="display: block;" href="http://www.booking.com/index.html?aid=1147066&lang=es">
                        <img style="width: 100%;" src="https://www.kmimos.com.mx/wp-content/uploads/2016/03/Banner-ofertas-hoteles300x100.png" alt="Booking-Kmimos">
                    </a>
                </li>
                <li style="float:left; margin: 5px; width: 48%; list-style: none;">
                        <img style="width: 100%;" src="https://www.kmimos.com.mx/wp-content/uploads/2016/03/Banner-accesorios300x100.png" alt="Accesorios-Mascotas">                    
                </li>
                <li style="float:left; margin: 5px; width: 48%; list-style: none;">
                    <a style="display: block;" href="https://www.volaris.com/">
                        <img style="width: 100%;" src="https://www.kmimos.com.mx/wp-content/uploads/2016/03/Banner-boletos-aereos300x100.png" alt="Boletos-aereos">
                    </a>
                    
                </li>
                <li style="float:left; margin: 5px; width: 48%; list-style: none;">
                    <a style="display: block;" href="https://cabify.com/mexico/mexico-city">
                        <img style="width: 100%;" src="https://www.kmimos.com.mx/wp-content/uploads/2016/03/Banner-transporte-mascotas300x100.png" alt="Transporte-Mascotas">
                    </a>
                    
                </li>
            </ul>
        </div>';

        return $html;
    }

}

/**
 *  Devuelve el contenido formateado de los correos.
 **/

if(!function_exists('kmimos_get_email_footer')){
    function kmimos_get_email_footer(){
        $html  = '<style> .footer {border-bottom: 3px solid #f25555; background-color: #00d2b7; font-size: 14px;font-family: HelveticaNeue, sans-serif; color: #494949; padding: 30px;} </style>';
        $html .= '<div class="footer"><span>Más información en <a href="'.get_home_url().'">'.$_SERVER['HTTP_HOST'].'</a> o por nuestros teléfonos </span><span><strong>+52 (55) 1791.4931</strong><span></div></div></div>';
        return $html;
    }

}



?>