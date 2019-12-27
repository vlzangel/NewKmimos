<?php

	function kv_get_email_html($content, $params = []){
		extract($params);
		$telf  = '01 (55) 8526 1162';
		$What  = '+52 1 (33) 1261 41 86';
		$email  = 'contactomex@kmimos.la';
        $header = "";
        if( $_header ){
            $header = "
            <div style='text-align:center; background-color: #000;'>
                <img src='".get_recurso('img')."KMIVET/img/logo.png' style='margin: 10px; height: 40px;' />
            </div>";
        }
        $html = "
        <html>
            <head>
                <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no'>
                <style>
                    p{ margin:0px; }
                    a[id*='kmimos_container'] > div { max-width: 600px !important; }
                    @media max-device-width: 480px { a[id*='kmimos_container'] > div { max-width: 600px !important; } }
                </style>
            </head>
            <body>
                <div id='kmimos_container' style='font-family: Arial;'>
                    <div style='margin: 0px auto; padding: 0px; max-width: 600px;'>
                        ".$header."
                        ".$content."
                        <div style='text-align:center;width:100%;'>
                            <div style='background-color:#000000; color: #fff; display: table; width: 100%; height: 62px; font-size: 11px; letter-spacing: 0.2px; padding: 0px; box-sizing: border-box;'>
                                <div style='display: table-cell; width: 240px; vertical-align: middle; text-align: left; padding-right: 15px; padding-left: 30px;'>
                                    <a href='".get_home_url()."' style='color: #FFF; text-decoration: none;'>
                                        <img src='".get_home_url()."/wp-content/themes/kmimos/KMIVET/img/logo_footer.png' style='height: 21px;float:left;'> 
                                    </a>
                                </div>
                                <div style='display: table-cell; width: 240px; vertical-align: middle; text-align: right; padding-left:15px; padding-right: 30px;'>
                                    <span style='display: inline-block; padding: 0px 5px 2px 0px; float:right'>
                                        <a href='https://www.facebook.com/Kmimosmx/'>
                                            <img src='".get_home_url()."/wp-content/themes/kmimos/KMIVET/img/icono_facebook_2.png' style='vertical-align: bottom; height: 30px;' align='center'>
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
            $html = str_replace(get_home_url(), "https://kmimos.com.mx/QA2", $html);
        }
        return $html;
    }
?>
