<?php

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











    if(!function_exists('get_email_html')){
        
        function get_email_html($content, $dudas = true){

            $ayuda = "";
            if( $dudas ){
                $ayuda = "
                    <div style='float:left;width:100%;margin-bottom: 31px;'>   
                        <div style='text-align:center;'>
                            <p style='font-family: Arial;font-weight: bold; font-size:12px; color:#B4B4B4; text-align: center; '>
                                En caso de dudas, puedes contactarte con nuestro equipo de atención al cliente al teléfono (01) 55 4742 3162, Whatsapp +52 (55) 6892 2182, o al correo contactomex@kmimos.la
                            </p>
                            <div  style='clear:both;'></div>
                        </div>
                        <div  style='clear:both;'></div>
                    </div>
                ";
            }

            $html = "
            <style type='text/css'> p{ margin:0px; } </style>
            <style type='text/css'>
                a[id*='kmimos_container'] > div {
                    max-width: 600px !important;
                }
                @media max-device-width: 480px {
                    a[id*='kmimos_container'] > div {
                        max-width: 400px !important;
                    }
                }
            </style>
            <div id='kmimos_container' style='font-family: Arial;'>
                <div style='margin: 0px auto; max-width: 600px; width: 100%;'>
                    <div style='text-align:center;'>
                        <img src='".get_home_url()."/wp-content/themes/kmimos/images/emails/bitmap.png' style='margin-bottom: 14px;' />
                    </div>

                    ".$content."

                    <div style='text-align:center;'>
                        
                        ".$ayuda."

                        <div style='font-family: Arial; font-size: 12px; font-weight: bold; letter-spacing: 0.2px; color: #6b1c9b; margin-bottom: 10px;'>
                            CON LA CONFIANZA Y SEGURIDAD QUE NECESITAS
                        </div>

                        <img style='margin-bottom: 16px;' src='".get_home_url()."/wp-content/themes/kmimos/images/emails/caracteristicas.png' >
                        <img style='margin-bottom: 30px;' src='".get_home_url()."/wp-content/themes/kmimos/images/emails/dog_footer.png' >

                        <div style='background-color:#000000; color: #fff; display: table; width: 100%; height: 62px; font-size: 11px; letter-spacing: 0.2px; padding: 0px; box-sizing: border-box;'>

                            <div style='display: table-cell; width: 33.333333333%; vertical-align: middle; text-align: left; padding-left: 30px;'>
                                <img src='".get_home_url()."/wp-content/themes/kmimos/images/emails/kamimos_footer.png' style='height: 21px;'> 
                            </div>

                            <div style='display: table-cell; width: 33.333333333%; vertical-align: middle; color: #FFF;'>
                                <a href='".get_home_url()."' style='color: #FFF; text-decoration: none;'>".$_SERVER['HTTP_HOST']."</a>
                            </div>

                            <div style='display: table-cell; width: 33.333333333%; vertical-align: middle; text-align: right; padding-right: 30px;'>
                                <span style='display: inline-block; padding: 0px 5px 0px 0px;'>Síguenos en</span> <img src='".get_home_url()."/wp-content/themes/kmimos/images/emails/icono_facebook.png' style='margin-top: -2px;' align='center'>
                            </div>

                        </div>

                        <p style='text-align: center; font-family: Arial; font-size: 11px; line-height: 1.73; padding: 10px;'>
                            ¿Tienes dudas? | Contáctanos
                        </p>
                    </div>
                </div>      
            </div>";

            return $html;
        }
    }

?>